<?php

namespace humhub\modules\humhubchat;

use Yii;
use yii\helpers\Url;
use humhub\modules\humhubchat\widgets\ChatFrame;
use humhub\modules\humhubchat\Assets;

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
        /*
        $event->sender->view->registerCssFile('/assets/b3b86249/flaticon.css');
        $event->sender->view->registerCssFile('/assets/b3b86249/pool.css');\
        */
         $event->sender->view->registerAssetBundle(Assets::className());
    }

    public static function onSpaceSidebarInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }
        $space = $event->sender->space;

        $event->sender->addWidget(ChatFrame::className(), array('contentContainer' => $space), array('sortOrder' => 0));

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
}
