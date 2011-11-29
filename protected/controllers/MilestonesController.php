<?php
/**
 * MilestonesController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for milestones 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class MilestonesController extends Controller
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
					'rearrange',
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
	 * Save order selected for all tasks related to one milestone
	 * @return null because use ajax live
	 */
	public function actionRearrange()
	{		
		// check if user has permissions to viewMilestones
		if(Yii::app()->user->checkAccess('viewMilestones'))
		{
			// verify that user has manager rights
			if (Yii::app()->user->IsManager)
			{
				// order position set using mouse drag event in client view
				$key_value = $_GET['positions'];
				
				// create string array of query command
				foreach($key_value as $k=>$v)
				{
					$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1);
				}
				
				// no hacks!!
				if(!$strVals) throw new Exception("No data!");
				
				// execute a query command 
				$command = Yii::app()->db->createCommand('UPDATE tb_tasks SET task_position = CASE task_id '.implode(' ',$strVals).' ELSE task_position END')->execute();
				
				// script execution end
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
	 * @return string with success message
	 */
	public function actionView($id)
	{
		// check if user has permission to viewMilestones
		if(Yii::app()->user->checkAccess('viewMilestones'))
		{
			// get Invoice object model
			$model = $this->loadModel($id);
			
			// Tasks dataprovider
			$dataProviderTasks = Tasks::model()->findAll(array(
				'condition'=>'t.milestone_id = :milestone_id',
				'params'=>array(
					':milestone_id'=>$model->milestone_id,
				),
				'order'=>'t.status_id ASC, t.task_priority DESC', //t.task_position ASC,
			));
			
			// finding by status
			$criteria = new CDbCriteria;
			$criteria->select = "count(t.status_id) as total";
			$criteria->condition = "t.milestone_id = :milestone_id";
			$criteria->params = array(
				':milestone_id' => $id,
			);			
			$criteria->group = "t.status_id";
			$foundTasksStatus = Tasks::model()->with('Status')->together()->findAll($criteria);
	
			$TasksStatus = array();
			foreach($foundTasksStatus as $task)
			{
				$TasksStatus[] = array(
					'name' => $task->Status->status_name,
					'data' => intval($task->total),
				);
			}
			
			// finding by priority
			$criteria = new CDbCriteria;
			$criteria->select = "t.task_priority, count(t.task_priority) as total";
			$criteria->condition = "t.milestone_id = :milestone_id";
			$criteria->params = array(
				':milestone_id' => $id,
			);			
			$criteria->group = "t.task_priority";
			$foundTasksPriority = Tasks::model()->findAll($criteria);
			$TasksPriority = array();
			foreach($foundTasksPriority as $task)
				$TasksPriority[] = array(Tasks::getNameOfTaskPriority($task->task_priority), intval($task->total));
			
			// output view
			$this->render('view',array(
				'model'=>$model,
				'percent'=>Milestones::model()->getMilestonePercent($model->milestone_id),
				'dataProviderTasks'=>$dataProviderTasks,
				'TasksStatus'=>$TasksStatus,
				'TasksPriority'=>$TasksPriority,
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
		// check if user has permissions to createMilestones
		if(Yii::app()->user->checkAccess('createMilestones'))
		{
			// create Milestones object model
			$model=new Milestones;
			
			// find all project managers
			$Users = Projects::model()->findManagersByProject(Yii::app()->user->getState('project_selected'));

			// if Milestones form exist
			if(isset($_POST['Milestones']))
			{
				// set form elements to Milestones model attributes
				$model->attributes=$_POST['Milestones'];
				$model->project_id = Yii::app()->user->getState('project_selected');
				
				// validate milestone model
				if ($model->validate())
				{
					// find milestones dates
					$milestone_startdate = date("Ymd", strtotime($model->milestone_startdate));
					$milestone_duedate = date("Ymd", strtotime($model->milestone_duedate));
					
					// get project data
					$project = Projects::model()->findByPk($model->project_id);
					
					// find project dates
					$project_startDate = date("Ymd", strtotime($project->project_startDate));
					$project_endDate = date("Ymd", strtotime($project->project_endDate));
					
					// If milestone dates are not within project dates ERROR!!
					if (($milestone_startdate >= $project_startDate) && ($milestone_startdate <= $project_endDate))
					{
						if (($milestone_duedate <= $project_endDate) && ($milestone_duedate >= $project_startDate))
						{
							// validate and save
							if($model->save())
							{
								// save log
								$attributes = array(
									'log_date' => date("Y-m-d G:i:s"),
									'log_activity' => 'MilestoneCreated',
									'log_resourceid' => $model->primaryKey,
									'log_type' => Logs::LOG_CREATED,
									'user_id' => Yii::app()->user->id,
									'module_id' => Yii::app()->controller->id,
									'project_id' => $model->project_id,
								);
								Logs::model()->saveLog($attributes);
								
								// notify to user that has a milestone to attend
								Yii::import('application.extensions.phpMailer.yiiPhpMailer');
								$mailer = new yiiPhpMailer;
								$subject = Yii::t('email','MilestoneAssigned')." :: ".$model->milestone_title;
								
								// user you will be notified
								$User = Users::model()->findByPk($model->user_id);
								$recipientsList = array(
									'name'=>$User->CompleteName,
									'email'=>$User->user_email,
								);
								
								// render template
								$str = $this->renderPartial('//templates/milestones/assigned',array(
									'milestone' => $model,
									'urlToMilestone' => Yii::app()->createAbsoluteUrl('milestones/view',array('id'=>$model->milestone_id)),
									'applicationName' => Yii::app()->name,
									'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
								),true);
								$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
								
								// to prevent F5 keypress, redirect to create page
								$this->redirect(array('view','id'=>$model->milestone_id));
							}
						}
						// error on milestone_duedate
						else
							$model->addError('milestone_duedate', Yii::t('milestones','DueDateError'));
					}
					// error on milestone_startdate
					else
						$model->addError('milestone_startdate', Yii::t('milestones','StartDateError'));
				}
			}

			$this->render('create',array(
				'model'=>$model,
				'users'=>$Users,
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
		// check if user has permissions to updateMilestones
		if(Yii::app()->user->checkAccess('updateMilestones'))
		{
			// get Milestones object from $_GET['id'] parameter
			$model=$this->loadModel();		
			
			// only managers can update budgets
			if (Yii::app()->user->IsManager)
			{
				// find all projects managers
				$Users = Projects::model()->findManagersByProject($model->project_id);

				if(isset($_POST['Milestones']))
				{
					// if Milestones form exist
					$model->attributes=$_POST['Milestones'];
					
					// validate and save
					if($model->save())
					{
						// save log
						$attributes = array(
							'log_date' => date("Y-m-d G:i:s"),
							'log_activity' => 'MilestoneUpdated',
							'log_resourceid' => $model->milestone_id,
							'log_type' => 'updated',
							'user_id' => Yii::app()->user->id,
							'module_id' => Yii::app()->controller->id,
							'project_id' => $model->project_id,
						);
						Logs::model()->saveLog($attributes);
						
						// to prevent F5 keypress, redirect to view page
						$this->redirect(array('view','id'=>$model->milestone_id));
					}
				}

				$this->render('update',array(
					'model'=>$model,
					'users'=>$Users,
				));
			}
			else
				Yii::app()->controller->redirect(array("milestones/view", 'id'=>$model->milestone_id));
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
		// check if user has permission to indexMilestones
		if(Yii::app()->user->checkAccess('indexMilestones'))
		{
			// create Milestones form search
			$model=new MilestonesSearchForm;
			$model->search();
			$model->unsetAttributes();  // clear any default values
			
			// set model attributes from milestones form
			if(isset($_GET['MilestonesSearchForm']))
				$model->attributes=$_GET['MilestonesSearchForm'];
			
			// find all projects managers
			$Users = Projects::model()->findManagersByProject(Yii::app()->user->getState('project_selected'));
				
			$this->render('index',array(
				'model'=>$model,
				'users'=>$Users,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param $_GET['id'] to compare with milestone_id
	 * @return CActiveRecord Milestones
	 */
	public function loadModel()
	{		
		if($this->_model===null)
		{
			if(isset($_REQUEST['id']))
				$this->_model=Milestones::model()->findByPk((int)$_REQUEST['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='milestones-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * 
	 * @return 
	 */
	/*public function actiongetManagers()
	{
		$data = Projects::model()->findManagersByProject((int)$_POST['projectid']);

		$data=CHtml::listData($data,'user_id','CompleteName');
		echo CHtml::tag('option', array('value'=>''),Yii::t('milestones', 'selectOption'),true);
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
	}
	
	public function actiongetMilestones()
	{
		$data = Milestones::model()->findMilestonesByProject((int)$_POST['projectid']);

		$data=CHtml::listData($data,'milestone_id','milestone_title');
		echo CHtml::tag('option', array('value'=>''),Yii::t('milestones', 'selectOption'),true);
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
		}
	}
	
	public function actionDetails()
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='getDetails')
		{
			// Verificar que tiene permisos primero para ver este milestone
			$criteria = new CDbCriteria;
			$criteria->condition = "t.milestone_id = :milestone_id";
			$criteria->params = array(
				':milestone_id' => $_POST['param'],
			);
			$model = Milestones::model()->find($criteria);
			
			$this->renderPartial('details',array(
				'model'=>$model,
			));
		}
		else
			echo Yii::t('milestones','NoDataToShow');
	}*/
	
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
				$response = (Milestones::model()->countMilestonesByProject((int)$_GET['id'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
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
