<?php
namespace humhub\modules\humhubchat\forms;

use Yii;

class SettingsForm extends \yii\base\Model
{

    public $theme;

    public $timeout;

    public function rules()
    {
        return [
            [
                [
                    'theme',
                    'timeout'
                ],
                'safe'
            ],
            [
                [
                    'theme',
                    'timeout'
                ],
                'required'
            ],
            [
                [
                    'theme'
                ],
                'string',
                'max' => 255
            ],
            [
                [
                    'timeout'
                ],
                'integer',
                'min' => 0,
                'max' => '365'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'theme' => Yii::t('Humhub-chatModule.base', 'theme'),
            'timeout' => Yii::t('Humhub-chatModule.base', 'timeout')
        ];
    }
}
