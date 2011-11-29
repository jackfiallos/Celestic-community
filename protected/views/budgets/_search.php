<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="subcolumns">
		<div class="c33l">
			<div class="row">
				<?php echo $form->labelEx($model,'budget_title'); ?>
				<span>
					<?php
						echo $form->textField($model,'budget_title',array('class'=>'betterform','style'=>'width:90%','tabindex'=>1));
						echo CHtml::label(Yii::t('budgets', 'FormTitleBudget'), CHtml::activeId($model, 'budget_title'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c33l">
			<div class="row">
				<?php echo $form->labelEx($model,'budget_notes'); ?>
				<span>
					<?php
						echo $form->textField($model,'budget_notes',array('class'=>'betterform','style'=>'width:90%','tabindex'=>2));
						echo CHtml::label(Yii::t('budgets', 'FormNotesBudget'), CHtml::activeId($model, 'budget_notes'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c33r">
			<div class="row">
				<?php echo $form->labelEx($model,'status_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'status_id',CHtml::listData($status, 'status_id', 'status_name'),array('class'=>'betterform','empty'=>Yii::t('budgets', 'selectOption'),'style'=>'width:90%','tabindex'=>3));
						echo CHtml::label(Yii::t('budgets', 'FormStatusBudget'), CHtml::activeId($model, 'status_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site','search'), array( 'tabindex'=>4, 'class'=>'button primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->