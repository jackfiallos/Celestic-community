<?php
/**
 * Controller class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class. 
 * @property string $layout
 * @property array $menu
 * @property array $allowedActions
 * @property array $breadcrumbs
 *
 **/
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to 'application.views.layouts.column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='application.views.layouts.column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
		
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	
	/**
	 * Initializes the controller.
	 */
	public function init()
    {
		parent::init();
        
        if (!isset(Yii::app()->request->cookies['sel_lang']))
        {
        	Yii::app()->request->cookies['sel_lang'] = new CHttpCookie('sel_lang', 'en_us');
        	Yii::app()->lc->setLanguage(Yii::app()->request->cookies['sel_lang']->value);
        }
        else 
        {
	        if ((isset($_REQUEST['lc'])) && (!empty($_REQUEST['lc'])))
				Yii::app()->request->cookies['sel_lang'] = new CHttpCookie('sel_lang', $_REQUEST['lc']);
	        
			if (in_array(strtolower(Yii::app()->request->cookies['sel_lang']->value), Yii::app()->params['languages']))
				Yii::app()->lc->setLanguage(Yii::app()->request->cookies['sel_lang']->value);
        }
        
        if ((isset($_GET['infoproject'])) && (!empty($_GET['infoproject'])) && (Users::model()->verifyUserInProject((int)Yii::app()->request->getParam("infoproject",0), Yii::app()->user->id)))
        {
        	Yii::app()->user->setState('project_selected', Yii::app()->request->getParam("infoproject",0));
        	Yii::app()->user->setState('project_selectedName', Projects::model()->findByPk(Yii::app()->user->getState('project_selected'))->project_name);
        	$this->redirect(Yii::app()->createUrl('site'));
        }
        
		$avoid = array('companies');
        
        if ((Yii::app()->user->getState('project_selected') == null) && (Yii::app()->controller->id != null) && (!empty(Yii::app()->controller->ActionParams['id'])) && (!in_array(Yii::app()->controller->id, $avoid)))
        {
	        // Finding module class name
        	$criteria=new CDbCriteria;
			$criteria->compare('module_name', Yii::app()->controller->id);
	        $module = Modules::model()->find($criteria);
	        
	        if ((isset($module->module_className)) &&(class_exists($module->module_className)))
	        {
		        // create class instance
		        $className = $module->module_className;
				$instance = new $className();
				
				// finding model record
				$criteria=new CDbCriteria;
				$criteria->compare($instance->getMetaData()->tableSchema->primaryKey, Yii::app()->controller->ActionParams['id']);
				$model = $instance->find($criteria);
				
				if ($model !== null)
				{
					// finding model relations
					$relations = $model->getMetaData()->relations;
					
					if ((array_key_exists("Projects", $relations)) || ($module->module_className == "Projects"))
					{
						if (Users::model()->verifyUserInProject($model->project_id, Yii::app()->user->id))
						{
							Yii::app()->user->setState('project_selected', $model->project_id);
							Yii::app()->user->setState('project_selectedName', Projects::model()->findByPk($model->project_id)->project_name);
						}
					}
				}
	        }
        }
    }
}