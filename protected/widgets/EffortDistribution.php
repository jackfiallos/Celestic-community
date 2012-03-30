<?php
/**
 * EffortDistribution class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property array $htmlOptions
 *
 **/ 
Yii::import('zii.widgets.CPortlet');
class EffortDistribution extends CPortlet
{
	public $htmlOptions;
	public $seriesStages = array();
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/charts.png')." ".Yii::t('portlet', 'EffortDistribution');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	public function getProjectDataEffort()
	{
		$criteria = new CDbCriteria;
		$criteria->select = "COUNT(DISTINCT t.task_id) as total, Status.status_id";
		$criteria->condition = "Projects.project_id = :project_id";
		$criteria->params = array(
			':project_id' => Yii::app()->user->getState('project_selected'),
		);			
		$criteria->group = "Stage.taskStage_id";
		$countStages = Tasks::model()->with('Projects','Stage')->together()->findAll($criteria);

		$totalTareas = 0;
		foreach($countStages as $key)
			$totalTareas += intval($key->total);
		
		foreach($countStages as $key){
			$this->seriesStages[] = array($key->Stage->taskStage_name, round((intval($key->total)/$totalTareas)*100));
		}
			
		/*echo "<pre>";
		print_r($this->seriesStages);
		die("</pre>");*/
		
		//return $this->seriesStages;
	}
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        $this->render('EffortDistribution');
    }
}
?>