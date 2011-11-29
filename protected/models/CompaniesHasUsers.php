<?php

/**
 * This is the model class for table "tb_companies_has_tb_users".
 */
class CompaniesHasUsers extends CActiveRecord
{
	public function primaryKey()
	{
		return 'user_id';
	}
	/**
	 * The followings are the available columns in table 'tb_companies_has_tb_users':
	 * @var integer $company_id
	 * @var integer $user_id
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return CompaniesHasUsers the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tb_companies_has_tb_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id, user_id', 'required'),
			array('company_id, user_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('company_id, user_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'Users'=>array(self::HAS_MANY, 'Users', 'user_id'),
			'Projects'=>array(self::HAS_MANY, 'Projects', 'project_id'),
			'Company'=>array(self::BELONGS_TO, 'Companies', 'company_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'company_id' => 'Company',
			'user_id' => 'User',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('company_id',$this->company_id);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function HasUsersAvailablesToAdd($account_id, $company_id)
	{
		$Users = Users::model()->findUsersByAccount($account_id, $company_id);
		if (count($Users) > 0)
			return true;
		return false;
	}
	
	public function HasClientsAvailablesToAdd($account_id, $company_id)
	{
		$Clients = Users::model()->findClientsByAccount($account_id, $company_id);
		if (count($Clients) > 0)
			return true;
		return false;
	}
}