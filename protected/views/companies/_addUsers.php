<div class="form">
	<?php
		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'companies-has-users-form',
			'enableAjaxValidation'=>true,
			'clientOptions'=>array(
				'validateOnSubmit'=>true, 
				'validateOnChange'=>false
			),
		));
	?>
   <?php echo $form->hiddenField($model,'company_id', array('value'=>$_GET['owner'])); ?>
    <div class="row">
        <?php echo $form->labelEx($model,'user_id'); ?>
        <?php echo $form->dropDownList($model,'user_id',CHtml::listData($users, 'user_id', 'CompleteName'),array('style'=>'width:140px', 'class'=>'betterform','empty'=>Yii::t('project', 'selectOption'))); ?>
        <?php echo $form->error($model,'user_id'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save')); ?>
    </div>
	<?php $this->endWidget(); ?>
</div><!-- form -->