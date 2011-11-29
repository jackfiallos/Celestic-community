<?php
/**
 * StatusController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for status
 * @property CActiveRecord $_model 
 *
 **/
class StatusController extends Controller
{
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			array(
				'application.filters.YXssFilter',
				'clean'   => '*',
				'tags'    => 'strict',
				'actions' => 'all'
			)
		);
	}
	
	/**
	 * Especify access control rights
	 * @return array access rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array(
					'getstatus',
				),
				'users'=>array('@'),
				'expression'=>'!$user->isGuest',
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Return all Status required by modules with concepts
	 * @return json object with status list
	 */
	public function actiongetStatus()
    {
		if(Yii::app()->request->isAjaxRequest)
		{			
			$Status = Status::model()->findAllRequired($_POST['required']);
			$output = array();
			foreach($Status as $clave){
				array_push($output, array(
					'iux'=>$clave->status_id,
					'nux'=>$clave->status_name,
				));
			}
			echo CJSON::encode($output);
			Yii::app()->end();
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
    }
}