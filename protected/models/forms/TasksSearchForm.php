<?php

/**
 * This is the model class for table "tb_projects_has_tb_users".
 *
 * The followings are the available columns in table 'tb_projects_has_tb_users':
 * @property integer $project_id
 *
 * The followings are the available model relations:
 */
class TasksSearchForm extends CFormModel
{
	public $task_name;
	public $task_priority;
	public $taskTypes_id;
	public $status_id;
	public $project_id;
	public $milestone_id;
	public $case_id;
	public $taskStage_id;
	public $task_participant;
	private $_itemsCount;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task_name', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('task_priority, taskTypes_id, status_id, project_id, milestone_id, case_id, taskStage_id, task_participant', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('task_name, task_priority, taskTypes_id, status_id, project_id, milestone_id, case_id, taskStage_id, task_participant', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'task_name' => Yii::t('tasks','task_name'),
			'task_priority' => Yii::t('tasks','task_priority'),
			'taskTypes_id' => Yii::t('tasks','taskTypes_id'),
			'status_id' => Yii::t('tasks','status_id'),
			'project_id' => Yii::t('tasks','project_id'),
			'milestone_id' => Yii::t('tasks','milestone_id'),
			'case_id' => Yii::t('tasks','case_id'),
			'taskStage_id' => Yii::t('tasks','taskStage_id'),
			'task_participant' => Yii::t('tasks','task_participant'),
		);
	}
	
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
		
		//$this->status_id = $_GET['TasksSearchForm']['status_id'];	
		
		$selected = Yii::app()->user->getState('project_selected');
		$criteria=new CDbCriteria;
		$criteria->compare('task_name',trim($this->task_name),true);
		$criteria->compare('task_priority', $this->task_priority);
		$criteria->compare('t.status_id',$this->status_id);
		$criteria->compare('taskTypes_id',$this->taskTypes_id);
		$criteria->compare('project_id',(!empty($selected)) ? $selected : ($this->project_id));
		$criteria->compare('case_id',$this->case_id);
		$criteria->compare('milestone_id',$this->milestone_id);
		$criteria->compare('taskStage_id',$this->taskStage_id);
		$criteria->compare('Users.user_id',$this->task_participant);
		$criteria->with = array('Users','Status');
		$criteria->together = true;
		$criteria->group = 't.task_id';
		$criteria->order = 'Status.status_order ASC';

		$items = new CActiveDataProvider('Tasks', array(
			'criteria'=>$criteria,
			'pagination'=>(Yii::app()->user->getState('view') == 'kanban') ? false : array('pageSize'=>10),
		));
		$this->_itemsCount = $items->totalItemCount;
		return $items;
	}	
	
	public function getItemsCount()
	{
		return $this->_itemsCount;
	}
}