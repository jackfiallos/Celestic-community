<?php
/**
 * DocumentsUploaded class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property array $htmlOptions
 *
 **/ 
Yii::import('zii.widgets.CPortlet');
class DocumentsUploaded extends CPortlet
{
	public $htmlOptions;
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/files.png')." ".Yii::t('portlet', 'RecentDocumentsTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	/**
	 * Get all documents related to selected project
	 * By default project_id is selected
	 * @return model list of documents
	 */
	public function getDocuments()
    {
		return Documents::model()->findDocuments(Yii::app()->user->getState('project_selected'));
    }
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        $this->render('DocumentsUploaded');
    }
}
?>