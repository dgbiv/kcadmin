<?php
namespace common\behaviors;

use yii\base\Behavior;
use yii\db\BaseActiveRecord;

/**
 * Created by PhpStorm.
 * User: iron
 * Date: 2018/9/15
 * Time: 12:26
 */
class SaveTimeBehavior extends Behavior
{
    public $createdAttribute = 'created_at';

    public $updatedAttribute = 'updated_at';

    public $attributes = [];
    private $_map;
    public function init()
    {
        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->createdAttribute, $this->updatedAttribute],//准备数据 在插入之前更新created和updated两个字段
                BaseActiveRecord::EVENT_BEFORE_UPDATE => [$this->updatedAttribute]// 在更新之前更新updated字段
            ];
        }

        $this->_map = [
            $this->createdAttribute => time(),//在这里你可以随意格式化
            $this->updatedAttribute => time(),
        ];
    }

    public function events()
    {
        return array_fill_keys(array_keys($this->attributes), 'evaluateAttributes');
    }

    public function evaluateAttributes($event)
    {
        if (!empty($this->attributes[$event->name])) {
            $attributes = $this->attributes[$event->name];
            foreach ($attributes as $attribute) {
                if (array_key_exists($attribute, $this->owner->attributes)) {
                    $this->owner->$attribute = $this->getValue($attribute);
                }
            }
        }
    }

    protected function getValue($attribute)
    {
        return $this->_map[$attribute];
    }
}