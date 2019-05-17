import React from "react";
import PropTyps from 'prop-types'
import ClassNames from 'classnames'
import style from '!!to-string-loader!css-loader!resolve-url-loader!sass-loader?sourceMap!./index.scss'

/**
 * 计算对角线长度
 * @param x
 * @param y
 * @returns {number}
 */
function getDiagonalLength(x, y) {
    return Math.sqrt(x * x + y * y);
}

export default class Movable extends React.Component {
    constructor(props) {
        super(props);

        this.refMovable = React.createRef();

        this.isMouseDown = false;
        this.isResizeHandleMouseDown = false;
        this.recordedPosX = 0;
        this.recordedPosY = 0;
        this.state = {
            active: false
        }
    }

    static propTypes = {
        posX: PropTyps.number,
        posY: PropTyps.number,
        width: PropTyps.number,
        height: PropTyps.number,
        scale: PropTyps.number,
        canResize: PropTyps.bool,
        keepAspectRatio: PropTyps.bool,
        resizeHandlerSize: PropTyps.number,
        onPosChange: PropTyps.func,
        onSizeChange: PropTyps.func
    };

    static defaultProps = {
        posX: 0,
        posY: 0,
        size: null,
        scale: 1,
        canResize: true,
        keepAspectRatio: true,
        resizeHandlerSize: 10,
        onPosChange: () => {},
        onSizeChange: () => {}
    };

    componentWillMount() {
        window.addEventListener('mousedown', (e) => {
            this.setState({
                active: false
            });
        }, true);
        window.addEventListener('mouseup', (e) => {
            this.handleMouseUp(e)
        });
        window.addEventListener('mousemove', (e) => {
            this.handleMouseMove(e)
        });
    }

    savePosition = (e) => {
        this.recordedPosX = e.clientX;
        this.recordedPosY = e.clientY;
    };

    handleMouseMove = (e) => {
        if (this.isResizeHandleMouseDown) {
            const {width, height, scale, keepAspectRatio, onSizeChange} = this.props;
            if (keepAspectRatio) {
                // 保持横纵比缩放
                // 参考 Photoshop 等比缩放的交互，往左和上移动视为减小大小，往右和下移动视为增加大小


                // const {x, y} = this.refMovable.current.getBoundingClientRect();
                // const ratioX = e.clientX + this.recordedPosX / width;
                // const ratioY = e.clientY - height / this.recordedPosY - height;

                // 具体实现：先计算 X 和 Y 方向的移动距离，屏幕左上角为原点，右下角方向为正
                const distanceX = (e.clientX - this.recordedPosX) * scale;
                const distanceY = (e.clientY - this.recordedPosY) * scale;

                // 取X和Y两个方向距离的叠加距离，
                // 可能全为正（鼠标往右下角移动）、全为负（鼠标往左上角移动）、正负叠加抵消（鼠标往左下角或右上角移动）
                const compoundDistance = distanceX + distanceY;

                // 计算取X和Y两个方向距离的叠加距离的对角线距离
                const compoundDiagonalDistance = getDiagonalLength(compoundDistance, compoundDistance);

                // 鼠标本次对角线方向的直接移动距离
                const diagonalDistance = getDiagonalLength(distanceX, distanceY);

                // 取叠加距离和直接距离的最小值，并附上正负
                const diagonalResult = (compoundDistance >= 0 ? 1 : -1) * Math.min(compoundDiagonalDistance, diagonalDistance);

                // const originalDiagonal = getDiagonalLength(width, height);

                // const ratio = diagonalResult / originalDiagonal;
                // const ratio = Math.min(ratioX, ratioY);
                // const ratio = getDiagonalLength(ratioX, ratioY);

                onSizeChange(width + diagonalResult, height + diagonalResult);
            } else {
                // 不保持横纵比缩放
                const distanceX = (e.clientX - this.recordedPosX) * scale;
                const distanceY = (e.clientY - this.recordedPosY) * scale;

                onSizeChange(width + distanceX, height + distanceY);
            }
            this.savePosition(e);
        } if (this.isMouseDown) {
            const {posX, posY, scale, onPosChange} = this.props;
            const distanceX = posX + (e.clientX - this.recordedPosX) / scale;
            const distanceY = posY + (e.clientY - this.recordedPosY) / scale;
            onPosChange(distanceX, distanceY);
            this.savePosition(e);
        }
    };

    handleMouseDown = (e) => {
        this.isMouseDown = true;
        this.isResizeHandleMouseDown = false;
        this.savePosition(e);
        this.setState({
            active: true
        });
    };
    
    handleResizeMouseDown = (e) => {
        this.isMouseDown = false;
        this.isResizeHandleMouseDown = true;
        this.savePosition(e);
        this.setState({
            active: true
        });
        document.body.style.cursor = 'se-resize';

        e.stopPropagation();
        e.preventDefault();
    };

    handleMouseUp = () => {
        this.isMouseDown = false;
        if (this.isResizeHandleMouseDown) {
            this.isResizeHandleMouseDown = false;
            document.body.style.cursor = 'default';
        }
    };

    render() {
        const {active} = this.state;
        const {posX, posY, width, height, canResize, resizeHandlerSize, children} = this.props;

        return (
            <div
                className={ClassNames('movable-object', {active})}
                style={{
                    left: `${posX}px`,
                    top: `${posY}px`,
                    width: `${width}px`,
                    height: `${height}px`,
                }}
                draggable="false"
                onMouseDown={this.handleMouseDown}
                ref={this.refMovable}
            >
                <style>{style}</style>
                <div 
                    className="resize-handler" 
                    style={{width: `${resizeHandlerSize}px`, height: `${resizeHandlerSize}px`}}
                    onMouseDown={this.handleResizeMouseDown}
                />
                {children}
            </div>
        )
    }
}