<?php

/**
 * This is the model class for table "tb_tasks".
 *
 * The followings are the available columns in table 'tb_tasks':
 * @property integer $task_id
 * @property string $task_name
 * @property string $task_description
 * @property string $task_startDate
 * @property string $task_endDate
 * @property integer task_priority
 * @property string task_buildNumber
 * @property string task_position
 * @property integer $status_id
 * @property integer $taskTypes_id
 * @property integer $project_id
 * @property integer $case_id
 * @property integer $milestone_id
 * @property integer $user_id
 * @property integer $taskStage_id
 *
 * The followings are the available model relations:
 */
class Tasks extends CActiveRecord
{
	public $total;
	public $dependant;
	public $numstatus;
	public $progress;
	
	const PRIORITY_LOW = 0;
	const PRIORITY_MEDIUM = 1;
	const PRIORITY_HIGH = 2;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Tasks the static model class
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
		return 'tb_tasks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('task_name, task_description, task_priority, status_id, taskTypes_id, project_id, user_id, taskStage_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('task_priority, task_position, status_id, taskTypes_id, case_id, project_id, milestone_id, user_id, taskStage_id', 'numerical', 'integerOnly'=>true),
			array('task_name', 'length', 'max'=>100, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('task_name', 'length', 'min'=>10, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('task_buildNumber', 'length', 'max'=>20, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('task_startDate, task_endDate', 'length', 'max'=>19, 'message'=>Yii::t('inputValidations','MaxValidation')),
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
			'Cases'=>array(self::BELONGS_TO, 'Cases', 'case_id'),
			'Status'=>array(self::BELONGS_TO, 'Status', 'status_id'),
			'Types'=>array(self::BELONGS_TO, 'TaskTypes', 'taskTypes_id'),
			'Projects'=>array(self::BELONGS_TO, 'Projects', 'project_id'),
			'Milestones'=>array(self::BELONGS_TO, 'Milestones', 'milestone_id'),
			'Users'=>array(self::MANY_MANY, 'Users', 'tb_users_has_tb_tasks(task_id,user_id)'),
			'UserReported'=>array(self::BELONGS_TO, 'Users', 'user_id'),
			'Stage'=>array(self::BELONGS_TO, 'TaskStages', 'taskStage_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'task_id' => Yii::t('tasks','task_id'),
			'task_name' => Yii::t('tasks','task_name'),
			'task_description' => Yii::t('tasks','task_description'),
			'task_startDate' => Yii::t('tasks','task_startDate'),
			'task_endDate' => Yii::t('tasks','task_endDate'),
			'task_priority' => Yii::t('tasks','task_priority'),
			'task_buildNumber' => Yii::t('tasks','task_buildNumber'),
			'task_position' => 'Position',
			'status_id' => Yii::t('tasks','status_id'),
			'taskTypes_id' => Yii::t('tasks','taskTypes_id'),
			'project_id' => Yii::t('tasks','project_id'),
			'case_id' => Yii::t('tasks','case_id'),
			'milestone_id' => Yii::t('tasks','milestone_id'),
			'user_id' => Yii::t('tasks','user_id'),
			'taskStage_id' => Yii::t('tasks','taskStage_id'),
			'dependant' => Yii::t('tasks','dependant'),
			'total' => Yii::t('tasks','total'),
			'progress' => Yii::t('tasks','progress'),
			'numstatus'=>'numstatus',
		);
	}
	
	public function findActivity($user_id, $project_id)
    {
        $criteria = new CDbCriteria;
		// Si se ha seleccionado un proyecto
		if (!empty($project_id))
		{
			$criteria->condition = 'Users.user_id = :user_id AND t.project_id = :project_id AND t.status_id NOT IN ('.implode(',',array(Status::STATUS_CANCELLED, Status::STATUS_CLOSED)).')';
			$criteria->params = array(
				':user_id'=>$user_id,
				'project_id'=>$project_id,
			);
		}
		else
		{
			$criteria->condition = 'Users.user_id = :user_id AND t.status_id NOT IN ('.implode(',',array(Status::STATUS_CANCELLED, Status::STATUS_CLOSED)).')';
			$criteria->params = array(
				':user_id'=>$user_id,
			);
		}
		$criteria->order = 't.task_startDate ASC';
		$criteria->limit = 10;
		
		return Tasks::model()->with('Users')->together()->findAll($criteria);
    }
	
	public function findActivityGroupedByTask($user_id, $project_id)
    {
        $criteria = new CDbCriteria;
		$criteria->select = 'count(t.status_id) as numstatus';
		// Si se ha seleccionado un proyecto
		if (!empty($project_id))
		{
			$criteria->condition = 'Users.user_id = :user_id AND t.project_id = :project_id';
			$criteria->params = array(
				':user_id'=>$user_id,
				'project_id'=>$project_id,
			);
		}
		else
		{
			$criteria->condition = 'Users.user_id = :user_id';
			$criteria->params = array(
				':user_id'=>$user_id,
			);
		}
		$criteria->group = 't.status_id';
		
		return Tasks::model()->with('Users','Status')->together()->findAll($criteria);
    }
	
	public function TaskCounter($user_id, $project_id)
	{
		$criteria = new CDbCriteria;
		
		// Si se ha seleccionado un proyecto
		if (!empty($project_id))
		{
			$criteria->condition = 'Users.user_id = :user_id AND t.project_id = :project_id';
			$criteria->params = array(
				':user_id'=>$user_id,
				'project_id'=>$project_id,
			);
		}
		else
		{
			$criteria->condition = 'Users.user_id = :user_id';
			$criteria->params = array(
				':user_id'=>$user_id,
			);
		}
		
		return Tasks::model()->with('Users')->count($criteria);
	}
	
	public function getNameOfTaskPriority($task_priority)
	{
		$output = Yii::t('tasks','task_priority')." ";
		switch($task_priority)
		{
			case 0:
				$output .= Yii::t('site','lowPriority');
				break;
			case 1:
				$output .= Yii::t('site','mediumPriority');
				break;
			case 2:
				$output .= Yii::t('site','highPriority');
				break;
			default:
				$output .= Yii::t('site','lowPriority');
				break;
		}
		
		return $output;
	}
	
	public function behaviors()
	{
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('task_name', 'task_description', 'task_startDate', 'task_endDate', 'task_buildNumber'),
			),
		);
	}
	
