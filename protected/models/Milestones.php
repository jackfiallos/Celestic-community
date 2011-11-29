<?php

/**
 * This is the model class for table "tb_milestones".
 *
 * The followings are the available columns in table 'tb_milestones':
 * @property integer $milestone_id
 * @property string $milestone_title
 * @property string $milestone_description
 * @property string $milestone_startdate
 * @property string $milestone_duedate
 * @property integer $project_id
 * @property integer $user_id
 *
 * The followings are the available model relations:
 */
class Milestones extends CActiveRecord
{
	public $percent;
	public $pending_tasks;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Milestones the static model class
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
		return 'tb_milestones';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('milestone_title, milestone_description, milestone_startdate, milestone_duedate, project_id, user_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('project_id, user_id', 'numerical', 'integerOnly'=>true),
			array('milestone_title', 'length', 'max'=>100, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('milestone_title', 'length', 'min'=>10, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('milestone_title', 'unique', 'message'=>Yii::t('inputValidations','UniqueValidation')),
			array('milestone_description, milestone_startdate, milestone_duedate', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('milestone_id, milestone_title, milestone_description, milestone_duedate, project_id, user_id', 'safe', 'on'=>'search'),
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
			'Projects'=>array(self::BELONGS_TO, 'Projects', 'project_id'),
			'Users'=>array(self::BELONGS_TO, 'Users', 'user_id'),
			'Tasks'=>array(self::HAS_MANY, 'Tasks', 'milestone_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'milestone_id' => Yii::t('milestones','milestone_id'),
			'milestone_title' => Yii::t('milestones','milestone_title'),
			'milestone_description' => Yii::t('milestones','milestone_description'),
			'milestone_startdate' => Yii::t('milestones','milestone_startdate'),
			'milestone_duedate' => Yii::t('milestones','milestone_duedate'),
			'project_id' => Yii::t('milestones','project_id'),
			'user_id' => Yii::t('milestones','user_id'),
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
		$criteria->compare('milestone_title',$this->milestone_title,true);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('milestone_title', 'milestone_description', 'milestone_duedate'),
			),
		);
	}
	
	public function findActivity($project_id)
    {
        $criteria = new CDbCriteria;
		// Si se ha seleccionado un proyecto
		if (!empty($project_id))
		{
			$criteria->condition = 't.milestone_duedate BETWEEN CURDATE() AND CURDATE() + INTERVAL 5 WEEK AND t.project_id = :project_id';
			$criteria->params = array(
				':project_id'=>$project_id,
			);
		}
		else
			$criteria->condition = 't.milestone_duedate BETWEEN CURDATE() AND CURDATE() + INTERVAL 5 WEEK';
		
		$criteria->order = 't.milestone_duedate ASC';
		$criteria->limit = '5';
		
		return Milestones::model()->with('Projects')->together()->findAll($criteria);
    }
	
	public function findOverdue($project_id)
    {
        $criteria = new CDbCriteria;
		// Si se ha seleccionado un proyecto
		if (!empty($project_id))
		{
			$criteria->condition = 't.milestone_duedate < NOW() AND t.project_id = :project_id AND Tasks.status_id IN ('.implode(',',array(Status::STATUS_PENDING,Status::STATUS_ACCEPTED,Status::STATUS_TOTEST, Status::STATUS_INPROGRESS)).')';
			$criteria->params = array(
				':project_id'=>$project_id,
			);
		}
		else
		{
			$WorkingProjects = Projects::model()->findMyProjects(Yii::app()->user->id);
			$projectList = array();
			foreach($WorkingProjects as $project)
				array_push($projectList, $project->project_id);
			
			$projects = (count($WorkingProjects == 0)) ? 0 : implode(",", $projectList);
			$criteria->condition = 't.milestone_duedate < NOW() AND Tasks.status_id IN ('.implode(',',array(Status::STATUS_PENDING,Status::STATUS_ACCEPTED,Status::STATUS_TOTEST, Status::STATUS_INPROGRESS)).') AND t.project_id IN ('.$projects.')';
		}
		
		$criteria->order = 't.milestone_duedate ASC';
		$criteria->group = 't.milestone_id';
		$criteria->together = true;
		$criteria->with = array(
			'Tasks',
		);		
		
		return Milestones::model()->findAll($criteria);
    }
    
	public function findMyMilestones($Projects)
    {
        $projectsArray = array();
    	foreach($Projects as $key)
        {
        	array_push($projectsArray, $key->project_id);
        }
		
		if (count($projectsArray) == 0)
			$projectsArray[] = 0;
        
    	return Milestones::model()->with('Projects')->together()->findAll(array(
            'condition'=>'t.project_id IN ('.implode(",",$projectsArray).')',
        ));
    }
	
	public function findMilestonesByProject($project_id)
	{
		return Milestones::model()->findAll(array(
			'condition'=>'t.project_id = :project_id',
			'params'=>array(
				':project_id' => $project_id
			)
		));
	}
	
	public function countMilestonesByProject($milestone_id, $project_id)
	{
		return Milestones::model()->count(array(
			'condition'=>'t.project_id = :project_id AND t.milestone_id = :milestone_id',
			'params'=>array(
				':project_id' => $project_id,
				':milestone_id' => $milestone_id
			)
		));
	}
	
	public function getMilestonePercent($milestone_id)
	{
		$criteria=new CDbCriteria;
		$criteria->select = '(
			SELECT (SUM(Status.status_value)/COUNT(Tasks.task_id))
			FROM `tb_tasks` Tasks 
			LEFT OUTER JOIN `tb_status` Status ON Tasks.status_id = Status.status_id
			WHERE Tasks.milestone_id = :milestone_id 
			) as percent';
		$criteria->params = array(
			':milestone_id' => $milestone_id,
		);
		$criteria->order = 't.milestone_id DESC';
		
		$result = Milestones::model()->find($criteria)->percent;
		return $result;
	}
	
	public function MilestoneWithPendingTasks()
	{
		$criteria=new CDbCriteria;
		$criteria->select = "Users.*, count(Tasks.task_id) as pending_tasks";
		$criteria->condition = 't.milestone_duedate < NOW() AND Tasks.status_id IN ('.implode(',',array(Status::STATUS_PENDING,Status::STATUS_ACCEPTED,Status::STATUS_TOTEST, Status::STATUS_INPROGRESS)).')';
		$criteria->group = 't.milestone_id';
		
		return Milestones::model()->with('Users','Tasks')->together()->findAll($criteria);
	}
}