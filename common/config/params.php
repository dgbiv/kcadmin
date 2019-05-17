<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'local' => [
        'basicWeight' => 3,
        'basicFee' => 10,
        'extraFee' => 1,
    ],
    'other' => [
        'basicFee' => 12,
        'extraFee' => 3,
    ],
    'description' => 'pay order',
    'freeShippingAmount' => 299,

    //willeny begin
    'coupon' => [
        'category' => [
            '1' => '满减券',//抵扣券
            '2' => '现金券',
            '3' => '打折券',
//            '4' => '新人券',

        ],
        'scopeOfApplication' => [
            '1' => '全场通用',
//            '2' => '指定类使用',
            '3' => '指定商品使用',
//            '4' => '指定店铺使用'
        ]
    ],
    'becomeDistributionConditions' => [
        '0' => '无条件',
        '1' => '单次消费金额',
        '2' => '累计消费金额',
        '3' => '购买指定商品',
    ],
    'accountType' => [
        '0' => '手动打款',
//        '1'=>'打款到微信钱包',
        '2' => '打款到余额'
    ],
    'withdrawStatus' => [
        '0' => '未审核',
        '1' => '审核通过',
        '-1' => '审核不通过',
        '2' => '已打款'
    ],
    //willeny end
    'refund_reason' => [
        1 => '不想买了',
        2 => '其他渠道价格更低',
        3 => '误下单',
        4 => '商品买错了/地址写错了',
        5 => '发货太慢了',
        6 => '拼团失败自动退款'
    ],
    'after_sale_reason' => [
        1 => '7天无理由退货',
        2 => '质量问题',
        3 => '买错东西',
        4 => '商品不满意',
    ],
    'pay_type' => [
        1 => '微信支付',
        2 => '余额支付',
    ],
    'distributor_level'=>[
        1=>'一级',
        2=>'二级',
        3=>'三级'
    ],
    'redis'=>[
        'host'=>'127.0.0.1',
        'port'=>'6379',
        'psw'=>'73937393'
    ],
    /* 物流接口配置 */
    'logistics_config'=>[
        'logistics_appid' =>'73887',
        'logistics_secret' =>'85e067c34f094c9f8be1278557889c5e',
    ],


];
