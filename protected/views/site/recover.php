<?php $this->pageTitle = Yii::app()->name . ' - '.Yii::t('site','ForgottenPassword'); ;?>
<h2 class="login">
	<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl."/images/celestic.png",CHtml::encode(Yii::app()->name).' v.'.Yii::app()->params['appVersion']), Yii::app()->createUrl('site/index')); ?>
</h2>
<h3><?php echo Yii::t('site','ForgottenPassword'); ?></h3>
<p>
	<?php echo Yii::t('site','restorePassword'); ?>
</p>
<?php if(Yii::app()->user->hasFlash('PasswordSuccessChanged')):?>
    <div class="info notification_success" id="CommentMessage">
        <?php echo Yii::app()->user->getFlash('PasswordSuccessChanged'); ?>
    </div><br />
<?php endif; ?>
<?php
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'user-recover-form',
		'enableAjaxValidation'=>false,
	));
?>
	<div class="input-module box" style="height:100px;">
		<div class="field last">
			<h4><?php echo $form->labelEx($model,'user_email'); ?></h4>
			<span class="input over"><?php echo $form->textField($model,'user_email', array('class'=>'betterform','tabindex'=>1)); ?></span>
			<?php echo $form->error($model,'user_email'); ?>
		</div>
	</div>
	<div class="buttons">
		<?php echo CHtml::button(Yii::t('site','send'), array('type'=>'submit', 'class'=>'button big primary','tabindex'=>2)); ?>
	</div>
<?php $this->endWidget(); ?>