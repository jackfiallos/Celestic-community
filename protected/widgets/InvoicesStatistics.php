<?php
/**
 * InvoicesStatistics class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property array $htmlOptions
 *
 **/ 
Yii::import('zii.widgets.CPortlet');
class InvoicesStatistics extends CPortlet
{
	public $htmlOptions;
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/invoices.png')." ".Yii::t('portlet', 'InvoicesStatisticsTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	/**
	 * Get all task waiting to solve
	 * By default project_id is selected
	 * @return model list of invoices statistics amounts
	 */
	public function getStatistics()
	{
		if(Yii::app()->user->getState('project_selected') != null)
			return Invoices::model()->getInvoicesStatistics(Yii::app()->user->getState('project_selected'));
		else
		{
			$Projects = Projects::model()->findMyProjects(Yii::app()->user->id);
			$projectList = array(0);
			foreach($Projects as $project)
				array_push($projectList, $project->project_id);
			
			return Invoices::model()->getInvoicesStatistics(implode(",", $projectList));
		}
	}
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        $this->render('InvoicesStatistics');
    }
}
?>