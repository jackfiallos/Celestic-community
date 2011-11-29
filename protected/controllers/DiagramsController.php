<?php

class DiagramsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
			array('deny',
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		if(Yii::app()->user->checkAccess('viewDiagrams'))
		{
			$this->render('view',array(
				'model'=>$this->loadModel(),
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
		if(Yii::app()->user->checkAccess('createDiagrams'))
		{
			$model=new Diagrams;

			if(isset($_POST['Diagrams']))
			{
				$model->attributes=$_POST['Diagrams'];
				if($model->save())
				{
					// Guardar log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'DiagramCreated',
						'log_resourceid' => $model->primaryKey,
						'log_type' => 'created',
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					$this->redirect(array('view','id'=>$model->diagram_id));
				}
			}

			$this->render('create',array(
				'model'=>$model,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		if(Yii::app()->user->checkAccess('updateDiagrams'))
		{
			$model=$this->loadModel();

			if(isset($_POST['Diagrams']))
			{
				$model->attributes=$_POST['Diagrams'];
				if($model->save())
				{
					// Guardar log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'DiagramUpdated',
						'log_resourceid' => $model->diagram_id,
						'log_type' => 'updated',
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					$this->redirect(array('view','id'=>$model->diagram_id));
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
	 */
	public function actionDelete()
	{
		if(Yii::app()->user->checkAccess('deleteDiagrams'))
		{
			if(Yii::app()->request->isPostRequest)
			{
				// we only allow deletion via POST request
				$this->loadModel()->delete();

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
		if(Yii::app()->user->checkAccess('indexDiagrams'))
		{
			$dataProvider=new CActiveDataProvider('Diagrams');
			$this->render('index',array(
				'dataProvider'=>$dataProvider,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		if(Yii::app()->user->checkAccess('adminDiagrams'))
		{
			$model=new Diagrams('search');
			$model->unsetAttributes();  // clear any default values
			if(isset($_GET['Diagrams']))
				$model->attributes=$_GET['Diagrams'];

			$this->render('admin',array(
				'model'=>$model,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Diagrams::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404, Yii::t('site', '404_Error'));
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='diagrams-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
