<div class="view">
	<div class="grid_7">
		<h3><?php echo CHtml::link(Yii::t('invoices', 'IdInvoice')." #".CHtml::encode($data->invoice_number), array('invoices/view', 'id'=>$data->invoice_id)); ?></h3>
		<?php
		$this->widget('widgets.HasComments',array(
			'countComments' => Logs::getCountComments(Yii::app()->controller->id,$data->invoice_id),
			'item_id' => $data->invoice_id,
		));
		?>
	</div>
	<div class="grid_5">
		<div class="subcolumns">
			<div class="c33l">
				<b><?php echo CHtml::encode($data->getAttributeLabel('invoice_date')); ?>:</b><br />
			</div>
			<div class="c66r">
				<?php echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->invoice_date, "medium", false)); ?><br />
			</div>
		</div>
		<div class="subcolumns">
			<div class="c33l">	
				<b><?php echo CHtml::encode($data->getAttributeLabel('status_id')); ?>:</b><br />
			</div>
			<div class="c66r">
				<span class="status st<?php echo CHtml::encode($data->status_id); ?>"><?php echo CHtml::encode($data->Status->status_name); ?></span>
			</div>
		</div>
		<div class="subcolumns">
			<div class="c33l">	
				<b><?php echo Yii::t('invoices','InvoiceAmount'); ?>:</b><br />
			</div>
			<div class="c66r">
				<?php echo Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($data->Cost), $data->Projects->Currency->currency_code); ?>
			</div>
		</div>
	</div>
	<div style="text-align:right">
		<b><?php echo CHtml::link(Yii::t('invoices', 'ViewDetails'), array('invoices/view', 'id'=>$data->invoice_id), array('class'=>'detailsImg')); ?></b>
	</div>
</div>