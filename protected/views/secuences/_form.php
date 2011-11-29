<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'secuences-form',
	'enableAjaxValidation'=>false,
	'method'=>'post',
)); ?>

	<?php echo Yii::t('secuences','FieldsRequired'); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($model,'secuence_step'); ?>
				<span>
					<?php
						echo $form->textField($model,'secuence_step', array('class'=>'betterform','style'=>'width:95%','tabindex'=>1));
						echo CHtml::label(Yii::t('secuences','FormSecuencesStep'), CHtml::activeId($model, 'secuence_step'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'secuence_responsetoAction'); ?>
				<span>
					<?php
						echo $form->textField($model,'secuence_responsetoAction',array('class'=>'betterform','style'=>'width:95%', 'maxlength'=>100,'tabindex'=>3));
						echo CHtml::label(Yii::t('secuences','FormSecuencesResponse'), CHtml::activeId($model, 'secuence_responsetoAction'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c50r">
			<div class="row">
				<?php echo $form->labelEx($model,'secuence_action'); ?>
				<span>
					<?php
						echo $form->textField($model,'secuence_action',array('class'=>'betterform','style'=>'width:95%', 'maxlength'=>100,'tabindex'=>2));
						echo CHtml::label(Yii::t('secuences','FormSecuencesAction'), CHtml::activeId($model, 'secuence_action'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'secuenceTypes_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'secuenceTypes_id',CHtml::listData($types, 'secuenceTypes_id', 'secuenceTypes_name'),array('style'=>'width:95%','class'=>'betterform','empty'=>Yii::t('secuences', 'selectOption'),'tabindex'=>4));
						echo CHtml::label(Yii::t('secuences','FormSecuencesTypes'), CHtml::activeId($model, 'secuenceTypes_id'), array('class'=>'labelhelper'));
					?>	
				</span>
			</div>
		</div>
	</div>
	
	<?php echo $form->hiddenField($model,'case_id',array('value'=>(isset($model->case_id)) ? CHtml::encode($model->case_id) : CHtml::encode($_GET['owner']))); ?>

	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary','tabindex'=>5)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big','tabindex'=>6)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->