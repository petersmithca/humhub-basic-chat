<?php

namespace humhub\modules\humhubchat;

use yii\helpers\Url;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentContainerActiveRecord;

class Module extends \humhub\modules\content\components\ContentContainerModule
{



    /**
     * @inheritdoc
     */
    public function disable()
    {
        parent::disable();
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::className(),
        ];
    }

    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        if ($container instanceof Space) {
            return "Adds Chat Functionality. Uses Code and Logic from http://tutorialzine.com/2010/10/ajax-web-chat-php-mysql/";
        }
    }

    /**
     * @inheritdoc
     */
    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        parent::disableContentContainer($container);

    }

}
