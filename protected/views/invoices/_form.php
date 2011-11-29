<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invoices-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('invoices', 'FieldsRequired'); ?>

	<?php echo $form->errorSummary($invoice,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($invoice,'invoice_number'); ?>
				<span>
					<?php
						echo $form->textField($invoice,'invoice_number',array('class'=>'betterform','style'=>'width:95%','maxlength'=>45,'tabindex'=>1));
						echo CHtml::label(CHtml::encode(($invoice->isNewRecord) ? Yii::t('invoices', 'FormInvoiceNumberNew').": ".$lastused : Yii::t('invoices', 'FormInvoiceNumberUpdate')), CHtml::activeId($invoice, 'invoice_number'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($invoice,'budget_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($invoice,'budget_id',CHtml::listData($budgets, 'budget_id', 'budget_title'),array('class'=>'betterform','empty'=>Yii::t('invoices', 'selectOption'),'style'=>'width:95%','tabindex'=>3));
						echo CHtml::label(Yii::t('invoices', 'FormBudgetId'), CHtml::activeId($invoice, 'budget_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c50r">	
			<div class="row">
				<?php echo $form->labelEx($invoice,'invoice_date'); ?>
				<span>
					<?php
						$this->widget('zii.widgets.jui.CJuiDatePicker', array(
							'options'=>array(
								'showAnim'=>'fold',
							),
							'model'=>$invoice,
							'attribute'=>'invoice_date',
							'htmlOptions'=>array(
								'class'=>'betterform',
								'style'=>'width:95%',
								'tabindex'=>2
							),
							'options'=>array(
								'dateFormat'=>'yy-mm-dd',
							),
						));
						echo CHtml::label(Yii::t('invoices', 'FormInvoiceDate'), CHtml::activeId($invoice, 'invoice_date'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>
	
	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($invoice->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary', 'tabindex'=>4)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big', 'tabindex'=>5)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->