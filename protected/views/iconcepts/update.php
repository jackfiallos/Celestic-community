<?php
$this->breadcrumbs=array(
	Yii::t('invoices', 'TitleInvoices')=>array('invoices/index'),
	$model->Invoices->invoice_number=>array('invoices/view','id'=>((isset($model->invoice_id)) ? $model->invoice_id : $_GET['owner'])),
	Yii::t('invoicesConcepts', 'TitleInvoiceConcepts')=>array('index','owner'=>$model->invoice_id),
	Yii::t('invoicesConcepts', 'FieldUpdate'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('invoices', 'TitleInvoices')." :: ".CHtml::encode($model->Invoices->invoice_number);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo invoices"><?php echo Yii::t('invoices', 'IdInvoice')." #".$model->Invoices->invoice_number; ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('invoices', 'ListInvoicesConcepts'), Yii::app()->controller->createUrl('index', array('owner'=>((isset($model->invoice_id)) ? $model->invoice_id : $_GET['owner']))),array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>