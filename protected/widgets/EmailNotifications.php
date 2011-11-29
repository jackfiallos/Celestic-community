<?php
Yii::import('zii.widgets.CPortlet');
class EmailNotifications extends CPortlet
{
	public $htmlOptions;
	
	public function init()
	{
		$this->title=Yii::t('portlet', 'EmailNotificationsTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	public function getActivity()
    {
		return Tasks::model()->findActivity(Yii::app()->user->id);
    }
	
    protected function renderContent()
    {
        $this->render('EmailNotifications');
    }
}
?>