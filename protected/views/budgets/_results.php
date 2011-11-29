<div class="view">
	<h3><?php echo CHtml::link(CHtml::encode($data->budget_title), array('budgets/view', 'id'=>$data->budget_id)); ?></h3>
	<div class="moduleTextDescription corners">
		<?php echo CHtml::encode($data->budget_notes); ?>
	</div>
</div>