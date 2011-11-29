<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'validations-form',
	'enableAjaxValidation'=>false,
	'method'=>'post',
)); ?>

	<?php echo Yii::t('validations','FieldsRequired'); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>

	<div class="row">
		<?php echo $form->labelEx($model,'validation_field'); ?>
		<span>
			<?php echo $form->textField($model,'validation_field',array('class'=>'betterform','size'=>45,'maxlength'=>45)); ?>
			<?php echo CHtml::label(Yii::t('validations','FormValidationField'), CHtml::activeId($model, 'secuence_step'), array('class'=>'labelhelper')); ?>
		</span>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'validation_description'); ?>
		<span>
			<?php echo $form->textField($model,'validation_description',array('class'=>'betterform','size'=>45,'maxlength'=>150)); ?>
			<?php echo CHtml::label(Yii::t('validations','FormValidationDescription'), CHtml::activeId($model, 'secuence_step'), array('class'=>'labelhelper')); ?>
		</span>
	</div>

	<?php echo $form->hiddenField($model,'case_id',array('value'=>(isset($model->case_id)) ? CHtml::encode($model->case_id) : CHtml::encode($_GET['owner']))); ?>

	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary')); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big')); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->