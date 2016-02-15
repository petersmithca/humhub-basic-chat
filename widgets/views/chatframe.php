<?php

use yii\helpers\Html;
use humhub\models\Setting;
?>
<div class="panel">
    <div class="panel-heading"><?php echo '<strong>Humhub</strong> Chat'; ?></div>
    <div class="panel-body">
        <div id="chatContainer">

            <div id="chatLineHolder"></div>

            <div id="chatUsers" class="rounded"></div>
            <div id="chatBottomBar" class="rounded">
                <div class="tip"></div>
                <form id="submitForm" method="post" action="">
                    <input id="chatText" name="chatText" class="rounded" maxlength="255" />
                    <input type="submit" class="blueButton" value="Submit" />
                </form>

            </div>

        </div>
    </div>
</div>
