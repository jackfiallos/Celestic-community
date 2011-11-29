<?php
Yii::import('zii.widgets.CPortlet');
class MyProjects extends CPortlet
{
	public $htmlOptions;
	
	public function init()
	{
		$this->title=Yii::t('portlet', 'MyProjectsTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	public function getMyProjects()
    {
		return Projects::model()->findMyProjects(Yii::app()->user->id);
    }
	
    protected function renderContent()
    {
        $this->render('MyProjects');
    }
}
?>