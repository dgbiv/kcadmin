export default function getStatus(order) {
    if (!order) return '无';
    let orderStatus = parseInt(order.order_status);
    let payStatus = parseInt(order.pay_status);
    let shippingStatus = parseInt(order.shipping_status);

    if (orderStatus === 1 && payStatus === 1) {
        return '付款确认中'
    } else if (orderStatus === 1 && payStatus === 2) {
        // 物流状态
        let shippingStatusText;
        if (order.shipping_type === 1) {
            shippingStatusText = ['待提货', '待提货', '已收货', '正在退货中'];
        } else {
            shippingStatusText = ['待发货', '待收货', '已收货', '正在退货中'];
        }
        return shippingStatusText[shippingStatus] || '已付款';
    }

    const orderStatusText = ['未确认', '待付款', '已取消', '申请退款中', '已关闭', '已退款', '已完成'];

    return orderStatusText[orderStatus] || '未知';
}
