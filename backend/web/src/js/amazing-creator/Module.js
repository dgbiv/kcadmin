import React from "react";
import styled from "styled-components";
import PropTypes from 'prop-types'
import Swiper from './modules/Swiper'
import GoodsList from './modules/GoodsList'

export default class Module extends React.Component {
  onLayoutChange = (layout) => {
    const {changeLayout} = this.props.store;
    changeLayout(layout);
  };

  static defaultProps = {
    layout: {},
    attr: {}
  };

  static propTypes = {
    layout: PropTypes.object,
    attr: PropTypes.object
  };

  render() {
    const {attr} = this.props;

    const moduleTable = {
      Swiper: <Swiper />,
      GoodsList: <GoodsList title={attr.title} type={attr.type} name={attr.data} />
    };

    if (moduleTable[attr.component]) {
      return (
        <Wrapper>
          {moduleTable[attr.component]}
          <HoverLayer>
            <EditButton className='fa fa-edit' />
          </HoverLayer>
        </Wrapper>
      )
    } else {
      return (
        <ErrorTip>{attr.component}</ErrorTip>
      )
    }
  }
}

const Wrapper = styled.div`
  position: relative;
  height: 100%;
  background: #fff;
  box-shadow: 0 0 40px 0 rgba(0, 0, 0, .1);
  overflow: hidden;
`;

const ErrorTip = styled.div`
  display: flex;
  word-break: break-all;
  height: 100%;
  justify-content: center;
  align-items: center;
  background: #efefef;
`;

const HoverLayer = styled.div`
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
  opacity: 0;
  background: rgba(0, 0, 0, .6);
  z-index: auto;
  transition: all .3s;
  &:hover {
  opacity: 1;
    z-index: 100;
  }
`;

const buttonSize = 40;
const EditButton = styled.div`
  display: flex;
  justify-content: center;
  align-items: center;
  width: ${buttonSize}px;
  height: ${buttonSize}px;
  border-radius: 100%;
  background: #fff;
  transition: all .1s;
  font-size: ${buttonSize / 2}px;
  font-weight: bold;
  color: #000;
  &:active {
    background:#eee;
    transform: scale(.8);
  }
`;