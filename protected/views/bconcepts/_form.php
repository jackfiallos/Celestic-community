<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'budgets-concepts-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('budgets','FieldsRequired'); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($model,'budgetsConcept_quantity'); ?>
				<span>
					<?php
						echo $form->textField($model,'budgetsConcept_quantity',array('class'=>'betterform','style'=>'width:95%','tabindex'=>1));
						echo CHtml::label(Yii::t('budgetsConcepts', 'FormBudgetsConcept_quantity'), CHtml::activeId($model, 'budgetsConcept_quantity'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c50r">
			<div class="row">
				<?php echo $form->labelEx($model,'budgetsConcept_amount'); ?>
				<span>
					<?php
						echo $form->textField($model,'budgetsConcept_amount',array('class'=>'betterform','style'=>'width:95%','tabindex'=>2));
						echo CHtml::label(Yii::t('budgetsConcepts', 'FormBudgetsConcept_amount'), CHtml::activeId($model, 'budgetsConcept_amount'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'budgetsConcept_description'); ?>
		<span>
			<?php
				echo $form->textArea($model,'budgetsConcept_description',array('rows'=>10, 'cols'=>50,'style'=>'width:95%','class'=>'betterform','tabindex'=>3));
				echo CHtml::label(Yii::t('budgetsConcepts', 'FormBudgetsConcept_description'), CHtml::activeId($model, 'budgetsConcept_description'), array('class'=>'labelhelper'));
			?>
		</span>
	</div>

	

	<div class="row">
		<?php echo $form->hiddenField($model,'budget_id',array('value'=>($model->isNewRecord)?$_GET['owner']:$model->budget_id)); ?>
		<?php echo $form->error($model,'budget_id'); ?>
	</div>

	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button primary big','tabindex'=>4)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big','tabindex'=>5)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->createUrl('bconcepts/index',array('owner'=>$_GET['owner'])), array('class'=>'button','tabindex'=>6)); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->