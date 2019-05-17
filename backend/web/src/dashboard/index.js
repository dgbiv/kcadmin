import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import PropTypes from 'prop-types'
import styled from 'styled-components'
import CircleCard from './CircleCard';
import LineChart from './LineChart'
import OverviewCard from './OverviewCard.js'
import '../styles/dashboard.scss'
import 'rc-switch/assets/index.css'
import Table from './Table'

const {total, sales, monthlySales, users, topGoods, topOrders, DAU,
    dailyConversionRate, monthlyConversionRate} = data;
const {salesAmount, salesCount, orderCount, goodsCount, userCount} = total;

const fixedSales = sales.map(i => ({
    ...i,
    value: Number.parseFloat(i.value).toFixed(2)
}));

const fixedonthlySales = monthlySales.map(i => ({
    ...i,
    value: Number.parseFloat(i.value).toFixed(2)
}));

const salesData = [
    {
        label: '30天',
        data: fixedSales
    },
    {
        label: '15天',
        data: fixedSales.slice(15),
    },
    {
        label: '12个月',
        data: fixedonthlySales.reverse(),
        key: 'month'
    },
];

const topGoodsData = [
    {
        label: '销量最高',
        highlightCol: 1,
        data: topGoods.count.map(i => ({
            '商品名称': i.name,
            '销量': i.count,
            '销售额': i.sales
        }))
    },
    {
        label: '销售额最高',
        highlightCol: 2,
        data: data.topGoods.amount.map(i => ({
            '商品名称': i.name,
            '销量': i.count,
            '销售额': i.sales
        }))
    },
];


const CirclePanel = styled.div`
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  height: 420px;
`;

const dashboard = (
    <div>
        <div className="row">
            <div className="total-display">
                <OverviewCard image='/img/money.png' title='总营业额' number={salesAmount} isPrice />
                <OverviewCard image='/img/sales.png' title='总销量' number={salesCount} />
                <OverviewCard image='/img/order.png' title='订单数量' number={orderCount} />
                <OverviewCard image='/img/goods.png' title='商品数量' number={goodsCount} />
                <OverviewCard image='/img/user.png' title='用户数量' number={userCount} />
            </div>
        </div>
        <div className="row">
            <LineChart
                title='最近<label>营业额'
                label='营业额'
                prefix='￥'
                data={salesData}
                color='#ed862b'
            />
            <CirclePanel>
                <CircleCard
                    title='当日订单转化率'
                    percent={dailyConversionRate.rate}
                    growth={dailyConversionRate.growth}
                    color='#705fff'
                />
                <CircleCard
                    title='当月订单转化率'
                    percent={monthlyConversionRate.rate}
                    growth={monthlyConversionRate.growth}
                    color='#eb607e'
                />
            </CirclePanel>
            {/*<Table*/}
                {/*title='<label>的商品'*/}
                {/*data={topGoodsData}*/}
                {/*priceCol={2}*/}
                {/*width='300px'*/}
            {/*/>*/}
        </div>
        <div className="row">
            <LineChart
                title='最近<days>天日活跃用户数'
                label='日活量'
                unit='人'
                dateCount={4}
                data={[{data: DAU}]}
                color='#17e7ae'
            />
            <LineChart
                title='最近<days>天新增用户数'
                label='新增用户数'
                unit='人'
                data={[{data: users}]}
                color='#17e7ae'
            />

            {/*<Table*/}
                {/*title='单笔最高'*/}
                {/*data={[{*/}
                    {/*highlightCol: 1,*/}
                    {/*data: topOrders.map(i => ({*/}
                        {/*'名字': i.user.name,*/}
                        {/*'金额': i.info.pay_fee*/}
                    {/*}))*/}
                {/*}]}*/}
                {/*color='#ed862b'*/}
                {/*priceCol={1}*/}
            {/*/>*/}
        </div>
    </div>
);

ReactDOM.render(
    dashboard,
    document.getElementById('react')
);