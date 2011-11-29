<?php

/**
 * This is the model class for table "tb_notifications".
 *
 * The followings are the available columns in table 'tb_notifications':
 * @property integer $notification_id
 * @property string $notification_action
 * @property integer $user_id
 * @property integer $project_id
 * @property integer $module_id
 *
 * The followings are the available model relations:
 * @property TbUsers $user
 * @property TbProjects $project
 * @property TbModules $module
 */
class Notifications extends CActiveRecord
{
	const ON_CREATE = 1;
	const ON_UPDATE = 2;
	const ON_DELETE = 3;
	const ON_COMMENT = 4;
	const ON_CHANGESTATUS = 5;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Notifications the static model class
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
		return 'tb_notifications';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('notification_action, user_id, project_id, module_id', 'required'),
			array('notification_action, user_id, project_id, module_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('notification_id, notification_action, user_id, project_id, module_id', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'TbUsers', 'user_id'),
			'project' => array(self::BELONGS_TO, 'TbProjects', 'project_id'),
			'module' => array(self::BELONGS_TO, 'TbModules', 'module_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'notification_id' => 'Notification',
			'notification_action' => 'Notification Action',
			'user_id' => 'User',
			'project_id' => 'Project',
			'module_id' => 'Module',
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

		$criteria->compare('notification_id',$this->notification_id);
		$criteria->compare('notification_action',$this->notification_action,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('module_id',$this->module_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}