<div class="form">

	<?php
	$form=$this->beginWidget('CActiveForm', array(
		'id'=>'budgets-form',
		'enableAjaxValidation'=>false,
	)); 
	?>

	<?php echo Yii::t('budgets', 'FieldsRequired'); ?>

	<?php echo $form->errorSummary($budget,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($budget,'budget_title'); ?>
				<span>
					<?php
						echo $form->textField($budget,'budget_title',array('class'=>'betterform','style'=>'width:95%','tabindex'=>1));
						echo CHtml::label(Yii::t('budgets', 'FormTitleBudget'), CHtml::activeId($budget, 'budget_title'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($budget,'budget_duedate'); ?>
				<span>
					<?php
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'options'=>array(
								'showAnim'=>'fold',
							),
							'model'=>$budget,
							'attribute'=>'budget_duedate',
							'htmlOptions'=>array(
								'class'=>'betterform',
								'style'=>'width:95%',
								'tabindex'=>3
							),
							'options'=>array(
								'dateFormat'=>'yy-mm-dd',
								'showButtonPanel'=>true,
								'changeMonth'=>true,
								'changeYear'=>true,
								'defaultDate'=>'+1w',
								'onSelect'=>'js:function(selectedDate){
									var option = "maxDate",
									instance = $(this).data("datepicker");
									date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
									$("#'.CHtml::activeId($budget, 'budget_date').'").datepicker("option", option, date);
								}'
							),
						));
						echo CHtml::label(Yii::t('budgets', 'FormDueDateBudget'), CHtml::activeId($budget, 'budget_duedate'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c50r">
			<div class="row">
				<?php echo $form->labelEx($budget,'budget_date'); ?>
				<span>
					<?php
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'options'=>array(
								'showAnim'=>'fold',
							),
							'model'=>$budget,
							'attribute'=>'budget_date',
							'htmlOptions'=>array(
								'class'=>'betterform',
								'style'=>'width:95%',
								'tabindex'=>2
							),
							'options'=>array(
								'dateFormat'=>'yy-mm-dd',
								'showButtonPanel'=>true,
								'changeMonth'=>true,
								'changeYear'=>true,
								'defaultDate'=>'+1w',
								'onSelect'=>'js:function(selectedDate){
									var option = "minDate",
									instance = $(this).data("datepicker");
									date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
									$("#'.CHtml::activeId($budget, 'budget_duedate').'").datepicker("option", option, date);
								}'
							),
						));
						echo CHtml::label(Yii::t('budgets', 'FormDateBudget'), CHtml::activeId($budget, 'budget_date'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>
	<div class="row">
		<?php echo $form->labelEx($budget,'budget_notes'); ?>
		<span>
			<?php
				echo $form->textArea($budget,'budget_notes',array('rows'=>10, 'cols'=>50,'style'=>'width:95%','class'=>'betterform','tabindex'=>4));
				echo CHtml::label(Yii::t('budgets', 'FormNotesBudget'), CHtml::activeId($budget, 'budget_notes'), array('class'=>'labelhelper'));
			?>
		</span>
	</div>
	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($budget->isNewRecord ? Yii::t('site', 'create') : Yii::t('site', 'save'), array('type'=>'submit', 'class'=>'button primary big','tabindex'=>6)); ?>
			<?php echo CHtml::button(Yii::t('site', 'reset'), array('type'=>'reset', 'class'=>'button big', 'tabindex'=>7)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->