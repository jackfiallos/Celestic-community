<div class="view">
	<h3><?php echo CHtml::link("#".$data->task_id." - ".CHtml::encode($data->task_name), array('tasks/view', 'id'=>$data->task_id)); ?></h3>
	<div class="moduleTextDescription corners">
		<?php echo CHtml::encode($data->task_description); ?>
	</div>
</div>