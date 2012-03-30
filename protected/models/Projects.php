<?php

/**
 * This is the model class for table "tb_projects".
 *
 * The followings are the available columns in table 'tb_projects':
 * @property integer $project_id
 * @property string $project_name
 * @property string $project_description
 * @property string $project_scope
 * @property string $project_restrictions
 * @property string $project_plataform
 * @property string $project_swRequirements
 * @property string $project_hwRequirements
 * @property string $project_startDate
 * @property string $project_endDate
 * @property integer $project_active
 * @property string project_functionalReq
 * @property string project_performanceReq
 * @property string project_additionalComments
 * @property string project_userInterfaces
 * @property string project_hardwareInterfaces
 * @property string project_softwareInterfaces
 * @property string project_communicationInterfaces
 * @property string project_backupRecovery
 * @property string project_dataMigration
 * @property string project_userTraining
 * @property string project_installation
 * @property string project_assumptions
 * @property string project_outReach
 * @property string project_responsibilities
 * @property string project_warranty
 * @property string project_additionalCosts
 * @property integer currency_id
 * @property integer $company_id
 */
class Projects extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Projects the static model class
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
		return 'tb_projects';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_name, project_description, project_plataform, project_startDate, project_endDate, company_id, currency_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('project_active', 'numerical', 'integerOnly'=>true),
			array('company_id', 'numerical', 'integerOnly'=>true),
			array('currency_id', 'numerical', 'integerOnly'=>true),
			array('project_name', 'length', 'max'=>100, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('project_name', 'length', 'min'=>10, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('project_restrictions, project_scope, project_swRequirements, project_hwRequirements, project_functionalReq, project_performanceReq, project_additionalComments, project_userInterfaces, project_hardwareInterfaces, project_softwareInterfaces, project_communicationInterfaces, project_backupRecovery, project_dataMigration, project_userTraining, project_installation, project_assumptions, project_outReach, project_responsibilities, project_warranty, project_additionalCosts', 'length', 'max'=>4000, 'message'=>Yii::t('inputValidations','MaxValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('project_id, project_name, project_description, project_scope, project_restrictions, project_plataform, project_swRequirements, project_hwRequirements, project_startDate, project_endDate, project_active, company_id, currency_id', 'safe', 'on'=>'search'),
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
			'Company'=>array(self::BELONGS_TO, 'Companies', 'company_id'),
			'Currency'=>array(self::BELONGS_TO, 'Currencies', 'currency_id'),
			'Users'=>array(self::MANY_MANY, 'Users', 'tb_projects_has_tb_users(user_id,project_id)'),
			'Cusers'=>array(self::MANY_MANY, 'Users', 'tb_projects_has_tb_users(project_id,user_id)'),
			'Budgets'=>array(self::HAS_MANY, 'Budgets', 'project_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			//Analysis Parameters
			'project_id' => Yii::t('projects','project_id'),
			'project_name' => Yii::t('projects','project_name'),
			'project_description' => Yii::t('projects','project_description'),
			'project_scope' => Yii::t('projects','project_scope'),
			'project_restrictions' => Yii::t('projects','project_restrictions'),
			'project_plataform' => Yii::t('projects','project_plataform'),
			'project_swRequirements' => Yii::t('projects','project_swRequirements'),
			'project_hwRequirements' => Yii::t('projects','project_hwRequirements'),
			'project_startDate' => Yii::t('projects','project_startDate'),
			'project_endDate' => Yii::t('projects','project_endDate'),
			'project_active' => Yii::t('projects','project_active'),
			'company_id' => Yii::t('projects','company_id'),
			'currency_id' => Yii::t('projects','currency_id'),
			'project_additionalCosts' => Yii::t('projects','project_additionalCosts'),
			'project_responsibilities' => Yii::t('projects','project_responsibilities'),
			//External Interfaces
			'project_userInterfaces' => Yii::t('projects','project_userInterfaces'),
			'project_hardwareInterfaces' => Yii::t('projects','project_hardwareInterfaces'),
			'project_softwareInterfaces' => Yii::t('projects','project_softwareInterfaces'),
			'project_communicationInterfaces' => Yii::t('projects','project_communicationInterfaces'),
			//Specific Requirements
			'project_functionalReq' => Yii::t('projects','project_functionalReq'),
			'project_performanceReq' => Yii::t('projects','project_performanceReq'),
			'project_additionalComments' => Yii::t('projects','project_additionalComments'),
			//Special User Requirements
			'project_backupRecovery' => Yii::t('projects','project_backupRecovery'),
			'project_dataMigration' => Yii::t('projects','project_dataMigration'),
			'project_userTraining' => Yii::t('projects','project_userTraining'),
			'project_installation' => Yii::t('projects','project_installation'),
			//Special Considerations
			'project_assumptions' => Yii::t('projects','project_assumptions'),
			'project_outReach' => Yii::t('projects','project_outReach'),
			'project_warranty' => Yii::t('projects','project_warranty'),
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

		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('project_name',$this->project_name,true);
		$criteria->compare('project_description',$this->project_description,true);
		$criteria->compare('project_restrictions',$this->project_restrictions,true);
		$criteria->compare('project_scope',$this->project_scope,true);
		$criteria->compare('project_plataform',$this->project_plataform,true);
		$criteria->compare('project_swRequirements',$this->project_swRequirements,true);
		$criteria->compare('project_hwRequirements',$this->project_hwRequirements,true);
		$criteria->compare('project_startDate',$this->project_startDate,true);
		$criteria->compare('project_endDate',$this->project_endDate,true);
		$criteria->compare('project_active',$this->project_active);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array(
					'project_name',
					'project_description',
					'project_scope',
					'project_restrictions',
					'project_plataform',
					'project_swRequirements',
					'project_hwRequirements',
					'project_startDate',
					'project_endDate',
					'project_userInterfaces',
					'project_hardwareInterfaces',
					'project_softwareInterfaces',
					'project_communicationInterfaces',
					'project_functionalReq',
					'project_performanceReq',
					'project_additionalComments',
					'project_backupRecovery',
					'project_dataMigration',
					'project_userTraining',
					'project_installation',
					'project_assumptions',
					'project_outReach',
					'project_responsibilities',
					'project_warranty',
					'project_additionalCosts',
				),
			),
		);
	}
	
	public function countProjects($project_id)
	{
		return Projects::model()->count(array(
			'condition'=>'t.project_id = :project_id',
			'params'=>array(
				':project_id' => $project_id,
			)	
		));
	}
	
	public function findMyProjects($userId)
    {
    	$projects = Projects::model()->with('Company.Cusers')->together()->findAll(array(
			'condition'=>'Cusers.user_id = :user_id AND t.project_active = 1',
			'params'=>array(
				':user_id' => $userId,
			),
			'group'=>'t.project_id',
		));
    	/*$projects = Projects::model()->with('Users')->together()->findAll(array(
    		'condition'=>'Users.user_id = :user_id',
    		'params'=>array(
    			':user_id' => $userId,
    		),
    		'group'=>'t.project_id',
    	));*/
		
		if (count($projects)<=0)
			return array();
		
		return $projects;
    }
	
	public function hasProject($user_id, $project_id)
	{
		$criteria = new CDbCriteria;
		$criteria->condition = "Cusers.user_id = :user_id AND t.project_id = :project_id AND t.project_active = 1";
		$criteria->params = array(
			':user_id' => $user_id,
			':project_id' => $project_id,
		);
		
		return Projects::model()->with('Company.Cusers')->together()->find($criteria);
	}
	
	public function getProjectCost($project_id)
	{
		return Budgets::model()->with('BudgetsConcepts')->find(array(
			'select'=>'t.budget_id AS c, SUM(BudgetsConcepts.budgetsConcept_amount) AS total',
			'condition'=>'t.project_id = :project_id AND t.status_id IN ('.implode(',', array(Status::STATUS_ACCEPTED, Status::STATUS_PENDING)).')',
			'params'=>array(
				':project_id' => $project_id,
			),
			'group'=>'t.budget_id',
			'together'=>true,
		));
	}
	
	public function getProjectProgress($project_id)
	{
		// ( total_tasks_per_status / total_tasks ) / status_value
		$criteria = new CDbCriteria;
		$criteria->select = '(ROUND((COUNT(t.task_id)/(SELECT COUNT(*) FROM tb_tasks tb WHERE tb.project_id = :project_id))*Status.status_value)) AS progress';
		$criteria->condition = 't.project_id = :project_id AND 200=200';
		$criteria->params = array(
			':project_id' => $project_id,
		);
		$criteria->group = 't.status_id';
		$criteria->order = 't.task_startDate ASC';
		
		return Tasks::model()->with('Status')->together()->find($criteria);
	}
	
	public function findManagersByProject($project_id)
	{
		return Users::model()->findAll(array(
			'condition'=>'ClientsManagers.project_id = :project_id',//AND ClientsManagers_ClientsManagers.isManager = 1',
			'params'=>array(
				':project_id' => $project_id,
			),
			'together'=>true,
			'order'=>'t.user_name',
			'with'=>array('ClientsManagers'),
		));
	}
	
	public function findAvailablesManagersByProject($project_id)
	{
		$Managers = $this->findManagersByProject($project_id);
		
		$managerList = array();
		if(count($Managers)>0)
		{
			foreach($Managers as $users)
				array_push($managerList, $users->user_id);
		}
		else
			array_push($managerList, -1);
		
		
		return Users::model()->findAll(
			array(
				'condition'=>'Companies.company_id = :company_id AND t.user_id NOT IN ('.implode(",", $managerList).')',
				'params'=>array(
					':company_id'=>Projects::model()->findByPk(Yii::app()->user->getState('project_selected'))->company_id,
				),
				'together'=>true,
				'order'=>'t.user_name ASC',
				'with'=>array('Companies'),
			)
		);
	}
	
	public function findAllUsersByProject($project_id)
	{
		return Users::model()->with('ClientsManagers')->findAll(array(
			'condition'=>'ClientsManagers.project_id = :project_id',
			'params'=>array(
				':project_id' => $project_id,
			),
			'together'=>true,
			'group'=>'t.user_id',
		));
	}
}