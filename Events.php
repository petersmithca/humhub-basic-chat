<?php

namespace humhub\modules\humhubchat;

use Yii;
use yii\helpers\Url;
use humhub\modules\humhubchat\widgets\ChatFrame;
use humhub\modules\humhubchat\Assets;
use humhub\modules\humhubchat\controllers\ChatController;
use humhub\modules\humhubchat\models\UserChatMessage;

/**
 * Description of Events
 *
 * @author luke
 */
class Events extends \yii\base\Object
{
    public static function onTopMenuInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

         $event->sender->view->registerAssetBundle(Assets::className());
    }


    public static function onDashboardSidebarInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $event->sender->addWidget(ChatFrame::className(), array(), array('sortOrder' => 0));

    }

     public static function onProfileSidebarInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }
        $user = $event->sender->user;
        if ($user != null) {
            $event->sender->addWidget(ChatFrame::className(), array('contentContainer' => $user), array('sortOrder' => 0));
        }
    }

    public static function onDailyCron($event)
    {
       // delete old chats
        UserChatMessage::deleteAll('ts < DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 DAY)');

    }
}
