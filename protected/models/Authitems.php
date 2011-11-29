<?php

/**
 * This is the model class for table "stb_authitems".
 *
 * The followings are the available columns in table 'stb_authitems':
 * @property string $name
 * @property integer $type
 * @property string $description
 * @property string $bizrule
 * @property string $data
 * @property integer $account_id
 *
 * The followings are the available model relations:
 */
class Authitems extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Authitems the static model class
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
		return 'stb_authItems';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type, description, account_id', 'required'),
			array('type, account_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>64),
			array('description, bizrule, data, account_id', 'safe'),
			//array('name', 'unique'),
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
			'name' => Yii::t('authitems','name'),
			'type' => Yii::t('authitems','type'),
			'description' => Yii::t('authitems','description'),
			'bizrule' => Yii::t('authitems','bizrule'),
			'data' => Yii::t('authitems','data'),
			'account_id' => Yii::t('authitems','account_id'),
		);
	}
}