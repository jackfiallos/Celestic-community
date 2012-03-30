<?php
/**
 * CommentsFeed class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property int $LineLenght
 * @property array $htmlOptions
 *
 **/ 
Yii::import('zii.widgets.CPortlet');
class CommentsFeed extends CPortlet
{
	public $htmlOptions;
	public $lineLenght = 11;
	public $limit = 10;
	
	/**
	 * Class object initialization
	 */
	public function init()
	{
		$this->title=CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/comment.png')." ".Yii::t('comments','CommentsTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	public function getActivity()
    {
    	return Comments::model()->findActivity(Yii::app()->user->getState('project_selected'), $this->limit);
    }
    
    public function findModuleTitle($module, $title, $resource)
    {
		$modelClass = new $module();
		return $modelClass::model()->findByPk($resource)->$title;
    }
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        $this->render('CommentsFeed');
    }
}
?>