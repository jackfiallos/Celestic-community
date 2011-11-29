<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'invoices-concepts-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('invoices','FieldsRequired'); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($model,'invoicesConcept_quantity'); ?>
				<span>
					<?php
						echo $form->textField($model,'invoicesConcept_quantity',array('class'=>'betterform','style'=>'width:95%','tabindex'=>1));
						echo CHtml::label(Yii::t('invoicesConcepts', 'FormInvoicesConcept_quantity'), CHtml::activeId($model, 'invoicesConcept_quantity'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c50r">
			<div class="row">
				<?php echo $form->labelEx($model,'invoicesConcept_amount'); ?>
				<span>
					<?php
						echo $form->textField($model,'invoicesConcept_amount',array('class'=>'betterform','style'=>'width:95%','tabindex'=>2));
						echo CHtml::label(Yii::t('invoicesConcepts', 'FormInvoicesConcept_amount'), CHtml::activeId($model, 'invoicesConcept_amount'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'invoicesConcept_description'); ?>
		<span>
			<?php
				echo $form->textArea($model,'invoicesConcept_description',array('rows'=>10, 'cols'=>50,'style'=>'width:95%','class'=>'betterform','tabindex'=>3));
				echo CHtml::label(Yii::t('invoicesConcepts', 'FormInvoicesConcept_description'), CHtml::activeId($model, 'invoicesConcept_description'), array('class'=>'labelhelper'));
			?>
		</span>
	</div>

	<div class="row">
		<?php echo $form->hiddenField($model,'invoice_id',array('value'=>($model->isNewRecord)?$_GET['owner']:$model->invoice_id)); ?>
		<?php echo $form->error($model,'invoice_id'); ?>
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