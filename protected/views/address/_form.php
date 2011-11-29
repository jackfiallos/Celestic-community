<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'address-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'address_text'); ?>
		<?php echo $form->textField($model,'address_text',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'address_text'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address_postalCode'); ?>
		<?php echo $form->textField($model,'address_postalCode',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'address_postalCode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address_phone'); ?>
		<?php echo $form->textField($model,'address_phone',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'address_phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address_web'); ?>
		<?php echo $form->textField($model,'address_web',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'address_web'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'city_id'); ?>
		<?php echo $form->textField($model,'city_id'); ?>
		<?php echo $form->error($model,'city_id'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->