import React from "react";
import PropTypes from "prop-types";
import { Chart, Geom, Axis, Tooltip, Legend, Coord } from 'bizcharts';
import styled from 'styled-components'
import RadioGroup from './RadioGroup'
import {setLightness} from 'polished'

export default class LineChart extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            checkedIndex: 0
        }
    }

    static propTypes = {
        data: PropTypes.oneOfType([PropTypes.array, PropTypes.object]).isRequired,
        label: PropTypes.string.isRequired,
        color: PropTypes.string,
        title: PropTypes.string,
        labelY: PropTypes.string,
        prefix: PropTypes.string,
        unit: PropTypes.string,
        dateCount: PropTypes.number,
        type: PropTypes.oneOf(['integer'])
    };

    static defaultProps = {
        data: [],
        label: '',
        color: '#5d58e9',
        title: '',
        labelY: '',
        prefix: '',
        unit: '',
        dateCount: 7,
        type: 'integer'
    };

    render() {
        const {checkedIndex} = this.state;
        const {data, label, prefix, unit, title, color, type, dateCount} = this.props;
        const currentData = data[checkedIndex];
        const position = `${currentData.key || 'date'}*value`;
        const isLittle = !currentData.data.find(i => i.value > 5);

        // 定义度量
        const cols = {
            [data[checkedIndex].key || 'date']: {
                alias: '日期',
                tickCount: dateCount
            },
            value: {
                alias: label,
                type: 'linear',
                formatter: value => `${prefix}${value}${unit}`,
                tickInterval: type === 'integer' && isLittle ? 1 : undefined
            }
        };

        return (
            <div className="line-chart">
                <Header>
                    <h1>
                        {title.replace(/<days>/g, data[checkedIndex].data.length)
                            .replace(/<label>/g, data[checkedIndex].label)}
                    </h1>
                    {data.length > 1 && (
                        <RadioGroup
                            current={checkedIndex}
                            items={data.map(i => i.label)}
                            color={color}
                            onChange={(newValue) => {
                                this.setState({checkedIndex: newValue})
                            }}
                        />
                    )}
                </Header>
                <div className="content">
                    <Chart
                        forceFit
                        height={300}
                        padding='auto'
                        data={data[checkedIndex].data}
                        scale={cols}
                    >
                        <Axis name="date" />
                        <Axis name="value" />
                        <Tooltip />
                        <Geom
                            type="line"
                            shape="smooth"
                            position={position}
                            color={color}
                            animate={{
                                appear: {
                                    animation: 'clipIn',
                                    easing: 'easePolyIn',
                                    duration: 1500,
                                    delay: 0
                                }
                            }}
                        />
                        <Geom
                            type="area"
                            shape="smooth"
                            position={position}
                            color={`l (90) 0:${setLightness(.85, color)} 0.7:${setLightness(.9, color)} 1:#ffffff`}
                            tooltip={false}
                            animate={{
                                appear: {
                                    animation: 'clipIn',
                                    easing: 'easePolyIn',
                                    duration: 1500,
                                    delay: 0
                                }
                            }}
                        />
                    </Chart>
                </div>
            </div>
        );
    }
}

const Header = styled.div`
  display: flex;
  align-items: center;
  margin: 10px 0 20px 0;
  h1 {
    flex-grow: 1;
    line-height: 1;
    margin: 0;
  }
`;