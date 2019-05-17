<?php
/**
 * Created by PhpStorm.
 * User: travis
 * Date: 19-1-14
 * Time: 上午11:41
 */

namespace common\event;
use yii\base\Event;

class GroupbookingEvent extends Event
{
    public $order;
    public $order_id;
    public $groupbooking_id;
    public $regiment_id;
}