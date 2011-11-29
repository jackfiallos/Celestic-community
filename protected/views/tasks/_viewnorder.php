<div class="view">
	<h3>
		<?php echo CHtml::link("#".$data->task_id." - ".CHtml::encode($data->task_name), array('tasks/view', 'id'=>$data->task_id)); ?>
		<span style="float:right" class="priority pr<?php echo CHtml::encode($data->task_priority); ?>">
			<?php echo Tasks::getNameOfTaskPriority($data->task_priority); ?>
		</span>
	</h3>
	<?php
	$this->widget('widgets.HasComments',array(
		'countComments' => Logs::getCountComments(Yii::app()->controller->id,$data->task_id),
		'item_id' => $data->task_id,
	));
	?>
	<div class="moduleTextDescription corners">
		<?php echo ECHtml::word_split(CHtml::encode($data->task_description),20)."..."; ?><br />
	</div>
	<div class="subcolumns">
		<div class="c33l">
			<?php if(strtotime($data->task_startDate)!=null) : ?>
			<div class="subcolumns">
				<div class="c38l">
					<b><?php echo $data->getAttributeLabel('task_startDate'); ?>:</b>
				</div>
				<div class="c62r">
					<?php echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->task_startDate, "medium", false)); ?>
				</div>
			</div>
			<?php endif; ?>
			<?php if(strtotime($data->task_endDate)!=null) : ?>
			<div class="subcolumns">
				<div class="c38l">
					<b><?php echo $data->getAttributeLabel('task_endDate'); ?>:</b>
				</div>
				<div class="c62r">
					<?php
						echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->task_endDate, "medium", false));
					?><br />
				</div>
			</div>
			<?php endif; ?>
			<div class="subcolumns">
				<div class="c38l">
					<b><?php echo $data->getAttributeLabel('taskTypes_id'); ?>:</b>
				</div>
				<div class="c62r">
					<span class="tasktypes tty<?php echo $data->taskTypes_id; ?>"><?php echo CHtml::encode($data->Types->taskTypes_name); ?></span>
				</div>
			</div>
		</div>
		<div class="c66r">
			<div class="subcolumns">
				<div class="c20l">
					<b><?php echo $data->getAttributeLabel('status_id'); ?>:</b>
				</div>
				<div class="c80r">
					<span class="status st<?php echo CHtml::encode($data->status_id); ?>"><?php echo CHtml::encode($data->Status->status_name); ?></span>
				</div>
			</div>
			<?php if (Users::countWorkersByTask($data->task_id) > 0): ?>
			<div>
				<?php echo CHtml::link(Yii::t('tasks','viewCollaborators'), Yii::app()->createUrl('tasks/ShowWorkers', array('id'=>$data->task_id)), array('class'=>'viewCollaborators')); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div style="text-align:right">
		<b><?php echo CHtml::link(Yii::t('tasks', 'ViewDetails'), array('tasks/view', 'id'=>$data->task_id), array('class'=>'detailsImg')); ?></b>
	</div>
</div>