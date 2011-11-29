<?php

/**
 * This is the model class for table "tb_modules".
 *
 * The followings are the available columns in table 'tb_modules':
 * @property integer $module_id
 * @property string $module_name
 * @property string $module_className
 * @property string $module_title
 * @property boolean $module_useNotifications
 * @property boolean $module_useSearchs
 *
 * The followings are the available model relations:
 */
class Modules extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Modules the static model class
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
		return 'tb_modules';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('module_name, module_className, module_title', 'required'),
			array('module_name, module_className, module_title', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('module_id, module_name, module_className, module_title, module_useNotifications', 'safe', 'on'=>'search'),
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
			'module_id' => 'Module',
			'module_name' => 'Module Name',
			'module_className' => 'Class Name',
			'module_title' => 'Title',
			'module_useNotifications' => 'Use Notifications',
			'module_useSearchs' => 'Use Searchs',
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

		$criteria->compare('module_id',$this->module_id);
		$criteria->compare('module_name',$this->module_name,true);
		$criteria->compare('module_className',$this->module_name,true);
		$criteria->compare('module_title',$this->module_name,true);
		$criteria->compare('module_useNotifications',$this->module_useNotifications,true);
		$criteria->compare('module_useSearchs',$this->module_useSearchs,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('module_name'),
			),
		);
	}
	
	public function searchOn()
	{
		return Modules::model()->findAll(array(
			'condition'=>'t.module_useSearchs = :module_useSearchs',
			'params'=>array(
				':module_useSearchs' => true,
			),
		));
	}
} 