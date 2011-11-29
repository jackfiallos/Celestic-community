<?php
$this->breadcrumbs=array(
	Yii::t('invoices', 'TitleInvoices')=>array('index'),
	'# '.$invoice->invoice_number=>array('view','id'=>$invoice->invoice_id),
	Yii::t('invoices', 'UpdateInvoices'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('invoices', 'UpdateInvoices')." :: #".$invoice->invoice_number;
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo invoices"><?php echo Yii::t('invoices', 'UpdateInvoices'); ?> # <?php echo $invoice->invoice_number; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('invoices', 'ListInvoices'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('invoices', 'CreateInvoices'), Yii::app()->controller->createUrl('create'), array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('invoices', 'ViewInvoices'), Yii::app()->controller->createUrl('view', array('id'=>$invoice->invoice_id)), array('class'=>'button')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('invoice'=>$invoice, 'budgets'=>$budgets)); ?>
	</div>
</div>