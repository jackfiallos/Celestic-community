<?php
$this->breadcrumbs=array(
	Yii::t('invoices', 'TitleInvoices')=>array('index'),
	Yii::t('invoices', 'CreateInvoices'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('invoices', 'TitleInvoices')." :: ".Yii::t('invoices', 'CreateInvoices');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo invoices"><?php echo Yii::t('invoices', 'CreateInvoices'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('invoices', 'ListInvoices'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('invoice'=>$invoice, 'budgets'=>$budgets, 'lastused'=>$lastused)); ?>
	</div>
</div>