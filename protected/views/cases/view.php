<?php
$this->breadcrumbs=array(
	Yii::t('cases', 'TitleCases')=>array('index'),
	$model->case_name,
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('cases', 'TitleCases')." :: ".CHtml::encode($model->case_name);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo cases"><?php echo $model->case_name; ?>&nbsp;
			<span class="priority pr<?php echo CHtml::encode($model->case_priority); ?>">
				<?php
				echo Yii::t('cases','case_priority')." ";
				switch($model->case_priority)
				{
					case Cases::PRIORITY_LOW:
						echo Yii::t('site','lowPriority');
						break;
					case Cases::PRIORITY_MEDIUM:
						echo Yii::t('site','mediumPriority');
						break;
					case Cases::PRIORITY_HIGH:
						echo Yii::t('site','highPriority');
						break;
					default:
						echo Yii::t('site','lowPriority');
						break;
				}
				?>
			</span>
		</h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('cases', 'ListCases'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
			<?php if(Yii::app()->user->checkAccess('createCases')): ?>
				<?php echo CHtml::link(Yii::t('cases', 'CreateCases'), Yii::app()->controller->createUrl('create'),array('class'=>'button')); ?>
			<?php endif; ?>
			<?php if(Yii::app()->user->checkAccess('updateCases')): ?>
				<?php echo CHtml::link(Yii::t('cases', 'UpdateCases'), Yii::app()->controller->createUrl('update',array('id'=>$model->case_id)),array('class'=>'button')); ?>
			<?php endif; ?>
		</div>
		<?php
		$this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
				array(
					'name'=>'case_date',
					'type'=>'text',
					'value'=>CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->case_date, 'medium', false)),
				),
				array(
					'name'=>'case_code',
					'type'=>'raw',
					'value'=>CHtml::encode($model->case_code),
					'visible'=>(!empty($model->case_code)) ? true : false,
				),
				array(
					'name'=>'case_actors',
					'type'=>'raw',
					'value'=>CHtml::encode($model->case_actors),
					'visible'=>(!empty($model->case_actors)) ? true : false,
				),
				array(
					'name'=>'case_description',
					'type'=>'raw',
					'value'=>Yii::app()->format->html(nl2br(ECHtml::createLinkFromString(CHtml::encode($model->case_description)))),
				),
				array(
					'label'=>'Status',
					'type'=>'raw',
					'value'=>(Yii::app()->user->checkAccess('changeStatusCases') && ($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_ACCEPTED) && ($model->status_id != Status::STATUS_CLOSED) ? '<span class="text status st'.CHtml::encode($model->Status->status_id).'">'.CHtml::encode($model->Status->status_name).'</span><div class="actions"><a class="edit" href="#"> _Edit</a></div>' : '<span class="text status st'.CHtml::encode($model->Status->status_id).'">'.CHtml::encode($model->Status->status_name).'</span>'),
					'cssClass'=>'statusContent',
					'visible'=>Yii::app()->user->checkAccess('changeStatusCases'),
				),
				array(
					'name'=>'case_requirements',
					'type'=>'raw',
					'value'=>Yii::app()->format->html(nl2br(ECHtml::createLinkFromString(CHtml::encode($model->case_requirements)))),
					'visible'=>(!empty($model->case_requirements)) ? true : false,
				),
			),
		));
		?>
		<br />
		<div class="portlet x12">
			<div class="portlet-content">
				<h1 class="ptitle"><?php echo Yii::t('cases', 'Secuences');?></h1>
				<?php if(Yii::app()->user->checkAccess('createSecuences')): ?>
				<ul class="portlet-tab-nav">
					<li class="portlet-tab-nav-active">
						<?php echo CHtml::link(CHtml::encode(Yii::t('cases', 'SecuencesAdd')), Yii::app()->createUrl("secuences/create",array('owner'=>$model->case_id)), array('id'=>'secuencesCreate')); ?>
					</li>
				</ul>
				<?php endif; ?>
				<?php if($NormalSecuense->totalItemCount > 0) :?>
				<br /><b><?php echo Yii::t('cases', 'SecuencesNormal'); ?></b>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'NormalSecuence-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$NormalSecuense,
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						array(
								'name'=>'secuence_step',
								'type'=>'raw',
								'value' =>'$data->secuence_step',
								'headerHtmlOptions'=>array(
									'style'=>'width:20%',
								),
						),
						array(
								'name'=>'secuence_action',
								'type'=>'raw',
								'value' =>'$data->secuence_action',
						),
						array(
								'name'=>'secuence_responsetoAction',
								'type'=>'raw',
								'value' =>'$data->secuence_responsetoAction',
						),
						array(
							'class'=>'CButtonColumn',
							'template' => '{update} {delete}',
							'buttons' => array(
								'update' => array(
									'url' =>'Yii::app()->controller->createUrl("secuences/update",array("id"=>$data->secuence_id, "owner"=>$data->case_id))',
									'imageUrl' => Yii::app()->baseUrl.'/images/update.png',
									'visible'=>'Yii::app()->user->checkAccess("updateSecuences")',
								),
								'delete' => array(
									'url' =>'Yii::app()->controller->createUrl("secuences/delete",array("id"=>$data->secuence_id))',
									'imageUrl' => Yii::app()->baseUrl.'/images/delete.png',
									'visible'=>'Yii::app()->user->checkAccess("deleteSecuences")',
								),
							),
						),
					),
				)); ?>
				<?php endif; ?>
				<?php if($AlternativeSecuense->totalItemCount > 0) :?>
				<b><?php echo Yii::t('cases', 'SecuencesAlternative'); ?></b>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'AlternativeSecuense-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$AlternativeSecuense,
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						array(
								'name'=>'secuence_step',
								'type'=>'raw',
								'value' =>'$data->secuence_step',
								'headerHtmlOptions'=>array(
									'style'=>'width:20%',
								),
						),
						'secuence_action',
						'secuence_responsetoAction',
						array(
							'class'=>'CButtonColumn',
							'template' => '{update} {delete}',
							'buttons' => array(
								'update' => array(
									'label'=> 'Update',
									'url' =>'Yii::app()->controller->createUrl("secuences/update",array("id"=>$data->secuence_id, "owner"=>$data->case_id))',
									'imageUrl' => Yii::app()->baseUrl.'/images/update.png',
									'visible'=>'Yii::app()->user->checkAccess("updateSecuences")',
								),
								'delete' => array(
									'label'=> 'Delete',
									'url' =>'Yii::app()->controller->createUrl("secuences/delete",array("id"=>$data->secuence_id))',
									'imageUrl' => Yii::app()->baseUrl.'/images/delete.png',
									'visible'=>'Yii::app()->user->checkAccess("deleteSecuences")',
								),
							),
						),
					),
				)); ?>
				<?php endif; ?>
				<?php if($ExceptionSecuense->totalItemCount > 0) :?>
				<b><?php echo Yii::t('cases', 'SecuencesException'); ?></b>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'ExceptionSecuense-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$ExceptionSecuense,
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						array(
								'name'=>'secuence_step',
								'type'=>'raw',
								'value' =>'$data->secuence_step',
								'headerHtmlOptions'=>array(
									'style'=>'width:20%',
								),
						),
						'secuence_action',
						'secuence_responsetoAction',
						array(
							'class'=>'CButtonColumn',
							'template' => '{update} {delete}',
							'buttons' => array(
								'update' => array(
									'label'=> 'Update',
									'url' =>'Yii::app()->controller->createUrl("secuences/update",array("id"=>$data->secuence_id, "owner"=>$data->case_id))',
									'imageUrl' => Yii::app()->baseUrl.'/images/update.png',
									'visible'=>'Yii::app()->user->checkAccess("updateSecuences")',
								),
								'delete' => array(
									'label'=> 'Delete',
									'url' =>'Yii::app()->controller->createUrl("secuences/delete",array("id"=>$data->secuence_id))',
									'imageUrl' => Yii::app()->baseUrl.'/images/delete.png',
									'visible'=>'Yii::app()->user->checkAccess("deleteSecuences")',
								),
							),
						),
					),
				)); ?>
				<?php endif; ?>
			</div>
		</div>
		<br />
		<div class="portlet x12">
			<div class="portlet-content">
				<h1 class="ptitle"><?php echo Yii::t('cases', 'Validations');?></h1>
				<?php if(Yii::app()->user->checkAccess('createValidations')): ?>
				<ul class="portlet-tab-nav">
					<li class="portlet-tab-nav-active">
						<?php echo CHtml::link(CHtml::encode(Yii::t('cases', 'ValidationsAdd')), Yii::app()->createUrl("validations/create",array('owner'=>$model->case_id)), array('id'=>'validationsCreate')); ?>
					</li>
				</ul>
				<?php endif; ?>
				<?php if($validations->totalItemCount > 0) :?>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'validations-grid',
					'cssFile'=>'css/screen.css',
					'dataProvider'=>$validations,
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						array(
							'name'=>'validation_field',
							'type'=>'raw',
							'value' =>'CHtml::encode($data->validation_field)',
							'headerHtmlOptions'=>array(
								'style'=>'width:18%',
							),
						),
						array(
							'name'=>'validation_description',
							'type'=>'raw',
							'value' =>'CHtml::encode($data->validation_description)',
							'headerHtmlOptions'=>array(
								'style'=>'width:75%',
							),
						),
						array(
							'class'=>'CButtonColumn',
							'template' => '{update} {delete}',
							'buttons' => array(
								'update' => array(
									'url' =>'Yii::app()->controller->createUrl("validations/update",array("id"=>$data->validation_id, "owner"=>$data->case_id))',
									'imageUrl' => Yii::app()->baseUrl.'/images/update.png',
									'visible'=>'Yii::app()->user->checkAccess("updateValidations")',
								),
								'delete' => array(
									'url' =>'Yii::app()->controller->createUrl("validations/delete",array("id"=>$data->validation_id))',
									'imageUrl' => Yii::app()->baseUrl.'/images/delete.png',
									'visible'=>'Yii::app()->user->checkAccess("deleteValidations")',
								),
							),
						),
					),
				)); ?>
				<?php endif; ?>
			</div>
		</div>
		<br />
		<?php
		if($dataProviderTasks->totalItemCount > 0) :?>
		<div class="portlet x12">
			<div class="portlet-content">
				<h1 class="ptitle tasks"><?php echo Yii::t('milestones','RelatedTasks'); ?></h1>
			</div>
			<?php
			$this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'tasks-grid',
				'cssFile'=>'css/screen.css',
				'dataProvider'=>$dataProviderTasks,
				'summaryText'=>Yii::t('site','summaryText'),
				'emptyText'=>Yii::t('site','emptyText'),
				'columns'=>array(
					array(
						'type'=>'raw',
						'name'=>'task_name',
						'value'=>'CHtml::link($data->task_name, Yii::app()->createUrl("tasks/view", array("id"=>$data->task_id)))',
						'htmlOptions'=>array(
							'style'=>'width:50%',
						),
					),
					array(
						'type'=>'raw',
						'name'=>'task_endDate',
						'value'=>'(strtotime($data->task_endDate)!=null) ? CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->task_endDate, "medium", false)) : "N/A"',
						'htmlOptions'=>array(
							'style'=>'width:15%',
						),
					),
					array(
						'type'=>'raw',
						'name'=>'task_priority',
						//'value'=>'($data->task_priority == 0) ? Yii::t("site","lowPriority") : (($data->task_priority == 1) ? Yii::t("site","mediumPriority") : Yii::t("site","highPriority"))',
						'value'=>'CHtml::tag("span",array("class"=>"priority pr".$data->task_priority),($data->task_priority == 0) ? Yii::t("site","lowPriority") : (($data->task_priority == 1) ? Yii::t("site","mediumPriority") : Yii::t("site","highPriority")))',
						'htmlOptions'=>array(
							'style'=>'width:15%',
						),
					),
					array(
						'type'=>'raw',
						'name'=>'Status.status_id',
						'value'=>'CHtml::tag("span",array("class"=>"text status st".$data->Status->status_id),$data->Status->status_name)',
						'htmlOptions'=>array(
							'style'=>'width:20%',
						),
					),
				),
			));
			?>
		</div>
		<?php endif; ?>
		<br />
		<div class="result">
		<?php $this->widget('widgets.ListComments',array(
			'resourceid'=>$model->case_id,
			'moduleid'=>Yii::app()->controller->id,
		)); ?>
		</div>
		<?php
		Yii::app()->clientScript->registerScript('jQueryStatusEditInPlace','
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
							required:true,
						}),
						success:function(data){
							select = "<select id=\"statusList\" name=\"statusList\">";
							jQuery.each(data, function(index, item) {
								select += "<option value="+item.iux+">"+item.nux+"</option>";
							});
							select += "</select>";
							container.empty().append(select);
							container.append("&nbsp;&nbsp;<span class=\"editTodo\"><a class=\"saveChanges\" href=\"#\">Save</a> or <a class=\"discardChanges\" href=\"#\">Cancel</a></span>");
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
							url:"'.Yii::app()->createUrl('cases/changeStatus').'",
							data:({
								id:'.$model->case_id.',
								changeto:container,
								YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'",
							}),
							complete:function(data) {
								elem.removeClass(elem.attr("class").split(" ").slice(-1));
								elem.addClass(currentStatus.find("#statusList option:selected").text());
								currentStatus.removeData("sourceText").find(".text").text(currentStatus.find("#statusList option:selected").text());
								elem.removeClass().addClass("text status st"+container);
								if (!(elem).hasClass("st1")) $(".statusContent a.edit").hide();
							}
						});
					}
				});				
			});
		');
		?>
	</div>
</div>