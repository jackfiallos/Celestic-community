<?php
$activity = $this->getActivity();
foreach($activity as $task): ?>
	<div style="height:25px">
		<?php
			echo CHtml::tag("span",array("class"=>"priority st".$task->status_id),$task->Status->status_name)." ";
			echo CHtml::link(ECHtml::word_split($task->task_name,3)."...", Yii::app()->createUrl("tasks/view",array("id"=>$task->task_id)));
		?>
	</div>
<?php endforeach; ?>