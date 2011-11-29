<?php
$this->breadcrumbs=array(
	Yii::t('budgets', 'TitleBudget')=>array('budgets/index'),
	$model->budget_title=>array('budgets/view','id'=>((isset($model->budget_id)) ? $model->budget_id : $_GET['owner'])),
	Yii::t('budgetsConcepts', 'TitleBudgetConcepts'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('budgets', 'TitleBudget')." :: ".CHtml::encode($model->budget_title);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<?php if (isset($model->budget_id)): ?>
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
		<?php if (isset($model->budget_token)): ?>
		<div style="position:absolute;right:15px;top:15px;">
		<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl."/images/printer.png", Yii::t('budgets','printToPDF')), Yii::app()->createUrl('budgets/viewPrintable', array(
			'token'=>(isset($model->budget_token) ? $model->budget_token : md5(":D")),
			'id'=>(isset($model->budget_id) ? $model->budget_id : $_GET['owner']),
			'cl'=>Yii::app()->user->id
		)), array('target'=>'_blank','title'=>Yii::t('budgets','printToPDF')));
		?>
		</div>
		<?php endif; ?>
		<?php if (($model->status_id == Status::STATUS_PENDING) && (Yii::app()->user->IsManager)) : ?>
		<div class="portlet-tab-nav">
			<?php if (Yii::app()->user->checkAccess('createBudgetConcepts')): ?>
				<?php echo CHtml::link(Yii::t('budgetsConcepts', 'FieldCreate'), Yii::app()->controller->createUrl('create', array('owner'=>((isset($model->budget_id)) ? $model->budget_id : $_GET['owner']))), array('class'=>'button primary')); ?>
			<?php endif; ?>
			<?php if($hasClientsManagers): ?>
				<?php echo CHtml::ajaxLink(Yii::t('budgetsConcepts', 'SendAnnouncement'), Yii::app()->createUrl('budgets/announcement', array('id'=>((isset($model->budget_id)) ? $model->budget_id : $_GET['owner']))), array(
					'success' => 'js:function(data){
						$("#modalMessages").html(data);
						$("#modalMessages").modal({
							opacity:80,
							containerCss:{
								backgroundColor:"#fff",
								height:300,
								width:400
							},
							overlayClose:true,
						});
					}',
				), array('class'=>'button'));
				?>
			<?php endif; ?>
		</div>
		<?php endif; ?>
		<?php if (($model->status_id == Status::STATUS_PENDING) && (Yii::app()->user->checkAccess('changeStatusBudgets')) && (Yii::app()->user->isClient)) : ?>
		<div class="decisionbox corners">
			<span>
				<?php echo Yii::t('budgetsConcepts','StatusChangeAnouncement');?>
			</span>
			<br />
			<button class="cupid-green"><?php echo Yii::t('budgetsConcepts','StatusAcepted'); ?></button>
			<button class="cupid-red"><?php echo Yii::t('budgetsConcepts','StatusRejected'); ?></button>
		</div>
		<?php endif; ?>		
		<?php echo "<div class=\"moduleTextDescription corners\"><strong>".Yii::t('budgetsConcepts','Notes').":</strong> ".Yii::app()->format->html(nl2br(ECHtml::createLinkFromString(CHtml::encode($model->budget_notes))))."</div>"; ?>
		<div class="subcolumns">
			<div class="c20l">
				<b><?php echo Yii::t('projects', 'company_id'); ?>:</b>
			</div>
			<div class="c80r">
				<?php echo CHtml::link(CHtml::encode($model->Projects->Company->company_name), Yii::app()->controller->createUrl('companies/view',array('id'=>$model->Projects->Company->company_id))); ?><br />
			</div>
		</div>
		<div class="subcolumns">
			<div class="c20l">
				<b><?php echo Yii::t('projects', 'project_name'); ?>:</b>
			</div>
			<div class="c80r">
				<?php echo CHtml::link(CHtml::encode($model->Projects->project_name), Yii::app()->controller->createUrl('projects/view',array('id'=>$model->Projects->project_id))); ?><br />
			</div>
		</div>
		<div class="subcolumns">
			<div class="c20l">
				<b><?php echo Yii::t('budgets', 'FieldStatusBudget'); ?>:</b>
			</div>
			<div class="c80r">
				<span class="text status st<?php echo CHtml::encode($model->status_id); ?>"><?php echo $model->Status->status_name; ?></span>
			</div>
		</div>
		<br />
		<?php endif; ?>
		
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'budgets-grid',
			'cssFile'=>'css/screen.css',
			'dataProvider'=>$dataProvider,
			'summaryText'=>Yii::t('site','summaryText'),
			'emptyText'=>Yii::t('site','emptyText'),
			'columns'=>array(
				array(
					'class'=>'CButtonColumn',
					'template' => '{update}{deleting}',
					'visible'=>(Yii::app()->user->checkAccess('updateBudgetConcepts') && $model->status_id == Status::STATUS_PENDING) ? true : false,
					'headerHtmlOptions'=>array(
						'style'=>'width:35px',
					),
					'buttons'=>array(
						'update' => array(
							'label' => Yii::t('site','EditLink'),
							'url' =>'Yii::app()->controller->createUrl("bconcepts/update",array("id"=>$data->budgetsConcept_id,"owner"=>$_GET["owner"],"ajax"=>true))',
						),
						'deleting' => array(
							'label' => Yii::t('site','DeleteLink'),
							'url' =>'Yii::app()->controller->createUrl("bconcepts/delete",array("id"=>$data->budgetsConcept_id,"ajax"=>true,"owner"=>$_GET["owner"]))',
							'imageUrl'=>Yii::app()->request->baseUrl."/images/delete.png",
							'click' => 'js:function(e){
                            	e.preventDefault();
                            	if(!confirm("'.Yii::t('site','confirmDelete').'")) return false;
								$.ajax({
									type:"POST",
									data:{
										YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'",
										iclstyp: 0,
									},
									dataType:"json",
									url:$(this).attr("href"),
									beforeSend:function(){
										$("#budgets-grid").addClass("loading");
									},
									success:function(data) {
										if(data.success){
											$("#TotalAmount").text(data.amount);
											$.fn.yiiGridView.update("budgets-grid");
										}
									}
                            	});
							}',
						),
					),
				),
				array(
					'type'=>'number',
					'name'=>'budgetsConcept_quantity',
					'value'=>'$data->budgetsConcept_quantity',
					'headerHtmlOptions'=>array(
						'style'=>'width:10%',
					),
				),
				array(
					'type'=>'raw',
					'name'=>'budgetsConcept_description',
					'value'=>'Yii::app()->format->html(nl2br(CHtml::encode($data->budgetsConcept_description)))',
					'headerHtmlOptions'=>array(
						'style'=>'width:60%',
					),
				),
				array(
					'type'=>'raw',
					'name'=>'budgetsConcept_amount',
					'value'=>'"<div style=\"text-align:right;\">".Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($data->budgetsConcept_amount), $data->Budgets->Projects->Currency->currency_code)."</div>"',
				),
			),
		)); ?>
		<div class="view total">
			<div class="subcolumns">
				<div class="c75l" style="text-align:right; font-weight:bold;">
					TOTAL (<?php echo CHtml::encode($model->Projects->Currency->currency_code); ?>)
				</div>
				<div class="c25r" style="text-align:right; font-weight:bold;" id="TotalAmount">
					<?php
						echo Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($model->Cost), $model->Projects->Currency->currency_code);
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="modalMessages" style="display:none;"></div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.simplemodal-1.4.1.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScript('jQueryAnnouncement','
	
');
?>