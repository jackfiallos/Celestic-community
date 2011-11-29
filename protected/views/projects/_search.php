<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'project_id'); ?>
		<?php echo $form->textField($model,'project_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'project_name'); ?>
		<?php echo $form->textField($model,'project_name',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'project_description'); ?>
		<?php echo $form->textArea($model,'project_description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'project_startDate'); ?>
		<?php echo $form->textField($model,'project_startDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'project_endDate'); ?>
		<?php echo $form->textField($model,'project_endDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'project_active'); ?>
		<?php echo $form->textField($model,'project_active'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site','search')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->