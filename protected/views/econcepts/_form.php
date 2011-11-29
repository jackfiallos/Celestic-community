<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'expenses-concepts-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('expenses','FieldsRequired'); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($model,'expensesConcept_quantity'); ?>
				<span>
					<?php
						echo $form->textField($model,'expensesConcept_quantity',array('class'=>'betterform','style'=>'width:95%','tabindex'=>1));
						echo CHtml::label(Yii::t('expensesConcepts', 'FormExpensesConcept_quantity'), CHtml::activeId($model, 'expensesConcept_quantity'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c50r">
			<div class="row">
				<?php echo $form->labelEx($model,'expensesConcept_amount'); ?>
				<span>
					<?php
						echo $form->textField($model,'expensesConcept_amount',array('class'=>'betterform','style'=>'width:95%','tabindex'=>2));
						echo CHtml::label(Yii::t('expensesConcepts', 'FormExpensesConcept_amount'), CHtml::activeId($model, 'expensesConcept_amount'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'expensesConcept_description'); ?>
		<span>
			<?php
				echo $form->textArea($model,'expensesConcept_description',array('rows'=>10, 'cols'=>50,'style'=>'width:95%','class'=>'betterform','tabindex'=>3));
				echo CHtml::label(Yii::t('expensesConcepts', 'FormExpensesConcept_description'), CHtml::activeId($model, 'expensesConcept_description'), array('class'=>'labelhelper'));
			?>
		</span>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'expense_id',array('value'=>($model->isNewRecord)?$_GET['owner']:$model->expense_id)); ?>
		<?php echo $form->error($model,'expense_id'); ?>
	</div>
	
	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary','tabindex'=>4)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big','tabindex'=>5)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button','tabindex'=>6)); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->