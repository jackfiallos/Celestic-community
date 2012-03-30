<?php
/**
 * UsersController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for tasks
 * @property string $layout
 * @property CActiveRecord $_model 
 *
 **/
class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to 'column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='column2';

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
					'notifications',
					'view',
					'create',
					'update',
					'delete',
					'index',
					'admin'
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
	 */
	public function actionNotifications()
	{
		$ModulesDataProdiver = new CActiveDataProvider('Modules',array(
			'criteria'=>array(
				'condition'=>'t.module_useNotifications != 0'
			),
			'pagination'=>array(
				'pageSize'=>15,
			)
		));
		
		$projectsList = Projects::model()->findAll(array(
			'condition'=>'Cusers.user_id = :user_id',
			'params'=>array(
				':user_id'=>Yii::app()->user->id,
			),
			'together'=>true,
			'order'=>'t.project_name ASC',
			'with'=>array('Company.Cusers'),
		));
		
		$this->render('../notifications/notifications',array(
			'dataProvider'=>$ModulesDataProdiver,
			'projectsList'=>$projectsList,
		));
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		if(Yii::app()->user->checkAccess('viewUsers'))
		{
			$model = $this->loadModel();
			
			$projectSelected = (Yii::app()->user->getState('project_selected')!=null) ? Yii::app()->user->getState('project_selected') : 0;
			
			$conditionProject = " AND TRUE AND t.project_id = ".$projectSelected;
			
			if (Yii::app()->user->id == $model->user_id)
			{
				$projectDataProdiver = new CActiveDataProvider('Projects',array(
					'criteria'=>array(
						'condition'=>'Cusers.user_id = :user_id',
						'params'=>array(
							':user_id'=>$model->user_id
						),
						'together'=>true,
						'order'=>'t.project_name ASC',
						'with'=>array('Company.Cusers'),
					),
				));
				
				$CompaniesDataProdiver = new CActiveDataProvider('Companies',array(
					'criteria'=>array(
						'condition'=>'Cusers.user_id = :user_id',
						'params'=>array(
							':user_id'=>$model->user_id
						),
						'together'=>true,
						'order'=>'t.company_name ASC',
						'with'=>array('Cusers'),
					),
				));
				
				$conditionProject = null;
			}
			
			$TasksDataProdiver = new CActiveDataProvider('Tasks',array(
				'criteria'=>array(
					'condition'=>'Users.user_id = :user_id AND t.status_id NOT IN ('.implode(',',array(Status::STATUS_CANCELLED, Status::STATUS_CLOSED)).')'.$conditionProject,
					'params'=>array(
						':user_id'=>$model->user_id,
					),
					'together'=>true,
					'order'=>'t.task_startDate ASC',
					'with'=>array('Users'),
				),
			));
			
			$this->render('view',array(
				'model'=>$model,
				'Projects'=>(Yii::app()->user->id == $model->user_id) ? $projectDataProdiver : null,
				'Companies'=>(Yii::app()->user->id == $model->user_id) ? $CompaniesDataProdiver : null,
				'Tasks'=>$TasksDataProdiver,
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
		if(Yii::app()->user->checkAccess('createUsers'))
		{
			$model=new Users;
			$address = new Address;

			if(isset($_POST['Users']) && isset($_POST['Address']))
			{
				$model->attributes=$_POST['Users'];
				$address->attributes = $_POST['Address'];
				
				$model->account_id = Users::model()->findByPk(Yii::app()->user->id)->account_id;
				
				$valid = $address->validate();
				$valid = $model->validate() && $valid;
				
				if($valid)
				{
					$address->save(false);
					$model->address_id = $address->primaryKey;
					$passBeforeMD5 = $model->user_password;
					$model->user_password = md5($model->user_password);
					
					if($model->save(false))
					{
						// Guardar log
						$attributes = array(
							'log_date' => date("Y-m-d G:i:s"),
							'log_activity' => 'UserCreated',
							'log_resourceid' => $model->primaryKey,
							'log_type' => 'created',
							'user_id' => Yii::app()->user->id,
							'module_id' => Yii::app()->controller->id
						);
						Logs::model()->saveLog($attributes);
						
						$str = $this->renderPartial('//templates/users/invitation',array(
							'userCreateInvitation' => Yii::app()->user->CompleteName,
							'user_email' => $model->user_email,
							'user_password' => $passBeforeMD5,
							'userInvited' => $model->CompleteName,
							'applicationName' => Yii::app()->name,
							'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
						),true);
						
						$subject = Yii::t('email','UserInvitation');
						
						Yii::import('application.extensions.phpMailer.yiiPhpMailer');
						$mailer = new yiiPhpMailer;
						//$mailer->Ready($subject, $str, array('email'=>$model->user_email, 'name'=>$model->CompleteName));
						$mailer->pushMail($subject, $str, array('email'=>$model->user_email, 'name'=>$model->CompleteName), Emails::PRIORITY_NORMAL);
						
						$this->redirect(array('view','id'=>$model->user_id));
					}
				}
			}

			$this->render('create',array(
				'model'=>$model,
				'allowEdit'=>true,
				'userManager'=>true,
				'address' => $address,
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
		if(Yii::app()->user->checkAccess('updateUsers'))
		{
			$allowEdit = false;
			$model=$this->loadModel();
			$tmppw = $model->user_password;
			if (!empty($model->address_id))
				$address = Address::model()->findByPk($model->address_id);
			else
				$address = new Address;
			
			$userManager = Yii::app()->user->IsManager;
			if (($model->user_id == Yii::app()->user->id) || ($userManager) || Yii::app()->user->IsAdministrator) {
				$allowEdit = true;
			}

			if(isset($_POST['Users']) && isset($_POST['Address']))
			{			
				$model->attributes=$_POST['Users'];
				$address->attributes = $_POST['Address'];
				
				if (isset($_POST['Users']['user_password']))
					$model->user_password = md5($model->user_password);
					
				$valid = $address->validate();
				$valid = $model->validate() && $valid;
				
				if($valid)
				{
					$address->save(false);
					$model->address_id = $address->primaryKey;
					
					if($model->save(false)) 
					{
						// Guardar log
						$attributes = array(
							'log_date' => date("Y-m-d G:i:s"),
							'log_activity' => 'UserUpdated',
							'log_resourceid' => $model->user_id,
							'log_type' => 'updated',
							'user_id' => Yii::app()->user->id,
							'module_id' => Yii::app()->controller->id
						);
						Logs::model()->saveLog($attributes);
						
						$this->redirect(array('view','id'=>$model->user_id));
					}
				}
			}

			$this->render('update',array(
				'model'=>$model,
				'allowEdit'=>$allowEdit,
				'userManager'=>$userManager,
				'address' => $address,
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
		if(Yii::app()->user->checkAccess('deleteUsers'))
		{
			if(Yii::app()->request->isPostRequest)
			{
				// we only allow deletion via POST request
				$this->loadModel()->delete();

				// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
				if(!isset($_GET['ajax']))
					$this->redirect(array('index'));
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
		if(Yii::app()->user->checkAccess('indexUsers'))
		{
			$Users = Users::model()->findByPk(Yii::app()->user->id);
			
			$dataProvider=new CActiveDataProvider('Users',array(
				'criteria'=>array(
					'condition'=>'Accounts.account_id = :account_id AND Clients.client_id IS NULL',
					'params'=>array(
						':account_id'=>$Users->account_id,
					),
					'order'=>'t.user_name',
					'with'=>array('Accounts','Clients'),
					'together'=>true,
				),
			));
			
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
		if(Yii::app()->user->checkAccess('adminUsers'))
		{
			$model=new Users('search');
			if(isset($_GET['Users']))
				$model->attributes=$_GET['Users'];

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
			{
				$this->_model=Users::model()->findbyPk($_GET['id']);/*, array(
					'select'=>'t.user_id, t.user_name, t.user_lastname, t.user_email, t.user_phone, t.user_admin, t.user_active, t.user_accountManager, t.address_id, t.account_id',
				));*/
			}
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function beforeAction($action)
	{
		switch ($action->id)
		{
			case "view":
				$response = (Users::model()->countUsersByAccount((int)$_GET['id'], Yii::app()->user->Accountid) > 0) ? true : false;
				break;
			case "update":
				$response = (Users::model()->countUsersByAccount((int)$_GET['id'], Yii::app()->user->Accountid) > 0) ? true : false;
				break;
			case "delete":
				$response = (Users::model()->countUsersByAccount((int)$_GET['id'], Yii::app()->user->Accountid) > 0) ? true : false;
				break;
			default:
				$response = true;
				break;
		}
		
		if(!$response)
			throw new CHttpException(403, Yii::t('site', '403_Error'));
		else
			return $response;
	}
}