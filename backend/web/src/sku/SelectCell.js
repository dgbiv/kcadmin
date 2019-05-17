import React from "react";
import styled from "styled-components";
import { Select, Spin, Icon, LocaleProvider } from 'antd';
import PropTypes from 'prop-types'
import axios from 'axios'

const Option = Select.Option;

const antIcon = <Icon type="loading" style={{ fontSize: 24 }} spin />;

export default class SelectCell extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            skuList: null
        };
    }

    static defaultProps = {
        onChange: () => {}
    };

    static propTypes = {
        items: PropTypes.array,
        defaultValue: PropTypes.array,
        value: PropTypes.array,
        label: PropTypes.string,
        onChange: PropTypes.func
    };

    render() {
        const {items, label, value, defaultValue, onChange} = this.props;

        return (
            <Root>
                <Label>{label}</Label>
                {items ? (
                    <Select
                        mode="multiple"
                        style={{width: '100%'}}
                        placeholder="请选择要使用的规格"
                        defaultValue={defaultValue}
                        value={value}
                        onChange={onChange}
                    >
                        {items.map(item => (
                            <Option
                                key={item.id}
                                disabled={item.disabled}
                                title={item.name}
                                value={item.id}
                            >{item.name}</Option>
                        ))}
                    </Select>
                ) : <Spin indicator={antIcon} />}
            </Root>
        )
    }
}

const Root = styled.div`
  display: flex;
  align-items: center;
  width: 100%;
  margin: 10px 0;
  input.ant-select-search__field:not([type="submit"]):not([type="button"]):not([type="reset"]) {
    padding: 1px;
    background: none!important;
  }
`;

const Label = styled.div`
  width: 100px;
  margin-right: 20px;
`;