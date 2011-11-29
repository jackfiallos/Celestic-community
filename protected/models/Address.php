<?php

/**
 * This is the model class for table "tb_address".
 *
 * The followings are the available columns in table 'tb_address':
 * @property integer $address_id
 * @property string $address_text
 * @property string $address_postalCode
 * @property string $address_phone
 * @property string $address_web
 * @property integer $city_id
 */
class Address extends CActiveRecord
{
	public $city_name;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Address the static model class
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
		return 'tb_address';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('address_text', 'required', 'on'=>'update'),
			array('city_id', 'numerical', 'integerOnly'=>true),
			array('address_text', 'length', 'max'=>250, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('address_phone', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('address_web', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('address_postalCode', 'length', 'max'=>6, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('address_web', 'url', 'message'=>Yii::t('inputValidations','UrlValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('address_id, address_text, address_postalCode, address_phone, address_web, city_id', 'safe', 'on'=>'search'),
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
			'City'=>array(self::BELONGS_TO, 'City', 'city_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'address_id' => Yii::t('address','address_id'),
			'address_text' => Yii::t('address','address_text'),
			'address_postalCode' => Yii::t('address','address_postalCode'),
			'address_phone' => Yii::t('address','address_phone'),
			'address_web' => Yii::t('address','address_web'),
			'city_id' => Yii::t('address','city_id'),
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

		$criteria->compare('address_id',$this->address_id);
		$criteria->compare('address_text',$this->address_text,true);
		$criteria->compare('address_postalCode',$this->address_postalCode,true);
		$criteria->compare('address_phone',$this->address_phone,true);
		$criteria->compare('address_web',$this->address_web,true);
		$criteria->compare('city_id',$this->city_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('address_text', 'address_postalCode', 'address_phone', 'address_web'),
			),
		);
	}
}