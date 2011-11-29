<?php
/**
 * TasksController class file
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
class TasksController extends Controller
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
			/*array(
				'application.filters.YXssFilter',
				'clean'   => '*',
				'tags'    => 'strict',
				'actions' => 'all'
			)*/
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
					'showworkers',
					'jointask',
					'relateddelete',
					'relatedcreate',
					'changestatus',
					'view',
					'create',
					'update',
					'delete',
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
	
	public function actionShowWorkers()
	{
		$workers = new CActiveDataProvider('Users', array(
			'criteria'=>array(
				'condition'=>'Tasks.task_id = :task_id',
				'params'=>array(
					':task_id'=>$_GET['id'],
				),
				'together'=>true,
				'order'=>'t.user_name',
				'with'=>array('Tasks'),
			),
		));
	
		$this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$workers,
			'summaryText'=>'',
			'itemView'=>'_tasksWorkers',
			'viewData'=>array(
				'canDelete'=>false
			),
			'htmlOptions'=>array(
				'style'=>'padding-left:25px'
			),
		));
		
		Yii::app()->end();
	}
	
	public function actionJoinTask()
	{
		if(Yii::app()->request->isAjaxRequest)
		{
			$model=new UsersHasTasks;
			$model->user_id = Yii::app()->user->id;
			$model->task_id = (int)$_GET['id'];
			
			$output = array(
				'saved' => false,
				'message'=>null
			);
			
			if ((!Yii::app()->user->HasJoined) && (Users::model()->verifyUserInProject((int)Yii::app()->user->getState('project_selected'), Yii::app()->user->id)))
    		{
				if($model->save())
				{
					$user = Users::model()->findByPk($model->user_id);
					$task = Tasks::model()->with('Projects','Milestones')->findByPk($model->task_id);
					$task->task_startDate = date("Y-m-d");//new CDbExpression('NOW()');
					$task->save(false);
					$recipientsList['email'] = $user->user_email;
					$recipientsList['name'] = $user->CompleteName;
					
					$subject = Yii::t('email','TaskAssigned')." - ".$task->task_name;
					
					$str = $this->renderPartial('//templates/tasks/assigment',array(
						'task' => $task,
						'task_url' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('tasks/view',array('id'=>$model->task_id)),
						'applicationName' => Yii::app()->name,
						'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
					),true);
					
					Yii::import('application.extensions.phpMailer.yiiPhpMailer');
					$mailer = new yiiPhpMailer;
					$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
					
					// Guardar log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'TaskAssigned',
						'log_resourceid' => $_GET['id'],
						'log_type' => Logs::LOG_ASSIGNED,
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => (int)Yii::app()->user->getState('project_id'),
					);
					
					Logs::model()->saveLog($attributes);
					
					$output = array(
						'saved' => true,
						'message'=>Yii::t('tasks','UserJoinTaskSuccess')
					);
				}
				else
				{
					$output = array(
						'saved' => false,
						'message'=>Yii::t('tasks','UserJoinTaskError')
					);
				}
			}
			die(CJSON::encode($output));
		}
	}
	
	public function actionRelatedDelete()
	{
		// check if user has permissions to viewTasks
    	if(Yii::app()->user->checkAccess('viewTasks'))
		{
			// verify params request
			if(Yii::app()->request->isAjaxRequest)
			{
				// verify params request
				if(Yii::app()->request->isPostRequest)
				{
					// verify user_id has relation with selected project and task_id has relation with selected project
					if ((Users::model()->verifyUserInProject((int)Yii::app()->user->getState('project_selected'),(int)$_POST['uid'])) && (Tasks::model()->verifyTasksInProject((int)Yii::app()->user->getState('project_selected'),(int)$_POST['task_id'])) && (Yii::app()->user->isManager))
					{
						$model = UsersHasTasks::model()->findBeforeDelete($_POST['uid'], $_POST['task_id']);
						if($model->delete())
						{							
							// Guardar log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'TaskRevoked',
								'log_resourceid' => $model->task_id,
								'log_type' => Logs::LOG_REVOKED,
								'user_id' => $model->user_id,
								'module_id' => Yii::app()->controller->id,
								'project_id' => (int)Yii::app()->user->getState('project_selected'),
							);
							
							Logs::model()->saveLog($attributes);
							
							$availableUsers = Users::model()->availablesUsersToTakeTask(Yii::app()->user->getState('project_selected'), $model->task_id);
							
							$str = $this->renderPartial('_dropdownUsersList',array(
								'availableUsers'=>$availableUsers
							), true);
							
							$output = array(
								'saved' => true,
								'html' => $str,
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
	
	public function actionRelatedCreate()
    {
		// check if user has permissions to viewTasks
    	if(Yii::app()->user->checkAccess('viewTasks'))
		{
			// verify params request
			if(Yii::app()->request->isAjaxRequest)
			{
				// verify params request
				if(Yii::app()->request->isPostRequest)
				{
					// verify user_id has relation with selected project and task_id has relation with selected project
					if ((Users::model()->verifyUserInProject((int)Yii::app()->user->getState('project_selected'),(int)$_POST['uid'])) && (Tasks::model()->verifyTasksInProject((int)Yii::app()->user->getState('project_selected'),(int)$_POST['task_id'])))
					{
						$output = array();
						$model=new UsersHasTasks;
						$model->user_id = $_POST['uid'];
						$model->task_id = $_POST['task_id'];
						if($model->save())
						{
							$user = Users::model()->findByPk($model->user_id);
							$task = Tasks::model()->with('Projects','Milestones')->findByPk($model->task_id);
							$recipientsList['email'] = $user->user_email;
							$recipientsList['name'] = $user->CompleteName;
							
							$subject = Yii::t('email','TaskAssigned')." - ".$task->task_name;
					
							$str = $this->renderPartial('//templates/tasks/assigment',array(
								'task' => $task,
								'task_url'=>"http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('tasks/view',array('id'=>$model->task_id)),
								'applicationName' => Yii::app()->name,
								'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
							),true);
							
							Yii::import('application.extensions.phpMailer.yiiPhpMailer');
							$mailer = new yiiPhpMailer;
							$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
							
							// Guardar log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'TaskAssigned',
								'log_resourceid' => $model->task_id,
								'log_type' => Logs::LOG_ASSIGNED,
								'user_id' => $model->user_id,
								'module_id' => Yii::app()->controller->id,
								'project_id' => (int)Yii::app()->user->getState('project_selected'),
							);
							
							Logs::model()->saveLog($attributes);
							
							$availableUsers = Users::model()->availablesUsersToTakeTask(Yii::app()->user->getState('project_selected'), $model->task_id);
							
							$str = $this->renderPartial('_dropdownUsersList',array(
								'availableUsers'=>$availableUsers
							), true);
							
							$output = array(
								'saved' => true,
								'html'=>$str,
								'hasreg' => (count($availableUsers) > 0) ? true : false,
							);
						}
						echo CJSON::encode($output);
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
	 */
	public function actionChangeStatus()
	{
		if(Yii::app()->user->checkAccess('changeStatusTasks'))
		{
			if((Yii::app()->request->isAjaxRequest) && (isset($_POST)))
			{
				$model = $this->loadModel($_REQUEST['id']);
				$model->status_id = $_POST['changeto'];
				
				// Set startDate or endDate for real dates to work
				switch($model->status_id)
				{
					case Status::STATUS_ACCEPTED:
						$model->task_startDate = date("Y-m-d"); //new CDbExpression('NOW()');
						break;
					case Status::STATUS_CLOSED:
						$model->task_endDate = date("Y-m-d"); //new CDbExpression('NOW()');
						break;
					default:
						break;
				}
				
				if($model->save())
				{
					// Guardar log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'TaskStatusChanged',
						'log_resourceid' => $model->task_id,
						'log_type' => 'updated',
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					$modelComment = new Comments;
					$modelComment->comment_date = date("Y-m-d G:i:s");
					$modelComment->comment_text = Status::STATUS_COMMENT.": ".$model->Status->status_id;
					$modelComment->user_id = Yii::app()->user->id;
					$modelComment->module_id = Modules::model()->find(array(
						'condition'=>'t.module_name = :module_name',
						'params'=>array(
							':module_name'=>$this->getId(),
						),
					))->module_id;
					$modelComment->comment_resourceid = $model->task_id;
					$modelComment->save(false);
					
					//$Users = Projects::model()->findManagersByProject($model->project_id);
					$Users = Projects::model()->findAllUsersByProject($model->project_id);
					
					$recipientsList = array();
					foreach($Users as $client)
					{			
						$recipientsList[] = array(
							'name'=>$client->CompleteName,
							'email'=>$client->user_email,
						);				
					}
					
					$subject = Yii::t('email','TaskStatusChanged')." - ".$model->task_name;
					$str = $this->renderPartial('//templates/tasks/statusChanged',array(
						'task' => $model,
						'username' => Users::model()->findByPk(Yii::app()->user->id)->completeName,
						'task_url' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('tasks/view',array('id'=>$model->task_id)),
						'applicationName' => Yii::app()->name,
						'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
					),true);
					
					Yii::import('application.extensions.phpMailer.yiiPhpMailer');
					$mailer = new yiiPhpMailer;
					$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
					
					echo "Status has been changed.";
				}
				else{
					echo "Something wrong, can not change status.";
					print_r($model->getErrors());
				}
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
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		if(Yii::app()->user->checkAccess('viewTasks'))
		{
			$workers = new CActiveDataProvider('Users', array(
				'criteria'=>array(
					'condition'=>'Tasks.task_id = :task_id',
					'params'=>array(
						':task_id'=>$id,
					),
					'together'=>true,
					'order'=>'t.user_name',
					'with'=>array('Tasks'),
				),
			));
			
			Users::model()->verifyUserInProject((int)Yii::app()->user->getState('project_selected'),1);
			
			$availableUsers = Users::model()->availablesUsersToTakeTask(Yii::app()->user->getState('project_selected'), $id);
			
			$this->render('view',array(
				'model'=>$this->loadModel($id),
				'workers'=>$workers,
				'hasTodoList'=>Todolist::model()->findList($id),
				'availableUsers'=>$availableUsers
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
		if(Yii::app()->user->checkAccess('createTasks'))
		{
			$model=new Tasks;
			
			$Milestones = Milestones::model()->with('Projects.Company.Cusers')->together()->findAll(array(
				'condition'=>'Cusers.user_id = :user_id AND t.project_id = :project_id AND Projects.project_endDate > CURDATE()',  //t.milestone_duedate
				'params'=>array(
					':user_id' => Yii::app()->user->id,
					':project_id' => Yii::app()->user->getState('project_selected'),
				),
				//'group'=>'t.project_id',
			));
			
			$Cases = Cases::model()->with('Projects.Company.Cusers')->together()->findAll(array(
				'condition'=>'Cusers.user_id = :user_id AND t.project_id = :project_id',
				'params'=>array(
					':user_id' => Yii::app()->user->id,
					':project_id' => Yii::app()->user->getState('project_selected'),
				),
			));

			if(isset($_POST['Tasks']))
			{
				$model->attributes=$_POST['Tasks'];
				$model->user_id = Yii::app()->user->id;
				$model->status_id = Status::STATUS_PENDING;
				$model->project_id = Yii::app()->user->getState('project_selected');
				$model->task_startDate = date("Y-m-d");//new CDbExpression('NOW()');
				if($model->save())
				{
					// Guardar log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'TaskCreated',
						'log_resourceid' => $model->primaryKey,
						'log_type' => 'created',
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					// avisar a los participantes del proyecto que una nueva tarea se ha agregado
					$Users = Projects::model()->findAllUsersByProject($model->project_id);
					$recipientsList = array();
					foreach($Users as $user)
					{			
						$recipientsList[] = array(
							'name'=>$user->CompleteName,
							'email'=>$user->user_email,
						);				
					}
					
					$subject = Yii::t('email','TaskStatusChanged')." - ".$model->task_name;
					$str = $this->renderPartial('//templates/tasks/statusChanged',array(
						'task' => $model,
						'username' => Yii::app()->user->CompleteName,
						'task_url' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('tasks/view',array('id'=>$model->task_id)),
						'applicationName' => Yii::app()->name,
						'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
					),true);
					
					Yii::import('application.extensions.phpMailer.yiiPhpMailer');
					$mailer = new yiiPhpMailer;
					$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
					
					$this->redirect(array('view','id'=>$model->task_id));
				}
			}

			$this->render('create',array(
				'model'=>$model,
				'status'=>Status::model()->findAll(),
				'types'=>TaskTypes::model()->findAll(),
				'stages'=>TaskStages::model()->findAll(),
				//'projects'=>$Projects,
				'milestones'=>$Milestones,
				'cases'=>$Cases,
				'allowEdit'=>true,
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
	public function actionUpdate($id)
	{
		if(Yii::app()->user->checkAccess('updateTasks'))
		{
			$allowEdit = false;
			$model=$this->loadModel($id);
			
			if (($model->status_id != Status::STATUS_CANCELLED) && ($model->status_id != Status::STATUS_ACCEPTED) && ($model->status_id != Status::STATUS_CLOSED))
			{
				if ($model->user_id == Yii::app()->user->id) {
					$allowEdit = true;
				}
				
				$Milestones = Milestones::model()->findMilestonesByProject($model->project_id);
				
				$Cases = Cases::model()->findCasesByProject($model->project_id);

				if(isset($_POST['Tasks']))
				{
					$model->attributes=$_POST['Tasks'];
					$model->user_id = Yii::app()->user->id;
					$model->task_startDate = date("Y-m-d");//new CDbExpression('NOW()');
					if($model->save())
					{
						// Guardar log
						$attributes = array(
							'log_date' => date("Y-m-d G:i:s"),
							'log_activity' => 'TaskUpdated',
							'log_resourceid' => $model->task_id,
							'log_type' => Logs::LOG_UPDATED,
							'user_id' => Yii::app()->user->id,
							'module_id' => Yii::app()->controller->id,
							'project_id' => $model->project_id,
						);
						Logs::model()->saveLog($attributes);
						
						$this->redirect(array('view','id'=>$model->task_id));
					}
				}

				$this->render('update',array(
					'model'=>$model,
					'status'=>Status::model()->findAll(),
					'types'=>TaskTypes::model()->findAll(),
					'stages'=>TaskStages::model()->findAll(),
					//'projects'=>$Projects,
					'milestones'=>$Milestones,
					'cases'=>$Cases,
					'allowEdit'=>$allowEdit,
				));
			}
			else
				Yii::app()->controller->redirect(array("tasks/view", 'id'=>$model->task_id));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->user->checkAccess('deleteTasks'))
		{
			if(Yii::app()->request->isPostRequest)
			{
				// we only allow deletion via POST request
				$this->loadModel($id)->delete();

				// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
				if(!isset($_GET['ajax']))
					$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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
		if(Yii::app()->user->checkAccess('indexTasks'))
		{
			
			$view = (Yii::app()->user->getState('view') != null) ? Yii::app()->user->getState('view') : 'list'; 
			if((isset($_GET['view'])) && (!empty($_GET['view'])))
			{
				if ($_GET['view'] == 'grid')
					$view = 'grid';
				elseif ($_GET['view'] == 'kanban')
					$view = 'kanban';
				else
					$view = 'list';
			}
			Yii::app()->user->setState('view',$view);
			
			$model=new TasksSearchForm;
			//$model->search();
			//$model->unsetAttributes();  // clear any default values
			
			$Milestones = Milestones::model()->with('Projects.Company.Cusers')->together()->findAll(array(
				'condition'=>'Cusers.user_id = :user_id AND t.project_id = :project_id',
				'params'=>array(
					':user_id' => Yii::app()->user->id,
					':project_id' => Yii::app()->user->getState('project_selected'),
				),
			));
			
			$Cases = Cases::model()->with('Projects.Company.Cusers')->together()->findAll(array(
				'condition'=>'Cusers.user_id = :user_id AND t.project_id = :project_id',
				'params'=>array(
					':user_id' => Yii::app()->user->id,
					':project_id' => Yii::app()->user->getState('project_selected'),
				),
			));
			
			if(isset($_GET['TasksSearchForm']))
				$model->attributes=$_GET['TasksSearchForm'];
			
			if ($view == 'kanban')
				$this->layout = 'column1';
				
			$this->render('index',array(
				'model'=>$model,
				'status'=>Status::model()->findAllOrdered(),
				'types'=>TaskTypes::model()->findAll(),
				'stages'=>TaskStages::model()->findAll(),
				'milestones'=>$Milestones,
				'cases'=>$Cases,
				'users'=>Projects::model()->findAllUsersByProject(Yii::app()->user->getState('project_selected')),
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
	public function loadModel($id)
	{
		$model=Tasks::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404, Yii::t('site', '404_Error'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='tasks-form')
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
				$response = (Tasks::model()->countTasksByProject((int)$_GET['id'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
				break;
			case "update":
				$response = (Tasks::model()->countTasksByProject((int)$_GET['id'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
				break;
			case "delete":
				$response = (Tasks::model()->countTasksByProject((int)$_GET['id'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
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
