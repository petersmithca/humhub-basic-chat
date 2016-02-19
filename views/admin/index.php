<?php
use humhub\compat\CActiveForm;
use humhub\compat\CHtml;
use humhub\models\Setting;
use humhub\modules\humhubchat\controllers\AdminController;
?>
<div class="panel panel-default">
	<div class="panel-heading"><?=Yii::t('Humhub-chatModule.base', '<strong>Humhub-Chat</strong>'); ?></div>
	<div class="panel-body">
		<?php $form = CActiveForm::begin(['id' => 'hhc-settings-form']); ?>
			<?=$form->errorSummary($model); ?>
			<div class="form-group">
				<?=$form->labelEx($model, 'theme'); ?>
				<?=$form->dropDownList($model, 'theme', AdminController::getThemes(), ['class' => 'form-control', 'readonly' => Setting::IsFixed('theme', 'humhubchat')]); ?>
			</div>
			<div class="form-group">
				<?=$form->labelEx($model, 'timeout'); ?>
				<?=$form->textField($model, 'timeout', ['class' => 'form-control', 'readonly' => Setting::IsFixed('timeout', 'humhubchat')]); ?>
			</div>
			<p class="help-block"><?=Yii::t('Humhub-chatModule.base', 'amount of days, after messages will be deleted; min value is 0 (no deletion); max: 365'); ?></p>
			<?= CHtml::submitButton(Yii::t('Humhub-chatModule.base', 'save'), ['class' => 'btn btn-primary']); ?>
			<?=\humhub\widgets\DataSaved::widget(); ?>
		<?php CActiveForm::end(); ?>
	</div>
</div>
