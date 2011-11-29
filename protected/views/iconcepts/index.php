<?php
$this->breadcrumbs=array(
	Yii::t('invoices', 'TitleInvoices')=> array('invoices/index'),
	'# '.$model->invoice_number => array('invoices/view','id'=>((isset($model->invoice_id)) ? $model->invoice_id : $_GET['owner'])),
	Yii::t('invoicesConcepts', 'TitleInvoiceConcepts'),
);
$this->pageTitle = Yii::app()->name." ".Yii::t('invoices', 'TitleInvoices');
$this->pageTitle = Yii::app()->name." - ".Yii::t('invoices', 'TitleInvoices')." :: ".CHtml::encode($model->invoice_number);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<?php if (isset($model)): ?>
		<?php echo CHtml::tag("div", array('class'=>'stick notification_'.$model->Status->status_id),Yii::t('invoices', $model->Status->status_name)); ?><br />
		<div class="subcolumns">
			<div class="c66l" style="font-weight:bold; font-size:14px;">
				<?php echo CHtml::encode($account->account_companyName); ?><br />
				<?php if(isset($account->Address->city_id)):?>
				<?php echo (!empty($account->Address->address_text)) ? CHtml::encode($account->Address->address_text)."<br />" : ''; ?>
				<?php echo (!empty($account->Address->City->city_name)) ? CHtml::encode($account->Address->City->city_name).", ".CHtml::encode($account->Address->City->Country->country_name)."<br />" : ''; ?>
				<?php endif;?>
				<?php echo (!empty($account->Address->address_postalCode)) ? "C.P. " . CHtml::encode($account->Address->address_postalCode) : ''; ?>
			</div>
			<div class="c33r">
				<div class="wraptocenter">
					<span></span>
					<?php
						if (!empty($account->account_logo))
							echo CHtml::image(Yii::app()->request->baseUrl."/".$account->account_logo, 'Company Logo', array('class'=>'borderCaption','width'=>'180px'));
						else
							echo CHtml::image('http://'.$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl.'/images/bg-avatar.png', 'Company Logo', array('class'=>'borderCaption'))
					?>
				</div>
			</div>
		</div>
		<br /><br />
		<div class="subcolumns">
			<div class="c66l">
				<h3 style="font-weight:bold"><?php echo CHtml::link(CHtml::encode($model->Projects->Company->company_name), Yii::app()->controller->createUrl('companies/view',array('id'=>$model->Projects->Company->company_id))); ?></h3>
				<?php if(isset($model->Projects->Company->Address->city_id)):?>
				<?php echo CHtml::encode($model->Projects->Company->Address->address_text); ?><br />
				<?php echo "C.P " . CHtml::encode($model->Projects->Company->Address->address_postalCode); ?><br />
				<?php echo CHtml::encode($model->Projects->Company->Address->City->city_name); ?><br />
				<?php echo CHtml::encode($model->Projects->Company->Address->City->Country->country_name); ?><br />
				<?php endif;?>
			</div>
			<div class="c33r">
				<div class="subcolumns">
					<div class="c50l">
						<?php echo Yii::t('invoices', 'IdInvoice'); ?> #<br />
						<?php echo Yii::t('invoices', 'InvoiceDate'); ?><br />
						<?php echo Yii::t('companies', 'uniqueId'); ?><br />
					</div>
					<div class="c50r" style="text-align:right">
						<?php echo $model->invoice_number; ?><br />
						<?php echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->invoice_date, "medium", false)); ?><br />
						<?php echo $model->Projects->Company->company_uniqueId; ?>
					</div>
				</div>
				<div style="background-color:#DFDFDF; width:100%; padding:5px 0 5px 5px;">
					<?php echo Yii::t('invoices', 'InvoiceAmount'); ?>
					<div style="text-align:right; float:right; padding-right:10px;">
						<?php echo Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($model->Cost), $model->Projects->Currency->currency_code)." ".$model->Projects->Currency->currency_code; ?>
					</div>
				</div>
			</div>
		</div>
		<br />
		<?php endif; ?>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('invoicesConcepts', 'FieldCreate'), Yii::app()->controller->createUrl('create',array('owner'=>$_GET['owner'])), array('class'=>'button primary')); ?>
		</div>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'invoices-grid',
			'cssFile'=>'css/screen.css',
			'dataProvider'=>$dataProvider,
			'summaryText'=>Yii::t('site','summaryText'),
			'emptyText'=>Yii::t('site','emptyText'),
			'columns'=>array(
				array(
					'class'=>'CButtonColumn',
					'template' => '{update}{deleting}',
					'visible'=>(Yii::app()->user->checkAccess('updateInvoiceConcepts') && $model->status_id == Status::STATUS_PENDING) ? true : false,
					'headerHtmlOptions'=>array(
						'style'=>'width:35px',
					),
					'buttons'=>array(
						'update' => array(
							'label' => Yii::t('site','EditLink'),
							'url' =>'Yii::app()->controller->createUrl("iconcepts/update",array("id"=>$data->invoicesConcept_id,"owner"=>$_GET["owner"],"ajax"=>true))',
						),
						'deleting' => array(
							'label' => Yii::t('site','DeleteLink'),
							'url' =>'Yii::app()->controller->createUrl("iconcepts/delete",array("id"=>$data->invoicesConcept_id,"ajax"=>true))',
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
										$("#invoices-grid").addClass("loading");
									},
									success:function(data) {
										if(data.success){
											$("#TotalAmount").text(data.amount);
											$.fn.yiiGridView.update("invoices-grid");
										}
									}
                            	});
							}',
						),
					),
				),
				array(
					'type'=>'number',
					'name'=>'invoicesConcept_quantity',
					'value'=>'$data->invoicesConcept_quantity',
					'headerHtmlOptions'=>array(
						'style'=>'width:10%',
					),
				),
				array(
					'type'=>'raw',
					'name'=>'invoicesConcept_description',
					'value'=>'$data->invoicesConcept_description',
					'headerHtmlOptions'=>array(
						'style'=>'width:60%',
					),
				),
				array(
					'type'=>'raw',
					'name'=>'invoicesConcept_amount',
					'value'=>'"<div style=\"text-align:right;\">".Yii::app()->numberFormatter->formatCurrency($data->invoicesConcept_amount, $data->Invoices->Projects->Currency->currency_code)."</div>"',
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