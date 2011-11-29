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
class ConfigurationController extends Controller
{
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	
	/**
	 * @var String Temporal uploaded filename
	 */
	private $tmpFileName='';
	
	const FOLDERIMAGES='resources/';
	
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
				'actions' => 'create'
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
					'account',
					'accountupdate',
					'providersearch',
					'admin',
					'localization',
					'userspermissions',
					'rolesmanage',
					'rolesassignments',
					'gettasks',
					'rolesoperations',
					'getroles',
					'showroledetails',
					'rolecreate',
					'roledelete',
					'projects',
					'changestatus',
					'createproject'
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
	* Sets the message that is displayed to the user
	* @param String $mess  The message to show
	*/
	private function _setMessage($mess) {
		Yii::app()->user->setState("message", $mess);
	}

	/**
	*
	* @return String Gets the message that will be displayed to the user
	*/
	private function _getMessage() {
		return Yii::app()->user->getState("message");
	}
	
	/**
	 * Show information about account
	 * @return account view
	 */
	public function actionAccount()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			$this->layout = 'column2';
			$this->render('accounts/view',array(
				'model'=>Accounts::model()->with('Address')->findByPk(Yii::app()->user->Accountid),
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
	public function actionAccountUpdate()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			// with user.account_id load account data
			$model = Accounts::model()->findByPk(Yii::app()->user->Accountid);
			
			// if account hasn't address create an Address object, else load
			if (!empty($model->address_id))
				$address = Address::model()->findByPk($model->address_id);
			else
				$address = new Address;

			// if Accounts and Address form exist
			if(isset($_POST['Accounts'], $_POST['Address']))
			{
				// set form elements to model attributes
				$model->attributes = $_POST['Accounts'];
				$address->attributes = $_POST['Address'];
				
				// validate both models
				$valid = $address->validate();
				$valid = $model->validate() && $valid;
				
				if($valid)
				{
					// save image path, if uploaded
					if ((isset($_FILES['Accounts']['name']['image'])) && (!empty($_FILES['Accounts']['name']['image'])))
					{
						// create an instance of file uploaded
						$model->image = CUploadedFile::getInstance($model,'image');
						if (!$model->image->getError())
						{
							$this->tmpFileName = trim(date('dmYHis-z-').microtime());
							$extension = $model->image->getExtensionName();
							$extensionAllowed = array('jpg','jpeg','png');
							
							// verify only allowed extensions
							if (in_array($extension,$extensionAllowed))
							{
								// save image from tmp folder to defined folder, after set account_logo path
								if($model->image->saveAs(ConfigurationController::FOLDERIMAGES.$this->tmpFileName.'.'.$extension))
									$model->account_logo = ConfigurationController::FOLDERIMAGES.$this->tmpFileName.'.'.$extension;
							}
						}
					}
					
					// save address
					$address->save(false);
					$model->address_id = $address->primaryKey;
					
					// save model
					$model->save(false);
					
					// to prevent F5 keypress, redirect to account page
					$this->redirect(Yii::app()->createUrl('configuration/account'));
				}
			}
			
			$this->layout = 'column2';
			$this->render('accounts/update',array(
				'model' => $model,
				'address' => $address,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}
	
	/**
	 * perform search to find cityname using $_GET['term'] param
	 */
	public function actionProviderSearch()
    {
	   	// verify _GET param exist
    	if((isset($_GET['term'])) && (!empty($_GET['term'])))
       	{
			// term param 
       		$name = $_GET['term'];

       		// find all cities with criteria param
			$criteria = new CDbCriteria;
			$criteria->condition = "t.city_name LIKE :city_name";
			$criteria->params = array(
				":city_name" => '%'.CHtml::encode($_GET['term']).'%',
			);
			$criteria->limit = 5;
			$Cities = City::model()->findAll($criteria);
			
			// build cities with array
			$result = '';
			foreach($Cities as $city)
			{
				$result[] = array(
					'id'=>$city->city_id,
					'label'=>$city->city_name,
				);   
			}
			
			// output json result
			echo CJSON::encode($result);
			Yii::app()->end();
       	}
    }

	/**
	 * This is the default 'index' action that is invoked
	 */
	public function actionAdmin()
	{			
		// check if user has permissions to permissionsConfiguration and user is account administrator
		if((Yii::app()->user->checkAccess('permissionsConfiguration')) && Yii::app()->user->IsAdministrator)
		{
			// query to get mysql version
			$connection=Yii::app()->db;
			$query = "SELECT VERSION();";
			$mysqlver = $connection->createCommand($query)->queryScalar();
			
			// apache version
			// $apachever = split("[/ ]",$_SERVER['SERVER_SOFTWARE']); // deprecated
			$apachever = preg_split('/ /',$_SERVER['SERVER_SOFTWARE'], -1, PREG_SPLIT_OFFSET_CAPTURE);
			
			$this->layout = 'column1';
			$this->render('admin', array(
				'mysqlVersion' => $mysqlver,
				'phpVersion' => phpversion(),
				'apacheVersion' => $apachever[0][0],
			));
		}
		elseif(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			$this->layout = 'column1';
			$this->redirect(Yii::app()->createUrl('users/view',array('id'=>Yii::app()->user->id)));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}
	
	/**
	 * Set configuration for localization
	 */
	public function actionLocalization()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			// load actual timezone for account -> Default if not set is America/Mexico_City
			$model = TimezoneForm::UpdateAccount();
			
			// if TimezoneForm form exist
			if(isset($_POST['TimezoneForm']))
			{
				// set form elements to TimezoneForm model attributes
				$model->attributes = $_POST['TimezoneForm'];
				
				// timezone is needed
				if (!empty($model->timezone))
				{
					// update timezone_id from user account
					$account = Accounts::model()->findByPk(Yii::app()->user->getAccountid());
					$account->timezone_id = $model->timezone;
					
					// save
					if ($account->save())
						Yii::app()->user->setFlash('updatedLocalizationSuccess', Yii::t('localization','UpdatedMessage'));
				}
				else
				{
					// show custom error message
					$model->addError('timezone', Yii::t('localization','timezoneRequired'));
				}
			}
			
			// set layout page and render content
			$this->layout = 'column2';		
			$this->render('assigments/localization',array(
				'model'=>$model,
				'timezones'=>Timezones::model()->findAll()
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}
	
	/**
	 * Set configuration for user permissions
	 */
	public function actionusersPermissions()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			// create criteria object to filter permissions
			$criteria = new CDbCriteria;
			// type = 2 meaning task level permissions
			$criteria->condition = 't.type = 2 AND t.account_id = :account_id';
			$criteria->params = array(
				':account_id'=>Users::model()->findByPk(Yii::app()->user->id)->account_id,
			);
			
			// only if ajax request exist
			if((isset($_POST['ManageUsers'])) && (Yii::app()->request->isPostRequest))
			{
				// find all roles selected by form
				$criteriaUserRoles = new CDbCriteria;
				$criteriaUserRoles->condition = 't.userid = :userid';
				$criteriaUserRoles->params = array(
					':userid'=>$_POST['ManageUsers']['lstUsers'],
				);
				
				// find all user roles
				$userRoles = array();
				$applied = Authassignments::model()->findAll($criteriaUserRoles);
				foreach($applied as $key) {
					array_push($userRoles, $key->itemname);
				}
				// create object roles manager
				$auth=Yii::app()->authManager;
				
				// if role was deleted from user list roles, remove from DB too
				if (isset($userRoles))
				{
					foreach($userRoles as $key => $value)
					{
						if (isset($_POST['ManageUsers']['asmSelect2'])) {
							if (!in_array($value, $_POST['ManageUsers']['asmSelect2']))
								$auth->revoke($value,$_POST['ManageUsers']['lstUsers']);	
						}
						else
							$auth->revoke($value,$_POST['ManageUsers']['lstUsers']);
					}
				}
				
				// asmSelect2 meaning roles change
				if (isset($_POST['ManageUsers']['asmSelect2']))
				{				
					// if permissiona hasnt set previosly, add whether
					foreach($_POST['ManageUsers']['asmSelect2'] as $key => $value)
					{					
						if (!$auth->isAssigned($value,$_POST['ManageUsers']['lstUsers']))
							$auth->assign($value,$_POST['ManageUsers']['lstUsers']);
					}
				}
				
				echo Yii::t('configuration','permissionsSaved');
				Yii::app()->end();
			}
		
			// set layout and render content
			$this->layout = 'column2';
			$this->render('manage/usersPermissions',array(
				'listData'=>Authitems::model()->findAll($criteria),
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}
	
	/**
	 * Show account roles list
	 */
	public function actionrolesManage()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			$this->layout = 'column2';
			// DataProvider for Authitems filter by role task type and account_id
			$roles = new CActiveDataProvider('Authitems',array(
				'criteria'=>array(
					'select' => 't.id, t.name, t.description, t.account_id',
					'condition'=>'t.type = 2 AND t.account_id = :account_id',
					'params'=>array(
						':account_id'=>Users::model()->findByPk(Yii::app()->user->id)->account_id,
					),
					'order'=>'t.name',
				),
			));
			
			// render page content
			$this->render('manage/rolesManage', array(
				'roles' => $roles,
	        ), false, true);
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}
	
	/**
	 * Allow to design custom roles based on permissions task
	 */
	public function actionrolesAssignments()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			$this->layout = 'column2';
			
			$this->render('manage/rolesAssignments', array(
				'listData'=>Authitems::model()->findAll(array(
					'condition' => 't.type = 2 AND t.account_id = :account_id',
					'params' => array(
						':account_id'=>Users::model()->findByPk(Yii::app()->user->id)->account_id,
					),
				)),
				'tasks'=>Authitems::model()->findAll(array(
					'condition' => 't.type = 1',
				)),
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}
	
	/**
	 * Response with a list of availables permissions
	 */
	public function actiongetTasks()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			// verify is assigments module exist
			if(isset($_POST['Assigments']))
			{
				// criteria to find previously selected authitems
				$criteriaSelected = new CDbCriteria;
				// criteria to find availables authitems
				$criteria = new CDbCriteria;
				$arrParent = array();
				$arrChild = array();
				
				// allowed items
				if (!empty($_POST['Assigments']['roles']))
				{
					// find all authitems with role param selected
					$authitem = Authitems::model()->findByPk($_POST['Assigments']['roles']);
					
					// find all authitemschilds for authitem and role selected
					$criteriaChild = new CDbCriteria;
					$criteriaChild->condition = "t.parent LIKE ('".$_POST['Assigments']['module']."')";
					$seleccionadosChild = Authitemchilds::model()->findAll($criteriaChild);
					foreach($seleccionadosChild as $child)
						array_push($arrChild, $child->child);
					
					$criteriaParent = new CDbCriteria;
					$criteriaParent->condition = 't.parent LIKE ("'.$authitem->name.'") AND t.child IN ("'.implode('","', $arrChild).'")';
					$seleccionadosParent = Authitemchilds::model()->findAll($criteriaParent);
					foreach($seleccionadosParent as $parent)
						array_push($arrParent, $parent->child);
					
					$criteriaSelected->condition = 't.type = 0 AND t.name IN ("'.implode('","', $arrParent).'")';
					
					// create array list with non selected authitemschilds
					$modules = array();
					foreach($seleccionadosChild as $key) {
						if (!in_array($key->child,$arrParent))
							array_push($modules, $key->child);
					}
					
					$criteria->condition = 't.type = 0 AND t.name IN ("'.implode('","', $modules).'")';
					
				}
				else {
					$criteriaSelected->condition = 't.id = -1';
					$criteria->condition = 't.id = -1';
				}
				
				// select layout and render the page
				$this->layout = 'simple';
				$this->render('assigments/operations', array(
					'authitems'=>Authitems::model()->findAll($criteria),
					'selected'=>Authitems::model()->findAll($criteriaSelected),
				));
			}
			else
				throw new CHttpException(400, Yii::t('site', '400_Error'));
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	/**
	 * Save changes between roles and modules
	 */
	public function actionrolesOperations()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			// verify itemsleft from list and roles exits
			if ((isset($_POST['Assigments']['itemsleft'])) && (!empty($_POST['Assigments']['roles'])))
			{
				// find role name
				$itemname = Authitems::model()->findByPk($_POST['Assigments']['roles'])->name;
				
				// find all authitems using list itemsleft param
				$items = Authitems::model()->findAll(array(
					'condition'=>'t.id IN ('.implode(',',$_POST['Assigments']['itemsleft']).')',
				));
				
				// create object roles manager
				$auth=Yii::app()->authManager;
				
				// returns the children using itemname
				//$aitems = $auth->getItemChildren($itemname);
				
				// find all authitemschilds previously selected for role and module
				$arrChild = array();
				$criteriaChild = new CDbCriteria;
				$criteriaChild->condition = "t.parent LIKE ('".$_POST['Assigments']['module']."')";
				$seleccionadosChild = Authitemchilds::model()->findAll($criteriaChild);
				foreach($seleccionadosChild as $child)
					array_push($arrChild, $child->child);
				
				$arrParent = array();
				$criteriaParent = new CDbCriteria;
				$criteriaParent->condition = 't.parent LIKE ("'.$itemname.'") AND t.child IN ("'.implode('","', $arrChild).'")';
				$seleccionadosParent = Authitemchilds::model()->findAll($criteriaParent);
				foreach($seleccionadosParent as $parent)
					array_push($arrParent, $parent->child);
				//--
				
				$newAssigment = array();
				foreach($items as $key) {
					// create array of new items
					array_push($newAssigment,$key->name);
					// if item not assigned, add to another list
					if (!$auth->hasItemChild($itemname,$key->name))
						$auth->addItemChild($itemname,$key->name);
				}
				
				// compute difference between old and new assigments
				$newAssigmentitems = array_diff($arrParent, $newAssigment);

				// Goin thru previous list
				foreach($newAssigmentitems as $key) {
					// remove items if not exist in new list
					$auth->removeItemChild($itemname,$key);
				}
				
				echo Yii::t('configuration','rolesAndModulesSaved',array('itemname'=>$itemname));
				Yii::app()->end();
			}
			else
			{
				echo Yii::t('configuration','rolesWithoutChanges');
				Yii::app()->end();
			}
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	/**
	 * Return roles list for selected user
	 */
	public function actiongetRoles()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			// find all roles for this account
			$criteriaAccountRoles = new CDbCriteria;
			$criteriaAccountRoles->condition = 't.type = 2 AND t.account_id = :account_id';
			$criteriaAccountRoles->params = array(
				':account_id'=>Users::model()->findByPk(Yii::app()->user->id)->account_id,
			);
			
			// find roles assigned to current user
			$criteriaUserRoles = new CDbCriteria;
			$criteriaUserRoles->condition = 't.userid = :userid';
			$criteriaUserRoles->params = array(
				':userid'=>$_POST['YII_CSRF_USID'],
			);
			
			$userRoles = array();
			$apply = Authassignments::model()->findAll($criteriaUserRoles);
			foreach($apply as $key) {
				array_push($userRoles, $key->itemname);
			}
			
			// set layout and render page
			$this->layout = 'simple';
			$this->render('manage/roles',array(
				'accountRoles'=>Authitems::model()->findAll($criteriaAccountRoles),
				'userRoles'=>$userRoles,
			));
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	/**
	 * Show roles information to edit
	 */
	public function actionshowRoleDetails()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			// find and load registry information
			$model=$this->loadModel();
			if(isset($_POST['Authitems']))
			{
				$NameBeforeChange = $model->name;
				// set form elements to Authitems model attributes
				$model->attributes=$_POST['Authitems'];
				// validate and save
				if($model->save())
				{
					$updateChilds = Authitemchilds::model()->updateAll(
						array(
							'parent'=>$model->name
						), 
						array(
							'condition'=>'parent = "'.$NameBeforeChange.'"'
						)
					);
					// redirect to rolesManage
					Yii::app()->controller->redirect(Yii::app()->createUrl('configuration/rolesManage'));
				}
			}
			
			// set layout and render page
			$this->layout = 'simple';
			$this->render('manage/updateRole',array(
				'model'=>$model,
			));
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	/**
	 * Create a new role
	 */
	public function actionroleCreate()
	{		
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			// create Authitems object
			$model=new Authitems;
			if(isset($_POST['Authitems']))
			{
				// set form elements to Authitems model attributes
				$model->attributes=$_POST['Authitems'];
				$model->type = 2;
				$model->bizrule = "return !Yii::app()->user->isGuest;";
				$model->data = "s:2:\"N;\";";
				$model->account_id = Users::model()->findByPk(Yii::app()->user->id)->account_id;
				// validate and save
				$model->save();
				// redirect to rolesManage
				Yii::app()->controller->redirect(Yii::app()->createUrl('configuration/rolesManage'));
			}
			
			// set layout and render page
			$this->layout = 'simple';
			$this->render('manage/createRol',array(
				'model'=>$model,
			));
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionroleDelete()
	{
		// check if user has permissions to permissionsConfiguration
		if(Yii::app()->user->checkAccess('permissionsConfiguration'))
		{
			// allow deleting via postRequest only
			if(Yii::app()->request->isPostRequest)
			{
				// we only allow deletion via POST request
				$item = Authitems::model()->findByPk($_POST['id'])->name;
				
				// delete all users linked to selected role
				$criteria = new CDbCriteria;
				$criteria->condition = "itemname LIKE ('".$item."')";
				Authassignments::model()->deleteAll($criteria);
				$this->loadModel()->delete();
				// set output message
				Yii::app()->user->setFlash('updateView','updateView');
			}
			else
				throw new CHttpException(400, Yii::t('site', '400_Error'));
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	/**
	 * 
	 * Manage all projects
	 */
	public function actionProjects()
	{
		// check if user has permissions to permissionsConfiguration and user is account administrator
		if((Yii::app()->user->checkAccess('permissionsConfiguration')) && Yii::app()->user->IsAdministrator)
		{
			// DataProvider for Clients Grid
			$dataProviderProjects = new CActiveDataProvider('Projects',array(
				'criteria'=>array(
					'condition'=>'Cusers.user_id = :user_id AND Cusers.user_admin = 1',
					'params'=>array(
						':user_id'=>Yii::app()->user->id,
					),
					'together'=>true,
					'order'=>'t.project_id ASC',
					'with'=>array('Cusers'),
				),
			));
			
			// set layout and render page
			$this->layout = 'column2';
			$this->render('projects', array(
				'dataProvider'=>$dataProviderProjects,
			));
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	public function actionchangeStatus()
	{
		// check if user has permissions to permissionsConfiguration and user is account administrator
		if((Yii::app()->user->checkAccess('permissionsConfiguration')) && Yii::app()->user->IsAdministrator)
		{				
			$project = Projects::model()->findByPk(Yii::app()->request->getParam("id",0));
			if ($project !== null)
			{
				$projectList = Yii::app()->user->getProjects();
				$arr_prj = array();
				foreach ($projectList as $prj)
					array_push($arr_prj, $prj->project_id);
				if (in_array($project->project_id, $arr_prj))
				{
					$project->project_active = !$project->project_active;
					$project->save(false);
				}
			}
			
			Yii::app()->end();
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateProject()
	{
		// check if user has permissions to createProjects
		if ((Yii::app()->user->checkAccess('createProjects')) && (Yii::app()->user->checkAccess('permissionsConfiguration')) && (Yii::app()->user->IsAdministrator))
		{
			// create Projects Object
			$model=new Projects;

			// if Projects form exist
			if(isset($_POST['Projects']))
			{
				// set form elements to Projects model attributes
				$model->attributes=$_POST['Projects'];
				
				// validate and save
				if($model->save())
				{
					// Create relation between user and project (this user will be first manager)
					$modelForm = new ProjectsHasUsersForm;
					$modelForm->project_id = $model->primaryKey;
					$modelForm->user_id = Yii::app()->user->id;
					$modelForm->isManager = 1;
					$modelForm->saveUser();
					 
					// Select current project has default selection project
					Yii::app()->user->setState('project_selected', $model->primaryKey);
					Yii::app()->user->setState('project_selectedName', $model->project_name);
					
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'ProjectCreated',
						'log_resourceid' => $model->primaryKey,
						'log_type' => Logs::LOG_ASSIGNED,
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->primaryKey,
					);
					Logs::model()->saveLog($attributes);
					
					// to prevent F5 keypress, redirect to create page
					Yii::app()->controller->redirect(Yii::app()->createUrl('projects/view',array('id'=>$model->primaryKey)));
				}
			}
			
			$this->layout = 'column2';

			// output create page
			$this->render('../projects/create',array(
				'model'=>$model,
				'companies'=>Companies::model()->findCompanyList(Yii::app()->user->id),
				'currencies'=>Currencies::model()->findAll(),
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
	public function loadModel()
	{
		$model=Authitems::model()->findByPk((int)$_POST['id']);
		if($model===null)
			throw new CHttpException(404, Yii::t('site', '404_Error'));
		return $model;
	}
}
