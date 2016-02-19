<?php
use yii\db\Schema;
use yii\db\Migration;
use humhub\modules\humhubchat\models\UserChatMessage;

class m151210_181254_create extends Migration
{

    public function up()
    {
        $this->createTable(UserChatMessage::tableName(), [
            'id' => $this->primaryKey(),
            'author' => $this->string(16)
                ->notNull(),
            'gravatar' => $this->string(255)
                ->notNull(),
            'text' => $this->string(255)
                ->notNull(),
            'ts' => 'timestamp NOT NULL default CURRENT_TIMESTAMP'
        ]);
    }

    public function down()
    {
        $this->dropTable(UserChatMessage::tableName());
        return true;
    }
}
