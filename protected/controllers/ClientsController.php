<?php
/**
 * ClientsController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for Clients 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class ClientsController extends Controller
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
			array('allow', 
				'actions'=>array(
					'view',
					'create',
					'update',
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
	 * @return view detail information
	 */
	public function actionView()
	{
		// check if user has permissions to viewClients
		if(Yii::app()->user->checkAccess('viewClients'))
		{
			$client = $this->loadModel();
			
			$conditionProject = " AND TRUE AND t.project_id = ".((Yii::app()->user->getState('project_selected')!=null) ? Yii::app()->user->getState('project_selected') : 0);
			
			if (Yii::app()->user->id == $client->user_id)
			{
				// DataProvider for Projects
				$projectDataProdiver = new CActiveDataProvider('Projects',array(
					'criteria'=>array(
						'condition'=>'Clients.client_id = :client_id',
						'params'=>array(
							':client_id'=>$client->client_id
						),
						'together'=>true,
						'order'=>'t.project_name ASC',
						'with'=>array('Company.Cusers.Clients'),
					),
				));
				
				// DataProvider for Companies
				$CompaniesDataProdiver = new CActiveDataProvider('Companies',array(
					'criteria'=>array(
						'condition'=>'Clients.client_id = :client_id',
						'params'=>array(
							':client_id'=>$client->client_id
						),
						'together'=>true,
						'order'=>'t.company_name ASC',
						'with'=>array('Cusers.Clients'),
					),
				));
				
				$conditionProject = null;
			}
			
			// DataProvider for Tasks
			$TasksDataProdiver = new CActiveDataProvider('Tasks',array(
				'criteria'=>array(
					//'condition'=>'Users.user_id = :user_id'.$conditionProject,
					'condition'=>'Users.user_id = :user_id AND t.status_id NOT IN ('.implode(',',array(Status::STATUS_CANCELLED, Status::STATUS_CLOSED)).')'.$conditionProject,
					'params'=>array(
						':user_id'=>$client->user_id
					),
					'together'=>true,
					'order'=>'t.task_startDate ASC',
					'with'=>array('Users'),
				),
			));
			
			$this->render('view',array(
				'model_client'=>$client,
				'model_user'=>Users::model()->findByPk($client->user_id),
				'Projects'=>(Yii::app()->user->id == $client->user_id) ? $projectDataProdiver : null,
				'Companies'=>(Yii::app()->user->id == $client->user_id) ? $CompaniesDataProdiver : null,
				'Tasks'=>$TasksDataProdiver,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Creates a new model.
	 * @return create view
	 */
	public function actionCreate()
	{
		// check if user has permissions to createClients
		if(Yii::app()->user->checkAccess('createClients'))
		{
			// create Users object model
			$modelUsers = new Users;
			// create Address object model
			$address = new Address;

			// if Users and Address form exist and was sent
			if(isset($_POST['Users']) && isset($_POST['Address']))
			{
				// set form elements to Users model attributes
				$modelUsers->attributes=$_POST['Users'];
				// set form elements to Address model attributes
				$address->attributes = $_POST['Address'];
				
				$modelUsers->user_admin = 0;
				$modelUsers->account_id = Yii::app()->user->Accountid;
				
				// validate both models
				$valid = $address->validate();
				$valid = $modelUsers->validate() && $valid;
				
				if($valid)
				{
					// save address
					$address->save(false);
					$modelUsers->address_id = $address->primaryKey;
					
					// temporary variable with user password
					$passBeforeMD5 = $modelUsers->user_password;
					// hashed user password
					$modelUsers->user_password = md5($modelUsers->user_password);
					
					// save user
					if($modelUsers->save(false))
					{
						// create clients object
						$model = new Clients;
						$model->user_id = $modelUsers->user_id;
						
						// validate and save
						if ($model->save())
						{
							// save log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'ClientCreated',
								'log_resourceid' => $model->primaryKey,
								'log_type' => Logs::LOG_CREATED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id
							);
							Logs::model()->saveLog($attributes);
							
							// prepare to send email template to new user
							$str = $this->renderPartial('//templates/users/invitation',array(
								'userCreateInvitation' => Yii::app()->user->CompleteName,
								'user_email' => $modelUsers->user_email,
								'user_password' => $passBeforeMD5,
								'userInvited' => $modelUsers->CompleteName,
								'applicationName' => Yii::app()->name,
								'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
							),true);
							
							$subject = Yii::t('email','UserInvitation');
							
							Yii::import('application.extensions.phpMailer.yiiPhpMailer');
							$mailer = new yiiPhpMailer;
							$mailer->pushMail($subject, $str, array('email'=>$modelUsers->user_email, 'name'=>$modelUsers->CompleteName), Emails::PRIORITY_NORMAL);
							
							// to prevent F5 keypress, redirect to view detail page
							$this->redirect(array('view','id'=>$model->client_id));
						}
					}
				}
			}

			$this->render('create',array(
				'model'=>$modelUsers,
				'address' => $address,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Updates a particular model.
	 * @param integer $_GET['id'] the ID of the model to be updated
	 * @return update view
	 */
	public function actionUpdate()
	{
		// check if user has permissions to updateClients
		if(Yii::app()->user->checkAccess('updateClients'))
		{
			// get Clients object from $id parameter
			$model=$this->loadModel();
			
			// find client user data
			$modelUsers = Users::model()->together()->findByPk($model->user_id);
			
			// if client hasn't address create an Address object, else load
			if (!empty($model->address_id))
				$address = Address::model()->findByPk($modelUsers->address_id);
			else
				$address = new Address;
			
			// only users with administration rights or own user can update his profiles
			if (($modelUsers->user_id == Yii::app()->user->id) || (Yii::app()->user->IsAdministrator)) 
			{
				// if Users and Address form exist
				if(isset($_POST['Users']) && isset($_POST['Address']))
				{
					// set form elements to Users model attributes
					$modelUsers->attributes=$_POST['Users'];
					// set form elements to Address model attributes
					$address->attributes = $_POST['Address'];
					
					// current user password
					$tmppw = $modelUsers->user_password;
					
					// if current password is different to new password
					if (($tmppw != $modelUsers->user_password) && (!empty($modelUsers->user_password)))
						$modelUsers->user_password = md5($modelUsers->user_password);
					
					// validate both models
					$valid = $address->validate();
					$valid = $modelUsers->validate() && $valid;
					
					if($valid)
					{
						// save address
						$address->save(false);
						$modelUsers->address_id = $address->primaryKey;
						
						// save user
						if($modelUsers->save(false))
						{
							// save log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'ClientUpdated',
								'log_resourceid' => $model->client_id,
								'log_type' => Logs::LOG_UPDATED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id
							);
							Logs::model()->saveLog($attributes);
						
							// to prevent F5 keypress, redirect to view page
							$this->redirect(array('view','id'=>$model->client_id));
						}
					}
				}

				$this->render('update',array(
					'model' => $modelUsers,
					'address' => $address,
				));
			}
			else
				throw new CHttpException(403, Yii::t('site', '403_Error'));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Lists all models.
	 * @return index view
	 */
	public function actionIndex()
	{
		// check if user has permissions to indexClients
		if(Yii::app()->user->checkAccess('indexClients'))
		{			
			// DataProvider Clients
			$dataProvider=new CActiveDataProvider('Clients',array(
				'criteria'=>array(
					'condition'=>'Accounts.account_id = :account_id',
					'params'=>array(
						':account_id'=>Yii::app()->user->Accountid
					),
					'order'=>'Users.user_name',
					'with'=>array('Users.Accounts'),
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
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @return CActiveRecord Clients
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Clients::model()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404, Yii::t('site', '404_Error'));
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 * @return CModel validation to form elements
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='clients-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * This method is invoked right before an action is to be executed (after all possible filters.)
	 * @param CAction $action the action to be executed.
	 * @return boolean whether the action should be executed
	 */
	public function beforeAction($action)
	{
		$response = false;
		//if (Yii::app()->user->getState('project_selected') != null)
		//{
			if (in_array($action->id, array('view','update')))
				$response = (Clients::model()->countClientsByAccount((int)$_GET['id'], Yii::app()->user->Accountid) > 0) ? true : false;
			else
				$response = true;
		//}
		//else
		//	return false;
		
		if(!$response)
			throw new CHttpException(403, Yii::t('site', '403_Error'));
		else
			return $response;
	}
}
