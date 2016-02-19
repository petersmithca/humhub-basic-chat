<?php
namespace humhub\modules\humhubchat\models;

use Yii;

/**
 * This is the model class for table "user_chat_message".
 *
 * @property integer $id
 * @property string $message
 * @property string $created_at
 * @property integer $created_by
 */
class UserChatMessage extends \humhub\components\ActiveRecord
{

    public static function tableName()
    {
        return 'user_chat_message';
    }

    public function rules()
    {
        return [
            [
                [
                    'message'
                ],
                'required'
            ],
            [
                [
                    'message'
                ],
                'safe'
            ]
        ];
    }

    public function getUser()
    {
        return $this->hasOne(\humhub\modules\user\models\User::className(), [
            'id' => 'created_by'
        ]);
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('Humhub-chatModule.base', 'ID'),
            'message' => Yii::t('Humhub-chatModule.base', 'message'),
            'created_at' => Yii::t('Humhub-chatModule.base', 'created'),
            'created_by' => Yii::t('Humhub-chatModule.base', 'author')
        ];
    }
}
