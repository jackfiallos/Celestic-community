<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('diagram_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->diagram_id), array('view', 'id'=>$data->diagram_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('diagram_name')); ?>:</b>
	<?php echo CHtml::encode($data->diagram_name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('project_id')); ?>:</b>
	<?php echo CHtml::encode($data->project_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status_id')); ?>:</b>
	<?php echo CHtml::encode($data->status_id); ?>
	<br />


</div>