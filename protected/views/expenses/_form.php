<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'expenses-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('expenses','FieldsRequired'); ?>

	<?php echo $form->errorSummary($expense,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($expense,'expense_name'); ?>
				<span>
					<?php
						$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
							'model'=>$expense,
							'attribute'=>'expense_name',
							'source'=>$this->createUrl('ProviderSearch'),
							// additional javascript options for the autocomplete plugin
							'options'=>array(
								'showAnim'=>'fold',
								//'minLength'=>'3',
								'select'=>"js:function(event, ui) {
									$('#".CHtml::activeId($expense, 'expense_name')."').val(ui.item.label);
									$('#".CHtml::activeId($expense, 'expense_identifier')."').val(ui.item.value);
									return false;
								}",
							),
							'htmlOptions'=>array(
								'class'=>'betterform',
								'style'=>'width:95%',
								'maxlength'=>45,
								'tabindex'=>1
							)
						));
						echo CHtml::label(Yii::t('expenses','FormExpenseName'), CHtml::activeId($expense, 'expense_name'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($expense,'expense_number'); ?>
				<span>
					<?php
						echo $form->textField($expense,'expense_number',array('class'=>'betterform','style'=>'width:95%','maxlength'=>45,'tabindex'=>3));
						echo CHtml::label(Yii::t('expenses','FormExpenseNumber'), CHtml::activeId($expense, 'expense_number'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c50r">
			<div class="row">
				<?php echo $form->labelEx($expense,'expense_identifier'); ?>
				<span>
					<?php
						echo $form->textField($expense,'expense_identifier',array('class'=>'betterform','style'=>'width:95%','maxlength'=>20,'tabindex'=>2));
						echo CHtml::label(Yii::t('expenses','FormExpenseIdentifier'), CHtml::activeId($expense, 'expense_identifier'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($expense,'expense_date'); ?>
				<span>
					<?php
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'options'=>array(
								'showAnim'=>'fold',
							),
							'model'=>$expense,
							'attribute'=>'expense_date',
							'htmlOptions'=>array(
								'class'=>'betterform',
								'style'=>'width:95%',
								'tabindex'=>4
							),
							'options'=>array(
								'dateFormat'=>'yy-mm-dd',
							),
						));
						echo CHtml::label(Yii::t('expenses','FormExpenseDate'), CHtml::activeId($expense, 'expense_date'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>

	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($expense->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary', 'tabindex'=>5)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button git', 'tabindex'=>6)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->