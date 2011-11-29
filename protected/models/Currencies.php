<?php

/**
 * This is the model class for table "tb_currencies".
 *
 * The followings are the available columns in table 'tb_currencies':
 * @property integer $currency_id
 * @property string $currency_sign
 * @property string $currency_code
 *
 * The followings are the available model relations:
 */
class Currencies extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Currencies the static model class
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
		return 'tb_currencies';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('currency_sign, currency_code', 'required'),
			array('currency_sign, currency_code', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('currency_id, currency_sign, currency_code', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'currency_id' => 'Currency',
			'currency_sign' => 'Currency Sign',
			'currency_code' => 'Currency Code',
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

		$criteria->compare('currency_id',$this->currency_id);
		$criteria->compare('currency_sign',$this->currency_sign,true);
		$criteria->compare('currency_code',$this->currency_code,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('currency_sign', 'currency_code'),
			),
		);
	}
}