<?php

/**
 * This is the model class for table "stb_authassignments".
 *
 * The followings are the available columns in table 'stb_authassignments':
 * @property string $itemname
 * @property string $userid
 * @property string $bizrule
 * @property string $data
 *
 * The followings are the available model relations:
 */
class Authassignments extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Authassignments the static model class
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
		return 'stb_authAssignments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('itemname, userid', 'required'),
			array('itemname, userid', 'length', 'max'=>64),
			array('bizrule, data', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('itemname, userid, bizrule, data', 'safe', 'on'=>'search'),
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
			'itemname' => 'Itemname',
			'userid' => 'Userid',
			'bizrule' => 'Bizrule',
			'data' => 'Data',
		);
	}
}