<div class="view">
	<div class="subcolumns">
		<div class="c25l">
			<?php echo CHtml::encode($data->expensesConcept_quantity); ?>
			<br />
		</div>
		<div class="c50l">
			<?php echo CHtml::encode($data->expensesConcept_description); ?>
			<br />
		</div>
		<div class="c25r" style="text-align:right;">
			<?php echo Yii::app()->numberFormatter->formatCurrency($data->expensesConcept_amount, $data->Expenses->Projects->Currency->currency_code); ?>
			<br />
		</div>
	</div>
	<div class="row" style="text-align:right;">
		<?php echo CHtml::link(CHtml::encode("Edit Concept"), array('update', 'id'=>$data->expensesConcept_id)); ?>
	</div>		
</div>