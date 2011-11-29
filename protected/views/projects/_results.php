<div class="view">
	<h3><?php echo CHtml::link(CHtml::encode($data->project_name), array('projects/view', 'id'=>$data->project_id)); ?></h3>
	<div class="moduleTextDescription corners">
		<?php echo CHtml::encode($data->project_description); ?>
	</div>
</div>