<?php
namespace humhub\modules\humhubchat;

use yii\web\AssetBundle;
use humhub\models\Setting;

class Assets extends AssetBundle
{

    public $css = [
        'chat_bright.css'
    ];

    public $js = [
        'script.js'
    ];

    public function init()
    {
        $theme = Setting::Get('theme', 'humhubchat');
        if ($theme)
            $this->css = [
                $theme
            ];
        
        $this->sourcePath = dirname(__FILE__) . '/assets';
        parent::init();
    }
}
