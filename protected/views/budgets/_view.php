<div class="view">
	<div class="grid_7">
		<h3><?php echo CHtml::link(CHtml::encode($data->budget_title), array('budgets/view', 'id'=>$data->budget_id)); ?></h3>
		<?php echo ECHtml::word_split(Yii::app()->format->ntext($data->budget_notes))."..."; ?><br />
		<?php
		$this->widget('widgets.HasComments',array(
			'countComments' => Logs::getCountComments(Yii::app()->controller->id,$data->budget_id),
			'item_id' => $data->budget_id,
		));
		?>
	</div>
	<div class="grid_5">
		<div class="subcolumns">
			<div class="c33l">
				<b><?php echo $data->getAttributeLabel('budget_date'); ?>:</b><br />
			</div>
			<div class="c66r">
				<abbr class="timeago" title="<?php echo CHtml::encode(Yii::app()->dateFormatter->format('yyyy-MM-dd', $data->budget_date)); ?>">
					<?php echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->budget_date, 'medium', false)); ?>
				</abbr>
			</div>
		</div>
		<div class="subcolumns">
			<div class="c33l">		
				<b><?php echo CHtml::encode($data->getAttributeLabel('budget_duedate')); ?>:</b><br />
			</div>
			<div class="c66r">
				<?php echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->budget_duedate, 'medium', false)); ?><br />
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
	</div>
	<div style="text-align:right">
		<b><?php echo CHtml::link(Yii::t('budgets', 'ViewDetailsBudget'), array('budgets/view', 'id'=>$data->budget_id), array('class'=>'detailsImg')); ?></b>
	</div>
</div>