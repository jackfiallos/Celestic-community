<?php

/**
 * This is the model class for table "tb_budgets".
 *
 * The followings are the available columns in table 'tb_budgets':
 * @property integer $budget_id
 * @property date $budget_date
 * @property date budget_duedate
 * @property string $budget_title
 * @property string $budget_notes
 * @property integer $project_id
 * @property integer $status_id
 */
class Budgets extends CActiveRecord
{
	const STATUS_DRAFT=1;
	
	public $total;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Budgets the static model class
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
		return 'tb_budgets';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('budget_date, budget_duedate, budget_title, budget_notes, project_id, status_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('project_id, status_id', 'numerical', 'integerOnly'=>true),
			array('budget_title', 'length', 'min'=>10, 'message'=>Yii::t('inputValidations','MinValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('budget_date, budget_duedate','default'),
			//array('budget_id, budget_title, budget_date, budget_duedate, budget_notes, project_id, status_id', 'safe', 'on'=>'search'),
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
			'Status'=>array(self::BELONGS_TO, 'Status', 'status_id'),
			'BudgetsConcepts'=>array(self::HAS_MANY, 'BudgetsConcepts', 'budget_id'),
			'Cost'=>array(self::STAT,'BudgetsConcepts','budget_id','select'=>'SUM(t.budgetsConcept_amount)'),
			'Users'=>array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'budget_id' => Yii::t('budgets', 'FieldIdBudget'),
			'budget_date' => Yii::t('budgets', 'FieldDateBudget'),
			'budget_duedate' => Yii::t('budgets', 'FieldDueDateBudget'),
			'budget_title' => Yii::t('budgets', 'FieldTitleBudget'),
			'budget_notes' => Yii::t('budgets', 'FieldNotesBudget'),
			'budget_token' => Yii::t('budgets', 'FieldTokenBudget'),
			'project_id' => Yii::t('budgets', 'FieldProjectBudget'),
			'status_id' => Yii::t('budgets', 'FieldStatusBudget'),
			'user_id' => Yii::t('budgets', 'FieldCreatedBy'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	/*public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('budget_title',$this->budget_title);
		$criteria->compare('budget_notes',$this->budget_notes,true);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('status_id',$this->status_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}*/
	
	public function countBudgetsByProject($budget_id, $project_id)
	{
		return Budgets::model()->count(array(
			'condition'=>'t.project_id = :project_id AND t.budget_id = :budget_id',
			'params'=>array(
				':project_id' => $project_id,
				':budget_id' => $budget_id
			)	
		));
	}
	
	public function findBudgetConceptByProject($budgetsConcept_id, $budget_id, $project_id)
	{
		return Budgets::model()->with('BudgetsConcepts')->count(array(
			'condition'=>'t.project_id = :project_id AND t.budget_id = :budget_id AND BudgetsConcepts.budgetsConcept_id = :budgetsConcept_id',
			'params'=>array(
				':project_id' => $project_id,
				':budget_id' => $budget_id,
				':budgetsConcept_id' => $budgetsConcept_id,
			)	
		));
	}
	
	// Scope
	public function findBudgetsByProjects($project_id)
	{
		return Budgets::model()->findAll(array(
			'condition'=>'t.project_id = :project_id',
			'params'=>array(
				':project_id' => $project_id
			)	
		));
	}
	
	public function hasItems()
	{
		$selected = Yii::app()->user->getState('project_selected');
		return Budgets::model()->count(array(
			'condition'=>'t.project_id = :project_id',
			'params'=>array(
				':project_id' => $selected,
			)	
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('budget_title', 'budget_date', 'budget_notes'),
			),
		);
	}

}