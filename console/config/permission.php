<?php

return [


    "网站基本权限" => [
        '首页' => '/site/index',
        '注销' => '/site/logout',
    ],
    "管理员管理" => [
        '管理员菜单' => '/auth-user',
        '管理员列表' => '/auth-user/index',
        '管理员创建' => '/auth-user/create',
        '管理员信息修改' => '/auth-user/update',
        '管理员密码重置' => '/auth-user/reset-pwd',
        '管理员删除' => '/auth-user/delete',
    ],
    "日志管理" => [
        '日志菜单' => '/operation-log',
        '日志列表' => '/operation-log/index',
        '日志详情' => '/operation-log/view',
    ],
    "rbac" => [
        'admin' => '/admin/*',
        'role-all' => '/role/*',
        'role' => '/role',
    ],
    "dev" => [
        'debug' => '/debug/*',
        'gii' => '/gii/*'
    ],
];

