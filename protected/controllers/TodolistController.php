<?php
/**
 * TodolistController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for tasks todolist 
 *
 **/
class TodolistController extends Controller
{
	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
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
					'save',
					'check',
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
	
	public function actionRearrange()
	{		
		if (Yii::app()->user->getHasJoined($_GET['task_id']))
		{
			$updateVals = array();
			$key_value = $_GET['positions'];
			foreach($key_value as $k=>$v)
			{
				$strVals[] = 'WHEN '.(int)$v.' THEN '.((int)$k+1);
			}
			
			if(!$strVals) throw new Exception("No data!");
			
			$command = Yii::app()->db->createCommand('UPDATE tb_todolist SET todolist_position = CASE todolist_id '.implode(' ',$strVals).' ELSE todolist_position END')->execute();
			//$command->update('tb_todolist', array('todolist_position'=>'CASE todolist_id '.implode(' ',$strVals).' ELSE todolist_position END'))->query();
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
		Yii::app()->end();
	}
	
	public function actionCreate()
	{		
		if (Yii::app()->user->getHasJoined((int)Yii::app()->request->getParam('task_id',0)))
		{
			$list = Todolist::model()->find(array('limit'=>1,'order'=>'t.todolist_position DESC'));
			
			if ($list == null)
				$position = 1;
			else
				$position = $list->todolist_position + 1;
			
			$model = new Todolist();
			$model->todolist_position = $position;
			$model->todolist_text = Yii::t('site','defaultTodolistText');
			$model->task_id = (int)Yii::app()->request->getParam('task_id',0);
			//$model->save(false);
			echo '<li id="todo-'.$model->primaryKey.'" class="todo">
				<div class="actionsleft" style="float: left;padding-right:3px;">
					<a href="#" class="check" title="'.Yii::t('site','CheckLink').'">'.Yii::t('site','CheckLink').'</a>
				</div>
				<div id="lst'.$model->primaryKey.'" class="textlist">'.$model->todolist_text.'</div>
				<div class="actions">
					<a href="#" class="edit" title="'.Yii::t('site','EditLink').'">'.Yii::t('site','EditLink').'</a>
					<a href="#" class="delete" title="'.Yii::t('site','DeleteLink').'">'.Yii::t('site','DeleteLink').'</a>
				</div>
			</li>';
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
		Yii::app()->end();
	}
	
	public function actionSave()
	{
		if (Yii::app()->user->getHasJoined($_GET['task_id']))
		{
			$model = Todolist::model()->findByPk($_GET['id']);
			$model->todolist_text = $_GET['text'];
			$model->save(false);
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
		Yii::app()->end();
	}
	
	public function actionCheck()
	{
		if (Yii::app()->user->getHasJoined($_GET['task_id']))
		{
			$model = Todolist::model()->findByPk($_GET['id']);
			$model->todolist_checked = 1;
			$bool = $model->save();
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
		Yii::app()->end((bool)$bool);
	}
	
	public function actionDelete($id)
	{		
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			if ($this->loadModel($id)->delete())
				echo CJSON::encode(array('success'=>true));
			else
				echo CJSON::encode(array('success'=>false));
		}
		else
			throw new CHttpException(400, Yii::t('site', '400_Error'));
		Yii::app()->end();
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Todolist::model()->find(array(
			'condition'=>'t.task_id = :task_id AND t.todolist_id = :todolist_id',
			'params'=>array(
				':todolist_id'=>$_POST['id'],
				':task_id'=>$_POST['task_id'],
			),
		));
		if($model===null)
			throw new CHttpException(404, Yii::t('site', '404_Error'));
		return $model;
	}
}