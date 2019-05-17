<?php
return [
  'adminEmail' => 'admin@example.com',
    'logistics'=>[
        'SELF'=>'商家配送',
        'shunfeng' => '顺丰速运',
        'shentong' => '申通快递',
        'zhongtong' => '中通快递',
        'yuantong' => '圆通快递',
        'huitong' => '百世快递(原汇通)',
        'yunda' => '韵达快递',
        'yousu' => 'UC优速快递',
        'gnxb' => '邮政小包',
        'youzhengguonei' => '邮政包裹/平邮/挂号信',
        'jingdong' => '京东快递',
    ],

    //willeny begin
    'webuploader' => [
        // 后端处理图片的地址，value 是相对的地址
        'uploadUrl' => 'upload',
        // 多文件分隔符
        'delimiter' => ',',
        // 基本配置
        'baseConfig' => [
            'defaultImage' => '',
            'disableGlobalDnd' => true,
            'accept' => [
                'title' => 'Images',
                'extensions' => 'gif,jpg,jpeg,bmp,png',
                'mimeTypes' => 'image/*',
            ],
            'pick' => [
                'multiple' => false,
            ],
        ],
    ],
    "changeLog" => [
        'weapp' => '/wechat/weapp/CHANGELOG.md',
        'free-creator' => '/backend/web/free-creator/CHANGELOG.md',
    ],
    //willeny en
];
