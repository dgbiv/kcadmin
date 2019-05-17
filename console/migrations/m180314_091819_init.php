<?php

use yii\db\Migration;

/**
 * Class m180314_091819_init
 */
class m180314_091819_init extends Migration
{
   
    public function up()
    {
        $sql = file_get_contents(__DIR__."/sql/init.sql");
        $this->execute($sql);
    }

    public function down()
    {
        $this->dropTable("auth_assignment");
        $this->dropTable("auth_item");
        $this->dropTable("auth_item_child");
        $this->dropTable("auth_rule");
        $this->dropTable("auth_user");
        $this->dropTable("menu");
        $this->dropTable("user");
        return true;
    }
}
