import React, {Component} from 'react';
import {render} from "react-dom";
import axios from 'axios'
import styled from 'styled-components'
import Previewer from './Previewer'
import PropsEditor from './PropsEditor'
import {Provider} from 'mobx-react'
import store from './store'
import DevTools from 'mobx-react-devtools';

const Wrapper = styled.div`
  display: flex;
`;

class AmazingCreator extends React.Component {
  render() {
    return (
      <Provider store={store}>
        <Wrapper>
          <Previewer />
          <PropsEditor />
          <DevTools />
        </Wrapper>
      </Provider>
    )
  }
}

render(<AmazingCreator />, document.getElementById('edit-home'));