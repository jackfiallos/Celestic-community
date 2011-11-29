<?php
$this->breadcrumbs=array(
	Yii::t('budgets', 'TitleBudget')=>array('index'),
	$model->budget_title,
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('budgets', 'TitleBudget')." :: ".CHtml::encode($model->budget_title);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo budgets">
			<?php
				$duedate = strtotime($model->budget_duedate);
				$now = time();
				$timeleft = $duedate-$now;
				$daysleft = round((($timeleft/24)/60)/60);
				
				if (strtotime($model->budget_duedate) > strtotime(date("Y-m-d")))
				{
					if (($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_ACCEPTED) && ($model->status_id != Status::STATUS_CLOSED))
						echo $model->budget_title." (".$daysleft." ".Yii::t('budgets', 'DaysLeftBudget').")";
					else
						echo $model->budget_title;
				}
				else
					echo $model->budget_title;
			?>
		</h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('budgets', 'ListBudget'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('budgets', 'CreateBudget'), Yii::app()->controller->createUrl('create'), array('class'=>'button')); ?>
			<?php if ((Yii::app()->user->IsManager) && ($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_ACCEPTED) && (strtotime($model->budget_duedate) >= strtotime(date("Y-m-d")))) : ?>
				<?php echo CHtml::link(Yii::t('budgets', 'UpdateBudget'), Yii::app()->controller->createUrl('update', array('id'=>$model->budget_id)), array('class'=>'button')); ?>
			<?php endif; ?>
		</div>
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
				array(
					'name'=>'budget_date',
					'type'=>'text',
					'value'=>CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->budget_date, 'medium', false)),
				),
				array(
					'name'=>'budget_duedate',
					'type'=>'text',
					'value'=>CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->budget_duedate, 'medium', false)),
				),
				array(
					'name'=>'budget_notes',
					'type'=>'raw',
					'value'=>Yii::app()->format->html(nl2br(ECHtml::createLinkFromString(CHtml::encode($model->budget_notes)))),
				),
				array(
					'name'=>'status_id',
					'type'=>'raw',
					'value'=>(Yii::app()->user->checkAccess('changeStatusBudgets') && ($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_ACCEPTED) && (strtotime($model->budget_duedate) >= strtotime(date("Y-m-d")))) ? '<span class="text status st'.CHtml::encode($model->status_id).'">'.CHtml::encode($model->Status->status_name).'</span><div class="actions"><a class="edit" href="#"> _Edit</a></div>' : '<span class="text status st'.CHtml::encode($model->status_id).'">'.CHtml::encode($model->Status->status_name).'</span>',
					'cssClass'=>'statusContent',
				),
				array(
					'name'=>Yii::t('budgets', 'BudgetCost'),
					'type'=>'raw',
					'value'=>Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($model->Cost), $model->Projects->Currency->currency_code)." ".$model->Projects->Currency->currency_code,
				),
				array(
					'type'=>'html',
					'value'=>'<b>'.CHtml::link(Yii::t('budgets', 'ViewDetailsBudget'), array('bconcepts/index', 'owner'=>$model->budget_id)).'</b>',
				),
			),
		)); ?>
		<br />
		<div class="result">
		<?php $this->widget('widgets.ListComments',array(
			'resourceid'=>$model->budget_id,
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
							url:"'.Yii::app()->createUrl('budgets/changeStatus').'",
							data:({
								id:'.$model->budget_id.',
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