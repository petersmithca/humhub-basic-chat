<?php

use yii\db\Migration;

class uninstall extends Migration
{

    public function up()
    {
        $this->dropTable('user_chat_message');
    }

    public function down()
    {
        echo "m151210_181254_create does not support migration down.\n";
        return false;
    }

}
