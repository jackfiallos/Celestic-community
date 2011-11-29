<ul class="<?php echo ($this->permission)?'subtasks ':null?>todoList">
<?php
foreach($this->getTodoList($this->task_id) as $list):
?>
<li id="todo-<?php echo $list->todolist_id; ?>" <?php echo (!$list->todolist_checked)?'class="todo"':'class="todocancel"';?>>
	<?php if(!$list->todolist_checked): ?>
	<div class="actionsleft" style="float: left;padding-right:3px;<?php echo ($this->permission)?'display:inline;':'display:none;'?>">
		<a href="#" class="check" title="<?php echo Yii::t('site','CheckLink'); ?>"><?php echo Yii::t('site','CheckLink'); ?></a>
	</div>
	<?php endif; ?>
	<div id="lst<?php echo $list->todolist_id; ?>" class="textlist" <?php echo ($list->todolist_checked)?'style="text-decoration: line-through;color:#9F9F9F;"':null;?>><?php echo $list->todolist_text; ?></div>
	<?php if(!$list->todolist_checked): ?>
	<div class="actions" style="<?php echo ($this->permission)?'display:inline;':'display:none;'?>">
		<a href="#" class="edit" title="<?php echo Yii::t('site','EditLink'); ?>"><?php echo Yii::t('site','EditLink'); ?></a>
		<a href="#" class="delete" title="<?php echo Yii::t('site','DeleteLink'); ?>"><?php echo Yii::t('site','DeleteLink'); ?></a>
	</div>
	<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>

<?php
Yii::app()->clientScript->registerScript('jquery.todolist','
var currentTODO;

var srtbl = function(){
	$(".subtasks").sortable({
		axis : "y",
		placeholder: "ui-state-highlight",
		cancel: ".todocancel",
		update : function(){
			var arr = $(".subtasks").sortable("toArray");
			arr = $.map(arr,function(val,key){
				return val.replace("todo-","");
			});
			$.get("'.$this->urlOrder.'",{positions:arr, task_id:'.$this->task_id.'});
		},
		stop: function(e,ui) {
			ui.item.css({"top":"0","left":"0"});
		}
	});
};srtbl();

$(".todo a").live("click",function(e){
	currentTODO = $(this).closest(".todo");
	currentTODO.data("id",currentTODO.attr("id").replace("todo-",""));
	$(".subtasks").sortable( "option", "disabled", false ).children().css("cursor","move");
	e.preventDefault();
}); 

$(".todo a.edit").live("click",function(){
	var container = currentTODO.find(".textlist");
	$(".subtasks").sortable( "option", "disabled", true ).children().css("cursor","auto");
	if(!currentTODO.data("origText"))
		currentTODO.data("origText",container.text());
	else
		return false;
	$("<input type=\"text\">").val(container.text()).appendTo(container.empty()).focus().select();
	container.append("<div class=\"editTodo corners\"><a class=\"saveChanges\" href=\"#\" title=\"'.Yii::t('site','SaveLink').'\">'.Yii::t('site','SaveLink').'</a> '.Yii::t('site','or').' <a class=\"discardChanges\" href=\"#\" title=\"'.Yii::t('site','CancelLink').'\">'.Yii::t('site','CancelLink').'</a></div>");
});

$(".todo a.check").live("click",function(){
	var el = $(this).parent();
	$.ajax({
		url:"'.$this->urlCheck.'",
		data:{
			rand:Math.random(),
			id:currentTODO.data("id"),
			task_id: "'.$this->task_id.'",
		},
		beforeSend: function(){
			$(".todolistcontainer").addClass("loading");
		},
		success: function(msg) {
			$(".todolistcontainer").removeClass("loading");
			if(msg == 1){
				el.next().css({
					"text-decoration":"line-through",
					"color":"#9F9F9F",
				});
				el.parent().removeClass("todo").addClass("todocancel");
				el.parent().find(".actionsleft, .actions").remove();
			}
			else
				alert(msg);
		},
	});
});

$(".todo a.discardChanges").live("click",function(){
	currentTODO.find(".textlist").text(currentTODO.data("origText")).end().removeData("origText");
}); 

$(".todo a.saveChanges").live("click",function(){
	var text = currentTODO.find("input[type=text]").val();
	$.ajax({
		url:"'.$this->urlSave.'",
		data:{
			id:currentTODO.data("id"),
			text:text,
			task_id: "'.$this->task_id.'",
		},
	});
	currentTODO.removeData("origText").find(".textlist").text(text);
}); 

$(".todo a.delete").live("click",function(){
	var el = $(this).parent();
	$.ajax({
		url:"'.$this->urlDelete.'&id="+currentTODO.data("id"),
		type:"POST",
		dataType:"json",
		data:{
			id:currentTODO.data("id"),
			task_id: "'.$this->task_id.'",
			YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'",
		},
		success: function(msg) {
			if (msg.success) el.parent().remove();
		},
	});
});

var timestamp=0;
$("#addButton").click(function(e){
	if((new Date()).getTime() - timestamp<5000) return false;
	$.ajax({
		url:"'.$this->urlNew.'",
		data:{
			rand:Math.random(),
			task_id:'.$this->task_id.'
		},
		beforeSend: function(){
			$(".todolistcontainer").addClass("loading");
		},
		success: function(msg) {
			$(".todolistcontainer").removeClass("loading");
			$(msg).hide().appendTo(".subtasks").fadeIn();
		},
	});
	timestamp = (new Date()).getTime();	
	e.preventDefault();
});

');
?>