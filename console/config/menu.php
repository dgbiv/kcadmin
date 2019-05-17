<?php

return [

    "权限管理" => [
        'data' => '{"icon":"permission"}',
        'children' => [
            ["角色管理", "/role"],
            ["管理员管理", "/auth-user"],
        ],
    ],
    "系统管理" => [
        'data' => '{"icon":"system"}',
        'children' => [
            ["系统操作日志", "/operation-log"],
        ],
    ],


];
