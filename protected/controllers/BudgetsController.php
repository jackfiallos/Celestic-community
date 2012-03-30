<?php
/**
 * BudgetsController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for Budgets 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class BudgetsController extends Controller
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
					'index',
					'viewPrintable',
					'TakeAction',
					'Announcement',
					'changeStatus',
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
	 * Generate PDF view of Budget information
	 * @return pdf document
	 */
	public function actionviewPrintable()
	{
		// get token sended by email to user destination
		$token = (isset($_GET['token'])) ? $_GET['token'] : null;
		// get budget id
		$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : null;
		// get client id
		$user = (isset($_GET['cl']) && is_numeric($_GET['cl'])) ? $_GET['cl'] : null;
		
		// token, budget_id and user_id cannot null
		if (($token != null) && ($id != null) && ($user != null))
		{
			// find budget object using id _get param
			$model = Budgets::model()->with('Projects.Company')->together()->findByPk($id);
			
			// stop if model token correspond to token _get param
			if ($model->budget_token != $token)
				throw new CHttpException(403, Yii::t('site', '403_Error'));
			else
			{
				// find all concepts related to this budget_id
				$criteria = new CDbCriteria;
				$criteria->condition = 'budget_id =:budget_id';
				$criteria->params = array(
					':budget_id'=>$id,
				);
				$dataProvider=new CActiveDataProvider('BudgetsConcepts', array(
					'criteria'=>$criteria,
				));
				// template to generate PDF stream output
				$output = $this->renderPartial('//templates/budgets/printable',array(
					'dataProvider'=>$dataProvider,
					'model'=>$model,
				),true);
				
				// Create pdf object
				Yii::import('application.extensions.tcpdf.ETcPdf');
				$pdf = new ETcPdf('P', 'mm', 'Letter', true, 'UTF-8');
				$pdf->SetHeaderData(null, null, "Qbit Mexhico", "Calle Morena no.421 Colonia del Valle\nDel. Benito Juarez, Ciudad de Mexico");
				$pdf->SetFont('Helvetica', null, 11);
				$pdf->AddPage();
				$pdf->writeHTML($output, true, false, false, false, '');
				// Close and output PDF document
				$pdf->Output($model->budget_title.'.pdf', 'I');
			}
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	/**
	 * Modify budget status from email sent to clients
	 */
	public function actionTakeAction()
	{
		// actual status of budget
		$state = (isset($_GET['state']) && is_numeric($_GET['state'])) ? $_GET['state'] : null;
		// get token sended by email to user destination
		$token = (isset($_GET['token'])) ? $_GET['token'] : null;
		// get budget id
		$id = (isset($_GET['id']) && is_numeric($_GET['id'])) ? $_GET['id'] : null;
		// get client id
		$user = (isset($_GET['cl']) && is_numeric($_GET['cl'])) ? $_GET['cl'] : null;
		
		// token, budget_id and user_id cannot null
		if (($state != null) && ($token != null) && ($id != null) && ($user != null))
		{
			// find budget object using id _get param
			$model = Budgets::model()->findByPk($id);
			
			$Users = Users::model()->with('Clients')->findManagersByProject($model->project_id);
			
			// if budget status is different to pending send error
			if ($model->status_id != Status::STATUS_PENDING)
				throw new CHttpException(403, Yii::t('site', '403_Error'));
			else
			{
				// stop if model token correspond to token _get param
				if ($model->budget_token != $token)
					throw new CHttpException(403, Yii::t('site', '403_Error'));
				else
				{
					// change budget status
					$model->status_id = $state;
					$model->save(false);
					
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'BudgetStatusChanged',
						'log_resourceid' => $model->budget_id,
						'log_type' => Logs::LOG_UPDATED,
						'user_id' => $user,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					// create comment to let then know that some user change the budget status
					$modelComment = new Comments;
					$modelComment->comment_date = date("Y-m-d G:i:s");
					$modelComment->comment_text = Status::STATUS_COMMENT.": ".$model->Status->status_id;
					$modelComment->user_id = $user;
					$modelComment->module_id = Modules::model()->find(array(
						'condition'=>'t.module_name = :module_name',
						'params'=>array(
							':module_name'=>'budgets',
						),
					))->module_id;					
					$modelComment->comment_resourceid = $model->budget_id;
					$modelComment->save(false);
					
					// prepare email template for each project manager
					Yii::import('application.extensions.phpMailer.yiiPhpMailer');
					$mailer = new yiiPhpMailer;
					$subject = Yii::t('email','BudgetStatusChange')." - ".$model->budget_title;
					
					$recipientsList = array();
					foreach($Users as $client)
					{			
						$recipientsList[] = array(
							'name'=>$client->CompleteName,
							'email'=>$client->user_email,
						);				
					}
					// load template
					$str = $this->renderPartial('//templates/budgets/StatusChanged',array(
						'budget' => $model,
						'user'=>Users::model()->findByPk($user),
						'urlToBudget' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('budgets/view',array('id'=>$model->budget_id)),
						'typeNews'=>($model->status_id == Status::STATUS_ACCEPTED) ? 'buenas' : 'malas',
						'applicationName' => Yii::app()->name,
						'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
					),true);
					
					$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
				}
			}
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	/**
	 * Send email to client announcement that budget it's already to check
	 * @return string view with success message
	 */
	public function actionAnnouncement()
	{
		// verify that request was made using ajax, user is manager and exist id param as numeric
		if (Yii::app()->request->isAjaxRequest && Yii::app()->user->IsManager && is_numeric($_GET['id']))
		{
			// find all clients managers
			$model = Clients::model()->with('Users.ClientsManagers.Budgets')->findAll(array(
				'condition'=>'Budgets.budget_id = :budget_id',
				'params'=>array(
					':budget_id' => (int)$_GET['id'],
				),
				'together'=>true,
			));
			
			// generate new budget token
			$budget = Budgets::model()->with('Projects')->findByPk((int)$_GET['id']);
			$budget->budget_token = md5(uniqid(rand(), true));
			$budget->save(false);
			
			// create object to send mail
			Yii::import('application.extensions.phpMailer.yiiPhpMailer');
			$mailer = new yiiPhpMailer;
			$subject = Yii::t('email','BudgetValidationRequest')." - ".$budget->budget_title;
			
			// prepare email template for each project manager
			$RecipientsList = array();
			foreach($model as $client)
			{
				$RecipientsList[] = array(
					'name'=>$client->Users->CompleteName,
					'email'=>$client->Users->user_email
				);
				
				$str = $this->renderPartial('//templates/budgets/announcement',array(
					'budget' => $budget,
					'applicationName' => Yii::app()->name,
					'urlToAprove' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('budgets/takeaction',array('state'=>3,'token'=>$budget->budget_token,'id'=>$budget->budget_id,'cl'=>$client->Users->user_id)),
					'urlToDeclive' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('budgets/takeaction',array('state'=>2,'token'=>$budget->budget_token,'id'=>$budget->budget_id,'cl'=>$client->Users->user_id)),
					'urltoPDF' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('budgets/viewPrintable',array('token'=>$budget->budget_token,'id'=>$budget->budget_id,'cl'=>$client->Users->user_id)),
					'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
				),true);
				
				$mailer->pushMail($subject, $str, array('name'=>$client->Users->CompleteName,'email'=>$client->Users->user_email), Emails::PRIORITY_HIGH);
			}
			
			// return success message
			echo $this->renderPartial('//templates/budgets/SuccessSent',array(
				'model'=>$model,
			));
			
			Yii::app()->end();
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
	}
	
	/**
	 * Change buget status using select-box
	 * @return string with success message
	 */
	public function actionchangeStatus()
	{
		// check if user has permissions to changeStatusBudgets
		if(Yii::app()->user->checkAccess('changeStatusBudgets'))
		{
			// verify is request was made via post ajax
			if((Yii::app()->request->isAjaxRequest) && (isset($_POST)))
			{
				// get Budgets object model
				$model = $this->loadModel();
				
				// set new status
				$model->status_id = $_POST['changeto'];
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'BudgetStatusChanged',
						'log_resourceid' => $model->budget_id,
						'log_type' => Logs::LOG_UPDATED,
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					// create comment to let then know that some user change the budget status
					$modelComment = new Comments;
					$modelComment->comment_date = date("Y-m-d G:i:s");
					$modelComment->comment_text = "StatusChanged: " . $model->Status->status_id;
					$modelComment->user_id = Yii::app()->user->id;
					$modelComment->module_id = Modules::model()->find(array(
						'condition'=>'t.module_name = :module_name',
						'params'=>array(
							':module_name'=>$this->getId(),
						),
					))->module_id;					
					$modelComment->comment_resourceid = $model->budget_id;
					$modelComment->save(false);
					
					// prepare email template for each project manager
					Yii::import('application.extensions.phpMailer.yiiPhpMailer');
					$mailer = new yiiPhpMailer;
					$subject = Yii::t('email','BudgetStatusChange')." - ".$model->budget_title;
					
					$Users = Users::model()->with('Clients')->findManagersByProject($model->project_id);
					
					$recipientsList = array();
					foreach($Users as $client)
					{			
						$recipientsList[] = array(
							'name'=>$client->CompleteName,
							'email'=>$client->user_email,
						);				
					}
					// load template
					$str = $this->renderPartial('//templates/budgets/StatusChanged',array(
						'budget' => $model,
						'user'=>Users::model()->findByPk(Yii::app()->user->id),
						'urlToBudget' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('budgets/view',array('id'=>$model->budget_id)),
						'typeNews'=>($model->status_id == Status::STATUS_ACCEPTED) ? 'buenas' : 'malas',
						'applicationName' => Yii::app()->name,
						'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
					),true);
					
					$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
					
					$output = Yii::t('budgets','StatusChanged');
				}
				else
					$output = Yii::t('budgets','StatusError');
				
				echo $output; 
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
	 * @return view detail information
	 */
	public function actionView()
	{
		// check if user has permissions to viewBudgets
		if((Yii::app()->user->checkAccess('viewBudgets')) && (is_numeric($_GET['id'])))
		{
			$this->render('view',array(
				'model' => $this->loadModel(),
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
		// check if user has permissions to createBudgets
		if(Yii::app()->user->checkAccess('createBudgets'))
		{
			// create Budgets object model
			$model = new Budgets;

			// if Budgets form exist
			if(isset($_POST['Budgets']))
			{
				// set form elements to Budgets model attributes
				$model->attributes=$_POST['Budgets'];
				$model->status_id = Status::STATUS_PENDING;
				$model->user_id = Yii::app()->user->id; //created by
				$model->project_id = Yii::app()->user->getState('project_selected');
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'BudgetCreated',
						'log_resourceid' => $model->primaryKey,
						'log_type' => Logs::LOG_CREATED,
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					$project = Projects::model()->findByPk(Yii::app()->user->getState('project_selected'));
					
					// send an email to projects managers only
					$recipientsList = array();
					$ProjectManagers = Projects::model()->findManagersByProject($project->project_id);
					$managersArray = array();
					foreach($ProjectManagers as $manager)
					{
						$managersArray['email'] = $manager->user_email;
						$managersArray['name'] = $manager->CompleteName;
						array_push($recipientsList, $managersArray);
					}
					
					// prepare template to send via email
					$str = $this->renderPartial('//templates/budgets/activity',array(
						'model' => $model,
						'projectName'=>$project->project_name,
						'userposted'=>Yii::app()->user->CompleteName,
						'applicationName' => Yii::app()->name,
						'applicationUrl' => Yii::app()->createAbsoluteUrl('budgets/view', array('id'=>$model->primaryKey)),
					),true);
					
					Yii::import('application.extensions.phpMailer.yiiPhpMailer');
					$mailer = new yiiPhpMailer;
					$subject = Yii::t('email','BudgetCreated')." - ".$project->project_name;
					$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
					
					// to prevent F5 keypress, redirect to create page
					$this->redirect(array('view','id'=>$model->budget_id));
				}
			}

			$this->render('create',array(
				'budget'=>$model
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
		// check if user has permissions to updateBudgets
		if(Yii::app()->user->checkAccess('updateBudgets'))
		{
			// get Budgets object from $_GET['id'] parameter
			$model = $this->loadModel();
			
			// check if budget hasn�t status cancelled, accepted or closed
			if (($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_ACCEPTED) && ($model->status_id != Status::STATUS_CLOSED))
			{				
				// only managers can update budgets
				if (Yii::app()->user->IsManager)
				{
					// if Budgets form exist
					if(isset($_POST['Budgets']))
					{
						// set form elements to Budgets model attributes
						$model->attributes = $_POST['Budgets'];
						
						// validate and save
						if($model->save())
						{
							// save log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'BudgetUpdated',
								'log_resourceid' => $model->primaryKey,
								'log_type' => Logs::LOG_UPDATED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id,
								'project_id' => $model->project_id,
							);
							Logs::model()->saveLog($attributes);
							
							// to prevent F5 keypress, redirect to view page
							$this->redirect(array('view','id'=>$model->budget_id));
						}
					}

					$this->render('update',array(
						'budget'=>$model,
					));
				}
				else
					Yii::app()->controller->redirect(array("budgets/view", 'id'=>$model->budget_id));
			}
			else
				Yii::app()->controller->redirect(array("budgets/view", 'id'=>$model->budget_id));
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
		// check if user has permission to indexBudgets
		if(Yii::app()->user->checkAccess('indexBudgets'))
		{
			// create Budget form search
			$model = new BudgetsSearchForm;
			//$model->unsetAttributes();
			
			// set model attributes from budget form
			if(isset($_GET['BudgetsSearchForm']))
				$model->attributes = $_GET['BudgetsSearchForm'];
			
			$model->search();
				
			$this->render('index',array(
				'model'=>$model,
				'status'=>Status::model()->findAllRequired(true),
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param $_GET['id'] to compare with budget_id
	 * @return CActiveRecord Budgets
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_REQUEST['id']))
				$this->_model=Budgets::model()->with('BudgetsConcepts')->together()->findbyPk((int)$_REQUEST['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='budgets-form')
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
				$response = (Budgets::model()->countBudgetsByProject((int)$_GET['id'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
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
