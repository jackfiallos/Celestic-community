<?php

/**
 * This is the model class for table "tb_logs".
 *
 * The followings are the available columns in table 'tb_logs':
 * @property integer $log_id
 * @property string $log_date
 * @property string $log_activity
 * @property integer $log_resourceid
 * @property integer $log_type
 * @property integer $log_commentid
 * @property integer $user_id
 * @property integer $module_id
 * @property integer $project_id
 *
 * The followings are the available model relations:
 */
class Logs extends CActiveRecord
{
	const LOG_CREATED = 'created';
	const LOG_UPDATED = 'updated';
	const LOG_DELETED = 'deleted';
	const LOG_COMMENTED = 'comment';
	const LOG_ASSIGNED = 'assigned';
	const LOG_REVOKED = 'revoked';
		
	public function getTitleFromLogItem($id, $className, $itemTitle)
	{
		if (class_exists($className))
		{
			$class = new $className;
			return $class->findByPk($id)->$itemTitle;
		}
		else
			return "";
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Logs the static model class
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
		return 'tb_logs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('log_date, log_activity, log_resourceid, log_type, user_id, module_id', 'required'),
			array('log_commentid, log_resourceid, user_id, module_id, project_id', 'numerical', 'integerOnly'=>true),
			array('log_activity', 'length', 'max'=>45),
			array('log_type', 'length', 'max'=>20),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('log_id, log_date, log_activity, log_resourceid, log_type, log_commentid, user_id, module_id, project_id', 'safe', 'on'=>'search'),
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
			'User'=>array(self::BELONGS_TO, 'Users', 'user_id'),
			'Module'=>array(self::BELONGS_TO, 'Modules', 'module_id'),
			'Project'=>array(self::BELONGS_TO, 'Projects', 'project_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'log_id' => 'Log',
			'log_date' => 'Date',
			'log_activity' => 'Activity',
			'log_resourceid' => 'Resource id',
			'log_type' => 'Type',
			'log_commentid' => 'Comment id',
			'user_id' => 'User',
			'module_id' => 'Module',
			'project_id'=>'Project',
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

		$criteria->compare('log_id',$this->log_id);
		$criteria->compare('log_date',$this->log_date,true);
		$criteria->compare('log_activity',$this->log_activity,true);
		$criteria->compare('log_resourceid',$this->log_resourceid);
		$criteria->compare('log_type',$this->log_type);
		$criteria->compare('log_commentid',$this->log_commentid);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('module_id',$this->module_id);
		$criteria->compare('project_id',$this->project_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors()
	{
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('log_date', 'log_activity', 'log_resourceid', 'log_type', 'user_id', 'module_id', 'project_id'),
			),
		);
	}
	
	public function findActivity($module, $projectList, $limit = 7)
    {
		if (count($projectList) <= 0)
			$projectList = array(-1);
		
    	return Logs::model()->with('Module')->findAll(array(
			'condition'=>'t.log_activity NOT LIKE "%Comment%" AND t.project_id <> 0 AND t.project_id IN ('.implode(",", $projectList).')', //AND Module.module_name LIKE ("'.$module.'")',
			'params'=>array(
				':module_name'=>$module,
				':project_id'=>implode(",", $projectList),
			),
			'order'=>'t.log_id DESC',
			'limit'=>$limit,
			'together' => true,
		));
    }
	
	public function saveLog($modelAttributes, $sendMail = false)
	{	
		$module = Modules::model()->find(array(
			'condition'=>'t.module_name = :module_name',
			'params'=>array(
				':module_name'=>$modelAttributes['module_id'],
			),
		));
		
		$modelAttributes['module_id'] = $module->module_id;
		
		$model=new Logs;
		$model->attributes=$modelAttributes;
		
		if($model->save())
		{
			/*if ($sendMail)
				$this->sendEmailAlert($modelAttributes);*/
		}
	}
	
	private function sendEmailAlert($attributes)
	{
		$recipients = Users::model()->with('Companies.Projects')->findAll(array(
			'condition'=>'Projects.project_id = '.$attributes['project_id'],
			'together' => true,
		));
		
		$recipientsList = array();
		foreach($recipients as $user)
			array_push($recipientsList, $user->user_email);
			
		$str;
		Yii::import('application.extensions.miniTemplator.miniTemplator');
		$t = new miniTemplator;
		$t->readTemplateFromFile("templates/ActivityCreation.tpl");
		$t->setVariable ("applicationName",Yii::app()->name);
		$t->setVariable ("project",Projects::model()->findByPk($attributes['project_id'])->project_name);
		$t->setVariable ("logActivity",$attributes['log_activity']);
		$t->setVariable ("activityCreatedByUser",Users::model()->findByPk($attributes['user_id'])->CompleteName);
		$t->setVariable ("date",$attributes['log_date']);
		$t->setVariable ("applicationUrl", "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl);
		$t->generateOutputToString($str);
		
		$subject = "Activity Notification";
		
		Yii::import('application.extensions.phpMailer.yiiPhpMailer');
		$mailer = new yiiPhpMailer;
		$mailer->Ready($subject, $str, $recipientsList);
	}
	
	public function getCountComments($module_name, $resource_id)
	{
		//get_class($this)
		$criteria=new CDbCriteria;
		$criteria->compare('module_name',$module_name);
		$module_id = Modules::model()->find($criteria)->module_id;
		
		return Logs::model()->count(array(
			'condition'=>'t.module_id = :module_id AND t.log_resourceid = :resource_id AND t.log_commentid <> 0',
			'params'=>array(
				':module_id' => $module_id,
				':resource_id' => $resource_id,
			)
		));
	}
}