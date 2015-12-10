<?php

namespace humhub\modules\humhubchat\models;

use Yii;

/**
 * This is the model class for table "user_chat_message".
 *
 * @property integer $id
 * @property string $author
 * @property string $gravatar
 * @property string $text
 * @property string $ts
 */
class UserChatMessage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user_chat_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author', 'gravatar', 'text'], 'required'],
            [['ts'], 'safe'],
            [['author'], 'string', 'max' => 16],
            [['gravatar', 'text'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author' => 'Author',
            'gravatar' => 'Gravatar',
            'text' => 'Text',
            'ts' => 'Ts',
        ];
    }
}
