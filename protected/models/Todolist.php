<?php

/**
 * This is the model class for table "tb_todolist".
 *
 * The followings are the available columns in table 'tb_todolist':
 * @property integer $todolist_id
 * @property integer $todolist_position
 * @property string $todolist_text
 * @property string $todolist_dtAdded
 * @property string $todolist_checked
 * @property integer $task_id
 */
class Todolist extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Todolist the static model class
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
		return 'tb_todolist';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('todolist_dtAdded, todolist_checked, task_id', 'required'),
			array('todolist_position, task_id', 'numerical', 'integerOnly'=>true),
			array('todolist_text', 'length', 'max'=>225),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('todolist_id, todolist_position, todolist_text, todolist_dtAdded, todolist_checked, task_id', 'safe', 'on'=>'search'),
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
			'Task'=>array(self::BELONGS_TO, 'Tasks', 'task_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'todolist_id' => 'Todolist',
			'todolist_position' => 'Todolist Position',
			'todolist_text' => 'Todolist Text',
			'todolist_dtAdded' => 'Todolist Dt Added',
			'todolist_checked' => 'Checked',
			'task_id' => 'Task',
		);
	}
	
	public function findList($task_id)
	{
		if (Todolist::model()->with('Task')->count(array(
			'condition'=>'t.task_id = :task_id',
			'params'=>array(
				':task_id'=>$task_id,
			),
			'together' => true,
		))>0)
			return true;
		else
			return false;
	}
	
	public function findActivity($task_id)
	{
		return Todolist::model()->with('Task')->findAll(array(
			'condition'=>'t.task_id = :task_id',
			'params'=>array(
				':task_id'=>$task_id,
			),
			'order'=>'t.todolist_position ASC',
			'together' => true,
		));
	}
}