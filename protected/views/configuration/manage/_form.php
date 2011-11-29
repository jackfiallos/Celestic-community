<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'manageroles-form',
	'enableAjaxValidation'=>false,
	'action'=>$model->isNewRecord ? Yii::app()->createUrl('configuration/rolecreate') : Yii::app()->createUrl('configuration/showRoleDetails'),
)); ?>

	<?php echo Yii::t('authitems', 'FieldsRequired'); ?>

	<?php echo $form->errorSummary($model); ?>
	
	<?php
	if (!$model->isNewRecord)
		echo CHtml::hiddenField('id', $model->id);	
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site','save'), array('id'=>'lnkBtnSave','class'=>'button big primary'));?>
		<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button')); ?>
	</div>

<?php $this->endWidget(); ?>

</div>