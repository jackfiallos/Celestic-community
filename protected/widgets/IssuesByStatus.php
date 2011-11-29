<?php
/**
 * IssuesByStatus class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property array $htmlOptions
 *
 **/ 
Yii::import('zii.widgets.CPortlet');
class IssuesByStatus extends CPortlet
{
	public $htmlOptions;
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/charts.png')." ".Yii::t('portlet', 'ProjectsProgressTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        $this->render('IssuesByStatus');
    }
}
?>