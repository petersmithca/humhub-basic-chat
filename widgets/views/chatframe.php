<?php

use yii\helpers\Html;
use humhub\models\Setting;
?>
<div class="panel">
  <div class="panel-heading">
    <?php echo '<strong>Community</strong> Chat'; ?>
  </div>
  <div class="panel-body">
    <div id="chatContainer">
      
      <div id="chatLineHolder">
      </div>
      
      <div id="chatUsers" class="rounded">
      </div>
      <div id="chatBottomBar" class="rounded">
        <div class="tip">
        </div>
        <form id="submitForm" action="#" method="post" _lpchecked="1">
          <div class="input-group">
            <input id="chatText" type="text" name="chatText" placeholder="Type Message ..." class="form-control" maxlength="510">
            <span class="input-group-btn">
              <button type="submit" value="Submit" class="btn btn-primary btn-flat">
                Send
              </button>
            </span>
          </div>
        </form>
        
      </div>
      
    </div>
  </div>
</div>
