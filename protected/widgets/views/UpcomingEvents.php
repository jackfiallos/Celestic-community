<?php foreach($this->getActivity() as $milestone): ?>
	<div class="comm_date">
		<span class="data">
			<span class="j"><?php echo CHtml::encode(CHtml::encode(Yii::app()->dateFormatter->format("dd", $milestone->milestone_duedate))); ?></span>
			<?php echo CHtml::encode(CHtml::encode(Yii::app()->dateFormatter->format("MM/yy", $milestone->milestone_duedate))); ?>
		</span>
	</div>	
	<div class="logactivity">
		<?php
			echo CHtml::image(Yii::app()->request->baseUrl.'/images/icons/milestones-12.png')."&nbsp;";
			echo CHtml::link(CHtml::encode($milestone->milestone_title), Yii::app()->createUrl("milestones/view",array("id"=>$milestone->milestone_id))) . "<br />";
			echo ECHtml::word_split(CHtml::encode($milestone->milestone_description),6)."...";
			//echo "&nbsp;<span style=\"font-size:10px;background-color:#FCFFCD;\">Due: ".CHtml::encode(CHtml::encode(Yii::app()->dateFormatter->format("dd.MM.yyyy", $milestone->milestone_duedate)))."</span>";
		?>
	</div>
	<br />
<?php endforeach; ?>