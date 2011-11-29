<?php

/**
 * This is the model class for table "stb_city".
 *
 * The followings are the available columns in table 'stb_city':
 * @property integer $city_id
 * @property string $city_name
 * @property string $city_code
 * @property string $city_district
 * @property string $city_population
 * @property integer $country_id
 */
class City extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return City the static model class
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
		return 'stb_city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('city_name, city_code, city_district, city_population, country_id', 'required'),
			array('country_id', 'numerical', 'integerOnly'=>true),
			array('city_name, city_code, city_district, city_population', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('city_id, city_name, city_code, city_district, city_population, country_id', 'safe', 'on'=>'search'),
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
			'Country'=>array(self::BELONGS_TO, 'Country', 'country_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'city_id' => Yii::t('city','city_id'),
			'city_name' => Yii::t('city','city_name'),
			'city_code' => Yii::t('city','city_code'),
			'city_district' => Yii::t('city','city_district'),
			'city_population' => Yii::t('city','city_population'),
			'country_id' => Yii::t('city','country_id'),
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

		$criteria->compare('city_id',$this->city_id);
		$criteria->compare('city_name',$this->city_name,true);
		$criteria->compare('city_code',$this->city_code,true);
		$criteria->compare('city_district',$this->city_district,true);
		$criteria->compare('city_population',$this->city_population,true);
		$criteria->compare('country_id',$this->country_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}