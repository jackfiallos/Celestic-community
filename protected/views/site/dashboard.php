<?php
$this->pageTitle=Yii::app()->name . ' - Dashboard';

$this->breadcrumbs=array(
	'Dashboard',
);
?>
<div class="container_12">
	<div class="grid_8">
		<?php 
		if (Yii::app()->user->getState('project_selected') == null)
		{
			// Projects Access logs
			if(Yii::app()->user->checkAccess('dashboardLogs'))
			{
				$this->widget('widgets.ListLogs',array(
					'userExtended'=>true,
					'htmlOptions' => array(
						'class'=>'portlet',
					)
				));
			}
		}
		else
		{
			// Projects Access
			if(Yii::app()->user->checkAccess('dashboardProjects'))
			{
				$this->widget('widgets.projectsProgress', array(
					'htmlOptions' => array(
						'class'=>'portlet'
					)
				));
			}
		}
		?>
	</div>
	<div class="grid_4">
		<?php
		// Calendar Access
		$this->widget('widgets.Calendar', array(
			'htmlOptions' => array(
				'class'=>'portlet',
			)
		));
		
		if (Yii::app()->user->getState('project_selected') == null)
		{
			if(Yii::app()->user->checkAccess('dashboardTasks'))
			{
				// Tasks
				$this->widget('widgets.LateTask',array(
					'htmlOptions' => array(
						'class'=>'portlet',
					)
				));
			}
		}
		?>
	</div>
	<div class="clear">&nbsp;</div>
</div>
<?php if (Yii::app()->user->getState('project_selected') != null) :?>
<div class="container_12">
	<div class="grid_4">
		<?php
		// Projects Access logs
		if(Yii::app()->user->checkAccess('dashboardLogs'))
		{
			$this->widget('widgets.ListLogs',array(
				'userExtended'=>true,
				'htmlOptions' => array(
					'class'=>'portlet',
				)
			));
		}
		?>
	</div>
	<div class="grid_4">
		<?php
		// Invoices Access
		if(Yii::app()->user->checkAccess('dashboardInvoices'))
		{		
			$this->widget('widgets.InvoicesStatistics',array(
				'htmlOptions' => array(
					'class'=>'portlet',
				)
			));
		}
		
		// Projects Access
		if(Yii::app()->user->getState('project_selected') != null)
		{
			if(Yii::app()->user->checkAccess('dashboardProjects'))
			{
				$this->widget('widgets.EffortDistribution',array(
					'htmlOptions' => array(
						'class'=>'portlet',
					)
				));
			}
		}
		
		// Milestones Access
		if(Yii::app()->user->checkAccess('dashboardMilestones'))
		{
			$this->widget('widgets.UpcomingEvents',array(
				'htmlOptions' => array(
					'class'=>'portlet',
				)
			));
		}
		
		// Milestones Access
		if(Yii::app()->user->checkAccess('dashboardMilestones'))
		{
			$this->widget('widgets.OverdueMilestones',array(
				'htmlOptions' => array(
					'class'=>'portlet',
				)
			));
		}
		?>
	</div>
	<div class="grid_4">
		<?php
		// Tasks Access
		if(Yii::app()->user->checkAccess('dashboardTasks'))
		{
			$this->widget('widgets.LateTask',array(
				'htmlOptions' => array(
					'class'=>'portlet',
				)
			));
			
			// Tasks Access
			$this->widget('widgets.HelpResources', array(
				'htmlOptions' => array(
					'class'=>'portlet',
				)
			));
		}
		
		// Documents Access
		if(Yii::app()->user->checkAccess('dashboardDownloads'))
		{
			$this->widget('widgets.DocumentsUploaded',array(
				'htmlOptions' => array(
					'class'=>'portlet',
				)
			));
		}
		?>
	</div>
	<div class="clear">&nbsp;</div>
</div>
<?php endif; ?>