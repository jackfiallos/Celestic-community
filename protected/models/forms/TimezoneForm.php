<?php

/**
 * This is the model class for table "tb_projects_has_tb_users".
 *
 * The followings are the available columns in table 'tb_projects_has_tb_users':
 * @property integer $project_id
 *
 * The followings are the available model relations:
 */
class TimezoneForm extends CFormModel
{
	public $timezone;
	public $account_id;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('timezone, account_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('account_id', 'numerical', 'integerOnly'=>true),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'timezone' => Yii::t('localization','timezone'),
			'account_id' => Yii::t('localization','account_id'),
		);
	}
	
	public function UpdateAccount()
	{
		$account = Accounts::model()->findByPk(Yii::app()->user->getAccountid());
		
		$model = new TimezoneForm;
		$model->timezone = $account->timezone_id;
		$model->account_id = $account->account_id;
		
		return $model;
	}
}