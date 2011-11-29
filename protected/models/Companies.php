<?php

/**
 * This is the model class for table "tb_companies".
 */
class Companies extends CActiveRecord
{
	/**
	 * The followings are the available columns in table 'tb_companies':
	 * @var integer $company_id
	 * @var string $company_name
	 * @var string $company_url
	 * @var string $company_uniqueId
	 * @var double $company_latitude
	 * @var double $company_longitude
	 * @var integer $address_id
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return Companies the static model class
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
		return 'tb_companies';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_name', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('company_uniqueId', 'required', 'on'=>'update', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('address_id', 'numerical', 'integerOnly'=>true),
			array('company_latitude, company_longitude', 'numerical'),
			array('company_name', 'length', 'max'=>100, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('company_name', 'length', 'min'=>9, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('company_uniqueId', 'length', 'max'=>20, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('company_url', 'length', 'max'=>100, 'message'=>Yii::t('inputValidations','MaxValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('company_id, company_name, company_url, company_uniqueId, company_latitude, company_longitude, address_id', 'safe', 'on'=>'search'),
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
			'Users'=>array(self::MANY_MANY, 'Users', 'tb_companies_has_tb_users(user_id,company_id)'),
			'Cusers'=>array(self::MANY_MANY, 'Users', 'tb_companies_has_tb_users(company_id,user_id)'),
			'Address'=>array(self::BELONGS_TO, 'Address', 'address_id'),
			'Projects'=>array(self::HAS_MANY, 'Projects', 'company_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'company_id' => Yii::t('companies','company_id'),
			'company_name' => Yii::t('companies','company_name'),
			'company_url' => Yii::t('companies','company_url'),
			'company_uniqueId' => Yii::t('companies','company_uniqueId'),
			'company_latitude' => Yii::t('companies','company_latitude'),
			'company_longitude' => Yii::t('companies','company_longitude'),
			'address_id' => Yii::t('companies','address_id'),
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
		$criteria->compare('company_name',$this->company_name,true);
		$criteria->compare('company_url',$this->company_url,true);
		$criteria->compare('company_uniqueId',$this->company_uniqueId,true);
		$criteria->compare('company_latitude',$this->company_latitude);
		$criteria->compare('company_longitude',$this->company_longitude);
		$criteria->compare('address_id',$this->address_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('company_name', 'company_url', 'company_uniqueId', 'company_latitude', 'company_longitude'),
			),
		);
	}
	
	public function findCompanyList($user_id)
	{
		return Companies::model()->with('Cusers.Accounts')->findAll(array(
			'condition'=>'Cusers.user_id= :user_id',
			'params'=>array(
				':user_id' => $user_id,
			),
			'together' => true,
		));
	}
	
	public function hasCompanyRelation($user_id, $company_id)
	{
		return Companies::model()->with('Cusers.Accounts')->count(array(
			'condition'=>'Cusers.user_id= :user_id AND t.company_id = :company_id',
			'params'=>array(
				':user_id' => $user_id,
				':company_id' => $company_id,
			),
			'together' => true,
		));
	}
	
	public function countCompaniesByAccount($company_id, $account_id)
	{
		return Companies::model()->count(array(
			'condition'=>'t.company_id = :company_id AND Cusers.account_id = :account_id',
			'params'=>array(
				':account_id' => $account_id,
				':company_id' => $company_id
			),
			'with' => array(
				'Cusers'
			),
		));
	}
}