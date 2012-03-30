<div class="view">
	<div class="grid_7">
		<h3>
			<?php echo CHtml::link("#".$data->task_id." - ".CHtml::encode($data->task_name), array('tasks/view', 'id'=>$data->task_id)); ?>
		</h3>
		<?php echo ECHtml::word_split(CHtml::encode($data->task_description),20)."..."; ?><br />
	</div>
	<div class="grid_5">
		<span style="float:right" class="priority pr<?php echo CHtml::encode($data->task_priority); ?>">
			<?php echo Tasks::getNameOfTaskPriority($data->task_priority); ?>
		</span>
		<?php if (Users::countWorkersByTask($data->task_id) > 0): ?>
		<div style="float:left;">
			<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/collaborators.png','',array('style'=>'vertical-align:middle;'))." ".CHtml::link(Yii::t('tasks','viewCollaborators'), Yii::app()->createUrl('tasks/ShowWorkers', array('id'=>$data->task_id)), array('class'=>'viewCollaborators')); ?>
		</div>
		<?php endif; ?>
		<?php if(substr($data->task_startDate,0,10) != '0000-00-00') : ?>
		<div class="subcolumns">
			<div class="c33l">
				<b><?php echo $data->getAttributeLabel('task_startDate'); ?>:</b>
			</div>
			<div class="c66r">
				<?php echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->task_startDate, "medium", false)); ?>
			</div>
		</div>
		<?php endif; ?>
		<?php if(substr($data->task_endDate,0,10) != '0000-00-00') : ?>
		<div class="subcolumns">
			<div class="c33l">
				<b><?php echo $data->getAttributeLabel('task_endDate'); ?>:</b>
			</div>
			<div class="c66r">
				<?php
					echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->task_endDate, "medium", false));
				?><br />
			</div>
		</div>
		<?php endif; ?>
	</div>
	<div class="clear" style="border-bottom:1px solid #ccc;"> </div>
	<hr style="width: 100%; border-bottom:1px solid #ccc; margin-bottom:5px;" />
	<div class="subcolumns">
		<div class="c50l">
			<span class="status st<?php echo CHtml::encode($data->status_id); ?>"><?php echo CHtml::encode($data->Status->status_name); ?></span>
			<span class="tasktypes tty<?php echo $data->taskTypes_id; ?>"><?php echo CHtml::encode($data->Types->taskTypes_name); ?></span>
			<?php
			$this->widget('widgets.HasComments',array(
				'countComments' => Logs::getCountComments(Yii::app()->controller->id,$data->task_id),
				'item_id' => $data->task_id,
			));
			?>
		</div>
		<div class="c50r" style="text-align:right">
			<b><?php echo CHtml::link(Yii::t('tasks', 'ViewDetails'), array('tasks/view', 'id'=>$data->task_id), array('class'=>'detailsImg')); ?></b>
		</div>
	</div>
</div>