<div class="view">
	<h3><?php echo CHtml::link("#".$data->case_id." - ".CHtml::encode($data->case_name), Yii::app()->controller->createUrl("cases/view", array("id"=>$data->case_id))); ?></h3>
	<div class="moduleTextDescription corners">
		<?php echo ECHtml::word_split(CHtml::encode($data->case_description), 20)."..."; ?><br />
	</div>
</div>