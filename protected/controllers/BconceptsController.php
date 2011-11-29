<?php
/**
 * BconceptsController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for BudgetsConcepts 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class BconceptsController extends Controller
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
					'create',
					'update',
					'delete'
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
	 * Creates a new model.
	 * @return create view
	 */
	public function actionCreate()
	{
		// check if user has permission to create budgetsConcepts
		if(Yii::app()->user->checkAccess('createBudgetConcepts'))
		{
			// verify owner param exist
			if ((isset($_GET['owner'])) && (!empty($_GET['owner'])))
			{
				// check if budget parent has status pending and the project_id it's the same project_id selected
				$budget = Budgets::model()->findByPk((int)$_GET['owner']);
				if (($budget->status_id == Status::STATUS_PENDING) && ($budget->project_id == Yii::app()->user->getState('project_selected')))
				{
					// create BudgetsConcepts object to made form elements to save
					$model = new BudgetsConcepts;
					
					// if BudgetsConcepts form exist
					if(isset($_POST['BudgetsConcepts']))
					{
						// set form elements to BudgetsConcepts model attributes
						$model->attributes=$_POST['BudgetsConcepts'];
						
						// valdiate and save if no errors
						if($model->save())
						{
							// Save to log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'BudgetConceptCreated',
								'log_resourceid' => $budget->budget_id,
								'log_type' => Logs::LOG_CREATED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id,
								'project_id' => $budget->Projects->project_id,
							);
							Logs::model()->saveLog($attributes);
							
							// set successfully message to ok
							Yii::app()->user->setFlash('bconcepts',Yii::t('budgetsConcepts','ConceptSavedSuccessfully'));

							// to prevent F5 keyboard, redirect to create page
							$this->redirect(array('create','owner'=>$model->budget_id));
						}
					}

					// output create page
					$this->render('create',array(
						'model'=>$model,
					));
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
	 * Updates a particular model.
	 * @return update view
	 */
	public function actionUpdate()
	{
		// check if user has permission to update budgetsConcepts
		if(Yii::app()->user->checkAccess('updateBudgetConcepts'))
		{
			// verify owner param exist
			if ((isset($_GET['owner'])) && (!empty($_GET['owner'])))
			{
				// check if budget parent has status pending and the project_id it's the same project_id selected
				$budget = Budgets::model()->findByPk((int)$_GET['owner']);
				if (($budget->status_id == Status::STATUS_PENDING) && ($budget->project_id == Yii::app()->user->getState('project_selected')))
				{
					// get BudgetsConcepts object from $_GET['id'] parameter
					$model = $this->loadModel();
					
					// verify that exist relation between budget and budgetConcept
					if ($model->budget_id == $budget->budget_id)
					{
						// if BudgetsConcepts form exist
						if(isset($_POST['BudgetsConcepts']))
						{
							// set form elements to BudgetsConcepts model attributes
							$model->attributes=$_POST['BudgetsConcepts'];
							
							// valdiate and save if no errors
							if($model->save())
							{
								// Save log
								$attributes = array(
									'log_date' => date("Y-m-d G:i:s"),
									'log_activity' => 'BudgetConceptUpdated',
									'log_resourceid' => $budget->budget_id,
									'log_type' => Logs::LOG_UPDATED,
									'user_id' => Yii::app()->user->id,
									'module_id' => Yii::app()->controller->id,
									'project_id' => $budget->Projects->project_id,
								);
								Logs::model()->saveLog($attributes);
								
								// to prevent F5 keyboard redirect to index page of BudgetConcept
								$this->redirect(array('index','owner'=>$model->budget_id));
							}
						}
		
						$this->render('update',array(
							'model'=>$model,
						));
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
	 * Deletes a particular model.
	 * @return json object {amount,success}
	 */
	public function actionDelete()
	{
		// check if user has permission to delete budgetsConcepts
		if(Yii::app()->user->checkAccess('deleteBudgetConcepts'))
		{
			// verify is a POST request / YII_CSRF_TOKEN
			if(Yii::app()->request->isPostRequest)
			{
				// verify owner param exist
				if ((isset($_GET['owner'])) && (!empty($_GET['owner'])))
				{
					// verify if BudgetConcept item parent Budget has status = pending and budget project_id its same as project_id selected
					$budget = Budgets::model()->with('Projects.Company')->together()->findByPk((int)$_GET['owner']);
					if (($budget->status_id == Status::STATUS_PENDING) && ($budget->project_id == Yii::app()->user->getState('project_selected')))
					{
						// we only allow deletion via POST request
						$concept = $this->loadModel();
						$result = array();
						$result['success'] = false;
						
						// delete concept
						if ($concept->delete())
						{
							// Save log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'BudgetConceptDeleted',
								'log_resourceid' => $budget->budget_id,
								'log_type' => Logs::LOG_DELETED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id,
								'project_id' => $budget->Projects->project_id,
							);
							Logs::model()->saveLog($attributes);
							
							// amount it's used to update amount budget and returner via json object
							$result['amount'] = Yii::app()->numberFormatter->formatCurrency(CHtml::encode($budget->Cost), $budget->Projects->Currency->currency_code);
							$result['success'] = true;
						}
						// output json object
						echo CJSON::encode($result);
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
	 * Lists all models.
	 * @return index view
	 */
	public function actionIndex()
	{
		// check if user has permission to indexbudgetsConcepts
		if(Yii::app()->user->checkAccess('indexBudgetConcepts'))
		{
			// verify owner param exist
			if ((isset($_GET['owner'])) && (!empty($_GET['owner'])))
			{
				// load budget object with owner param 
				$budget = Budgets::model()->with('Projects.Company')->together()->findByPk((int)$_GET['owner']);
				
				// get all budgetsConcepts using budget_id param
				$criteria = new CDbCriteria;
				$criteria->condition = 'budget_id = :budget_id';
				$criteria->params = array(
					':budget_id'=>$budget->budget_id,
				);
				
				$dataProvider = new CActiveDataProvider('BudgetsConcepts', array(
					'criteria'=>$criteria,
				));
				
				// count all clients with manager permission for this project
				$clients = Users::model()->with('Managers','Clients')->count(array(
					'condition'=>'Managers.project_id = :project_id AND Clients.client_id IS NOT NULL',
					'params'=>array(
						':project_id' => $budget->project_id,
					),
					'together'=>true,
					'group'=>'t.user_id',
				));
				
				$this->render('index',array(
					'dataProvider'=>$dataProvider,
					'model'=>$budget,
					'hasClientsManagers'=>($clients > 0) ? true : false,
				));
			}
			else
				throw new CHttpException(403, Yii::t('site', '403_Error'));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param $_GET['id'] to compare with budgetsConcept_id
	 * @return BudgetsConcepts model
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if((isset($_GET['id'])) && (!empty($_GET['id'])))
				$this->_model=BudgetsConcepts::model()->findbyPk((int)$_GET['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='budgets-concepts-form')
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
			if (in_array($action->id, array('update','delete','index','create')))
				$response = (Budgets::model()->countBudgetsByProject((int)$_GET['owner'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
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
