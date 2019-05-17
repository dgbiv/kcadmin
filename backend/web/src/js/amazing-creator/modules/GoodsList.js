import React from "react";
import PropTypes from "prop-types";
import styled from 'styled-components'

export default class GoodsList extends React.Component {
  static propTypes = {
    name: PropTypes.string,
    title: PropTypes.string,
    type: PropTypes.string,
    onChangeTitle: PropTypes.func,
    onChangeType: PropTypes.func
  };

  render() {
    const {name, title, type, onChangeTitle, onChangeType} = this.props;
    const typeImageTable = {
      grid: '/img/grid.png',
      list: '/img/list.png',
      horizScroll: '/img/scroll.png',
    };

    const options = [
      {value: 1, label: '横排(右滚动)'},
      {value: 2, label: '多列(三列)'},
      {value: 3, label: '竖排(下滚动)'}
    ];

    return (
      <Wrapper>
        <Header>
          <Title>{title}</Title>
          <More>查看更多></More>
        </Header>
        <Image src={typeImageTable[type]} draggable="false" />
      </Wrapper>
    )
  }
}

const Wrapper = styled.div`
  width: 100%;
`;

const Header = styled.div`
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 5px 10px;
`;

const Title = styled.div`
  color: #e2314a;
  font-weight: bold;
`;

const More = styled.div`
  font-size: .8em;
  color: #aaa;
  cursor: pointer;
  &:active {
    color: #666;
  }
`;

const Image = styled.img`
  width: 100%;
`;