<div class="view">
	<div class="subcolumns">
		<div class="c25l">
			<?php echo CHtml::encode($data->invoicesConcept_quantity); ?>
			<br />
		</div>
		<div class="c50l">
			<?php echo CHtml::encode($data->invoicesConcept_description); ?>
			<br />
		</div>
		<div class="c25r" style="text-align:right;">
			<?php echo Yii::app()->numberFormatter->formatCurrency($data->invoicesConcept_amount, $data->Invoices->Projects->Currency->currency_code); ?>
			<br />
		</div>
	</div>
	<div class="row" style="text-align:right;">
		<?php echo CHtml::link(CHtml::encode("Edit Concept"), array('update', 'id'=>$data->invoicesConcept_id)); ?>
	</div>
</div>