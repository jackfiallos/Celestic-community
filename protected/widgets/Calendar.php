<?php
/**
 * Calendar class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property array $htmlOptions
 *
 **/  
Yii::import('zii.widgets.CPortlet');
class Calendar extends CPortlet
{	
	public $htmlOptions;
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/calendar.png')." ".Yii::t('portlet', 'CalendarTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	/**
	 * Get all projects events (milestones)
	 * By default project_id is selected
	 * @return model list milestones
	 */
	public function getCalendarEvents()
	{
		if (Yii::app()->user->getState('project_selected') != null)
			$projects = Yii::app()->user->getState('project_selected');
		else
		{
			$WorkingProjects = Projects::model()->findMyProjects(Yii::app()->user->id);
			$projectList = array();
			foreach($WorkingProjects as $project)
				array_push($projectList, $project->project_id);
			
			$projects = implode(",", $projectList);
		}
		
		// Finding all projects milestones
		$Milestones = Milestones::model()->findAll(array(
            'condition'=>'t.project_id IN (:project_id)',
			'params'=>array(
				'project_id' => $projects,
			),
        ));
		
		// Creating event format required by fullcalendar component
		$arrayOfEvents = array();
		foreach($Milestones as $milestone)
		{
			array_push($arrayOfEvents, array(
				'title' => $milestone->milestone_title,
				'start' => CHtml::encode(Yii::app()->dateFormatter->format('yyyy-MM-dd',$milestone->milestone_duedate)),
				'end' => CHtml::encode(Yii::app()->dateFormatter->format('yyyy-MM-dd',$milestone->milestone_duedate)),
				'description' => $milestone->milestone_description,
				'className' => 'holiday',
			));
		}
		
		return $arrayOfEvents;
	}
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        //if(Yii::app()->user->getState('project_selected') != null)
    		$this->render('Calendar');
    }
}
?>