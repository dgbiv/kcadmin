import React, {Component} from "react";
import GridLayout from "react-grid-layout";
import styled from 'styled-components'
import "react-grid-layout/css/styles.css";
import "react-resizable/css/styles.css";
import {inject, observer} from 'mobx-react'
import Module from './Module'

@inject('store')
@observer
export default class GridEditor extends React.Component {
  onLayoutChange = (layout) => {
    const {changeLayout} = this.props.store;
    changeLayout(layout);
  };

  render() {
    const {layout, attrs, add} = this.props.store;

    return (
      <GridLayout
        layout={layout}
        cols={12}
        rowHeight={30}
        width={300}
        onLayoutChange={this.onLayoutChange}
      >
        {layout.map(item => (
          <div key={item.i}>
            <Module attr={attrs[item.i]} />
          </div>
        ))}
      </GridLayout>
    )
  }
}