<?php

/**
 * This is the model class for table "tb_users_has_tb_tasks".
 *
 * The followings are the available columns in table 'tb_users_has_tb_tasks':
 * @property integer $user_id
 * @property integer $task_id
 *
 * The followings are the available model relations:
 * @property TbTasks $task
 * @property TbUsers $user
 */
class UsersHasTasks extends CActiveRecord
{	
	/**
	 * Returns the static model of the specified AR class.
	 * @return UsersHasTasks the static model class
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
		return 'tb_users_has_tb_tasks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, task_id', 'required'),
			array('users_has_tasks_id, user_id, task_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, task_id', 'safe', 'on'=>'search'),
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
			'task' => array(self::BELONGS_TO, 'TbTasks', 'task_id'),
			'user' => array(self::BELONGS_TO, 'TbUsers', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'users_has_tasks_id'=>'UHT',
			'user_id' => 'User',
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

		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('task_id',$this->task_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function findBeforeDelete($user_id, $task_id)
	{
		return UsersHasTasks::model()->find(array(
			'condition'=>'t.user_id = :user_id AND t.task_id = :task_id',
			'params'=>array(
				':user_id'=>$user_id,
				':task_id'=>$task_id
			),
		));
	}
}