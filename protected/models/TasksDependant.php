<?php

/**
 * This is the model class for table "tb_tasksDependant".
 *
 * The followings are the available columns in table 'tb_tasksDependant':
 * @property integer $taskDependant_id
 * @property integer $taskDependant_task_id
 * @property integer $task_id
 */
class TasksDependant extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TasksDependant the static model class
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
		return 'tb_tasksDependant';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('taskDependant_task_id, task_id', 'required'),
			array('taskDependant_task_id, task_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('taskDependant_id, taskDependant_task_id, task_id', 'safe', 'on'=>'search'),
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
			'taskDependant_id' => 'Task Dependant',
			'taskDependant_task_id' => 'Task Dependant Task',
			'task_id' => 'Task',
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

		$criteria->compare('taskDependant_id',$this->taskDependant_id);
		$criteria->compare('taskDependant_task_id',$this->taskDependant_task_id);
		$criteria->compare('task_id',$this->task_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
}