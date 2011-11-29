<?php
/**
 * LateTask class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property array $htmlOptions
 *
 **/  
Yii::import('zii.widgets.CPortlet');
class LateTask extends CPortlet
{
	public $htmlOptions;
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/bug.png')." ".Yii::t('portlet', 'TasksToDoTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	/**
	 * Get all task waiting to solve
	 * By default user_id and project_id
	 * @return model list of task to solve
	 */
	public function getActivity()
    {
		return Tasks::model()->findActivity(Yii::app()->user->id, Yii::app()->user->getState('project_selected'));
    }
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        $this->render('LateTask');
    }
}
?>