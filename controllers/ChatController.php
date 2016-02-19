<?php
namespace humhub\modules\humhubchat\controllers;

use Yii;
use humhub\modules\User\models\User;
use humhub\modules\humhubchat\models\UserChatMessage;
use yii\helpers\Url;

class ChatController extends \humhub\components\Controller
{

    public function behaviors()
    {
        return [
            'acl' => [
                'class' => \humhub\components\behaviors\AccessControl::className()
            ]
        ];
    }

    /**
     * Overview of all messages
     */
    public function actionChats()
    {
        $last_id = Yii::$app->request->get('lastID', 0);
        $query = UserChatMessage::find()->where([
            '>',
            'id',
            $last_id
        ])->asArray(true);
        
        $response = [];
        foreach ($query->all() as $chat) {
            $chat['time'] = [
                'date' => Yii::$app->formatter->asDate($chat['ts']),
                'time' => Yii::$app->formatter->asTime($chat['ts'], 'php:H:i'),
                'datetime' => Yii::$app->formatter->asDatetime($chat['ts'])
            ];
            $response[] = $chat;
        }
        
        Yii::$app->response->format = 'json';
        return $response;
    }

    public function actionSubmit()
    {
        if (($message_text = Yii::$app->request->get('chatText', null)) == null) {
            // if nothing was submitted
            return;
        }
        
        $user = Yii::$app->user->getIdentity();
        
        $chat = new UserChatMessage();
        $chat->text = $message_text;
        $chat->author = $user->displayName;
        $chat->gravatar = $user->getProfileImage()->getUrl();
        $chat->save();
    }

    public function actionUsers()
    {
        $query = \humhub\modules\user\components\Session::getOnlineUsers();
        $response = [];
        foreach ($query->all() as $user) {
            $response[] = [
                'name' => $user->displayName,
                'gravatar' => $user->getProfileImage()->getUrl(),
                'profile' => Url::toRoute([
                    '/profile',
                    'uguid' => $user->guid
                ])
            ];
        }
        
        Yii::$app->response->format = 'json';
        return $response;
    }
}
