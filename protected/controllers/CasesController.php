<?php
/**
 * CasesController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for Cases 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class CasesController extends Controller
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
					'index',
					'getcases'
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
	 * Change Case status using select-box
	 */
	public function actionChangeStatus()
	{
		// check if user has permissions to changeStatusCases
		if(Yii::app()->user->checkAccess('changeStatusCases'))
		{
			// verify is request was made via post ajax
			if((Yii::app()->request->isAjaxRequest) && (isset($_POST)))
			{
				// get Cases object model
				$model = $this->loadModel($_REQUEST['id']);
				
				// set new status
				$model->status_id = $_POST['changeto'];
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'CaseStatusChanged',
						'log_resourceid' => $model->case_id,
						'log_type' => Logs::LOG_UPDATED,
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					// create comment to let then know that some user change the case status
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
					$modelComment->comment_resourceid = $model->case_id;
					$modelComment->save(false);
					
					// prepare email template for each project manager
					Yii::import('application.extensions.phpMailer.yiiPhpMailer');
					$mailer = new yiiPhpMailer;
					$subject = Yii::t('email','CaseStatusChange')." - ".$model->case_name;
					
					//$Users = Users::model()->with('Clients')->findManagersByProject($model->project_id);
					$Users = Projects::model()->findAllUsersByProject($model->project_id);
					
					$recipientsList = array();
					foreach($Users as $client)
					{			
						$recipientsList[] = array(
							'name'=>$client->CompleteName,
							'email'=>$client->user_email,
						);				
					}
					// load template
					$str = $this->renderPartial('//templates/cases/StatusChanged',array(
						'case' => $model,
						'user'=>Users::model()->findByPk(Yii::app()->user->id),
						'urlToCase' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('cases/view',array('id'=>$model->case_id)),
						'typeNews'=>(($model->status_id == Status::STATUS_ACCEPTED) || ($model->status_id == Status::STATUS_TOREVIEW)) ? 'buenas' : 'malas',
						'applicationName' => Yii::app()->name,
						'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
					),true);
					
					$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
					
					$output = Yii::t('cases','StatusChanged');
				}
				else
					$output = Yii::t('cases','StatusError');
				
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
	 */
	public function actionView()
	{
		// check if user has permissions to viewCases
		if(Yii::app()->user->checkAccess('viewCases'))
		{			
			// DataProvider for Normal Secuence
			$NormalSecuense = new CActiveDataProvider('Secuences',array(
				'criteria'=>array(
					//'select' => 't.secuence_step, t.secuence_action, t.secuence_id, t.case_id',
					'condition'=>'t.case_id = :case_id AND SecuenceTypes.secuenceTypes_id = 1',
					'params'=>array(
						':case_id'=>$_GET['id'],
					),
					'order'=>'t.secuence_id',
					'with'=>array('SecuenceTypes'),
					'together'=>true,
				),
			));
			
			// DataProvider for Normal Alternative
			$AlternativeSecuense = new CActiveDataProvider('Secuences',array(
				'criteria'=>array(
					//'select' => 't.secuence_step, t.secuence_action, t.secuence_id',
					'condition'=>'t.case_id = :case_id AND SecuenceTypes.secuenceTypes_id = 2',
					'params'=>array(
						':case_id'=>$_GET['id'],
					),
					'order'=>'t.secuence_id',
					'with'=>array('SecuenceTypes'),
					'together'=>true,
				),
			));
			
			// DataProvider for Normal Exception
			$ExceptionSecuense = new CActiveDataProvider('Secuences',array(
				'criteria'=>array(
					//'select' => 't.secuence_step, t.secuence_action, t.secuence_id',
					'condition'=>'t.case_id = :case_id AND SecuenceTypes.secuenceTypes_id = 3',
					'params'=>array(
						':case_id'=>$_GET['id'],
					),
					'order'=>'t.secuence_id',
					'with'=>array('SecuenceTypes'),
					'together'=>true,
				),
			));
			
			// DataProvider for Validations
			$Validations = new CActiveDataProvider('Validations',array(
				'criteria'=>array(
					'condition'=>'t.case_id = :case_id',
					'params'=>array(
						':case_id'=>$_GET['id'],
					),
					'order'=>'t.validation_id',
				),
			));
			
			// DataProvider for Tasks relateds
			$dataProviderTasks = new CActiveDataProvider('Tasks',array(
				'criteria'=>array(
					'condition'=>'t.case_id = :case_id',//AND t.status_id NOT IN ('.implode(',',array(Status::STATUS_CANCELLED,Status::STATUS_CLOSED)).')',
					'params'=>array(
						':case_id'=>$_GET['id'],
						//':status_id'=>implode(',',array(Status::STATUS_CANCELLED,Status::STATUS_CLOSED)),
					),
					'order'=>'t.task_id',
				),
			));
			
			$this->render('view',array(
				'model'=>$this->loadModel(),
				'NormalSecuense'=>$NormalSecuense,
				'AlternativeSecuense'=>$AlternativeSecuense,
				'ExceptionSecuense'=>$ExceptionSecuense,
				'validations'=>$Validations,
				'dataProviderTasks'=>$dataProviderTasks,
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
		// check if user has permissions to createCases
		if(Yii::app()->user->checkAccess('createCases'))
		{
			// create Cases object model
			$model = new Cases;

			// if Cases form exist and was sent
			if(isset($_POST['Cases']))
			{
				// set form elements to Budgets model attributes
				$model->attributes=$_POST['Cases'];
				$model->case_date = date("Y-m-d");
				$model->project_id = Yii::app()->user->getState('project_selected');
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'CaseCreated',
						'log_resourceid' => $model->primaryKey,
						'log_type' => Logs::LOG_CREATED,
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);

					// to prevent F5 keypress, redirect to create page
					$this->redirect(array('view','id'=>$model->case_id));
				}
			}

			$this->render('create',array(
				'model'=>$model,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Updates a particular model.
	 * @param integer $id the ID of the model to be updated
	 * @return update view
	 */
	public function actionUpdate($id)
	{
		// check if user has permissions to updateCases
		if(Yii::app()->user->checkAccess('updateCases'))
		{
			// get Cases object from $_GET[id] parameter
			$model = $this->loadModel($id);
			
			// only managers can update Cases
			if (Yii::app()->user->IsManager)
			{
				// if Cases form exist
				if(isset($_POST['Cases']))
				{
					// set form elements to Cases model attributes
					$model->attributes=$_POST['Cases'];
					
					// validate and save
					if($model->save())
					{
						// save log
						$attributes = array(
							'log_date' => date("Y-m-d G:i:s"),
							'log_activity' => 'CaseUpdated',
							'log_resourceid' => $model->case_id,
							'log_type' => Logs::LOG_UPDATED,
							'user_id' => Yii::app()->user->id,
							'module_id' => Yii::app()->controller->id,
							'project_id' => $model->project_id,
						);
						Logs::model()->saveLog($attributes);
						
						// to prevent F5 keypress, redirect to view page
						$this->redirect(array('view','id'=>$model->case_id));
					}
				}

				$this->render('update',array(
					'model'=>$model,
				));
			}
			else
				Yii::app()->controller->redirect(array("cases/view", 'id'=>$model->case_id));
		}
		else
			throw new CHttpException(403, Yii::t('cases', '403_ErrorCase'));
	}

	/**
	 * Lists all models.
	 * @return index view
	 */
	public function actionIndex()
	{
		// check if user has permission to indexCases
		if(Yii::app()->user->checkAccess('indexCases'))
		{
			// create Cases form search
			$model = new CasesSearchForm;
			$model->search();
			$model->unsetAttributes();  // clear any default values
			
			// set model attributes from Cases form
			if(isset($_GET['CasesSearchForm']))
				$model->attributes=$_GET['CasesSearchForm'];
			
			$this->render('index',array(
				'model'=>$model,
				'status'=>Status::model()->findAllRequired(true)
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param $id to compare with budget_id
	 * @return CActiveRecord Cases
	 */
	public function loadModel()
	{		
		if($this->_model===null)
		{
			if(isset($_REQUEST['id']))
				$this->_model=Cases::model()->findByPk((int)$_REQUEST['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='cases-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 * @return CModel validation to form elements
	 */
	public function actiongetCases()
	{
		$data = Cases::model()->findCasesByProject((int)$_POST['projectid']);

		$data=CHtml::listData($data,'case_id','CaseTitle');
		echo CHtml::tag('option', array('value'=>''),Yii::t('cases', 'selectOption'),true);
		foreach($data as $value=>$name)
		{
			echo CHtml::tag('option', array('value'=>$value),CHtml::encode($name),true);
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
				$response = (Cases::model()->countCasesByProject((int)$_GET['id'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
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
