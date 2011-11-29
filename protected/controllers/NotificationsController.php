<?php

class NotificationsController extends Controller
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
					'save',
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
	
	public function actionSave()
	{		
		if(isset($_POST['Notifications']))
		{
			$arrayOfQueryValues = array();
			foreach($_POST['Notifications'] as $key => $value)
			{
				foreach($_POST['Notifications'][$key] as $inkey => $invalue)
					array_push($arrayOfQueryValues,"(".Yii::app()->user->id.",".$_POST['Project'].",".$key.",".$inkey.")");
			}
			
			if (isset(Notifications::model()->findByPk(Yii::app()->user->id)->user_id))
				Notifications::model()->deleteAll('user_id = :user_id', array('user_id'=>Yii::app()->user->id));
			
			$connection=Yii::app()->db;
			$query = "INSERT INTO tb_notifications (user_id, project_id, module_id, notification_action) VALUES ".implode(",",$arrayOfQueryValues);
			$command=$connection->createCommand($query);
			$command->execute();
		}
		
		Yii::app()->end();
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Notifications;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Notifications']))
		{
			$model->attributes=$_POST['Notifications'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->notification_id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Notifications']))
		{
			$model->attributes=$_POST['Notifications'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->notification_id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Notifications::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404, Yii::t('site', '404_Error'));
		return $model;
	}
}
