<?php

/**
 * This is the model class for table "tb_projects_has_tb_users".
 *
 * The followings are the available columns in table 'tb_projects_has_tb_users':
 * @property integer $task_id
 * @property integer $user_id
 * @property integer $client_id
 *
 * The followings are the available model relations:
 */
class UsersHasTasksForm extends CFormModel
{
	public $task_id;
	public $user_id;
	public $client_id;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task_id, user_id, client_id', 'required'),
			array('task_id, user_id, client_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('task_id, user_id, client_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'task_id' => 'Task',
			'user_id' => 'User',
			'client_id' => 'Client',
		);
	}
}