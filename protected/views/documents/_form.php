<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'documents-form',
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
	),
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('documents','FieldsRequired'); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($model,'document_name'); ?>
				<span>
					<?php
						echo $form->textField($model,'document_name',array('class'=>'betterform','style'=>'width:95%','maxlength'=>45,'tabindex'=>1));
						echo CHtml::label(Yii::t('documents','FormDocumentName'), CHtml::activeId($model, 'document_name'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			
		</div>
		<div class="c50r">
			<div class="row">
				<?php echo $form->labelEx($model,'image'); ?>
				<span>
					<?php
						echo CHtml::activeFileField($model, 'image', array('tabindex'=>2));
						echo CHtml::label(Yii::t('documents','FormDocumentImage'), CHtml::activeId($model, 'image'), array('class'=>'labelhelper','style'=>'width:95%'));
					?>
				</span>				
			</div>
		</div>
	</div>	
	
	<div class="row">
		<?php echo $form->labelEx($model,'document_description'); ?>
		<span>
			<?php
				echo $form->textArea($model,'document_description',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>3));
				echo CHtml::label(Yii::t('documents','FormDocumentDescription'), CHtml::activeId($model, 'document_description'), array('class'=>'labelhelper'));
			?>
		</span>
	</div>
	
	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary', 'tabindex'=>4)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big', 'tabindex'=>5)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->