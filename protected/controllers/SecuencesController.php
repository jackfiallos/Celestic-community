<?php
/**
 * SecuencesController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for secuences 
 * @property string $layout
 *
 **/
class SecuencesController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
					'view',
					'create',
					'update'
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
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		// check if user has permissions to viewSecuences
		if(Yii::app()->user->checkAccess('viewSecuences'))
		{
			// output view page
			$this->render('view',array(
				// load model attributes information
				'model'=>$this->loadModel($id),
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		// check if user has permissions to createSecuences
		if(Yii::app()->user->checkAccess('createSecuences'))
		{
			// create secuences object
			$model=new Secuences;
			
			// find case parent
			$cases=Cases::model()->findByPk((int)$_GET['owner']);
			
			// output message
			$returnMessage = null;
			
			// verify Secuences form exist
			if(isset($_POST['Secuences']))
			{
				// set form elements to Secuences model attributes
				$model->attributes=$_POST['Secuences'];
				// validate and save			
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'SecuenceCreated',
						'log_resourceid' => $model->case_id,
						'log_type' => Logs::LOG_CREATED,
						'user_id' => Yii::app()->user->id,
						'module_id' => 'cases',
						'project_id' => $model->Cases->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					$returnMessage = Yii::t('secuences','successMessage');
					// create new secuence model for clear inputs form
					$model=new Secuences;
				}
				else
					$returnMessage = Yii::t('secuences','errorMessage');
			}

			// output create page
			$this->render('create',array(
				'model'=>$model,
				'types'=>SecuenceTypes::model()->findAll(),
				'cases'=>$cases,
				'returnMessage'=>$returnMessage,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		// check if user has permissions to updateSecuences
		if(Yii::app()->user->checkAccess('updateSecuences'))
		{
			// get Projects object from id parameter
			$model=$this->loadModel($id);

			// verify Secuences form was used
			if(isset($_POST['Secuences']))
			{
				// set form elements to secuences model attributes
				$model->attributes=$_POST['Secuences'];
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'SecuenceUpdated',
						'log_resourceid' => $model->case_id,
						'log_type' => Logs::LOG_UPDATED,
						'user_id' => Yii::app()->user->id,
						'module_id' => 'cases',
						'project_id' => $model->Cases->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					// redirect to prevent F5 keypress
					$this->redirect(array('cases/view','id'=>$model->case_id));
				}
			}

			// output update view
			$this->render('update',array(
				'model'=>$model,
				'types'=>SecuenceTypes::model()->findAll(),
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Secuences::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404, Yii::t('site', '404_Error'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='secuences-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
