import React from "react";
import PropTypes from "prop-types";
import styled from "styled-components";
import {setLightness} from 'polished'
import dayjs from 'dayjs'

export default class Card extends React.Component {
    constructor(props) {
        super(props);
        this.state = {};
    }

    static propTypes = {
        icon: PropTypes.string,
        title: PropTypes.string,
        color: PropTypes.string,
        data: PropTypes.arrayOf(PropTypes.object)
    };

    static defaultProps = {
        icon: '',
        title: '',
        color: '#5845e9',
        data: []
    };

    render() {
        const {icon, title, color, data} = this.props;
        return (
            <Root>
                <Header color={color}>
                    <Icon className={'fa ' + ('fa-' + icon)} color={color} />
                    <Title>{title}</Title>
                </Header>
                <Body>
                    {data.map((item, index) => {
                        let value = item.value;

                        if (item.type === 'time') {
                            value = dayjs(item.value * 1000).format('YYYY-MM-DD HH:mm:ss')
                        } else if (item.type === 'price') {
                            value = `${item.prefix || ''}￥${value}`;
                        }

                        return (
                            <Row key={index}>
                                <Label>{item.label}</Label>
                                <Value
                                    className={'card-type__' + item.type}
                                >
                                    {value || '无'}
                                    {(item.link && item.link.url) && <a href={item.link.url}>{item.link.text}</a>}
                                </Value>
                            </Row>
                        )
                    })}
                </Body>
            </Root>
        )
    }
}

const iconSize = 50;

const Root = styled.div`
  //width: 300px;
  flex-grow: 1;
  margin: 10px;
  border-radius: 10px;
  background: #fff;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, .1);
`;

const Header = styled.div`
  position: relative;
  padding: 10px;
  border-radius: 10px 10px 0 0;
  background: ${props => setLightness(.95, props.color)};
  color: ${props => setLightness(.2, props.color)};
`;

const Icon = styled.div`
  position: absolute;
  left: 10px;
  bottom: -${iconSize / 2}px;
  display: flex;
  justify-content: center;
  align-items: center;
  width: ${iconSize}px;
  height: ${iconSize}px;
  font-size: ${iconSize / 2}px!important;
  border-radius: 100%;
  background: ${props => props.color};
  color: #fff;
  box-shadow: 0 2px 20px 3px rgba(0, 0, 0, .2);
`;

const Body = styled.div`
  padding: 10px 20px;
  min-height: 100px;
  margin-top: ${iconSize / 2}px;
`;

const Title = styled.div`
  margin-left: ${iconSize + 10}px;
  font-weight: bold;
`;

const Row = styled.div`
  display: table-row;
`;

const Label = styled.div`
  display: table-cell;
  font-weight: bold;
  padding: 10px 20px 10px 0;
`;

const Value = styled.div`
  display: table-cell;
  &.card-type__price {
    color: #e83139;
  }
  a {
    margin-left: 10px;
  }
`;