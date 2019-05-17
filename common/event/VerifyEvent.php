<?php

namespace common\event;

use yii\base\Event;

/**
 * Created by PhpStorm.
 * User: iron
 * Date: 19-1-7
 * Time: 上午9:12
 */
class VerifyEvent extends Event
{
   public $order;
   public $data;
   public $type;
   public $operatorId;
   public $desc;
}