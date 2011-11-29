<?php
/**
 * ValidationsController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for tasks
 * @property string $layout 
 *
 **/
class ValidationsController extends Controller
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
					'update',
					'delete',
					'index',
					'admin',
				),
				'users'=>array('@'),
				'expression'=>'!$user->isGuest',
			),
			array('allow', 
				'actions'=>array(
					'view',
					'create',
					'update',
					'delete',
					'index'
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
		if(Yii::app()->user->checkAccess('viewValidations'))
		{
			$this->render('view',array(
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
		if(Yii::app()->user->checkAccess('createValidations'))
		{
			$model=new Validations;
			$cases=Cases::model()->findByPk($_GET['owner']);
			$returnMessage = null;
			
			if(isset($_POST['Validations']))
			{
				$model->attributes=$_POST['Validations'];
				if($model->save()) 
				{
					// Guardar log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'ValidationCreated',
						'log_resourceid' => $model->case_id,
						'log_type' => Logs::LOG_CREATED,
						'user_id' => Yii::app()->user->id,
						'module_id' => 'cases',
						'project_id' => $model->Cases->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					//$this->redirect(Yii::app()->createUrl('secuences/create', array('owner'=>$cases->case_id)));
					$returnMessage = "Validation was succefully created.";
					$model=new Validations;
				}
			}

			$this->render('create',array(
				'model'=>$model,
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
		if(Yii::app()->user->checkAccess('updateValidations'))
		{
			$model=$this->loadModel($id);

			if(isset($_POST['Validations']))
			{
				$model->attributes=$_POST['Validations'];
				if($model->save())
				{
					// Guardar log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'ValidationUpdated',
						'log_resourceid' => $model->case_id,
						'log_type' => 'updated',
						'user_id' => Yii::app()->user->id,
						'module_id' => 'cases',
						'project_id' => $model->Cases->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					$this->redirect(array('cases/view','id'=>$model->case_id));
				}
			}

			$this->render('update',array(
				'model'=>$model,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->user->checkAccess('deleteValidations'))
		{
			if(Yii::app()->request->isPostRequest)
			{
				// we only allow deletion via POST request
				$this->loadModel($id)->delete();

				// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
				if(!isset($_GET['ajax']))
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
			}
			else
				throw new CHttpException(400, Yii::t('site', '400_Error'));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		if(Yii::app()->user->checkAccess('indexValidations'))
		{
			$dataProvider=new CActiveDataProvider('Validations');
			$this->render('index',array(
				'dataProvider'=>$dataProvider,
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
		$model=Validations::model()->findByPk((int)$id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='validations-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
