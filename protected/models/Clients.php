<?php

/**
 * This is the model class for table "tb_clients".
 *
 * The followings are the available columns in table 'tb_clients':
 * @property integer $client_id
 * @property integer $user_id
 */
class Clients extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Clients the static model class
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
		return 'tb_clients';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('client_id, user_id', 'safe', 'on'=>'search'),
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
			'Users'=>array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'client_id' => 'Client',
			'user_id' => 'User',
			'user_name' => 'Name',
			'user_lastname' => 'Lastname',
			'user_email' => 'Email',
			'user_admin' => 'Is Admin',
			'user_password' => 'Password',
			'user_active' => 'Active',
			'account_id' => 'Account',
			'address_id' => 'Address',
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

		$criteria->compare('client_id',$this->client_id);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	
	
	public function countClientsByAccount($client_id, $account_id)
	{
		return Clients::model()->count(array(
			'condition'=>'t.client_id = :client_id AND Users.account_id = :account_id',
			'params'=>array(
				':account_id' => $account_id,
				':client_id' => $client_id
			),
			'with'=>array(
				'Users'
			),
		));
	}
}