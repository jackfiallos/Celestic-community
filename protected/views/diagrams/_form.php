<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'diagrams-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'diagram_name'); ?>
		<?php echo $form->textField($model,'diagram_name',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'diagram_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'project_id'); ?>
		<?php echo $form->textField($model,'project_id'); ?>
		<?php echo $form->error($model,'project_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status_id'); ?>
		<?php echo $form->textField($model,'status_id'); ?>
		<?php echo $form->error($model,'status_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::button($model->isNewRecord ? 'Create' : 'Save', array('type'=>'submit', 'class'=>'button blue square')); ?>
		<?php echo CHtml::button('Reset', array('type'=>'reset', 'class'=>'button gray square')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->