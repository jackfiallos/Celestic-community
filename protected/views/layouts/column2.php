<?php $this->beginContent('application.views.layouts.main'); ?>

<div class="container rounded">
	<div class="grid_8">
		<?php echo $content; ?>
	</div>
	<div class="grid_4">
		<?php
			// Milestones Access
			if(Yii::app()->user->checkAccess('dashboardMilestones'))
			{
				$this->widget('widgets.UpcomingEvents', array(
					'htmlOptions' => array(
						'class'=>'portlet'
					)
				));
			}
			
			// Milestones Access
			if(Yii::app()->user->checkAccess('dashboardMilestones'))
			{
				$this->widget('widgets.OverdueMilestones', array(
					'htmlOptions' => array(
						'class'=>'portlet'
					)
				));
			}
			// Commens ticker
			$this->widget('widgets.CommentsFeed', array(
				'htmlOptions' => array(
					'class'=>'portlet'
				),
				'limit'=>10
			));
			
			// Tasks Access
			if(Yii::app()->user->checkAccess('dashboardTasks'))
			{
				$this->widget('widgets.LateTask', array(
					'htmlOptions' => array(
						'class'=>'portlet'
					)
				));
				
				$this->widget('widgets.ListLogs',array(
					'userExtended'=>false,
					'htmlOptions' => array(
						'class'=>'portlet',
					)
				));
			}
		?>
	</div>
	<div class="clear">&nbsp;</div>
	<?php $this->endContent(); ?>
</div>