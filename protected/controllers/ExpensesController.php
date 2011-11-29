<?php
/**
 * ExpensesController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for Expenses 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class ExpensesController extends Controller
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
	public function actionChangeStatus()
	{
		// check if user has permissions to changeStatusExpenses
		if(Yii::app()->user->checkAccess('changeStatusExpenses'))
		{
			// verify is request was made via post ajax
			if((Yii::app()->request->isAjaxRequest) && (isset($_POST)))
			{
				// get Expense object model
				$model = $this->loadModel();
				
				// set new status
				$model->status_id = $_POST['changeto'];
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'ExpenseStatusChanged',
						'log_resourceid' => $model->expense_id,
						'log_type' => Logs::LOG_UPDATED,
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					// create comment to let then know that some user change the expense status
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
					$modelComment->comment_resourceid = $model->expense_id;
					$modelComment->save(false);
					
					$output = Yii::t('expenses','StatusChanged');
				}
				else
					$output = Yii::t('expenses','StatusError');
				
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
		// check if user has permissions to viewExpenses
		if ((Yii::app()->user->checkAccess('viewExpenses'))  && (is_numeric($_GET['id'])))
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
		// check if user has permissions to createExpenses
		if(Yii::app()->user->checkAccess('createExpenses'))
		{
			// create Expenses object model
			$model=new Expenses;

			// if Expenses form exist
			if(isset($_POST['Expenses']))
			{
				// set form elements to Expenses model attributes
				$model->attributes=$_POST['Expenses'];
				$model->project_id = Yii::app()->user->getState('project_selected');
				$model->status_id = Status::STATUS_PENDING;
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'ExpenseCreated',
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
					$str = $this->renderPartial('//templates/expenses/activity',array(
						'model' => $model,
						'projectName'=>$project->project_name,
						'userposted'=>Yii::app()->user->CompleteName,
						'applicationName' => Yii::app()->name,
						'applicationUrl' => Yii::app()->createAbsoluteUrl('expenses/view', array('id'=>$model->primaryKey)),
					),true);
					
					Yii::import('application.extensions.phpMailer.yiiPhpMailer');
					$mailer = new yiiPhpMailer;
					$subject = Yii::t('email','ExpenseCreated')." - ".$project->project_name;
					$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
					
					// to prevent F5 keypress, redirect to create page
					$this->redirect(array('view','id'=>$model->expense_id));
				}
			}

			$this->render('create',array(
				'expense'=>$model,
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
		// check if user has permissions to updateExpenses
		if(Yii::app()->user->checkAccess('updateExpenses'))
		{
			// get Expenses object from $_GET['id'] parameter
			$model=$this->loadModel();
			
			// check if expense hasn�t status cancelled, accepted or closed
			if (($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_ACCEPTED) && ($model->status_id != Status::STATUS_CLOSED)) 
			{
				// only managers can update budgets
				if (Yii::app()->user->IsManager)
				{
					// if Expenses form exist
					if(isset($_POST['Expenses']))
					{
						// set form elements to Expenses model attributes
						$model->attributes=$_POST['Expenses'];
						
						// validate and save
						if($model->save())
						{
							// save log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'ExpenseUpdated',
								'log_resourceid' => $model->expense_id,
								'log_type' => Logs::LOG_UPDATED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id,
								'project_id' => $model->project_id,
							);
							Logs::model()->saveLog($attributes);
							
							// to prevent F5 keypress, redirect to view page
							$this->redirect(array('view','id'=>$model->expense_id));
						}
					}

					$this->render('update',array(
						'expense'=>$model,
					));	
				}
				else
					Yii::app()->controller->redirect(array("expenses/view", 'id'=>$model->expense_id));
			}
			else
				Yii::app()->controller->redirect(array("expenses/view", 'id'=>$model->expense_id));
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
		// check if user has permission to indexExpenses
		if(Yii::app()->user->checkAccess('indexExpenses'))
		{
			// create Expense form search
			$model=new ExpensesSearchForm;
			$model->search();
			$model->unsetAttributes();  // clear any default values
			
			// set model attributes from expense form
			if(isset($_GET['ExpensesSearchForm']))
				$model->attributes=$_GET['ExpensesSearchForm'];
			
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
	 * @param $_GET['id'] to compare with expense_id
	 * @return CActiveRecord Budgets
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_REQUEST['id']))
				$this->_model=Expenses::model()->findbyPk((int)$_REQUEST['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='expenses-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * This methos search into expenses names similar to input text sent via _GET[term] 
	 * @return json data with expenses list
	 */
	public function actionProviderSearch()
    {
	   if(isset($_GET['term']))
       {
			$name = $_GET['term']; 
			$criteria = new CDbCriteria;
			$criteria->condition = "t.expense_name LIKE :expense_name";
			$criteria->params = array(
				":expense_name" => '%'.CHtml::encode($_GET['term']).'%',
			);
			$criteria->limit = 5;
			$Expenses = Expenses::model()->findAll($criteria);
			
			$result = '';
			foreach($Expenses as $expense)
			{
				$result[] = array(
					'label'=>$expense->expense_name,
					'value'=>$expense->expense_identifier,
				);   
			}
			
			echo CJSON::encode($result);
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
				$response = (Expenses::model()->countExpensesByProject((int)$_GET['id'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
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
