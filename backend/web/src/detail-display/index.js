import React from "react";
import PropTypes from "prop-types";
import styled from "styled-components";
import ReactDOM from "react-dom";
import GoodsCard from './GoodsCard'
import Card from './Card'
import getOrderStatus from './getOrderStatus'
import {Collapse} from 'antd';
const Panel = Collapse.Panel;

export default class DetailDisplay extends React.Component {
    constructor(props) {
        super(props);
        this.state = {};
    }

    static defaultProps = {};

    static propTypes = {};

    render() {
        const {id, order_sn, goodsList, consignee, tel, created_at, pay_at,
            goods_fee, shipping_fee, pay_fee, discount_money, remark,
            postscript, pay_id, pay_type, shipping_type, goods_amount, province, city,
            district, address, shipping_sn, shipping_detail_link
        } = order_info;

        let userInfo = [
            {
                label: '备注',
                value: postscript,
            },
            {
                label: '配送方式',
                value: shipping_type,
            },
            {
                label: '支付来源',
                value: pay_id,
            },
            {
                label: '支付方式',
                value: pay_type,
            },
        ];

        if (shipping_type === '快递配送') {
            userInfo = [
                {
                    label: '地区',
                    value: province + city + district,
                },
                {
                    label: '详细地址',
                    value: address,
                },
                {
                    label: '联系人',
                    value: consignee,
                },
                {
                    label: '联系电话',
                    value: tel,
                },
                ...userInfo
            ]
        } else {
            userInfo = [
                {
                    label: '提货人',
                    value: consignee,
                },
                {
                    label: '联系电话',
                    value: tel,
                },
                ...userInfo
            ];
        }
        return (
            <Root>
                <CardList>
                    <Card
                        icon='info'
                        title='基本信息'
                        color='#55cbb8'
                        data={[
                            {
                                label: '订单状态',
                                value: getOrderStatus(order_info),
                            },
                            {
                                label: '订单号',
                                value: order_sn,
                            },
                            {
                                label: '发货单号',
                                value: shipping_sn,
                                link: {
                                    url: shipping_detail_link,
                                    text: '查看'
                                }
                            },
                            {
                                label: '数量',
                                value: goods_amount,
                            },{
                                label: '下单时间',
                                value: created_at,
                                type: 'time'
                            },
                            {
                                label: '支付时间',
                                value: pay_at,
                                type: 'time'
                            },
                        ]}
                    />
                    <Card
                        icon='contacts'
                        title='客户信息'
                        color='#6254a6'
                        data={userInfo}
                    />
                    <Card
                        icon='money'
                        title='价格'
                        color='#ef5e5c'
                        data={[
                            {
                                label: '商品价格',
                                value: goods_fee,
                                type: 'price'
                            },
                            {
                                label: '运费',
                                value: shipping_fee,
                                type: 'price',
                                prefix: '+'
                            },
                            {
                                label: '优惠金额',
                                value: discount_money,
                                type: 'price',
                                prefix: '-'
                            },
                            {
                                label: '优惠内容',
                                value: remark,
                            },
                            {
                                label: '实付金额',
                                value: pay_fee,
                                type: 'price'
                            },
                        ]}
                    />
                </CardList>
                <GoodsArea>
                    <Collapse>
                        <Panel header="商品列表" key="1">
                            <GoodsList>
                                {goodsList.map(goodsInfo => (
                                    <GoodsCard key={goodsInfo.id} goodsInfo={goodsInfo} />
                                ))}
                            </GoodsList>
                        </Panel>
                    </Collapse>
                </GoodsArea>
            </Root>
        )
    }
}

const Root = styled.div`

`;

const GoodsList = styled.div`
  
`;

const CardList = styled.div`
  display: flex;
  flex-wrap: wrap;
  margin: 10px -10px;
`;

const GoodsArea = styled.div`
  max-width: 500px;
  margin-bottom: 20px;
  .ant-collapse {
    border: none;
    box-shadow: 0 0 20px 0 rgba(0, 0, 0, .1);
  }
  .ant-collapse-header {
    background: #fbf5e8;
    color: #573e07!important;
    border: none;
  }
`;

ReactDOM.render(
    <DetailDisplay />,
    document.getElementById('detail-display')
);