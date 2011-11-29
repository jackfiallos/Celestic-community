<div class="view">
	<div class="subcolumns">
		<div class="c50l">
			<h3><?php echo CHtml::link("#".$data->milestone_id." - ".CHtml::encode($data->milestone_title), array('milestones/view', 'id'=>$data->milestone_id)); ?></h3>
		</div>
		<div class="c50r">
			<div class="prgrss-container">          
				<div style="width:<?php echo round($data->percent,2); ?>%">
					<span><?php echo round($data->percent,2); ?>%</span>
				</div>
			</div>
		</div>
	</div>
	<?php
	$this->widget('widgets.HasComments',array(
		'countComments' => Logs::getCountComments(Yii::app()->controller->id,$data->milestone_id),
		'item_id' => $data->milestone_id,
	));
	?>
	<div class="moduleTextDescription corners">
		<?php echo ECHtml::word_split(Yii::app()->format->ntext($data->milestone_description),20)."..."; ?><br />
	</div>
	<div class="subcolumns">
		<div class="c20l">
			<b><?php echo CHtml::encode($data->getAttributeLabel('milestone_duedate')); ?>:</b><br />
		</div>
		<div class="c80r">
			<?php echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->milestone_duedate, "medium", false)); ?><br />
		</div>
	</div>
	<div class="subcolumns">
		<div class="c20l">
			<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b><br />
		</div>
		<div class="c80r">
			<?php echo (isset($data->user_id)) ? CHtml::link(CHtml::encode($data->Users->completeName), Yii::app()->createUrl('users/view',array('id'=>$data->user_id))) : ''; ?><br />	
		</div>
	</div>
	<div style="text-align:right">
		<b><?php echo CHtml::link(Yii::t('milestones', 'ViewDetails'), array('milestones/view', 'id'=>$data->milestone_id), array('class'=>'detailsImg')); ?></b>
	</div>
</div>