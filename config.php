<?php
namespace humhub\modules\humhubchat;
use humhub\modules\user\widgets\AccountMenu;
use humhub\modules\admin\widgets\AdminMenu;
use humhub\widgets\TopMenu;
use humhub\commands\CronController;
use humhub\modules\user\widgets\ProfileMenu;
use humhub\modules\space\widgets\Sidebar;
use humhub\modules\user\widgets\ProfileSidebar;


return [
    'id' => 'humhub-chat',
    'class' => 'humhub\modules\humhubchat\Module',
    'namespace' => 'humhub\modules\humhubchat',
     'events' => [
        ['class' => TopMenu::className(), 'event' => TopMenu::EVENT_INIT, 'callback' => ['humhub\modules\humhubchat\Events', 'onTopMenuInit']],
        ['class' => CronController::className(), 'event' => CronController::EVENT_ON_DAILY_RUN, 'callback' => ['humhub\modules\humhubchat\Events', 'onDailyCron']],
        ['class' => \humhub\modules\dashboard\widgets\Sidebar::className(), 'event' => \humhub\modules\dashboard\widgets\Sidebar::EVENT_INIT, 'callback' => array('humhub\modules\humhubchat\Events', 'onDashboardSidebarInit')],
        ['class' => Sidebar::className(), 'event' => Sidebar::EVENT_INIT, 'callback' => array('humhub\modules\humhubchat\Events', 'onSpaceSidebarInit')],
        ['class' => ProfileSidebar::className(), 'event' => ProfileSidebar::EVENT_INIT, 'callback' => array('humhub\modules\humhubchat\Events', 'onProfileSidebarInit')],
          

    ],
];
?>
