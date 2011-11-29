<?php

/**
 * This is the model class for table "tb_cases".
 *
 * The followings are the available columns in table 'tb_cases':
 * @property integer $case_id
 * @property integer $case_date
 * @property string $case_code
 * @property string $case_name
 * @property string $case_actors
 * @property string $case_description
 * @property string $case_priority
 * @property string $case_requirements
 * @property integer $project_id
 * @property integer $diagram_id
 * @property integer $status_id
 *
 * The followings are the available model relations:
 */
class Cases extends CActiveRecord
{
	const PRIORITY_LOW = 0;
	const PRIORITY_MEDIUM = 1;
	const PRIORITY_HIGH = 2;
	
	public function getCaseTitle()
	{
		return $this->case_code." - ".$this->case_name;
	}
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Cases the static model class
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
		return 'tb_cases';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('case_date, case_name, case_description, case_priority, project_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('case_priority, diagram_id, project_id, status_id', 'numerical', 'integerOnly'=>true),
			array('case_code', 'length','max'=>15),
			array('case_name', 'length', 'min'=>5, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('case_name, case_actors', 'length', 'max'=>100, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('case_requirements', 'length', 'max'=>1000, 'message'=>Yii::t('inputValidations','MaxValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('case_id, case_date, case_code, case_name, case_actors, case_description, case_requirements, project_id, diagram_id, status_id', 'safe', 'on'=>'search'),
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
			'Diagrams'=>array(self::BELONGS_TO, 'Diagrams', 'diagram_id'),
			'Projects'=>array(self::BELONGS_TO, 'Projects', 'project_id'),
			'Tasks'=>array(self::HAS_MANY, 'Tasks', 'case_id'),
			'Status'=>array(self::BELONGS_TO, 'Status', 'status_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'case_id' => Yii::t('cases','case_id'),
			'case_date' => Yii::t('cases','case_date'),
			'case_code' => Yii::t('cases','case_code'),
			'case_name' => Yii::t('cases','case_name'),
			'case_actors' => Yii::t('cases','case_actors'),
			'case_description' => Yii::t('cases','case_description'),
			'case_requirements' => Yii::t('cases','case_requirements'),
			'project_id' => Yii::t('cases','project_id'),
			'diagram_id' => Yii::t('cases','diagram_id'),
			'status_id' => Yii::t('cases','status_id'),
			'case_priority' => Yii::t('cases','case_priority'),
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
		$criteria->compare('case_name',$this->case_name,true);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('case_priority',$this->case_priority);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('case_date', 'case_code', 'case_name', 'case_actors', 'case_description', 'case_requirements'),
			),
		);
	}
	
	public function findCasesByProject($project_id)
	{
		return Cases::model()->findAll(array(
			'condition'=>'t.project_id = :project_id',
			'params'=>array(
				':project_id' => $project_id
			)
		));
	}
	
	public function countCasesByProject($case_id, $project_id)
	{
		return Cases::model()->count(array(
			'condition'=>'t.project_id = :project_id AND t.case_id = :case_id',
			'params'=>array(
				':project_id' => $project_id,
				':case_id' => $case_id
			)
		));
	}
}