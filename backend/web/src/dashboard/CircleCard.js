import Circle from 'react-circle';
import React from "react";
import PropTypes from "prop-types";
import styled from 'styled-components'
import ClassNames from 'classnames'
import {setLightness} from 'polished'
import {Icon} from 'antd'
import NumberEasing from "che-react-number-easing"

export default class RadioGroup extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            percentForDisplay: 0,
            growthForDisplay: 0,
        }
    }

    static propTypes = {
        title: PropTypes.string,
        percent: PropTypes.number,
        growth: PropTypes.number,
        color: PropTypes.string,
        animationDuration: PropTypes.number
    };

    static defaultProps = {
        title: '',
        percent: 0,
        growth: 0,
        color: '#312c50',
        animationDuration: 1500
    };

    componentDidMount() {
        const {percent, growth} = this.props;
        this.setState({
            percentForDisplay: percent.toFixed(4) * 100,
            growthForDisplay: growth.toFixed(4) * 100
        })
    }

    render() {
        const {percentForDisplay, growthForDisplay} = this.state;
        const {title, growth, color, animationDuration} = this.props;

        return (
            <Root>
                <Title>{title}</Title>
                <CircleWrapper>
                    <Circle
                        animate={true}
                        animationDuration={`${animationDuration / 1000}s`}
                        progress={percentForDisplay}
                        progressColor={color}
                        textColor={color}
                        size={120}
                        textStyle={{
                            font: 'bold 7rem Helvetica, Arial, sans-serif'
                        }}
                        roundedStroke={true}
                        showPercentage={false}
                    />
                    <Percentage>
                        <NumberEasing
                            value={percentForDisplay}
                            speed={animationDuration}
                            precision={2}
                        />
                        <span>%</span>
                    </Percentage>
                </CircleWrapper>
                {growth !== 0 && (
                    <Growth className={ClassNames({
                        up: growth > 0,
                        zero: growth === 0,
                        down: growth < 0
                    })} title={`同比${growth > 0 ? '增长' : '下降'} ${Math.abs(growth.toFixed(4) * 100)}%`}>
                        <Icon type={'arrow-' + (growth > 0 ? 'up' : 'down')} />
                        <NumberEasing
                            value={Math.abs(growthForDisplay)}
                            speed={animationDuration}
                            precision={2}
                        />
                        <span>%</span>
                    </Growth>
                )}
            </Root>
        )
    }
}

const Root = styled.div`
  height: 200px;
  display: flex;
  flex-direction: column;
  justify-content: space-around;
  align-items: center;
  border-radius: 10px;
  padding: 20px;
  background: #fff;
  box-shadow: 0 0 30px 0 rgba(0, 0, 0, 0.1)!important;
`;

const Title = styled.div`
  font-weight: bold;
  font-size: 1em;
`;

const Growth = styled.div`
  font-weight: bold;
  &.up {
    color: #3fbe67;
  }
  &.zero {
    color: #666;
  }
  &.down {
    color: #ee4d48;
  }
  .anticon {
    margin-right: 5px;
  }
`;

const CircleWrapper = styled.div`
  position: relative;
`;

const Percentage = styled.div`
  position: absolute;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  font-size: 1.5em;
  font-weight: bold;
  color: ${(props) => props.color};
`;