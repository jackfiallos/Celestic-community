<div class="view">
	<h3><?php echo CHtml::link("#".$data->document_id." - ".CHtml::encode($data->document_name), array('documents/view', 'id'=>$data->document_id)); ?></h3>
	<div class="moduleTextDescription corners">
		<?php echo CHtml::encode($data->document_description); ?>
	</div>
</div>