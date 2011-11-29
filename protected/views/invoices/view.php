<?php
$this->breadcrumbs=array(
	Yii::t('invoices', 'TitleInvoices')=>array('index'),
	'# '.$model->invoice_number,
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('invoices', 'TitleInvoices')." :: #".CHtml::encode($model->invoice_number);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo invoices"><?php echo Yii::t('invoices', 'IdInvoice'); ?> #<?php echo $model->invoice_number; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('invoices', 'ListInvoices'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('invoices', 'CreateInvoices'), Yii::app()->controller->createUrl('create'), array('class'=>'button')); ?>
			<?php if (($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_ACCEPTED)) : ?>
			<?php echo CHtml::link(Yii::t('invoices', 'UpdateInvoices'), Yii::app()->controller->createUrl('update', array('id'=>$model->invoice_id)), array('class'=>'button')); ?>
			<?php endif; ?>
		</div>
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
				array(
					'name'=>'invoice_date',
					'type'=>'text',
					'value'=>CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->invoice_date, "medium", false)),
				),
				array(
					'name'=>'status_id',
					'type'=>'raw',
					'value'=>(Yii::app()->user->checkAccess('changeStatusInvoices') && ($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_ACCEPTED)) ? '<span class="text status st'.CHtml::encode($model->status_id).'">'.CHtml::encode($model->Status->status_name).'</span><div class="actions"><a class="edit" href="#"> _Edit</a></div>' : '<span class="text status st'.CHtml::encode($model->status_id).'">'.CHtml::encode($model->Status->status_name).'</span>',
					'cssClass'=>'statusContent',
					'visible'=>(Yii::app()->user->checkAccess('changeStatusInvoices')),
				),
				array(
					'label'=>Yii::t('invoices','InvoiceAmount'),
					'type'=>'raw',
					'value'=>Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($model->Cost), $model->Projects->Currency->currency_code)." ".$model->Projects->Currency->currency_code,
				),
				array(
					'type'=>'html',
					'value'=>"<b>".CHtml::link(Yii::t('invoices', 'ViewDetails'), array('iconcepts/index', 'owner'=>$model->invoice_id))."</b>",
				),
			),
		)); ?>
		<br />
		<div class="result">
		<?php $this->widget('widgets.ListComments',array(
			'resourceid'=>$model->invoice_id,
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
							container.append("&nbsp;&nbsp;<span class=\"editTodo\"><a class=\"saveChanges\" href=\"#\">'.Yii::t('invoices', 'StatusSave').'</a> '.Yii::t('invoices','StatusOr').' <a class=\"discardChanges\" href=\"#\">'.Yii::t('invoices', 'StatusCancel').'</a></span>");
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
							url:"'.Yii::app()->createUrl('invoices/changeStatus').'",
							data:({
								id:'.$model->invoice_id.',
								changeto:container,
								YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'",
							}),
							complete:function(data) {
								elem.removeClass(elem.attr("class").split(" ").slice(-1));
								elem.addClass(currentStatus.find("#statusList option:selected").text());
								currentStatus.removeData("sourceText").find(".text").text(currentStatus.find("#statusList option:selected").text());
								elem.removeClass().addClass("text status st"+container);
							}
						});
					}
				}); 
			});
		');
		?>
	</div>
</div>