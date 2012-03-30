<?php $this->pageTitle = Yii::app()->name." - Project Manager"; ?>
<h2 class="login">
	<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/celestic.png",CHtml::encode(Yii::app()->name).' v.'.Yii::app()->params['appVersion']); ?>
</h2>
<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableAjaxValidation'=>false,
));
?>
	<div class="input-module box">
		<div class="field">
			<h4><?php echo $form->labelEx($model,'username'); ?></h4>
			<span class="input over"><?php echo $form->textField($model,'username', array('class'=>'username betterform','tabindex'=>1)); ?></span>
			<?php echo $form->error($model,'username',array('class'=>'errorMessage labelhelper')); ?>
		</div>
		<div class="field last">
			<h4><?php echo $form->labelEx($model,'password'); ?></h4>
			<span class="input"><?php echo $form->passwordField($model,'password', array('class'=>'password betterform','tabindex'=>2)); ?> <?php echo CHtml::link(Yii::t('site', 'ForgottenPassword'), Yii::app()->controller->createUrl("site/recover")); ?></span>
			<?php echo $form->error($model,'password',array('class'=>'errorMessage labelhelper')); ?>
		</div>
	</div>
	<div class="buttons">
		<?php echo CHtml::button(Yii::t('site','send'), array('type'=>'submit', 'class'=>'button big primary','tabindex'=>4)); ?>
		<?php if(Yii::app()->params['multiplesAccounts']): ?>
		<div class="field last" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','alreadyToRegister'), Yii::app()->createUrl('site/register')); ?>
		</div>
		<?php endif; ?>
	</div>
<?php $this->endWidget(); ?>
<p style="border-top:1px solid #ccc; margin-top:5px;">
	<span class="corners"><?php echo Yii::app()->name; ?> <?php echo Yii::t('site','CelesticExplanation'); ?></span>
</p>