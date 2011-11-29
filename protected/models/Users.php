<?php

/**
 * This is the model class for table "tb_users".
 */
class Users extends CActiveRecord
{
	public function getCompleteName()
	{
		return $this->user_name." ".$this->user_lastname;
	}
	
	/**
	 * The followings are the available columns in table 'tb_users':
	 * @var integer $user_id
	 * @var string $user_name
	 * @var string $user_lastname
	 * @var string $user_email
	 * @var string $user_phone
	 * @var integer $user_admin
	 * @var integer $user_password
	 * @var integer $user_active
	 * @var integer $account_id
	 * @var integer $address_id
	 * @var integer $user_accountManager
	 * @var datetime $user_lastLogin
	 */

	/**
	 * Returns the static model of the specified AR class.
	 * @return Users the static model class
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
		return 'tb_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_name, user_lastname, user_email, user_admin, user_active, account_id, user_accountManager, user_password', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('user_name, user_lastname', 'length', 'min'=>3, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('user_admin, user_active, account_id, address_id, user_accountManager', 'numerical', 'integerOnly'=>true),
			array('user_name, user_lastname, user_email', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('user_phone', 'length', 'max'=>30, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('user_password', 'length', 'max'=>20, 'min'=>6, 'on'=>array('create'), 'message'=>Yii::t('inputValidations','BetweenValidation')),
			array('user_password', 'length', 'min'=>6, 'on'=>array('update'), 'message'=>Yii::t('inputValidations','MinValidation')),
			array('user_email', 'email', 'message'=>Yii::t('inputValidations','EmailValidation')),
			array('user_email', 'unique', 'message'=>Yii::t('inputValidations','UniqueValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('user_id, user_name, user_lastname, user_email, user_phone, user_admin, user_active, account_id, address_id, user_accountManager', 'safe', 'on'=>'search'),
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
			'Clients'=>array(self::HAS_ONE, 'Clients', 'user_id'),
			'Accounts'=>array(self::BELONGS_TO, 'Accounts', 'account_id'),
			'Companies'=>array(self::MANY_MANY, 'Companies', 'tb_companies_has_tb_users(user_id,company_id)'),
			//'Cuser'=>array(self::HAS_MANY, 'CompaniesHasUsers', 'user_id)'),
			'Cuser'=>array(self::HAS_MANY, 'CompaniesHasUsers', 'user_id'),
			'Managers'=>array(self::MANY_MANY, 'ProjectsHasUsers', 'tb_projects_has_tb_users(user_id,project_id)'),
			'ClientsManagers'=>array(self::MANY_MANY, 'Projects', 'tb_projects_has_tb_users(user_id,project_id)'),
			'Tasks'=>array(self::MANY_MANY, 'Tasks', 'tb_users_has_tb_tasks(user_id,task_id)'),
			//'Workers'=>array(self::MANY_MANY, 'UsersHasTasks', 'tb_users_has_tb_tasks(user_id,task_id)'),
			'Address'=>array(self::BELONGS_TO, 'Address', 'address_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'user_id' => Yii::t('users','user_id'),
			'user_name' => Yii::t('users','user_name'),
			'user_lastname' => Yii::t('users','user_lastname'),
			'user_email' => Yii::t('users','user_email'),
			'user_phone' => Yii::t('users','user_phone'),
			'user_admin' => Yii::t('users','user_admin'),
			'user_password' => Yii::t('users','user_password'),
			'user_active' => Yii::t('users','user_active'),
			'account_id' => Yii::t('users','account_id'),
			'address_id' => Yii::t('users','address_id'),
			'user_accountManager' => Yii::t('users','user_accountManager'),
			'user_lastLogin' => Yii::t('users','user_lastLogin'),
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
		$criteria->compare('user_name',Users::user_name,true);
		$criteria->compare('user_lastname',Users::user_lastname,true);
		$criteria->compare('user_email',Users::user_email,true);
		$criteria->compare('user_phone',Users::user_phone,true);
		$criteria->compare('user_active',Users::user_active);

		return new CActiveDataProvider(get_class(Users), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('user_name', 'user_lastname', 'user_email', 'user_phone'),
			),
		);
	}
	
	public function findUsersAndClientsByAccount($account_id)
	{
		return Users::model()->with('Accounts')->findAll(array(
			'condition'=>'t.account_id = :account_id',
			'params'=>array(
				':account_id' => $account_id,
			),
			'together' => true,
		));
	}
	
	public function findUserWithoutAccountManager()
	{
		return Users::model()->findAll(array(
			'condition'=>'t.account_id = :account_id AND t.user_accountManager <> 1',
			'params'=>array(
				':account_id' => Yii::app()->user->Accountid,
			),
		));
	}
	
	public function findUsersByAccount($account_id, $company_id)
    {
        return Users::model()->with('Clients','Companies')->findAll(array(
			'select'=>'t.user_id, t.user_name, t.user_lastname',
			//'condition'=>'t.account_id = :account_id AND Clients.client_id IS NULL AND Companies_Companies.user_id IS NULL',
			'condition'=>'t.account_id = :account_id 
				AND Clients.client_id IS NULL 
				/*AND Companies_Companies.company_id != :company_id */
				AND t.user_id NOT IN (
					SELECT tbc.user_id  
					FROM tb_companies_has_tb_users tbc
					WHERE tbc.company_id = :company_id 
				)',
			'params'=>array(
				':account_id'=>$account_id,
				':company_id'=>$company_id,//$_GET['owner'],
			),
			'together'=>true,
			'order'=>'t.user_name',
			'group'=>'t.user_id',
		));
    }
	
	public function findClientsByAccount($account_id, $company_id)
    {
        return Users::model()->with('Clients','Companies')->findAll(array(
			//'condition'=>'t.account_id = :account_id AND Clients.client_id IS NOT NULL AND Companies_Companies.user_id IS NULL',
			'select'=>'t.user_id, t.user_name, t.user_lastname',
			'condition'=>'t.account_id = :account_id 
				AND Clients.client_id IS NOT NULL 
				/*AND Companies_Companies.company_id != :company_id */
				AND t.user_id NOT IN (
					SELECT tbc.user_id  
					FROM tb_companies_has_tb_users tbc
					WHERE tbc.company_id = :company_id 
				)',
			'params'=>array(
				':account_id'=>$account_id,
				':company_id'=>$company_id,
			),
			'together'=>true,
			'order'=>'t.user_name',
			'group'=>'t.user_id',
		));
    }
	
	public function findUsersByProject($project_id)
	{
		return Users::model()->with('Clients','Companies.Projects')->findAll(array(
			'condition'=>'Projects.project_id = :project_id AND Clients.client_id IS NULL',
			'params'=>array(
				':project_id' => $project_id,
			),
			'together'=>true,
			'order'=>'t.user_name',
		));
	}
	
	public function findClientsByProject($project_id)
	{
		return Users::model()->with('Clients','Companies.Projects')->findAll(array(
			'condition'=>'Projects.project_id = :project_id AND Clients.client_id IS NOT NULL',
			'params'=>array(
				':project_id' => $project_id,
			),
			'together'=>true,
			'order'=>'t.user_name',
		));
	}
	
	public function findUsersAndClientsByProject($project_id)
	{
		return Users::model()->with('Companies.Projects')->findAll(array(
			'addCondition'=>'Projects.project_id = :project_id',
			'params'=>array(
				':project_id' => $project_id,
			),
			'together'=>true,
			'order'=>'t.user_name',
		));
	}
	
	/**
	 * 
	 * Scope filterManagers
	 */
	public function filterManagers($list)
	{
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.user_id NOT IN ('.implode(',',$list).')',
        ));
        
        return $this;
    }
	
	/*public function findManagersByProject($project_id)
	{
		return Users::model()->with('Managers')->findAll(array(
			//'select'=>'t.user_id',
			'condition'=>'Managers.project_id = :project_id',
			'params'=>array(
				':project_id' => $project_id,
			),
			'together'=>true,
			'group'=>'t.user_id',
		));
	}*/
	
	public function verifyUserInProject($project_id, $user_id){
		$count = Users::model()->with('ClientsManagers')->count(array(
			'condition'=>'ClientsManagers.project_id = :project_id AND t.user_id = :user_id AND 1=1',
			'params'=> array(
				':project_id'=>$project_id,
				':user_id'=>$user_id,
			),
			'together'=>true
		));
		
		if ($count>0)
			return true;
		
		return false;
	}
	
	public function findWorkersByTask($task_id)
	{
		return Users::model()->with('Tasks')->findAll(array(
			//'select'=>'t.user_id',
			'condition'=>'Tasks.task_id = :task_id',
			'params'=>array(
				':task_id' => $task_id,
			),
			'together'=>true,
			'group'=>'t.user_id',
		));
	}
	
	public function countWorkersByTask($task_id)
	{
		return Users::model()->with('Tasks')->count(array(
			'select'=>'t.user_id',
			'condition'=>'Tasks.task_id = :task_id',
			'params'=>array(
				':task_id' => $task_id,
			),
			'together'=>true,
			'group'=>'t.user_id',
		));
	}
	
	public function countUsersByAccount($user_id, $account_id)
	{
		return Users::model()->count(array(
			'condition'=>'t.user_id = :user_id AND t.account_id = :account_id',
			'params'=>array(
				':account_id' => $account_id,
				':user_id' => $user_id
			)
		));
	}
	
	public function availablesUsersToTakeTask($project_id, $task_id)
	{
		$Users = Users::model()->with('Tasks')->findAll(array(
			'condition'=>'Tasks.task_id = :task_id',
			'params'=>array(
				':task_id'=>$task_id, 
			),
		));
		
		$usersArray = array(0);
		foreach($Users as $user)
		{
			array_push($usersArray, $user->user_id);
		}
		
		return Users::model()->with('ClientsManagers')->findAll(array(
			'condition'=>'ClientsManagers.project_id = :project_id AND t.user_id NOT IN ('.implode(",",$usersArray).')',
			'params'=>array(
				':project_id'=>$project_id, 
			),
			'together'=>true,
		));
	}
}
