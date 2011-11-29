<?php
/**
 * CompaniesController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license h	ttp://celestic.mx/license/
 * @description
 * Controller handler for Companies 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class CompaniesController extends Controller
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
					'deleteuser',
					'addusers',
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
	 * Remove relation between user and company
	 */
	public function actionDeleteUser()
	{
		// check if user has permissions to deleteuserCompanies
		if(Yii::app()->user->checkAccess('deleteuserCompanies'))
		{
			// only allow deletion via POST request
			if(Yii::app()->request->isPostRequest)
			{
				// verify if params exist
				if(isset($_GET['id']) && isset($_POST['cid']))
				{
					// verify if company_id exist in some projects of current user
					$projects = Yii::app()->user->Projects;
					foreach($projects as $project)
					{
						$valid = false;
						if ($project->company_id == (int)$_POST['cid'])
						{
							$valid = true;
							break 1;
						}
					}
					
					// ok.. project has relation with current company
					if($valid)
					{
						// find data in table CompaniesHasUsers before delete
						$model = CompaniesHasUsers::model()->findByPk($_GET['id']);
		
						// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
						if(!isset($_GET['ajax']))
							$this->redirect(array('index'));
						else
						{
							$result = array();
							// delete relation
							if($model->delete())
							{
								$result['hasreg'] = (isset($_POST['iclstyp']) && (int)$_POST['iclstyp'] == 0) ? (bool)CompaniesHasUsers::model()->HasUsersAvailablesToAdd(Yii::app()->user->Accountid,$model->company_id) : (bool)CompaniesHasUsers::model()->HasClientsAvailablesToAdd(Yii::app()->user->Accountid,$model->company_id);
								$result['deleted'] = true;
							}
							else
							{
								$result['hasreg'] = (isset($_POST['iclstyp']) && (int)$_POST['iclstyp'] == 0) ? (bool)CompaniesHasUsers::model()->HasUsersAvailablesToAdd(Yii::app()->user->Accountid,$model->company_id) : (bool)CompaniesHasUsers::model()->HasClientsAvailablesToAdd(Yii::app()->user->Accountid,$model->company_id);$result['hasreg'](isset($_POST['iclstyp']) && (int)$_POST['iclstyp'] == 0) ? (bool)CompaniesHasUsers::model()->HasUsersAvailablesToAdd(Yii::app()->user->Accountid,$model->company_id) : (bool)CompaniesHasUsers::model()->HasClientsAvailablesToAdd(Yii::app()->user->Accountid,$model->company_id);
								$result['deleted'] = false;
							}
							// output data
							echo CJSON::encode($result);
							Yii::app()->end();
						}
					}
					else
						throw new CHttpException(403, Yii::t('site', '403_Error'));
				}
				else
					throw new CHttpException(403, Yii::t('site', '403_Error'));
			}
			else
				throw new CHttpException(400, Yii::t('site', '400_Error'));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}
	
	/**
	 * Add relation between user and company
	 */
	public function actionAddUsers()
    {
        // check if user has permissions to addusersCompanies
    	if(Yii::app()->user->checkAccess('addusersCompanies'))
		{			
			// account_id used to find all users in my account
			$account_id = Users::model()->findByPk(Yii::app()->user->id)->account_id;
			
			// verify params request
			if((Yii::app()->request->isAjaxRequest) && (isset($_POST['action'])))
			{
				// users and clients filter
				if ($_POST['action'] == 'useradd')
					$availableUsers = Users::model()->findUsersByAccount($account_id, $_GET['owner']);
				elseif ($_POST['action'] == 'clientadd')
					$availableUsers = Users::model()->findClientsByAccount($account_id, $_GET['owner']);
				else
					throw new CHttpException(403, Yii::t('site', '403_Error'));
				
				$html = $this->renderPartial('_dropdownUsersList',array(
					'availableUsers'=>$availableUsers
				), true);
				
				echo $html;
				
				Yii::app()->end();
			}
			
			if(Yii::app()->request->isAjaxRequest)
			{
				// create object model for CompaniesHasUsers
				$model=new CompaniesHasUsers;
				
				if(Yii::app()->request->isPostRequest)
				{
					// set params to CompaniesHasUsers attributes
					$_POST['CompaniesHasUsers'] = array(
						'user_id'=>$_POST['icls'],
						'company_id'=>$_POST['owner'],
					);
					$model->attributes=$_POST['CompaniesHasUsers'];
					// perform validation
					$error = CActiveForm::validate($model, array('user_id','company_id'));
					if($error == '[]')
					{
						// output json array
						$result = array();
						// save new user - company relation
						if($model->save()) {
							$result['hasreg'] = (isset($_POST['iclstyp']) && (int)$_POST['iclstyp'] == 0) ? (bool)CompaniesHasUsers::model()->HasUsersAvailablesToAdd(Yii::app()->user->Accountid,$model->company_id) : (bool)CompaniesHasUsers::model()->HasClientsAvailablesToAdd(Yii::app()->user->Accountid,$model->company_id);
							$result['saved'] = true;
							
							if ((isset($_GET['action'])) && (!empty($_GET['action'])) && ($_GET['action'] == 'useradd'))
								$availableUsers = Users::model()->findUsersByAccount(Yii::app()->user->Accountid, $model->company_id);
							else
								$availableUsers = Users::model()->findClientsByAccount(Yii::app()->user->Accountid, $model->company_id);
							
							$html = $this->renderPartial('_dropdownUsersList',array(
								'availableUsers'=>$availableUsers
							), true);
							
							$result['html'] = $html;
							
							$userSelected = Users::model()->findByPk($model->user_id);
							
							// template to send mail
							$str = $this->renderPartial('//templates/companies/useradd',array(
								'userCreateInvitation' => Yii::app()->user->CompleteName,
								'userInvited' => $userSelected->CompleteName,
								'companyName' => $model->Company->company_name,
								'applicationName' => Yii::app()->name,
								'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
							),true);
							
							$subject = Yii::t('email','UserAddtoCompany');
							
							Yii::import('application.extensions.phpMailer.yiiPhpMailer');
							$mailer = new yiiPhpMailer;
							$mailer->pushMail($subject, $str, array('email'=>$userSelected->user_email, 'name'=>$userSelected->CompleteName), Emails::PRIORITY_NORMAL);
						}
						else 
						{
							$result['hasreg'] = (isset($_POST['iclstyp']) && (int)$_POST['iclstyp'] == 0) ? (bool)CompaniesHasUsers::model()->HasUsersAvailablesToAdd(Yii::app()->user->Accountid,$model->company_id) : (bool)CompaniesHasUsers::model()->HasClientsAvailablesToAdd(Yii::app()->user->Accountid,$model->company_id);
							$result['saved'] = false;
						}
						echo CJSON::encode($result);
						Yii::app()->end();
					}
					else {
						echo $error;
						Yii::app()->end();
					}
				}
				else
					throw new CHttpException(403, Yii::t('site', '403_Error'));
			}
			else
				throw new CHttpException(403, Yii::t('site', '403_Error'));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
    }

	/**
	 * Displays a particular model.
	 * @return update view
	 */
	public function actionView()
	{
		// check if user has permissions to viewCompanies
		if(Yii::app()->user->checkAccess('viewCompanies'))
		{
			if(Companies::model()->hasCompanyRelation(Yii::app()->user->id, (int)$_GET['id'])>0)
			{
				// DataProvider for Clients
				$dataProviderClients=new CActiveDataProvider('Clients',array(
					'criteria'=>array(
						'condition'=>'Companies.company_id = :company_id AND t.client_id IS NOT NULL',
						'params'=>array(
							':company_id'=>$_GET['id'],
						),
						'together'=>true,
						'order'=>'Users.user_name',
						'with'=>array('Users.Companies'),
					),
				));
				
				// DataProvider for Users
				$dataProviderUsers=new CActiveDataProvider('Users',array(
					'criteria'=>array(
						'condition'=>'Companies.company_id = :company_id AND Clients.client_id IS NULL',
						'params'=>array(
							':company_id'=>$_GET['id'],
						),
						'together'=>true,
						'order'=>'t.user_admin DESC',
						'with'=>array('Clients','Companies'),
					),
				));
				
				// DataProvider for Projects
				$dataProviderProjects=new CActiveDataProvider('Projects',array(
					'criteria'=>array(
						'condition'=>'Company.company_id = :company_id',
						'params'=>array(
							':company_id'=>$_GET['id'],
						),
						'together'=>true,
						'order'=>'t.project_name',
						'with'=>array('Company'),
					),
				));
				
				$this->render('view',array(
					'model'=>$this->loadModel(),
					'Clients'=>$dataProviderClients,
					'Projects'=>$dataProviderProjects,
					'Users'=>$dataProviderUsers,
					'UsersList' => Users::model()->findUsersByAccount(Yii::app()->user->Accountid, (int)$_GET['id']),
					'ClientsList' => Users::model()->findClientsByAccount(Yii::app()->user->Accountid, (int)$_GET['id']),
				));
			}
			else
				throw new CHttpException(403, Yii::t('site', '403_Error'));
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
		// check if user has permissions to createCompanies
		if(Yii::app()->user->checkAccess('createCompanies'))
		{
			// object models
			$model=new Companies;
			$address = new Address;

			// if Companies form exist and was sent
			if(isset($_POST['Companies']))
			{
				$model->attributes=$_POST['Companies'];
				$address->attributes = $_POST['Address'];
				
				// validate models
				$valid = $address->validate();
				$valid = $model->validate() && $valid;
				
				// validation results
				if($valid)
				{
					// save address
					$address->save(false);
					$model->address_id = $address->primaryKey;
					
					// save company model
					if($model->save(false)) 
					{
						// create object relation and save
						$companiesHasUsers = new CompaniesHasUsers;
						$companiesHasUsers->company_id = $model->company_id;
						$companiesHasUsers->user_id = Yii::app()->user->id;
						if($companiesHasUsers->save(false))
						{
							// save log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'CompanyCreated',
								'log_resourceid' => $model->primaryKey,
								'log_type' => Logs::LOG_CREATED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id,
							);
							Logs::model()->saveLog($attributes);
							
							// to prevent F5 keypress, redirect to view detail page
							$this->redirect(array('view','id'=>$model->company_id));
						}
					}
				}
			}

			$this->render('create',array(
				'model'=>$model,
				'address' => $address,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Updates a particular model.
	 * @return update view
	 */
	public function actionUpdate()
	{
		// check if user has permissions to updateCompanies
		if(Yii::app()->user->checkAccess('updateCompanies'))
		{
			// get Company object from $id parameter
			$model = $this->loadModel();
			
			// if company hasn't address create an Address object, else load
			if (!empty($model->address_id))
				$address = Address::model()->findByPk($model->address_id);
			else
				$address = new Address;
				
			// only users with administration rights or own user can update his profiles
			if (Yii::app()->user->IsAdministrator) 
			{
				// if Company form exist
				if(isset($_POST['Companies']))
				{
					// set form elements to Companies model attributes
					$model->attributes=$_POST['Companies'];
					// set form elements to Address model attributes
					$address->attributes = $_POST['Address'];
					
					// validate both models
					$valid = $address->validate();
					$valid = $model->validate() && $valid;
					
					if($valid)
					{
						// save address
						$address->save(false);
						$model->address_id = $address->primaryKey;
						
						// save company
						if($model->save())
						{
							// save log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'CompanyUpdated',
								'log_resourceid' => $model->company_id,
								'log_type' => Logs::LOG_UPDATED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id,
							);
							Logs::model()->saveLog($attributes);
							
							// to prevent F5 keypress, redirect to view page
							$this->redirect(array('view','id'=>$model->company_id));
						}
					}
				}
			}

			$this->render('update',array(
				'model'=>$model,
				'address' => $address,
			));
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
		// check if user has permissions to indexCompanies
		if(Yii::app()->user->checkAccess('indexCompanies'))
		{
			// Intente filtrar los resultados desde el CActiveDataProvider pero no funciono
			// por alguna razon se hace bien el count() pero no la consulta completa
			// por lo que tuve que separar las consultas en 2
			$Companies = Companies::model()->findCompanyList(Yii::app()->user->id);
			$companies_id = array();
			foreach ($Companies as $companies){
				array_push($companies_id, $companies->company_id);
			}

			// Si no hay una lista de companias enlazadas al usuario es porque tiene que crear al menos 1
			if (count($companies_id) > 0){
				$dataProvider=new CActiveDataProvider('Companies', array(
					'criteria'=>array(
						'condition'=>'t.company_id IN ('.implode(',',$companies_id).')',
						'order'=>'t.company_name'
					),
				));
				
				$this->render('index',array(
					'dataProvider'=>$dataProvider,
				));
			}
			else {
				$this->actionCreate();
			}
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @return CActiveRecord Companies
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Companies::model()->findbyPk($_GET['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='companies-form')
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
				$response = (Companies::model()->countCompaniesByAccount((int)$_GET['id'], Yii::app()->user->Accountid) > 0) ? true : false;
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