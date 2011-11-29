<?php
/**
 * ProjectsController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for projects 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class ProjectsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
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
					'showmanagers',
					'removemanager',
					'addmanager',
					'removeuser',
					'adduser',
					'addclient',
					'view',
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
	 * List projects managers selecteds to work on a project, used after RelatedCreate is close
	 * @return CListView render with projects managers
	 */
	public function actionShowManagers()
	{
		// check if user has permissions to viewProjects
		if(Yii::app()->user->checkAccess('viewProjects'))
		{
	    	// verify is users has manager rights
			if (Yii::app()->user->isManager)
			{
				// DataProvider with managers information
				$Managers = new CActiveDataProvider('Users', array(
					'criteria'=>array(
						'condition'=>'ClientsManagers.project_id = :project_id AND ClientsManagers_ClientsManagers.isManager = 1',
						'params'=>array(
							':project_id'=>(int)Yii::app()->user->getState('project_selected'),
						),
						'together'=>true,
						'order'=>'t.user_name',
						'with'=>array('ClientsManagers'),
					),
				));
			
				// CListView render with projects managers DataProvider 
				$this->widget('zii.widgets.CListView', array(
					'dataProvider'=>$Managers,
					'summaryText'=>'',
					'itemView'=>'_projectsManagers',
					'htmlOptions'=>array(
						'style'=>'padding-left:25px'
					),
				));
				
				// end script execution
				Yii::app()->end();
			}
			else
				throw new CHttpException(403, Yii::t('site', '403_Error'));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}
	
	public function actionRemoveManager()
	{
		// check if user has permissions to updateProjects
    	if((Yii::app()->user->checkAccess('updateProjects')) && (Yii::app()->user->getIsManager()))
		{
			// verify params request
			if(Yii::app()->request->isAjaxRequest)
			{
				// verify params request
				if(Yii::app()->request->isPostRequest)
				{
					// verify user_id has relation with selected project
					if (Users::model()->verifyUserInProject((int)Yii::app()->user->getState('project_selected'),(int)$_POST['uid']))
					{
						// find record in Project Has User table
						$model = ProjectsHasUsers::model()->findBeforeDelete($_POST['uid'], (int)Yii::app()->user->getState('project_selected'));
						$output = array();
						if($model->delete())
						{							
							//Save log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'ManagerDeleted',
								'log_resourceid' => $model->project_id,
								'log_type' => Logs::LOG_DELETED,
								'user_id' => $model->user_id,
								'module_id' => Yii::app()->controller->id,
								'project_id' => (int)Yii::app()->user->getState('project_selected'),
							);
							
							Logs::model()->saveLog($attributes);
							
							$availablesManagers = Projects::model()->findAvailablesManagersByProject(Yii::app()->user->getState('project_selected'));
							
							$str = $this->renderPartial('_dropdownUsersList',array(
								'availablesManagers'=>$availablesManagers,
							), true);
							
							//verifivar si queda un administrador, enviar hasreg false para que no se pueda eliminar
							
							$output = array(
								'saved' => true,
								'html'=>$str,
								'hasreg' => true
							);
						}
						echo CJSON::encode($output);
						Yii::app()->end();
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
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}
    
	/**
	 * Create user as manager and relate to current project
	 */
	public function actionAddManager()
    {
        // check if user has permissions to updateProjects
    	if((Yii::app()->user->checkAccess('updateProjects')) && (Yii::app()->user->getIsManager()))
		{
			// verify params request
			if(Yii::app()->request->isAjaxRequest)
			{
				// verify params request
				if(Yii::app()->request->isPostRequest)
				{
					// create object to save relation between user and project as manager
					$model=new ProjectsHasUsers;
					$model->user_id = $_POST['uid'];
					$model->project_id = (int)Yii::app()->user->getState('project_selected');
					$model->isManager = 1;						
					if($model->save()) 
					{
						// find data for project and user to send an email
						$userSelected = Users::model()->findByPk($model->user_id);
						$project = Projects::model()->findByPk($model->project_id);

						// prepares to send email to user added to PM
						$str = $this->renderPartial('//templates/projects/useradd',array(
							'userCreateInvitation' => Yii::app()->user->CompleteName,
							'userInvited' => $userSelected->CompleteName,
							'projectName' => $project->project_name,
							'applicationName' => Yii::app()->name,
							'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
						),true);
						
						$subject = Yii::t('email','UserAddtoProject');
						
						Yii::import('application.extensions.phpMailer.yiiPhpMailer');
						$mailer = new yiiPhpMailer;
						$mailer->pushMail($subject, $str, array('email'=>$userSelected->user_email, 'name'=>$userSelected->CompleteName), Emails::PRIORITY_NORMAL);
						
						// save log
						$attributes = array(
							'log_date' => date("Y-m-d G:i:s"),
							'log_activity' => 'ManagerAssigned',
							'log_resourceid' => $model->project_id,
							'log_type' => Logs::LOG_ASSIGNED,
							'user_id' => Yii::app()->user->id,
							'module_id' => Yii::app()->controller->id,
							'project_id' => $model->project_id,
						);
						Logs::model()->saveLog($attributes);
						
						$availablesManagers = Projects::model()->findAvailablesManagersByProject(Yii::app()->user->getState('project_selected'));
						
						$str = $this->renderPartial('_dropdownUsersList',array(
							'availablesManagers'=>$availablesManagers
						), true);

						// output is a json denotation result
						$output = array(
							'saved' => true,
							'html'=>$str,
							'hasreg' => (count($availablesManagers) > 0) ? true : false,
						);
					}
				}
				else
					throw new CHttpException(403, Yii::t('site', '403_Error'));
				
				echo CJSON::encode($output);
				Yii::app()->end();
			}
			else
				throw new CHttpException(403, Yii::t('site', '403_Error'));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
    }
    
    /**
     * Remove users from related project
     */
    public function actionRemoveUser()
    {
    	// check if user has permissions to updateProjects
    	if((Yii::app()->user->checkAccess('updateProjects')) && (Yii::app()->user->getIsManager()))
		{
			// verify params request and post request
			if((Yii::app()->request->isAjaxRequest) && (Yii::app()->request->isPostRequest))
			{
				// verify user_id has relation with selected project
				if (Users::model()->verifyUserInProject((int)Yii::app()->user->getState('project_selected'),(int)Yii::app()->request->getParam("uid",0)))
				{
					// find record in Project Has User table
					$model = ProjectsHasUsers::model()->findBeforeDelete((int)Yii::app()->request->getParam("uid",0), (int)Yii::app()->user->getState('project_selected'));
					$output = array();
					if($model->delete())
					{
						//Save log
						$attributes = array(
							'log_date' => date("Y-m-d G:i:s"),
							'log_activity' => 'UserDeleted',
							'log_resourceid' => $model->project_id,
							'log_type' => Logs::LOG_DELETED,
							'user_id' => $model->user_id,
							'module_id' => Yii::app()->controller->id,
							'project_id' => (int)Yii::app()->user->getState('project_selected'),
						);
						
						Logs::model()->saveLog($attributes);
						
						// Managers List
						$Managers = Projects::model()->findManagersByProject(Yii::app()->user->getState('project_selected'));
						
						$managerList = array();
						if(count($Managers)>0)
						{
							foreach($Managers as $users)
								array_push($managerList, $users->user_id);
						}
						else
							array_push($managerList, -1);
						
						// validate source (user or client)
						$sourceRequest = Yii::app()->request->getParam("action","user");
						if (!empty($sourceRequest))
						{
							if ($sourceRequest == "client")
								$availables = Users::model()->filterManagers($managerList)->findClientsByProject($model->project_id);
							else
								$availables = Users::model()->filterManagers($managerList)->findUsersByProject($model->project_id);
							
							// Availables
							$ManagersAvailables = Users::model()->findAll(
								array(
									'condition'=>'Companies.company_id = :company_id AND t.user_id NOT IN ('.implode(",", $managerList).')',
									'params'=>array(
										':company_id'=>Projects::model()->findByPk(Yii::app()->user->getState('project_selected'))->company_id,
									),
									'together'=>true,
									'order'=>'t.user_name ASC',
									'with'=>array('Companies'),
								)
							);
							
							$str = $this->renderPartial('_dropdownUsersList',array(
								'availablesManagers'=>$availables,
							), true);
							
							$strManager = $this->renderPartial('_dropdownUsersList',array(
								'availablesManagers'=>$ManagersAvailables,
							), true);
							
							//verifivar si queda un administrador, enviar hasreg false para que no se pueda eliminar							
							$output = array(
								'saved' => true,
								'html'=>$str,
								'htmlManager'=>$strManager,
								'hasreg' => true
							);
						}
					}
					echo CJSON::encode($output);
					Yii::app()->end();
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
	 * Create user as project participant
	 */
	public function actionAddUser()
    {
        // check if user has permissions to updateProjects
    	if((Yii::app()->user->checkAccess('updateProjects')) && (Yii::app()->user->getIsManager()))
		{
			// verify params request
			if(Yii::app()->request->isAjaxRequest)
			{
				// verify params request
				if(Yii::app()->request->isPostRequest)
				{
					// create object to save relation between user and project as manager
					$model=new ProjectsHasUsers;
					$model->user_id = $_POST['uid'];
					$model->project_id = (int)Yii::app()->user->getState('project_selected');
					if($model->save()) 
					{
						// find data for project and user to send an email
						$userSelected = Users::model()->findByPk($model->user_id);
						$project = Projects::model()->findByPk($model->project_id);

						// prepares to send email to user added to PM
						$str = $this->renderPartial('//templates/projects/useradd',array(
							'userCreateInvitation' => Yii::app()->user->CompleteName,
							'userInvited' => $userSelected->CompleteName,
							'projectName' => $project->project_name,
							'applicationName' => Yii::app()->name,
							'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
						),true);
						
						$subject = Yii::t('email','UserAddtoProject');
						
						Yii::import('application.extensions.phpMailer.yiiPhpMailer');
						$mailer = new yiiPhpMailer;
						$mailer->pushMail($subject, $str, array('email'=>$userSelected->user_email, 'name'=>$userSelected->CompleteName), Emails::PRIORITY_NORMAL);
						
						// save log
						$attributes = array(
							'log_date' => date("Y-m-d G:i:s"),
							'log_activity' => 'UserAssigned',
							'log_resourceid' => $model->project_id,
							'log_type' => Logs::LOG_ASSIGNED,
							'user_id' => Yii::app()->user->id,
							'module_id' => Yii::app()->controller->id,
							'project_id' => $model->project_id,
						);
						Logs::model()->saveLog($attributes);
						
						$managers = Projects::model()->findManagersByProject((int)Yii::app()->user->getState('project_selected'));
						$managerList = array();
						if(count($managers)>0)
						{
							foreach($managers as $users)
								array_push($managerList, $users->user_id);
						}
						else
							array_push($managerList, -1);
						
						// Availables
						$ManagersAvailables = Users::model()->findAll(
							array(
								'condition'=>'Companies.company_id = :company_id AND t.user_id NOT IN ('.implode(",", $managerList).')',
								'params'=>array(
									':company_id'=>Projects::model()->findByPk(Yii::app()->user->getState('project_selected'))->company_id,
								),
								'together'=>true,
								'order'=>'t.user_name ASC',
								'with'=>array('Companies'),
							)
						);
						
						$availablesUsers = Users::model()->filterManagers($managerList)->findUsersByProject((int)Yii::app()->user->getState('project_selected'));
						
						$str = $this->renderPartial('_dropdownUsersList',array(
							'availablesManagers'=>$availablesUsers
						), true);
						
						$strManager = $this->renderPartial('_dropdownUsersList',array(
							'availablesManagers'=>$ManagersAvailables,
						), true);

						// output is a json denotation result
						$output = array(
							'saved' => true,
							'html'=>$str,
							'htmlManager'=>$strManager,
							'hasreg' => (count($availablesUsers) > 0) ? true : false,
						);
					}
				}
				else
					throw new CHttpException(403, Yii::t('site', '403_Error'));
				
				echo CJSON::encode($output);
				Yii::app()->end();
			}
			else
				throw new CHttpException(403, Yii::t('site', '403_Error'));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
    }
    
	/**
	 * Create client user as project participant
	 */
	public function actionAddClient()
    {
        // check if user has permissions to updateProjects
    	if((Yii::app()->user->checkAccess('updateProjects')) && (Yii::app()->user->getIsManager()))
		{
			// verify params request
			if(Yii::app()->request->isAjaxRequest)
			{
				// verify params request
				if(Yii::app()->request->isPostRequest)
				{
					// create object to save relation between user and project as manager
					$model=new ProjectsHasUsers;
					$model->user_id = $_POST['uid'];
					$model->project_id = (int)Yii::app()->user->getState('project_selected');
					if($model->save()) 
					{
						// find data for project and user to send an email
						$userSelected = Users::model()->findByPk($model->user_id);
						$project = Projects::model()->findByPk($model->project_id);

						// prepares to send email to user added to PM
						$str = $this->renderPartial('//templates/projects/useradd',array(
							'userCreateInvitation' => Yii::app()->user->CompleteName,
							'userInvited' => $userSelected->CompleteName,
							'projectName' => $project->project_name,
							'applicationName' => Yii::app()->name,
							'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
						),true);
						
						$subject = Yii::t('email','UserAddtoProject');
						
						Yii::import('application.extensions.phpMailer.yiiPhpMailer');
						$mailer = new yiiPhpMailer;
						$mailer->pushMail($subject, $str, array('email'=>$userSelected->user_email, 'name'=>$userSelected->CompleteName), Emails::PRIORITY_NORMAL);
						
						// save log
						$attributes = array(
							'log_date' => date("Y-m-d G:i:s"),
							'log_activity' => 'ClientAssigned',
							'log_resourceid' => $model->project_id,
							'log_type' => Logs::LOG_ASSIGNED,
							'user_id' => Yii::app()->user->id,
							'module_id' => Yii::app()->controller->id,
							'project_id' => $model->project_id,
						);
						Logs::model()->saveLog($attributes);
						
						$managers = Projects::model()->findManagersByProject((int)Yii::app()->user->getState('project_selected'));
						$managerList = array();
						if(count($managers)>0)
						{
							foreach($managers as $users)
								array_push($managerList, $users->user_id);
						}
						else
							array_push($managerList, -1);
						
						$ManagersAvailables = Users::model()->findAll(
							array(
								'condition'=>'Companies.company_id = :company_id AND t.user_id NOT IN ('.implode(",", $managerList).')',
								'params'=>array(
									':company_id'=>Projects::model()->findByPk(Yii::app()->user->getState('project_selected'))->company_id,
								),
								'together'=>true,
								'order'=>'t.user_name ASC',
								'with'=>array('Companies'),
							)
						);
							
						$availablesClients = Users::model()->filterManagers($managerList)->findClientsByProject(Yii::app()->user->getState('project_selected'));
						
						$str = $this->renderPartial('_dropdownUsersList',array(
							'availablesManagers'=>$availablesClients
						), true);
						
						$strManager = $this->renderPartial('_dropdownUsersList',array(
							'availablesManagers'=>$ManagersAvailables,
						), true);

						// output is a json denotation result
						$output = array(
							'saved' => true,
							'html'=>$str,
							'htmlManager'=>$strManager,
							'hasreg' => (count($availablesClients) > 0) ? true : false,
						);
					}
				}
				else
					throw new CHttpException(403, Yii::t('site', '403_Error'));
				
				echo CJSON::encode($output);
				Yii::app()->end();
			}
			else
				throw new CHttpException(403, Yii::t('site', '403_Error'));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
    }

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{	
		// check if user has permissions to viewProjects
		if(Yii::app()->user->checkAccess('viewProjects'))
		{
			// DataProvider for Managers List
			$Managers = new CActiveDataProvider('Users', array(
				'criteria'=>array(
					'condition'=>'ClientsManagers.project_id = :project_id AND ClientsManagers_ClientsManagers.isManager = 1',
					'params'=>array(
						':project_id'=>(int)Yii::app()->user->getState('project_selected'),
					),
					'together'=>true,
					'order'=>'t.user_name',
					'with'=>array('ClientsManagers'),
				),
			));
			
			$managerList = array();
			if(count($Managers->data)>0)
			{
				foreach($Managers->data as $users)
					array_push($managerList, $users->user_id);
			}
			else
				array_push($managerList, -1);
			
			
			$ManagersAvailables = Users::model()->findAll(
				array(
					'condition'=>'Companies.company_id = :company_id AND t.user_id NOT IN ('.implode(",", $managerList).')',
					'params'=>array(
						':company_id'=>Projects::model()->findByPk(Yii::app()->user->getState('project_selected'))->company_id,
					),
					'together'=>true,
					'order'=>'t.user_name ASC',
					'with'=>array('Companies'),
				)
			);
			
			// Get project total cost, taking all accepted budgets
			$ProjectCost = Projects::model()->getProjectCost((int)Yii::app()->user->getState('project_selected'));
			
			// validate project total
			if (!isset($ProjectCost->total))
				$ProjectCost->total = 0;
			
			// DataProvider for Budgets Grid
			$dataProviderBudgets = new CActiveDataProvider('Budgets',array(
				'criteria'=>array(
					'condition'=>'t.project_id = :project_id',
					'params'=>array(
						':project_id'=>(int)Yii::app()->user->getState('project_selected'),
					),
					'together'=>true,
				),
			));
			
			// DataProvider for Invoices Grid
			$dataProviderInvoices = new CActiveDataProvider('Invoices',array(
				'criteria'=>array(
					'condition'=>'t.project_id = :project_id',
					'params'=>array(
						':project_id'=>$_GET['id'],
					),
					'together'=>true,
				),
			));
			
			// DataProvider for Users Grid
			$dataProviderUsers = new CActiveDataProvider('Users',array(
				'criteria'=>array(
					'condition'=>'ClientsManagers.project_id = :project_id AND Clients.client_id IS NULL',
					'params'=>array(
						':project_id'=>$_GET['id'],
					),
					'together'=>true,
					'order'=>'t.user_name',
					'with'=>array('Clients','ClientsManagers'),
				),
			));
			
			// DataProvider for Clients Grid
			$dataProviderClients = new CActiveDataProvider('Clients',array(
				'criteria'=>array(
					'condition'=>'ClientsManagers.project_id = :project_id AND t.client_id IS NOT NULL',
					'params'=>array(
						':project_id'=>$_GET['id'],
					),
					'together'=>true,
					'order'=>'Users.user_name',
					'with'=>array('Users.ClientsManagers'),
				),
			));
			
			// DataProvider for Documents Grid
			$dataProviderDocuments = new CActiveDataProvider('Documents',array(
				'criteria'=>array(
					'condition'=>'t.document_id IN(
							SELECT MAX( t.document_id ) 
							FROM `tb_documents` `t` 
							WHERE t.project_id = :project_id
							GROUP BY t.document_baseRevision
						)',
					'params'=>array(
						':project_id'=>$_GET['id'],
					),
				),
			));
			
			// DataProvider for Milestones Grid
			$dataProviderMilestones = new CActiveDataProvider('Milestones',array(
				'criteria'=>array(
					'condition'=>'t.project_id = :project_id',
					'params'=>array(
						':project_id'=>$_GET['id'],
					),
				),
			));
			
			foreach($dataProviderUsers->data as $users)
				array_push($managerList, $users->user_id);
			
			foreach($dataProviderClients->data as $users)
				array_push($managerList, $users->user_id);
			
			// output view page
			$this->render('view',array(
				'model'=>$this->loadModel(),
				'Budgets'=>$dataProviderBudgets,
				'Invoices'=>$dataProviderInvoices,
				'Users'=>$dataProviderUsers,
				'Clients'=>$dataProviderClients,
				'Documents'=>$dataProviderDocuments,
				'Milestones'=>$dataProviderMilestones,
				'ProjectCost'=>$ProjectCost,
				'managers'=>$Managers,
				'availablesManagers'=>$ManagersAvailables,
				'UsersList' => Users::model()->filterManagers($managerList)->findUsersByProject((int)$_GET['id']),
				'ClientsList' => Users::model()->filterManagers($managerList)->findClientsByProject((int)$_GET['id']),
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
		// check if user has permissions to updateProjects
		if(Yii::app()->user->checkAccess('updateProjects'))
		{
			if (Yii::app()->user->isManager)
			{
				// get Projects object from $_GET[id] parameter
				$model=$this->loadModel();

				// if Projects form exist
				if(isset($_POST['Projects']))
				{
					// set form elements to Projects model attributes
					$model->attributes=$_POST['Projects'];
					// validate and save
					if($model->save()) 
					{
						// save log
						$attributes = array(
							'log_date' => date("Y-m-d G:i:s"),
							'log_activity' => 'ProjectUpdated',
							'log_resourceid' => $model->project_id,
							'log_type' => Logs::LOG_UPDATED,
							'user_id' => Yii::app()->user->id,
							'module_id' => Yii::app()->controller->id,
							'project_id' => $model->project_id,
						);
						Logs::model()->saveLog($attributes);
						
						// to prevent F5 keypress, redirect to create page
						$this->redirect(array('view','id'=>$model->project_id));
					}
				}

				// output update page
				$this->render('update',array(
					'model'=>$model,
					'companies'=>Companies::model()->findCompanyList(Yii::app()->user->id),
					'currencies'=>Currencies::model()->findAll(),
				));
			}
			else
				Yii::app()->controller->redirect(array("projects/view", 'id'=>$_GET['id']));
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
		// check if user has permission to indexProjects
		if(Yii::app()->user->checkAccess('indexProjects'))
		{
			// save project_id selected has $_GET param
			$_GET['id'] = Yii::app()->user->getState('project_selected');
			// handler actionView
			$this->actionView();
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @return CActiveRecord Cases
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Projects::model()->findbyPk((int)$_GET['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='projects-form')
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
		if (Yii::app()->user->getState('project_selected') != null)
		{
			if (in_array($action->id, array('view','update')))
				$response = (Projects::model()->countProjects(Yii::app()->user->getState('project_selected')) > 0) ? true : false;
			else
				$response = true;
		}
		else
			$response = false;
		
		if(!$response)
			throw new CHttpException(403, Yii::t('site', '403_Error'));
		else
			return $response;
	}
}
