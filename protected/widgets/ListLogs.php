<?php
/**
 * ListLogs class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property int $moduleid
 * @property boolean $userExtended
 * @property array $htmlOptions
 *
 **/ 
Yii::import('zii.widgets.CPortlet');
class ListLogs extends CPortlet
{
	public $moduleid;
	public $userExtended;
	public $htmlOptions;

	/**
	 * Class object initialization
	 */
    public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/logs.png')." ".Yii::t('portlet', 'RecentActivityTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
 
	/**
	 * Get all project activity
	 * @param string $moduleName
	 * @return model list of log activity
	 */
    public function getActivity($moduleName)
    {
		$project_idSelected = Yii::app()->user->getState('project_selected');
		
		if (empty($project_idSelected))
		{
			$projectList = array();
			$Projects = Projects::model()->findMyProjects(Yii::app()->user->id);
			foreach($Projects as $project)
			{
				array_push($projectList,$project->project_id);
			}
		}
		
		return Logs::model()->findActivity(
			$moduleName, 
			(!empty($project_idSelected)?array($project_idSelected):$projectList)
		);
    }
 
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        ($this->userExtended) ? $this->render('ListLogsExtended') : $this->render('ListLogs');
    }
}
?>