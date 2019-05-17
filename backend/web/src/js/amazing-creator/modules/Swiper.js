import React from "react";
import styled from 'styled-components'

export default class Swiper extends React.Component {
  render() {
    return <Image src='/img/banner.png' draggable="false" />
  }
}

const Image = styled.img`
  width: 100%;
  height: 100%;
  user-select: none;
`;