<?php
/**
 * ValidateUser class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Extended class from CWebUser used to get information from current user logged
 *
 **/
class ValidateUser extends CWebUser
{
	/**
	 * Obtain user account_id
	 * By default user_id as unique param
	 * @return integer account_id value
	 */
	public function getAccountid()
	{
		$Users = Users::model()->findByPk(Yii::app()->user->id);
		return (int)$Users->account_id;
	}
	
	/**
	 * Obtain user projects list
	 * By default user_id as unique param
	 * @return model of projects
	 */
	public function getProjects()
	{
		$projects = Projects::model()->findMyProjects(Yii::app()->user->id);
		return $projects;
	}
	
	/**
	 * Determine if loqued user is account administrator
	 * @return boolean
	 */
	public function getIsAdministrator()
	{
		return (bool)Users::model()->findByPk(Yii::app()->user->id)->user_admin;
	}
	
	/**
	 * Obtain user complete name
	 * By default user_id as unique param
	 * @return string Complete name of current user
	 */
	public function getCompleteName()
	{
		$Users = Users::model()->findByPk(Yii::app()->user->id);
		return $Users->CompleteName;
	}
	
	/**
	 * Return a value indicating whether the user is or not manager
	 * By default user_id as unique param
	 * @return boolean value indicating wheter is or not manager
	 */
	public function getIsManager()
	{
		$Users = Users::model()->with('ClientsManagers')->count(array(
			'condition'=>'ClientsManagers.project_id = :project_id AND t.user_id = :user_id AND ClientsManagers_ClientsManagers.isManager = 1',
			'params'=>array(
				':project_id'=>Yii::app()->user->getState('project_selected'),
				':user_id'=>Yii::app()->user->id
			),
			'together'=>true,
			'order'=>'t.user_name',
		));
		
		return (bool)$Users;
	}
	
	/**
	 * Return a value indicating whether the user is or not client
	 * By default user_id as unique param
	 * @return boolean value indicating wheter is or not client
	 */
	public function getisClient()
	{
		$Users = Clients::model()->with("Users")->count(array(
			'select' => 't.user_id',
			'condition' => 'Users.user_id = :user_id',
			'params' => array(
				':user_id' => Yii::app()->user->id,
			),
		));
		return (bool)$Users;
	}
	
	/**
	 * Return a value indicating whether the user has joined or not into specific task
	 * @param int $id
	 * @return boolean value
	 */
	public function getHasJoined($id = 0)
	{
		$exist = false;
		$task_id = ($id == 0) ? $_GET['id'] : $id;
		$exist = UsersHasTasks::model()->exists('t.user_id = :user_id AND t.task_id = :task_id', array(
			':user_id' => Yii::app()->user->id,
			':task_id' => $task_id)
		);
		return (bool)$exist;
	}
}
?>