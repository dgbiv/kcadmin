<?php

namespace common\event;

use yii\base\Event;

/**
 * Created by PhpStorm.
 * User: iron
 * Date: 19-1-6
 * Time: 下午4:25
 */
class ConfirmPaymentEvent extends Event
{
    public $order;
    public $data;
}