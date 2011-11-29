<?php
$this->breadcrumbs=array(
	Yii::t('invoices', 'TitleInvoices')=>array('invoices/view','id'=>((isset($model->invoice_id)) ? $model->invoice_id : $_GET['owner'])),
	Yii::t('invoicesConcepts', 'TitleInvoiceConcepts')=>array('index','owner'=>$_GET['owner']),
	Yii::t('invoicesConcepts', 'FieldCreate'),
);
$this->pageTitle = Yii::app()->name." ".Yii::t('invoices', 'TitleInvoices');
$this->pageTitle = Yii::app()->name." - ".Yii::t('invoices', 'TitleInvoices')." :: ".Yii::t('invoices', 'CreateInvoicesConcepts');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo invoices"><?php echo Yii::t('invoices', 'CreateInvoicesConcepts'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('invoices', 'ListInvoicesConcepts'), Yii::app()->controller->createUrl('index', array('owner'=>((isset($model->invoice_id)) ? $model->invoice_id : $_GET['owner']))), array('class'=>'button primary')); ?>
		</div>
		<?php if(Yii::app()->user->hasFlash('iconcepts')):?>
			<div class="info notification_success">
				<?php echo Yii::app()->user->getFlash('iconcepts'); ?>
			</div>
		<?php endif; ?>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
<?php
Yii::app()->clientScript->registerScript(
   'FlashMessage',
   '$(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   CClientScript::POS_READY
);
?>