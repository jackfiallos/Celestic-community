<?php
/**
 * UserIdentity class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description	
 * Represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 *
 **/
class UserIdentity extends CUserIdentity
{
	const ERROR_USER_INACTIVE = 3;
	/**
	 * @var set as private unique user id
	 */
	private $_id;
		
	/**
	 * Authenticates a user.
	 * @return <boolean> whether authentication succeeds.
	 */
	public function authenticate()
	{
		$criteria = new CDbCriteria();
		$criteria->condition = 'LOWER(user_email) = :user_email';
		$criteria->params = array(
			':user_email'=>strtolower($this->username),
		);
		
		$users = Users::model()->find($criteria);
		
		if($users === null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($users->user_password != md5($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else if($users->user_active == false)
			$this->errorCode=self::ERROR_USER_INACTIVE;
		else
		{
			$this->errorCode=self::ERROR_NONE;
			$this->_id = $users->user_id;
			
			// Set state variables
			$this->setState('user_email', $users->user_email);
			$this->setState('user_lastLogin', $users->user_lastLogin);
			$this->setState('refer', Yii::app()->user->getState('project_selected'));
			
			// Save last access to application
			$users->user_lastLogin = date("Y-m-d G:i:s");
			$users->save(false);
		}
		
		//initializes the authManager
		$auth=Yii::app()->authManager;
		
		return !$this->errorCode;
	}
	
	/**
	 * Return user unique id
	 * @return <integer> user_id
	 */
	public function getId()
	{
		return (int)$this->_id;
	}
}