<?php
Yii::app()->clientScript->registerCSSFile(Yii::app()->request->baseUrl.'/css/todolist.css');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
?>
<ul class="<?php echo ($allowSort) ? 'todoList subtasks' : 'tasklist'; ?>">
	<?php foreach($tasks as $task): ?>
  <li id="listItem_<?php echo $task->task_id; ?>" class="todo_">
  	<?php echo "<strong>#".$task->task_id."</strong>&nbsp;".CHtml::link($task->task_name, Yii::app()->createUrl("tasks/view", array("id"=>$task->task_id))); ?>
  	<?php echo CHtml::tag("div",array("style"=>"float:right;padding:0px 5px;margin-left:3px;","class"=>"priority pr".$task->task_priority),($task->task_priority == 0) ? Yii::t("site","lowPriority") : (($task->task_priority == 1) ? Yii::t("site","mediumPriority") : Yii::t("site","highPriority"))); ?>&nbsp;&nbsp;
	<?php echo CHtml::tag("div",array("style"=>"float:right;padding:0px 5px","class"=>"text status st".$task->Status->status_id),$task->Status->status_name); ?>
  </li>
  <?php endforeach; ?>
</ul>
<?php
/*Yii::app()->clientScript->registerScript('jquery.todolist','
$(".todoList").sortable({
	axis : "y",
	placeholder: "ui-state-highlight",
	update : function () {
		//var order = $(".todoList").sortable("serialize");
		var arr = $(".todoList").sortable("toArray");
		arr = $.map(arr,function(val,key){
			return val.replace("listItem_","");
		});
		$.get("'.Yii::app()->createUrl('milestones/rearrange').'",{positions:arr, task_id:'.$milestone->milestone_id.'});
    }
});
');*/
?>