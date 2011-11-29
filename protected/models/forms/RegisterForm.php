<?php

/**
 * RegisterForm class.
 */
class RegisterForm extends CFormModel
{
	public $user_name;
	public $user_lastname;
	public $user_email;
	public $user_password;
	public $user_passwordRepeat;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('user_name, user_lastname, user_email, user_password, user_passwordRepeat', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('user_password', 'compare', 'compareAttribute'=>'user_passwordRepeat', 'message'=>Yii::t('inputValidations','CompareValidation')),
			array('user_email', 'email', 'message'=>Yii::t('inputValidations','EmailValidation')),
		);
	}

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'user_name' => Yii::t('users','user_name'),
			'user_lastname' => Yii::t('users','user_lastname'),
			'user_email' => Yii::t('users','user_email'),
			'user_password' => Yii::t('users','user_password'),
			'user_passwordRepeat' => Yii::t('users','user_passwordRepeat'),
			'user_accountManager' => Yii::t('users','user_password')
		);
	}
	
	public function create()
	{
		$account = new Accounts();
		$account->account_name = date("mdYGis");
		if($account->save(false))
		{
			$user = new Users();
			$user->user_name = $this->user_name;
			$user->user_lastname = $this->user_lastname;
			$user->user_email = strtolower($this->user_email);
			$user->user_admin = 1;
			$user->user_active = 1;
			$user->user_accountManager = 1;
			$user->account_id = $account->primaryKey;
			$passBeforeMD5 = $this->user_password;
			$user->user_password = md5($this->user_password);
			
			if($user->save(false))
			{
				/*$auth=Yii::app()->authManager;
				$auth->assign('SuperUser',$user->primaryKey, 'return !Yii::app()->user->isGuest;', 'N;');
				
				$str = Yii::app()->controller->renderPartial('//templates/account/registration',array(
					'user' => $user,
					'passBeforeMD5' => $passBeforeMD5,
					'applicationName' => Yii::app()->name,
					'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
				),true);
				
				$subject = Yii::t('email','NewAccountRegistration');
				
				Yii::import('application.extensions.phpMailer.yiiPhpMailer');
				$mailer = new yiiPhpMailer;
				$mailer->Ready($subject, $str, array('name'=>$user->CompleteName,'email'=>$user->user_email), Emails::PRIORITY_NORMAL);
				*/
				return true;
			}
			else
				return false;
		}
		else
			return false;
	}
}