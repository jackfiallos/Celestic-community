<div class="view">
	<div class="grid_7">
		<h3><?php echo CHtml::link(CHtml::encode($data->expense_name." No. ".$data->expense_number), array('expenses/view', 'id'=>$data->expense_id)); ?></h3>
		<?php
		$this->widget('widgets.HasComments',array(
			'countComments' => Logs::getCountComments(Yii::app()->controller->id,$data->expense_id),
			'item_id' => $data->expense_id,
		));
		?>
	</div>
	<div class="grid_5">
		<div class="subcolumns">	
			<div class="c33l">
				<b><?php echo $data->getAttributeLabel('expense_date'); ?>:</b>
			</div>
			<div class="c66r">
				<?php echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->expense_date, "medium", false)); ?><br />
			</div>
		</div>
		<?php if (!empty($data->expense_identifier)):?>
		<div class="subcolumns">
			<div class="c33l">
				<b><?php echo $data->getAttributeLabel('expense_identifier'); ?>:</b>
			</div>
			<div class="c66r">
				<?php echo CHtml::encode($data->expense_identifier); ?>
			</div>
		</div>
		<?php endif;?>
		<div class="subcolumns">
			<div class="c33l">
				<b><?php echo $data->getAttributeLabel('status_id'); ?>:</b>
			</div>
			<div class="c66r">
				<span class="status st<?php echo CHtml::encode($data->status_id); ?>"><?php echo CHtml::encode($data->Status->status_name); ?></span>
			</div>
		</div>
	</div>
	<div style="text-align:right">
		<b><?php echo CHtml::link(Yii::t('expenses','ViewDetails'), array('expenses/view', 'id'=>$data->expense_id), array('class'=>'detailsImg')); ?></b>
	</div>
</div>