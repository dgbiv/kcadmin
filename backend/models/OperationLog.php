<?php

namespace backend\models;
/**
 * Created by PhpStorm.
 * User: iron
 * Date: 19-1-15
 * Time: 下午7:40
 *
 * @property int $admin_id 管理员id
 * @property string $admin_name 管理员名
 * @property string $ip 客户端ip
 * @property string $admin_agent 客户端版本
 * @property string $path 访问路径
 * @property string $method http方法
 * @property array $params 各类参数
 * @property int $created_at 创建时间
 */

use yii\mongodb\ActiveRecord;

class OperationLog extends ActiveRecord
{
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'operation_log';
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return ['_id', 'admin_id', 'admin_name', 'ip', 'admin_agent', 'path', 'method', 'params', 'created_at'];
    }


    public function attributeLabels()
    {
        return [
            'admin_id'=> '管理员id',
            'admin_name'=> '管理员名称',
            'ip'=> '客户端IP',
            'admin_agent'=> '客户端版本',
            'path'=> '操作内容',
            'method'=> '请求方式',
            'params'=> '参数',
            'created_at'=> '访问时间'
        ];
    }
}