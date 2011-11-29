<?php
/**
 * ListComments class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property int $resourceid
 * @property int $moduleid
 *
 **/ 
Yii::import('zii.widgets.CPortlet');
class ListComments extends CPortlet
{
	public $resourceid;
	public $moduleid;
	public $htmlOptions;
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title=Yii::t('comments','CommentsTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	/**
	 * Get all comments from module
	 * @param string $moduleName
	 * @return model list of comments
	 */
    public function getComments($moduleName)
    {
		return Comments::model()->findComments($moduleName, $this->resourceid);
    }
	
	/**
	 * Get all files attached to comment
	 * @param int $comment_id
	 * @return model list comments with attach files
	 */
	public function getAttachments($comment_id)
	{
		return Comments::model()->findAttachments($comment_id);
	}
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        $this->render('ListComments');
    }
}
?>