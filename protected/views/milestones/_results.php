<div class="view">
	<h3><?php echo CHtml::link("#".$data->milestone_id." - ".CHtml::encode($data->milestone_title), array('milestones/view', 'id'=>$data->milestone_id)); ?></h3>
	<div class="moduleTextDescription corners">
		<?php echo CHtml::encode($data->milestone_description); ?>
	</div>
</div>