<?php

/**
 * This is the model class for table "tb_validations".
 *
 * The followings are the available columns in table 'tb_validations':
 * @property integer $validation_id
 * @property string $validation_field
 * @property string $validation_description
 * @property integer $case_id
 *
 * The followings are the available model relations:
 */
class Validations extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Validations the static model class
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
		return 'tb_validations';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('validation_field, validation_description, case_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('case_id', 'numerical', 'integerOnly'=>true),
			array('validation_field', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('validation_description', 'length', 'max'=>150, 'message'=>Yii::t('inputValidations','MaxValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('validation_id, validation_field, validation_description, case_id', 'safe', 'on'=>'search'),
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
			'Cases'=>array(self::BELONGS_TO, 'Cases', 'case_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'validation_id' => Yii::t('validations','validation_id'),
			'validation_field' => Yii::t('validations','validation_field'),
			'validation_description' => Yii::t('validations','validation_description'),
			'case_id' => Yii::t('validations','case_id'),
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

		$criteria->compare('validation_id',$this->validation_id);
		$criteria->compare('validation_field',$this->validation_field,true);
		$criteria->compare('validation_description',$this->validation_description,true);
		$criteria->compare('case_id',$this->case_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}