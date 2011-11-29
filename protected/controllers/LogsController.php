<?php
/**
 * LogsController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for logs 
 *
 **/
class LogsController extends Controller
{
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
					'create'
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
	 * Creates a new model.
	 * @return create view
	 */
	public function actionCreate()
	{
		// create Logs object model
		$model=new Logs;

		// if Logs form exist
		if(isset($_POST['Logs']))
		{
			// set form elements to Logs model attributes
			$model->attributes=$_POST['Logs'];
			
			// find module_name before save
			$module = Modules::model()->find(array(
				'condition'=>'t.module_name = :module_name',
				'params'=>array(
					':module_name'=>$model->module_id,
				),
			));
			
			// set finded module_id to module->module_id
			$model->module_id = $module->module_id;
			
			// save without validate
			$model->save(false);
		}
	}
}