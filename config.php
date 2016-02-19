<?php
namespace humhub\modules\humhubchat;

return [
    'id' => 'humhub-chat',
    'class' => 'humhub\modules\humhubchat\Module',
    'namespace' => 'humhub\modules\humhubchat',
    'events' => [
        [
            'class' => \humhub\commands\CronController::className(),
            'event' => \humhub\commands\CronController::EVENT_ON_DAILY_RUN,
            'callback' => [
                'humhub\modules\humhubchat\Events',
                'onDailyCron'
            ]
        ],
        [
            'class' => \humhub\modules\dashboard\widgets\Sidebar::className(),
            'event' => \humhub\modules\dashboard\widgets\Sidebar::EVENT_INIT,
            'callback' => array(
                'humhub\modules\humhubchat\Events',
                'addChatFrame'
            )
        ],
        [
            'class' => \humhub\modules\space\widgets\Sidebar::className(),
            'event' => \humhub\modules\space\widgets\Sidebar::EVENT_INIT,
            'callback' => array(
                'humhub\modules\humhubchat\Events',
                'addChatFrame'
            )
        ],
        [
            'class' => \humhub\modules\user\widgets\ProfileSidebar::className(),
            'event' => \humhub\modules\user\widgets\ProfileSidebar::EVENT_INIT,
            'callback' => array(
                'humhub\modules\humhubchat\Events',
                'addChatFrame'
            )
        ],
        [
            'class' => \humhub\modules\admin\widgets\AdminMenu::className(),
            'event' => \humhub\modules\admin\widgets\AdminMenu::EVENT_INIT,
            'callback' => [
                'humhub\modules\humhubchat\Events',
                'onAdminMenuInit'
            ]
        ]
    ]
];
?>
