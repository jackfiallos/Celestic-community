<div class="view">
	<h3><?php echo CHtml::link(Yii::t('invoices', 'IdInvoice')." #".CHtml::encode($data->invoice_number), array('invoices/view', 'id'=>$data->invoice_id)); ?></h3>
</div>