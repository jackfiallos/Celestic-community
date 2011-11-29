<div class="view">
	<div class="subcolumns">
		<div class="c25l">
			<?php echo CHtml::encode($data->budgetsConcept_quantity); ?>
			<br />
		</div>
		<div class="c50l">
			<?php echo CHtml::encode($data->budgetsConcept_description); ?>
			<br />
		</div>
		<div class="c25r" style="text-align:right;">
			<?php echo Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($data->budgetsConcept_amount), $data->Budgets->Projects->Currency->currency_code); ?>
			<br />
		</div>
	</div>
	<div class="row" style="text-align:right;">
		<?php echo CHtml::link(CHtml::encode("Edit Concept"), array('update', 'id'=>$data->budgetsConcept_id)); ?>
	</div>	
</div>