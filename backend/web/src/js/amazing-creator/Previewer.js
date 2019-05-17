import React, {Component} from "react";
import GridEditor from './GridEditor'
import styled from 'styled-components'

export default class Previewer extends React.Component {
  state = {
    time: `${new Date().getHours()}:${new Date().getMinutes()}`
  };

  componentWillMount() {
    this.timer = setInterval(() => {
      const now = new Date();
      this.setState({
        time: `${now.getHours()}:${now.getMinutes().toString().padStart(2, '0')}`
      })
    }, 1000)
  }

  componentWillUnmount() {
    this.timer && clearInterval(this.timer);
  }

  render() {
    const {time} = this.state;
    // layout is an array of objects, see the demo for more complete usage

    return (
      <Wrapper>
        <Header>
          <StatusBar>
            <span>{time}</span>
          </StatusBar>
          <TitleBar>
            <span>首页</span>
          </TitleBar>
        </Header>
        <Content>
          <GridEditor />
        </Content>
        <Tabbar />
      </Wrapper>
    )
  }
}

const ratio = 16 / 9;
const width = 300;

const Wrapper = styled.div`
  display: flex;
  flex-direction: column;
  width: ${width}px;
  height: ${width * ratio}px;
  border: 1px solid #eee;
  border-radius: 10px;
  overflow-x: hidden;
  background: #fff;
  box-shadow: 0 0 40px 0 rgba(0, 0, 0, .1);
`;

const Header = styled.div`
  flex-shrink: 0;
  width: 100%;
  background-size: cover;
  background-repeat: no-repeat;
  text-align: center;
  font-size: 10px;
  margin-bottom: 3px;
`;

const SimulationBar = styled.div`
  width: 100%;
  background-size: contain;
  background-repeat: no-repeat;
`;

const StatusBar = styled(SimulationBar)`
  background-image: url("/img/status-bar.png");
  background-size: cover;
`;

const TitleBar = styled(SimulationBar)`
  display: flex;
  align-items: center;
  height: 38px;
  background-image: url("/img/weapp-status-bar.png");
  span {
    margin-left: 10px;
    font-size: 1.4em;
  }
`;

const Tabbar = styled(SimulationBar)`
  flex-shrink: 0;
  width: 100%;
  height: 40px;
  border-top: 1px solid #eee;
  background-size: contain;
  background-repeat: no-repeat;
  background-image: url('/img/tabbar.png');
`;

const Content = styled.div`
  flex-grow: 1;
  overflow-y: auto;
`;
