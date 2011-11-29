<?php foreach($this->getMyProjects() as $project): ?>
	<div style="height:60px;">
		<div class="comm_date">
			<span class="data">
				<span class="j"><?php echo CHtml::encode(CHtml::encode(Yii::app()->dateFormatter->format("dd", $project->project_endDate))); ?></span>
				<?php echo CHtml::encode(CHtml::encode(Yii::app()->dateFormatter->format("MM/yy", $project->project_endDate))); ?>
			</span>
		</div>
		<div class="logactivity">
		<?php
			echo CHtml::link(ECHtml::word_split(CHtml::encode($project->project_name),4), Yii::app()->createUrl("projects/view",array("id"=>$project->project_id)))."<br />";
			echo "<span class=\"new\"><abbr class=\"timeago\" title=\"".CHtml::encode(Yii::app()->dateFormatter->format('yyyy-MM-dd', $project->project_startDate))."\">".CHtml::encode(Yii::app()->dateFormatter->format('dd-MM-yyyy', $project->project_startDate))."</abbr></span><br />";
		?>
		</div>
	</div>
<?php endforeach; ?>