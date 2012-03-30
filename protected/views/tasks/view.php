<?php
$this->breadcrumbs=array(
	Yii::t('tasks', 'TitleTasks')=>array('index'),
	$model->task_name,
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('tasks', 'TitleTasks')." :: ".CHtml::encode($model->task_name);
$cs = Yii::app()->getClientScript();
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/menu.css');
$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/todolist.css');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
?>
<div class="portlet x9">
	<div class="portlet-content">
		<div class="grid_8">
			<h1 class="ptitleinfo tasks">
				<?php echo $model->task_name; ?>
			</h1>
		</div>
		<div class="grid_4">
			<h1 class="prtribbon">
				<span class="priority pr<?php echo CHtml::encode($model->task_priority); ?>">
					<?php echo Tasks::getNameOfTaskPriority($model->task_priority); ?>
				</span>
			</h1>
		</div>
		<div class="clear">&nbsp;</div>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('tasks', 'ListTasks'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('tasks', 'CreateTasks'), Yii::app()->controller->createUrl('create'),array('class'=>'button')); ?>
			<?php if (($model->user_id == Yii::app()->user->id) && ($model->status_id == Status::STATUS_PENDING)) : ?>
				<?php echo CHtml::link(Yii::t('tasks', 'UpdateTasks'), Yii::app()->controller->createUrl('update',array('id'=>$model->task_id)),array('class'=>'button')); ?>
			<?php endif; ?>
		</div>
		<?php
			$this->widget('zii.widgets.CDetailView', array(
				'data'=>$model,
				'attributes'=>array(
					array(
						'name'=>'task_description',
						'type'=>'raw',
						'value'=>Yii::app()->format->html(nl2br(ECHtml::createLinkFromString(CHtml::encode($model->task_description)))),
					),
					array(
						'name'=>'task_buildNumber',
						'type'=>'raw',
						'value'=>CHtml::encode($model->task_buildNumber),
						'visible'=>(!empty($model->task_buildNumber))
					),
					array(
						'name'=>'user_id',
						'type'=>'raw',
						'value'=>CHtml::link($model->UserReported->CompleteName, Yii::app()->controller->createUrl("users/view",array("id"=>$model->user_id))),
					),
					array(
						'name'=>'task_startDate',
						'type'=>'text',
						'value'=>CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->task_startDate, "medium", false)),
						'visible' => ($model->task_startDate!='0000-00-00'),
					),
					array(
						'name'=>'task_endDate',
						'type'=>'text',
						'value'=> CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->task_endDate, "medium", false)),
						'visible' => ($model->task_endDate!='0000-00-00'),
					),
					array(
						'name'=>'taskStage_id',
						'type'=>'raw',
						'value'=>$model->Stage->taskStage_name,
						'visible'=>(!empty($model->taskStage_id))
					),
					array(
						'name'=>'status_id',
						'type'=>'raw',
						'value'=>(Yii::app()->user->checkAccess('changeStatusTasks') && ($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_CLOSED) && Yii::app()->user->HasJoined) ? '<span class="text status st'.CHtml::encode($model->status_id).'">'.CHtml::encode($model->Status->status_name).'</span><div class="actions"><a class="edit" href="#"> _Edit</a></div>' : '<span class="text status st'.CHtml::encode($model->status_id).'">'.CHtml::encode($model->Status->status_name).'</span><div class="actions" style="display:none;"><a class="edit" href="#"> _Edit</a></div>',
						'cssClass'=>'statusContent',
						'visible'=>Yii::app()->user->checkAccess('changeStatusTasks'),
					),
					array(
						'name'=>'taskTypes_id',
						'type'=>'raw',
						'value'=>'<span class="text tasktypes tty'.$model->taskTypes_id.'">'.$model->Types->taskTypes_name.'</span>',
						'cssClass'=>'typesContent',
					),
					array(
						'name'=>'case_id',
						'type'=>'raw',
						'value'=>(!empty($model->Cases->case_name)) ? CHtml::link($model->Cases->case_name, Yii::app()->createUrl('cases/view', array('id'=>$model->case_id))) : null,
						'visible'=>(!empty($model->Cases->case_name)),
					),
					array(
						'name'=>'milestone_id',
						'type'=>'raw',
						'value'=>(!empty($model->Milestones->milestone_title)) ? CHtml::link($model->Milestones->milestone_title, Yii::app()->createUrl('milestones/view', array('id'=>$model->milestone_id))) : null,
						'visible'=>(!empty($model->Milestones->milestone_title))
					),
				),
			));
			?>
			<br />
			<div class="portlet grid_12">
				<div class="portlet-content">
					<h1 class="ptitle users"><?php echo Yii::t('tasks','UsersWorkingOn'); ?></h1>
				</div>
				<?php if (((bool)Yii::app()->user->IsManager == true) && ($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_CLOSED)) { ?>
				<div style="width:150px;" class="tzSelect">
					<div class="selectBox"><?php echo Yii::t('tasks','UsersWorkingOn'); ?></div>
					<div class="dropdown_1column align_right corners">
			            <div class="col_1" id="usersList">
			                <ul class="availablesUsers" link="<?php echo Yii::app()->createUrl('tasks/RelatedCreate'); ?>">
			                	<?php
			                		$this->renderPartial('_dropdownUsersList',array(
										'availableUsers'=>$availableUsers
									));
			                	?>
			                </ul>
			            </div>
			        </div>
				</div>
				<?php
				}
				elseif (($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_TOTEST) && ($model->status_id != Status::STATUS_INPROGRESS) && ($model->status_id != Status::STATUS_CLOSED) && !Yii::app()->user->HasJoined) { ?>
				<ul class="portlet-tab-nav">
					<li class="portlet-tab-nav-active">
						<?php
						echo CHtml::ajaxLink(Yii::t('tasks','UserAddMe'), Yii::app()->createUrl("tasks/jointask", array('id'=>$model->task_id)), array(
								'dataType' => 'json',
								'success' => 'js:function(data){
									jQuery.each(data, function(index, item) {
										if(data.saved) $("#lnkrelated").hide();
										else $("#lnkrelated").show();
									});
								}',
								'complete' => 'js:function(){$.fn.yiiListView.update("users-gallery");}',
							), array('id'=>'lnkrelated'));
						?>
					</li>
				</ul>
				<?php } ?>
				<?php $this->widget('zii.widgets.CListView', array(
					'id'=>'users-gallery',
					'dataProvider'=>$workers,
					'summaryText'=>'',
					'itemView'=>'_tasksWorkers',
					'viewData'=>array(
						'canDelete'=>true
					),
					'htmlOptions'=>array(
						'style'=>'padding-left:25px'
					),
				));?>
				<div class="clear"></div>
			</div>
			<br />
			<div class="portlet grid_12">
				<div class="portlet-content">
					<h1 class="ptitle todolist"><?php echo Yii::t('tasks','TodoList'); ?></h1>
				</div>
				<div class="portlet-tab-nav btnTodoList" style="display:<?php echo (!in_array($model->status_id,array(Status::STATUS_CANCELLED,Status::STATUS_CLOSED)) && Yii::app()->user->HasJoined) ? "inline" : "none"; ?>">
					<?php echo CHtml::Link(Yii::t('tasks','TodoListCreate'), Yii::app()->createUrl("todolist/create", array('task_id'=>$model->task_id)), array('id'=>'addButton','class'=>'button')); ?>
				</div>
				<div class="todolistcontainer">
				<?php
					$this->widget('widgets.TaskTodoList',array(
						'task_id' => $model->task_id,
						'urlOrder' => Yii::app()->createUrl("todolist/rearrange"),
						'urlNew' => Yii::app()->createUrl("todolist/create"),
						'urlSave' => Yii::app()->createUrl("todolist/save"),
						'urlCheck' => Yii::app()->createUrl("todolist/check"),
						'urlDelete' => Yii::app()->createUrl("todolist/delete", array('ajax'=>1)),
						'permission' => Yii::app()->user->HasJoined,
					));
				?>
				</div>
			</div>
			<div class="clear">&nbsp;</div>
			<div class="result">
				<?php $this->widget('widgets.ListComments',array(
					'resourceid'=>$model->task_id,
					'moduleid'=>Yii::app()->controller->id,
					'htmlOptions' => array(
						'class'=>'portlet'
					),
				)); ?>
			</div>
	</div>
