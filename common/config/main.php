<?php
return [

    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
        '@root' => dirname(dirname(__DIR__)),
        '@kcdev/yii2/web' => "@vendor/kcdev/yii2/web",
        '@kcdev/sms' => '@vendor/kcdev/sms',
        '@kcdev/blobt/guantong' => '@vendor/kcdev/blobt/guantong',
        '@kcdev/yii2/assets' => "@vendor/kcdev/yii2/assets",
        '@lmz/exchange' => "@vendor/lmz/exchange", //新增exchangegit
        '@endroid' => '@vendor/endroid/qr-code',
        '@kcdev/blobt/baiduapi' => '@vendor/kcdev/blobt/baidu-api/src',
        '@commentPath' => '@backend/web/',//评论图片上传路径
    ],
    'components' => [
        'mongodb' => [
            'class' => '\yii\mongodb\Connection',
            'dsn' => 'mongodb://127.0.0.1:27017/smart_park',
            'options' => [
//                "username" => "",
//                "password" => ""
            ]
        ],
    ],
    'timeZone' => 'Asia/Chongqing',
];
