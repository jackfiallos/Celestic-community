<?php
/**
 * EconceptsController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for Expense Concepts 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class EconceptsController extends Controller
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

	/**
	 * Creates a new model.
	 * @return create view
	 */
	public function actionCreate()
	{
		// check if user has permissions to createExpenseConcepts
		if(Yii::app()->user->checkAccess('createExpenseConcepts'))
		{
			// verify owner param exist
			if ((isset($_GET['owner'])) && (!empty($_GET['owner'])))
			{
				// check if expense parent has status pending and the project_id it's the same project_id selected
				$expense = Expenses::model()->findByPk((int)$_GET['owner']);
				if (($expense->status_id == Status::STATUS_PENDING) && ($expense->project_id == Yii::app()->user->getState('project_selected')))
				{
					// create new model object
					$model = new ExpensesConcepts;
		
					// use if ExpensesConcepts form was used
					if(isset($_POST['ExpensesConcepts']))
					{
						// set form field to model attributes
						$model->attributes=$_POST['ExpensesConcepts'];
						
						// validate and save
						if($model->save())
						{
							// save log
							$attributes = array(
								'log_date' => date("Y-m-d G:i:s"),
								'log_activity' => 'ExpenseConceptCreated',
								'log_resourceid' => $model->expense_id,
								'log_type' => Logs::LOG_CREATED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id,
								'project_id' => $model->Expenses->project_id,
							);
							Logs::model()->saveLog($attributes);
							
							// set return message
							Yii::app()->user->setFlash('econcepts',Yii::t('expensesConcepts','ConceptSavedSuccessfully'));
							
							// to prevent F5, redirect to create page
							$this->redirect(array('create','owner'=>$model->expense_id));
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
		// check if user has permissions to updateExpenseConcepts
		if(Yii::app()->user->checkAccess('updateExpenseConcepts'))
		{
			// verify owner param exist
			if ((isset($_GET['owner'])) && (!empty($_GET['owner'])))
			{
				// check if expense parent has status pending and the project_id it's the same project_id selected
				$expense = Expenses::model()->findByPk((int)$_GET['owner']);
				if (($expense->status_id == Status::STATUS_PENDING) && ($expense->project_id == Yii::app()->user->getState('project_selected')))
				{
					// load model object from $id param
					$model=$this->loadModel();
					
					// verify that exist relation between expense and expenseConcept
					if ($model->expense_id == $expense->expense_id)
					{
						// verify is ExpensesConcepts form was used
						if(isset($_POST['ExpensesConcepts']))
						{
							// set form elements to model attributes
							$model->attributes=$_POST['ExpensesConcepts'];
							if($model->save())
							{
								// save log
								$attributes = array(
									'log_date' => date("Y-m-d G:i:s"),
									'log_activity' => 'ExpenseConceptUpdated',
									'log_resourceid' => $model->expense_id,
									'log_type' => Logs::LOG_UPDATED,
									'user_id' => Yii::app()->user->id,
									'module_id' => Yii::app()->controller->id,
									'project_id' => $model->Expenses->project_id,
								);
								Logs::model()->saveLog($attributes);
								
								// redirect to index of expenses concepts
								$this->redirect(array('index','owner'=>$model->expense_id));
							}
						}
						
						// render update page
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
		// check if user has permissions to deleteExpenseConcepts
		if(Yii::app()->user->checkAccess('deleteExpenseConcepts'))
		{
			// verify request was call via POST/YII_CSRF_TOKEN
			if(Yii::app()->request->isPostRequest)
			{
				// verify owner param exist
				if ((isset($_GET['owner'])) && (!empty($_GET['owner'])))
				{
					// verify if ExpenseConcept item parent Expense has status = pending and expense project_id its same as project_id selected
					$expense = Expenses::model()->with('Projects.Company')->together()->findByPk((int)$_GET['owner']);
					if (($expense->status_id == Status::STATUS_PENDING) && ($expense->project_id == Yii::app()->user->getState('project_selected')))
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
								'log_activity' => 'ExpenseConceptDeleted',
								'log_resourceid' => $concept->concept_id,
								'log_type' => Logs::LOG_DELETED,
								'user_id' => Yii::app()->user->id,
								'module_id' => Yii::app()->controller->id,
								'project_id' => $concept->Expenses->project_id,
							);
							Logs::model()->saveLog($attributes);
								
							// amount it's used to update amount expense and returner via json object
							$result['amount'] = Yii::app()->numberFormatter->formatCurrency(CHtml::encode($expense->Cost), $expense->Projects->Currency->currency_code);
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
		// check if user has permission to indexExpenseConcepts
		if(Yii::app()->user->checkAccess('indexExpenseConcepts'))
		{
			// verify owner param exist
			if ((isset($_GET['owner'])) && (!empty($_GET['owner'])))
			{
				// load expense object with owner param
				$expense = Expenses::model()->with('Projects')->together()->findByPk((int)$_GET['owner']);
				
				// get all expenseConcepts using expense_id param
				$criteria = new CDbCriteria;
				$criteria->condition = 'expense_id =:expense_id';
				$criteria->params = array(
					':expense_id'=>$_GET['owner'],
				);
				
				$dataProvider=new CActiveDataProvider('ExpensesConcepts', array(
					'criteria'=>$criteria,
				));
				
				$this->render('index',array(
					'dataProvider'=>$dataProvider,
					'model'=>$expense,
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
	 * @param $_GET['id'] to compare with expenseConcept_id
	 * @return ExpenseConcepts model
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=ExpensesConcepts::model()->findbyPk((int)$_GET['id']);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='expenses-concepts-form')
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
				$response = (Expenses::model()->countExpensesByProject((int)$_GET['owner'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
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
