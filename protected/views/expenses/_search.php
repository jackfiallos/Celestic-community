<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="subcolumns">
		<div class="c25l">
			<div class="row">
				<?php echo $form->labelEx($model,'expense_name'); ?>
				<span>
					<?php
						echo $form->textField($model,'expense_name',array('class'=>'betterform','style'=>'width:90%','maxlength'=>45,'tabindex'=>1));
						echo CHtml::label(Yii::t('expenses','FormExpenseName'), CHtml::activeId($model, 'expense_name'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c25l">
			<div class="row">
				<?php echo $form->labelEx($model,'expense_identifier'); ?>
				<span>
					<?php
						echo $form->textField($model,'expense_identifier',array('class'=>'betterform','style'=>'width:90%','maxlength'=>20,'tabindex'=>2));
						echo CHtml::label(Yii::t('expenses','FormExpenseIdentifier'), CHtml::activeId($model, 'expense_identifier'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c25l">
			<div class="row">
				<?php echo $form->labelEx($model,'expense_number'); ?>
				<span>
					<?php
						echo $form->textField($model,'expense_number',array('class'=>'betterform','style'=>'width:90%','maxlength'=>45,'tabindex'=>3));
						echo CHtml::label(Yii::t('expenses','FormExpenseNumber'), CHtml::activeId($model, 'expense_number'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c25r">
			<div class="row">
				<?php echo $form->labelEx($model,'status_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'status_id',CHtml::listData($status, 'status_id', 'status_name'),array('class'=>'betterform','empty'=>Yii::t('budgets', 'selectOption'),'style'=>'width:90%','tabindex'=>4));
						echo CHtml::label(Yii::t('expenses','FormExpenseStatus'), CHtml::activeId($model, 'status_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site','search'), array( 'tabindex'=>5, 'class'=>'button primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->