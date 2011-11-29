<?php
/**
 * OverdueMilestones class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property array $htmlOptions
 *
 **/
Yii::import('zii.widgets.CPortlet');
class OverdueMilestones extends CPortlet
{
	public $htmlOptions;
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/overdue.png')." ".Yii::t('portlet', 'OverdueMilestonesTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	/**
	 * Get milestoned overdue
	 * By default project_id is selected
	 * @return model milestones
	 */
	public function getOverdue()
    {
		return Milestones::model()->findOverdue(Yii::app()->user->getState('project_selected'));
    }
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        $this->render('OverdueMilestones');
    }
}
?>