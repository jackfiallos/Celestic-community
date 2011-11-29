<?php

/**
 * This is the model class for table "stb_authitemchilds".
 *
 * The followings are the available columns in table 'stb_authitemchilds':
 * @property string $parent
 * @property string $child
 *
 * The followings are the available model relations:
 */
class Authitemchilds extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Authitemchilds the static model class
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
		return 'stb_authItemChilds';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('parent, child', 'required'),
			array('parent, child', 'length', 'max'=>64),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('parent, child', 'safe', 'on'=>'search'),
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
			'parent' => 'Parent',
			'child' => 'Child',
		);
	}
}