<div class="form">

<?php
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
	'action'=>Yii::app()->createUrl('site/register'),
));
?>
	<h3><?php echo Yii::t('users','user_accountManager');?></h3>
	<div class="input-module box">
		<div class="subcolumns">
			<div class="c50l">
				<div class="field">
					<h4><?php echo $form->labelEx($model,'user_name'); ?></h4>
					<span class="input"><?php echo $form->textField($model,'user_name',array('class'=>'betterform','style'=>'width:95%','maxlength'=>45,'tabindex'=>2)); ?></span>
					<?php echo $form->error($model,'user_name',array('class'=>'errorMessage labelhelper')); ?>
				</div>
			</div>
			<div class="c50r">
				<div class="field">
					<h4><?php echo $form->labelEx($model,'user_lastname'); ?></h4>
					<span class="input"><?php echo $form->textField($model,'user_lastname',array('class'=>'betterform','style'=>'width:95%','maxlength'=>45,'tabindex'=>3)); ?></span>
					<?php echo $form->error($model,'user_lastname',array('class'=>'errorMessage labelhelper')); ?>
				</div>
			</div>
		</div>
		<div class="field">
			<h4><?php echo $form->labelEx($model,'user_email'); ?></h4>
			<span class="input"><?php echo $form->textField($model,'user_email',array('class'=>'betterform','style'=>'width:95%','maxlength'=>45,'tabindex'=>4)); ?></span>
			<?php echo $form->error($model,'user_email',array('class'=>'errorMessage labelhelper')); ?>
		</div>
		<div class="subcolumns">
			<div class="c50l">
				<div class="field last">
					<h4><?php echo $form->labelEx($model,'user_password'); ?></h4>
					<span class="input"><?php echo $form->passwordField($model,'user_password',array('value'=>'','class'=>'betterform','style'=>'width:95%','maxlength'=>20,'tabindex'=>5)); ?></span>
					<?php echo $form->error($model,'user_password',array('class'=>'errorMessage labelhelper')); ?>
				</div>
			</div>
			<div class="c50r">
				<div class="field last">
					<h4><?php echo $form->labelEx($model,'user_passwordRepeat'); ?></h4>
					<span class="input"><?php echo $form->passwordField($model,'user_passwordRepeat',array('value'=>'','class'=>'betterform','style'=>'width:95%','maxlength'=>20,'tabindex'=>6)); ?></span>
					<?php echo $form->error($model,'user_passwordRepeat',array('class'=>'errorMessage labelhelper')); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row buttons">
		<?php echo CHtml::button(Yii::t('site','create'), array('type'=>'submit', 'class'=>'button big primary', 'tabindex'=>7)); ?>
		<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big', 'tabindex'=>8)); ?>
	</div><br /><br />

<?php $this->endWidget(); ?>

</div>