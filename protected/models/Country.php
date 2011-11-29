<?php

/**
 * This is the model class for table "stb_country".
 *
 * The followings are the available columns in table 'stb_country':
 * @property integer $country_id
 * @property string $country_name
 * @property string $country_continent
 * @property string $country_region
 */
class Country extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Country the static model class
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
		return 'stb_country';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('country_name, country_continent, country_region', 'required'),
			array('country_name, country_continent, country_region', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('country_id, country_name, country_continent, country_region', 'safe', 'on'=>'search'),
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
			'country_id' => Yii::t('country','country_id'),
			'country_name' => Yii::t('country','country_name'),
			'country_continent' => Yii::t('country','country_continent'),
			'country_region' => Yii::t('country','country_region'),
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

		$criteria->compare('country_id',$this->country_id);
		$criteria->compare('country_name',$this->country_name,true);
		$criteria->compare('country_continent',$this->country_continent,true);
		$criteria->compare('country_region',$this->country_region,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}