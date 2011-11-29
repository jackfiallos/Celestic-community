<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="subcolumns">
		<div class="c33l">
			<div class="row">
				<?php echo $form->labelEx($model,'invoice_number'); ?>
				<span>
					<?php
						echo $form->textField($model,'invoice_number',array('class'=>'betterform','style'=>'width:90%','maxlength'=>45,'tabindex'=>1));
						echo CHtml::label(Yii::t('invoices', 'FormInvoiceNumberUpdate'), CHtml::activeId($model, 'invoice_number'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c33l">
			<div class="row">
				<?php echo $form->labelEx($model,'status_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'status_id',CHtml::listData($status, 'status_id', 'status_name'),array('class'=>'betterform','empty'=>Yii::t('invoices', 'selectOption'),'style'=>'width:90%','tabindex'=>2));
						echo CHtml::label(Yii::t('invoices', 'FormStatusId'), CHtml::activeId($model, 'status_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c33r">
			<div class="row">
				<?php echo $form->labelEx($model,'budget_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'budget_id',CHtml::listData($budgets, 'budget_id', 'budget_title'),array('class'=>'betterform','empty'=>Yii::t('invoices', 'selectOption'),'style'=>'width:90%','tabindex'=>3));
						echo CHtml::label(Yii::t('invoices', 'FormBudgetId'), CHtml::activeId($model, 'budget_id'), array('class'=>'labelhelper'));
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