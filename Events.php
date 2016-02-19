<?php
namespace humhub\modules\humhubchat;

use Yii;
use yii\helpers\Url;
use humhub\modules\humhubchat\widgets\ChatFrame;
use humhub\modules\humhubchat\Assets;
use humhub\modules\humhubchat\models\UserChatMessage;

class Events extends \yii\base\Object
{

    public static function onDashboardSidebarInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }
        $event->sender->view->registerAssetBundle(Assets::className());
        $event->sender->addWidget(ChatFrame::className(), array(), array(
            'sortOrder' => 0
        ));
        $event->sender->view->registerjsVar('chat_ListUsers', Url::to([
            '/humhub-chat/chat/users'
        ]));
        $event->sender->view->registerjsVar('chat_Submit', Url::to([
            '/humhub-chat/chat/submit'
        ]));
        $event->sender->view->registerjsVar('chat_GetChats', Url::to([
            '/humhub-chat/chat/chats'
        ]));
    }

    public static function onProfileSidebarInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }
        $user = $event->sender->user;
        if ($user != null) {
            $event->sender->view->registerAssetBundle(Assets::className());
            $event->sender->addWidget(ChatFrame::className(), array(), array(
                'sortOrder' => 0
            ));
            $event->sender->view->registerjsVar('chat_ListUsers', Url::to([
                '/humhub-chat/chat/users'
            ]));
            $event->sender->view->registerjsVar('chat_Submit', Url::to([
                '/humhub-chat/chat/submit'
            ]));
            $event->sender->view->registerjsVar('chat_GetChats', Url::to([
                '/humhub-chat/chat/chats'
            ]));
        }
    }

    public static function onSpaceSidebarInit($event)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }
        
        $space = $event->sender->space;
        
        $event->sender->view->registerAssetBundle(Assets::className());
        $event->sender->addWidget(ChatFrame::className(), array(), array(
            'sortOrder' => 0
        ));
        $event->sender->view->registerjsVar('chat_ListUsers', Url::to([
            '/humhub-chat/chat/users'
        ]));
        $event->sender->view->registerjsVar('chat_Submit', Url::to([
            '/humhub-chat/chat/submit'
        ]));
        $event->sender->view->registerjsVar('chat_GetChats', Url::to([
            '/humhub-chat/chat/chats'
        ]));
    }

    public static function onDailyCron($event)
    {
        // delete old chats
        UserChatMessage::deleteAll('ts < DATE_ADD(CURRENT_TIMESTAMP, INTERVAL -1 DAY)');
    }
}
