<?php
/**
 * InvoicesController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for Invoices 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class InvoicesController extends Controller
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
					'changestatus',
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
	 * @return string with success message
	 */
	public function actionchangeStatus()
	{
		// check if user has permissions to changeStatusInvoices
		if(Yii::app()->user->checkAccess('changeStatusInvoices'))
		{
			// verify is request was made via post ajax
			if((Yii::app()->request->isAjaxRequest) && (isset($_POST)))
			{
				// get Invoice object model
				$model = $this->loadModel();
				
				// set new status
				$model->status_id = $_POST['changeto'];
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'InvoiceStatusChanged',
						'log_resourceid' => $model->invoice_id,
						'log_type' => Logs::LOG_UPDATED,
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					// create comment to let then know that some user change the invoice status
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
					$modelComment->comment_resourceid = $model->invoice_id;
					$modelComment->save(false);
					
					$output = Yii::t('invoices','StatusChanged');
				}
				else
					$output = Yii::t('invoices','StatusError');
				
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
		// check if user has permissions to viewInvoices
		if ((Yii::app()->user->checkAccess('viewInvoices')) && (is_numeric($_GET['id'])))
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
	 * @return create view
	 */
	public function actionCreate()
	{
		// check if user has permissions to createInvoices
		if(Yii::app()->user->checkAccess('createInvoices'))
		{
			// create Invoices object model
			$model=new Invoices;
			// find all budgets relateds to current project
			$Budgets = Budgets::model()->findBudgetsByProjects(Yii::app()->user->getState('project_selected'));
			
			// if Invoices form exist
			if(isset($_POST['Invoices']))
			{
				// set form elements to Invoices model attributes
				$model->attributes=$_POST['Invoices'];
				$model->project_id = Yii::app()->user->getState('project_selected');
				$model->status_id = Status::STATUS_PENDING;
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'InvoiceCreated',
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
					$str = $this->renderPartial('//templates/invoices/activity',array(
						'model' => $model,
						'projectName'=>$project->project_name,
						'userposted'=>Yii::app()->user->CompleteName,
						'applicationName' => Yii::app()->name,
						'applicationUrl' => Yii::app()->createAbsoluteUrl('invoices/view', array('id'=>$model->primaryKey)),
					),true);
					
					Yii::import('application.extensions.phpMailer.yiiPhpMailer');
					$mailer = new yiiPhpMailer;
					$subject = Yii::t('email','InvoiceCreated')." - ".$project->project_name;
					$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);

					// to prevent F5 keypress, redirect to create page
					$this->redirect(array('view','id'=>$model->invoice_id));
				}
			}

			$this->render('create',array(
				'invoice'=>$model,
				'lastused'=>Invoices::model()->getLastUsed(),
				'budgets'=>$Budgets,
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
		// check if user has permissions to updateInvoices
		if(Yii::app()->user->checkAccess('updateInvoices'))
		{
			// get Invoices object from $_GET['id'] parameter
			$model=$this->loadModel();
			// find all budgets relateds to current project
			$Budgets = Budgets::model()->findBudgetsByProjects(Yii::app()->user->getState('project_selected'));
			
			// check if invoice hasn�t status cancelled, accepted or closed
			if (($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_ACCEPTED) && ($model->status_id != Status::STATUS_CLOSED)) 
			{
				// only managers can update budgets
				if (Yii::app()->user->IsManager)
				{
					// if Invoices form exist
					if(isset($_POST['Invoices']))
					{
						// set form elements to Invoices model attributes
						$model->attributes=$_POST['Invoices'];
						
						// validate and save
						if($model->save())
						{
							// save log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'InvoiceUpdated',
								'log_resourceid' => $model->invoice_id,
								'log_type' => Logs::LOG_UPDATED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id,
								'project_id' => $model->project_id,
							);
							Logs::model()->saveLog($attributes);
							
							// to prevent F5 keypress, redirect to view page
							$this->redirect(array('view','id'=>$model->invoice_id));
						}
					}

					$this->render('update',array(
						'invoice'=>$model,
						'budgets'=>$Budgets,
					));
				}
				else
					Yii::app()->controller->redirect(array("invoices/view", 'id'=>$model->invoice_id));
			}
			else
				Yii::app()->controller->redirect(array("invoices/view", 'id'=>$model->invoice_id));
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
		// check if user has permission to indexInvoices
		if(Yii::app()->user->checkAccess('indexInvoices'))
		{
			// create Expense form search
			$model=new InvoicesSearchForm;
			$model->search();
			$model->unsetAttributes();  // clear any default values
			
			$Budgets = Budgets::model()->findBudgetsByProjects(Yii::app()->user->getState('project_selected'));
			
			// set model attributes from invoices form
			if(isset($_GET['InvoicesSearchForm']))
				$model->attributes=$_GET['InvoicesSearchForm'];
			
			$this->render('index',array(
				'model'=>$model,
				'status'=>Status::model()->findAllRequired(true),
				'budgets'=>$Budgets,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param $_GET['id'] to compare with invoice_id
	 * @return CActiveRecord Invoices
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_REQUEST['id']))
				$this->_model=Invoices::model()->findbyPk($_REQUEST['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='invoices-form')
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
				$response = (Invoices::model()->countInvoicesByProject((int)$_GET['id'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
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
