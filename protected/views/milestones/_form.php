<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'milestones-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('milestones','FieldsRequired'); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($model,'milestone_title'); ?>
				<span>
					<?php
						echo $form->textField($model,'milestone_title',array('class'=>'betterform','style'=>'width:95%','maxlength'=>100,'tabindex'=>1));
						echo CHtml::label(Yii::t('milestones','FormMilestoneTitle'), CHtml::activeId($model, 'milestone_title'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'milestone_duedate'); ?>
				<span>
					<?php
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'options'=>array(
								'showAnim'=>'fold',
							),
							'model'=>$model,
							'attribute'=>'milestone_duedate',
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
									$("#'.CHtml::activeId($model, 'milestone_startdate').'").datepicker("option", option, date);
								}'
							),
						));
						echo CHtml::label(Yii::t('milestones','FormMilestoneDueDate'), CHtml::activeId($model, 'milestone_duedate'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c50r">
			<div class="row">
				<?php echo $form->labelEx($model,'milestone_startdate'); ?>
				<span>
					<?php
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'options'=>array(
								'showAnim'=>'fold',
							),
							'model'=>$model,
							'attribute'=>'milestone_startdate',
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
									$("#'.CHtml::activeId($model, 'milestone_duedate').'").datepicker("option", option, date);
								}'
							),
						));
						echo CHtml::label(Yii::t('milestones','FormMilestoneStartDate'), CHtml::activeId($model, 'milestone_startdate'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'user_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'user_id',CHtml::listData($users, 'user_id', 'completeName'),array('id'=>'manager','style'=>'width:95%','class'=>'betterform','empty'=>Yii::t('milestones', 'selectOption'),'tabindex'=>4));
						echo CHtml::label(Yii::t('milestones','FormMilestoneUser'), CHtml::activeId($model, 'user_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'milestone_description'); ?>
		<span>
			<?php
				echo $form->textArea($model,'milestone_description',array('rows'=>10, 'cols'=>50,'class'=>'betterform','style'=>'width:95%','tabindex'=>5));
				echo CHtml::label(Yii::t('milestones','FormMilestoneDescription'), CHtml::activeId($model, 'milestone_description'), array('class'=>'labelhelper'));
			?>
		</span>
	</div>

	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary', 'tabindex'=>6)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big', 'tabindex'=>7)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->