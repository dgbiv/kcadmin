import React from "react";
import styled from "styled-components";
import ReactDOM from "react-dom";
import { Select, Modal, Icon, LocaleProvider, Alert } from 'antd';
import zhCN from 'antd/lib/locale-provider/zh_CN';
import axios from 'axios'
import SelectCell from './SelectCell'

const Option = Select.Option;

const antIcon = <Icon type="loading" style={{ fontSize: 24 }} spin />;

const currentAttr = window.currentAttr.map(({id, name, value}) => ({id, name, value}));
const canNotDeleteAttr = window.canNotDeleteAttr;console.log(canNotDeleteAttr)
const allAttr = window.allAttr.map(({id, name, value}, index) => ({
    id: parseInt(id),
    name,
    disabled: canNotDeleteAttr.some(i => i.id === parseInt(id)),
    value: value.split(',').map(i => ({
        id: i,
        name: i,
        disabled: Boolean(canNotDeleteAttr[index]) && canNotDeleteAttr[index].value.some(j => j === i)
    }))
}));

class Sku extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            skuList: currentAttr
        };
    }

    static defaultProps = {};

    static propTypes = {};

    handleChangeAttr = (value, changedValue) => {
        let {skuList} = this.state;

        if (window.canNotDeleteAttr.find(i => i.id === parseInt(changedValue[0].props.value))) {
            Modal.error({
                title: '删除失败',
                content: '此规格已经被某些商品使用，不能删除，请先到对应的商品中删除此规格再操作',
            });
        } else {
            this.setState({
                skuList: value.map(id => {
                    let result = skuList.find(i => i.id === id);

                    if (!result) {
                        const attr = allAttr.find(i => i.id === id);
                        if (attr) {
                            const {id, name, value} = attr;
                            result = {id, name, value: value.map(i => i.id)};
                        }
                    }

                    return result;
                })
            })
        }
    };

    handleChangeAttrItem = (index, value, changedValue) => {
        let {skuList} = this.state;

        skuList[index].value = value;
        this.setState({
            skuList
        })
    };

    render() {
        const {skuList} = this.state;

        return (
            <Root>
                {(canNotDeleteAttr && canNotDeleteAttr.length > 0) && (
                    <Header>
                        <Alert
                            message="提示"
                            description="一些规格已经被使用，不能删除。若想删除请先到商品列表找到该商品，点击右侧的“添加 SKU”按钮进入“SKU 管理页面”，删除使用此规格的所有 SKU，再回到这里进行删除。"
                            type="info"
                            showIcon
                        />
                    </Header>
                )}
                <SelectGroup>
                    <SelectCell
                        label='规格类型'
                        items={allAttr}
                        value={skuList.map(i => i.id)}
                        onChange={this.handleChangeAttr}
                    />
                    {skuList && skuList.map((sku, index) => (
                        <SelectCell
                            key={sku.id}
                            label={sku.name}
                            items={allAttr.find(i => i.id === sku.id).value}
                            value={sku.value}
                            onChange={this.handleChangeAttrItem.bind(undefined, index)}
                        />
                    ))}
                    <input
                        type="hidden"
                        id="attribute"
                        name="attribute"
                        value={JSON.stringify(skuList.map(({id, value}) => ({id, value})))}
                    />
                </SelectGroup>
            </Root>
        )
    }
}

const Root = styled.div`
  
`;

const Header = styled.div`
  margin-bottom: 30px;
`;

const SelectGroup = styled.div`
  width: 100%;
`;

ReactDOM.render(
    <LocaleProvider locale={zhCN}>
        <Sku />
    </LocaleProvider>,
    document.getElementById('sku-choose')
);