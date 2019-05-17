import React from "react";
import PropTypes from "prop-types";
import styled from 'styled-components'
import {setLightness} from 'polished'

export default class RadioGroup extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            number: 0
        }
    }

    static propTypes = {
        current: PropTypes.number,
        items: PropTypes.arrayOf(PropTypes.string).isRequired,
        color: PropTypes.string,
        onChange: PropTypes.func
    };

    static defaultProps = {
        current: '',
        items: [],
        onChange: () => {
        },
        color: '#5d58e9'
    };

    render() {
        const {current, items, color, onChange} = this.props;

        return (
            <Root>
                {items.map((item, index) => (
                    <Item
                        key={index}
                        color={color}
                        className={current === index ? 'active' : ''}
                        onClick={() => {
                            current !== item && onChange(index)
                        }}
                    >{item}</Item>
                ))}
            </Root>
        )
    }
}

const Root = styled.div`
  display: flex;
  flex-wrap: wrap;
  align-items: center;
`;

const Item = styled.div`
  margin: 5px;
  padding: 2px 10px;
  font-size: .8em;
  border-radius: 40px;
  border: 2px solid ${(props) => props.color};
  color: ${(props) => props.color};
  background: #fff;
  transition: all .2s;
  white-space: nowrap;
  cursor: pointer;
  &:hover {
    background: ${(props) => setLightness(.9, props.color)};
  }
  &.active {
    background: ${(props) => props.color};
    color: #fff;
    cursor: default;
  }
`;