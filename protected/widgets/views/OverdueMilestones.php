<?php foreach($this->getOverdue() as $milestone): ?>
	<div class="comm_date" style="background-color:#FFCFCF;">
		<span class="data">
			<span class="j"><?php echo CHtml::encode(CHtml::encode(Yii::app()->dateFormatter->format("dd", $milestone->milestone_duedate))); ?></span>
			<?php echo CHtml::encode(CHtml::encode(Yii::app()->dateFormatter->format("MM/yy", $milestone->milestone_duedate))); ?>
		</span>
	</div>
	<div class="logactivity">
		<?php
			echo CHtml::image(Yii::app()->request->baseUrl.'/images/icons/milestonesoverd-12.png')."&nbsp;";
			echo CHtml::link(CHtml::encode($milestone->milestone_title), Yii::app()->createUrl("milestones/view",array("id"=>$milestone->milestone_id)), array('style'=>'color:#9F0000;'))."<br />";
			echo ECHtml::word_split(CHtml::encode($milestone->milestone_description),9)."...";
		?>
	</div>
	<br />
<?php endforeach; ?>