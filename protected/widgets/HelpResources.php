<?php
/**
 * HelpResources class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property array $htmlOptions
 *
 **/ 
Yii::import('zii.widgets.CPortlet');
class HelpResources extends CPortlet
{
	public $htmlOptions;
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/progressbar.png')." ".Yii::t('portlet', 'HelpResourcesTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	public function getActivityGroupedByTask()
    {
		return Tasks::model()->findActivityGroupedByTask(Yii::app()->user->id, Yii::app()->user->getState('project_selected'));
    }
	
	/**
	 * Get item numbers for each task status
	 * By default project_id is selected
	 * @return model list of task status counter
	 */
	public function getTaskCounter()
    {
		return Tasks::model()->TaskCounter(Yii::app()->user->id, Yii::app()->user->getState('project_selected'));
    }
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        $this->render('HelpResources');
    }
}
?>