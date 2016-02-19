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
        ]);
        
        $response = [];
        foreach ($query->all() as $entry) {
            $response[] = [
                'id' => $entry->id,
                'message' => $entry->message,
                'author' => [
                    'name' => $entry->user->displayName,
                    'gravatar' => $entry->user->getProfileImage()->getUrl(),
                    'profile' => Url::toRoute([
                        '/profile',
                        'uguid' => $entry->user->guid
                    ])
                ],
                'time' => [
                    'hours' => Yii::$app->formatter->asTime($entry->created_at, 'php:H'),
                    'minutes' => Yii::$app->formatter->asTime($entry->created_at, 'php:i')
                ]
            ];
        }
        
        Yii::$app->response->format = 'json';
        return $response;
    }

    public function actionSubmit()
    {
        if (($message_text = Yii::$app->request->post('chatText', null)) == null) {
            // if nothing was submitted
            return;
        }
        $chat = new UserChatMessage();
        $chat->message = $message_text;
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
        return [
            'online' => Yii::t('Humhub-chatModule.base', count($response) == 1 ? '{count} person online' : '{count} people online', [
                '{count}' => count($response)
            ]),
            'users' => $response
        ];
    }
}
