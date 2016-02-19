<?php
namespace humhub\modules\humhubchat\controllers;

use Yii;
use humhub\models\Setting;
use yii\helpers\Url;

class AdminController extends \humhub\modules\admin\components\Controller
{

    public function behaviors()
    {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::className(),
                'adminOnly' => true
            ]
        ];
    }

    public function actionIndex()
    {
        $form = new \humhub\modules\humhubchat\forms\SettingsForm();
        if ($form->load(Yii::$app->request->post())) {
            if ($form->validate()) {
                Setting::Set('theme', $form->theme, 'humhubchat');
                Setting::Set('timeout', $form->timeout, 'humhubchat');
                
                Yii::$app->session->setFlash('data-saved', Yii::t('Humhub-chatModule.base', 'Saved'));
                // $this->redirect(Url::toRoute('index'));
            }
        } else {
            $form->theme = Setting::Get('theme', 'humhubchat');
            $form->timeout = Setting::Get('timeout', 'humhubchat');
        }
        
        return $this->render('index', [
            'model' => $form
        ]);
    }

    public static function getThemes()
    {
        return [
            'chat_bright.css' => Yii::t('Humhub-chatModule.base', 'light theme'),
            'chat_dark.css' => Yii::t('Humhub-chatModule.base', 'dark theme')
        ];
    }
}
