import React from "react";
import PropTypes from "prop-types";
import NumberEasing from 'che-react-number-easing';

export default class OverviewCard extends React.Component{
    constructor(props) {
        super(props);
        this.state = {
            number: 0
        }
    }

    static propTypes = {
        title: PropTypes.string.isRequired,
        number: PropTypes.oneOfType([
            PropTypes.number,
            PropTypes.string
        ]).isRequired,
        image: PropTypes.string,
        isPrice: PropTypes.bool
    };

    static defaultProps = {
        isPrice: false
    };


    componentDidMount() {
        this.setState({
            number: parseFloat(this.props.number).toFixed(2)
        })
    }

    render() {
        const {image, title, isPrice} = this.props;
        return (
            <div className='overview-card'>
                <div className="main">
                    <h1>{title}</h1>
                    <p className='number'>{isPrice && '￥'}
                        <NumberEasing
                            value={this.state.number}
                            speed={1500}
                            precision={this.props.isPrice ? 2 : 0}
                        />
                    </p>
                </div>
                <img src={image} alt="text"/>
            </div>
        )
    }
}