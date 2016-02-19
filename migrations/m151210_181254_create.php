<?php
use yii\db\Schema;
use yii\db\Migration;

class m151210_181254_create extends Migration
{

    public function up()
    {
        $this->createTable('user_chat_message', [
            'id' => 'pk',
            'author' => 'varchar(16) NOT NULL',
            'gravatar' => 'varchar(255) NOT NULL',
            'text' => 'varchar(255) NOT NULL',
            'ts' => 'timestamp NOT NULL default CURRENT_TIMESTAMP'
        ]);
    }

    public function down()
    {
        $this->dropTable('user_chat_message');
        return true;
    }
}
