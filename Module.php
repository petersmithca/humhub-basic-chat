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

}