</div>
<?php
Yii::app()->clientScript->registerScript('jquery.todolisteditinplace','
	$(document).ready(function() {
		var currentStatus;
		$(".statusContent a").live("click",function(e){
			currentStatus = $(this).closest(".statusContent");
			e.preventDefault();
		});
		$(".statusContent a.edit").live("click",function(){
			var container = currentStatus.find(".text");
			if(!currentStatus.data("sourceText"))
				currentStatus.data("sourceText",container.text());
			else
				return false;
			
			$.ajax({
				type:"POST",
				url:"'.Yii::app()->createUrl('status/getStatus').'",
				dataType:"json",
				data:({
					YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'",
					required:0,
				}),
				beforeSend:function(){
					$(".status").addClass("loading");
				},
				success:function(data){
					$(".status").removeClass("loading");
					select = "<select id=\"statusList\" name=\"statusList\">";
					jQuery.each(data, function(index, item) {
						select += "<option value="+item.iux+">"+item.nux+"</option>";
					});
					select += "</select>";
					container.empty().append(select);
					container.append("&nbsp;&nbsp;<span class=\"editTodo\"><a class=\"saveChanges\" href=\"#\" title=\"'.Yii::t('site','SaveLink').'\">'.Yii::t('site','SaveLink').'</a> '.Yii::t('site','or').' <a class=\"discardChanges\" href=\"#\" title=\"'.Yii::t('site','CancelLink').'\">'.Yii::t('site','CancelLink').'</a></span>");
				},
			});
		}); 
		
		// The cancel edit link:
		$(".statusContent a.discardChanges").live("click",function(){
			currentStatus.find(".text").text(currentStatus.data("sourceText")).end().removeData("sourceText");
		});

		// The save changes link:
		$(".statusContent a.saveChanges").live("click",function(){
			var answer = confirm("'.Yii::t('site','confirmStatusChange').'");
			if (answer){
				var container = currentStatus.find(".text select").val();
				var elem = $(".statusContent > td > span");
				$.ajax({
					async:false,
					type:"POST",
					url:"'.Yii::app()->createUrl('tasks/changeStatus').'",
					data:({
						id:'.$model->task_id.',
						changeto:container,
						YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'",
					}),
					complete:function(data) {
						elem.removeClass(elem.attr("class").split(" ").slice(-1));
						elem.addClass(currentStatus.find("#statusList option:selected").text());
						currentStatus.removeData("sourceText").find(".text").text(currentStatus.find("#statusList option:selected").text());
						elem.removeClass().addClass("text status st"+container);
						if (!(elem).hasClass("st1")) $(".lnkupdate").hide();
						if((container == '.Status::STATUS_CANCELLED.') || (container == '.Status::STATUS_CLOSED.')){
							$(".statusContent > td > div").remove(".actions");
							$("#addButton").parents("ul").remove();
							$("#lnkrelated").parents().remove("ul");
						}
						else if(container == '.Status::STATUS_INPROGRESS.'){
							$("#lnkrelated").parents().remove("ul");
						}
					}
				});
			}
		});					
	});
');
Yii::app()->clientScript->registerScript('jquery.dropdownflyer','
	$(".selectBox").click(function(e) {
		$(this).next().slideDown("fast").show();
		$(this).next().hover(function() {
		}, function(){
			$(this).slideUp("fast");
		});
	});

	$(".deleteUser").live("click",function(e){
		e.preventDefault();
		var el=$(this); 
		$.ajax({
			url:el.attr("href"),
			data: ({YII_CSRF_TOKEN: \''.Yii::app()->request->csrfToken.'\', uid: $(this).attr("data"), task_id:\''.$model->task_id.'\'}),
			type: "POST",dataType:"json",
			success:function(data) {
				if (data.saved){
					$(".availablesUsers").html(data.html);
					$.fn.yiiListView.update("users-gallery");
					$(".actions").hide();
					$(".btnTodoList").hide();
				}
				if(data.hasreg)$(".selectBox").show();
			},
		});
	});

	$(".checkSelItem").live("change",function(e){
		if ($(this).attr("checked")) {
	        var el=$(this); 
			$.ajax({
				url:el.parents().find(".availablesUsers").attr("link"),
				data: ({YII_CSRF_TOKEN: \''.Yii::app()->request->csrfToken.'\', uid: $(this).attr("data"), task_id:\''.$model->task_id.'\'}),
				type: "POST",dataType:"json",
				success:function(data) {
					if (data.saved){
						$(".availablesUsers").html(data.html);
						$.fn.yiiListView.update("users-gallery");
						$(".actions").show();
						$(".btnTodoList").show();
					}
					if(!data.hasreg)$(".selectBox").hide();
				},
			});
	    }
	});
');
?>