<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ForgottenPasswordForm extends CFormModel
{
	public $user_email;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('user_email', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
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
			'user_email'=>Yii::t('users','user_email'),
		);
	}
}