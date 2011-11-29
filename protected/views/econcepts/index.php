<?php
$this->breadcrumbs=array(
	Yii::t('expenses', 'TitleExpenses') => array('expenses/index'),
	$model->expense_name." No. ".$model->expense_number => array('expenses/view','id'=>((isset($model->expense_id)) ? $model->expense_id : $_GET['owner'])),
	Yii::t('expensesConcepts', 'TitleExpenseConcepts'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('expenses', 'TitleExpenses')." :: ".CHtml::encode($model->expense_name);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<?php if (isset($model)) { ?>
		<?php echo CHtml::tag("div", array('class'=>'stick notification_'.$model->status_id),Yii::t('expenses', $model->Status->status_name)); ?><br />
		<div class="subcolumns">
			<div class="c75l">
				<h2 class="ptitleinfo expenses"><?php echo CHtml::link(CHtml::encode($model->Projects->project_name), Yii::app()->controller->createUrl('projects/view',array('id'=>$model->Projects->project_id))); ?><br /></h2>
			</div>
			<div class="c25r" style="text-align:right">
				<h2 class="ptitleinfo "><?php echo Yii::t('expenses','InvoiceNo')." #".$model->expense_number; ?></h2>
			</div>
		</div>
		<hr />
		<div class="subcolumns">
			<div class="c50l">
				<div class="row">
					<?php
						echo "<b>".Yii::t('expenses','ExpenseName').":</b><br />".$model->expense_name;
					?>
				</div>
				<br />
				<div class="row">
					<?php
						echo "<b>".Yii::t('expenses','ExpenseIdentifier').":</b><br />".$model->expense_identifier;
					?>
				</div>
			</div>
			<div class="c50r" style="text-align:right">
				<div class="row" style="text-align:right">
					<?php echo "<b>".Yii::t('expenses','ExpenseDate').": </b><br />".CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->expense_date, "medium", false)); ?>
				</div>
			</div>
		</div>
		<br /><hr />
		<?php } ?>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('expensesConcepts', 'FieldCreate'), Yii::app()->controller->createUrl('create', array('owner'=>((isset($model->expense_id)) ? $model->expense_id : $_GET['owner']))), array('class'=>'button primary')); ?>
		</div>
		<?php
		$this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'expenses-grid',
			'cssFile'=>'css/screen.css',
			'dataProvider'=>$dataProvider,
			'summaryText'=>Yii::t('site','summaryText'),
			'emptyText'=>Yii::t('site','emptyText'),
			'columns'=>array(
				array(
					'class'=>'CButtonColumn',
					'template' => '{update}{deleting}',
					'visible'=>(Yii::app()->user->checkAccess('updateExpenseConcepts') && $model->status_id == Status::STATUS_PENDING) ? true : false,
					'headerHtmlOptions'=>array(
						'style'=>'width:35px',
					),
					'buttons'=>array(
						'update' => array(
							'label' => Yii::t('site','EditLink'),
							'url' =>'Yii::app()->controller->createUrl("econcepts/update",array("id"=>$data->expensesConcept_id,"owner"=>$_GET["owner"],"ajax"=>true))',
						),
						'deleting' => array(
							'label' => Yii::t('site','DeleteLink'),
							'url' =>'Yii::app()->controller->createUrl("econcepts/delete",array("id"=>$data->expensesConcept_id,"ajax"=>true))',
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
										$("#expenses-grid").addClass("loading");
									},
									success:function(data) {
										if(data.success){
											$("#TotalAmount").text(data.amount);
											$.fn.yiiGridView.update("expenses-grid");
										}
									}
                            	});
							}',
						),
					),
				),
				array(
					'type'=>'number',
					'name'=>'expensesConcept_quantity',
					'value'=>'$data->expensesConcept_quantity',
					'headerHtmlOptions'=>array(
						'style'=>'width:10%',
					),
				),
				array(
					'type'=>'raw',
					'name'=>'expensesConcept_description',
					'value'=>'$data->expensesConcept_description',
					'headerHtmlOptions'=>array(
						'style'=>'width:60%',
					),
				),
				array(
					'type'=>'raw',
					'name'=>'expensesConcept_amount',
					'value'=>'"<div style=\"text-align:right;\">".Yii::app()->numberFormatter->formatCurrency($data->expensesConcept_amount, $data->Expenses->Projects->Currency->currency_code)."</div>"',
				),
			),
		));
		?>
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