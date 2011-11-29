<?php
/**
 * UpcomingEvents class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property array $htmlOptions
 *
 **/
Yii::import('zii.widgets.CPortlet');
class UpcomingEvents extends CPortlet
{
	public $htmlOptions;
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/overdue.png')." ".Yii::t('portlet', 'UpcomingMilestonesTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	/**
	 * Return all milestones next to due
	 * By default project_id is selected
	 * @return model list of milestones
	 */
	public function getActivity()
    {
		return Milestones::model()->findActivity(Yii::app()->user->getState('project_selected'));
    }
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
	protected function renderContent()
    {
        $this->render('UpcomingEvents');
    }
}
?>