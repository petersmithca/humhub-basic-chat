<?php
namespace humhub\modules\humhubchat\widgets;

use humhub\components\Widget;

class ChatFrame extends Widget
{

    public $contentContainer;

    public function run()
    {
        return $this->render('chatframe', array());
    }
}