	public function countTasksByProject($task_id, $project_id)
	{
		return Tasks::model()->count(array(
			'condition'=>'t.project_id = :project_id AND t.task_id = :task_id',
			'params'=>array(
				':project_id' => $project_id,
				':task_id' => $task_id
			)
		));
	}
	
	public function findTaskByMilestone($milestone_id)
	{
		return Tasks::model()->with('Users')->findAll(array(
			'condition'=>'t.milestone_id = :milestone_id AND t.status_id IN ('.implode(',',array(Status::STATUS_PENDING,Status::STATUS_ACCEPTED,Status::STATUS_TOTEST, Status::STATUS_INPROGRESS)).')',
			'params'=>array(
				':milestone_id' => $milestone_id,
			)
		));
	}
	
	public function getTasksByMilestones($milestone_id, $pages)
	{
		$criteria=new CDbCriteria;
		$criteria->compare('milestone_id',$milestone_id);
		
		return new CActiveDataProvider('Tasks', array(
			'criteria'=>$criteria,
			'pagination'=>array(
                'pageSize'=>$pages
			),
		));
	}
	
	public function verifyTasksInProject($project_id, $task_id){
		$count = Tasks::model()->count(array(
			'condition'=>'t.project_id = :project_id AND t.task_id = :task_id',
			'params'=> array(
				':project_id'=>$project_id,
				':task_id'=>$task_id,
			),
		));
		
		if ($count>0)
			return true;
		
		return false;
	}
}