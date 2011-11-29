<div class="view">
	<h3><?php echo CHtml::link(CHtml::encode($data->company_name), array('view', 'id'=>$data->company_id)); ?></h3>
	<?php if(!empty($data->company_url)): ?>
	<div class="subcolumns">
		<div class="c20l">
			<b><?php echo CHtml::encode($data->getAttributeLabel('company_url')); ?>:</b>
		</div>
		<div class="c80r">
			<?php echo CHtml::link(CHtml::encode($data->company_url),CHtml::encode($data->company_url)); ?>
		</div>
	</div>
	<?php endif; ?>
	<?php if(!empty($data->company_uniqueId)): ?>
	<div class="subcolumns">
		<div class="c20l">
			<b><?php echo CHtml::encode($data->getAttributeLabel('company_uniqueId')); ?>:</b>
		</div>
		<div class="c80r">
			<?php echo CHtml::encode($data->company_uniqueId); ?>
		</div>
	</div>
	<?php endif; ?>
	<div style="text-align:right">
		<b><?php echo CHtml::link(Yii::t('companies','ViewDetails'), array('view', 'id'=>$data->company_id)); ?></b>
	</div>
</div>