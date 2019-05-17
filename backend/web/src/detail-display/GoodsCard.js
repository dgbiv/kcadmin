import React from "react";
import PropTypes from "prop-types";
import styled from "styled-components";

export default class GoodsCard extends React.Component {
    constructor(props) {
        super(props);
        this.state = {};
    }

    static propTypes = {
        goodsInfo: PropTypes.object.isRequired
    };

    static defaultProps = {
        goodsInfo: {}
    };

    render() {
        const {goodsInfo} = this.props;

        return (
            <Root>
                <Left><img src={'//demo.kcshop.pro' + goodsInfo.goods_img} alt=""/></Left>
                <Right>
                    <div>{goodsInfo.goods_name}</div>
                    <Price>￥{goodsInfo.shop_price}</Price>
                </Right>
                <Other>x{goodsInfo.goods_number}</Other>
            </Root>
        )
    }
}

const Root = styled.div`
  display: flex;
  padding: 10px 20px;
  margin: 10px 0;
  &:not(:last-child) {
    border-bottom: 1px solid #eee;
  }
`;

const imageSize = 60;
const Left = styled.div`
  width: ${imageSize}px;
  height: ${imageSize}px;
   img {
    max-width: 100%;
    max-height: 100%;
   }
`;

const Right = styled.div`
  flex-grow: 1;
  margin-left: 10px;
  display: flex;
  flex-direction: column;
  justify-content: space-around;
`;

const Other = styled.div`
  display: flex;
  align-items: center;
`;

const Price = styled.div`
  display: flex;
  align-items: center;
  color: #e83139;
`;