<?php

/**
 * This is the model class for table "tb_expenses".
 *
 * The followings are the available columns in table 'tb_expenses':
 * @property integer $expense_id
 * @property string $expense_name
 * @property string $expense_number
 * @property string $expense_date
 * @property string $expense_identifier
 * @property integer $project_id
 * @property integer $status_id
 */
class Expenses extends CActiveRecord
{
	public $total;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Expenses the static model class
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
		return 'tb_expenses';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('expense_name, expense_number, expense_date, project_id, status_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('project_id, status_id', 'numerical', 'integerOnly'=>true),
			array('expense_name, expense_number', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('expense_identifier', 'length', 'max'=>20, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('expense_name', 'length', 'min'=>5, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('expense_number', 'unique', 'message'=>Yii::t('inputValidations','UniqueValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('expense_id, expense_name, expense_number, expense_date, expense_identifier, project_id, status_id', 'safe', 'on'=>'search'),
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
			'Concepts'=>array(self::HAS_MANY, 'ExpensesConcepts', 'expense_id'),
			'Cost'=>array(self::STAT,'ExpensesConcepts','expense_id','select'=>'SUM(t.expensesConcept_amount)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'expense_id' => Yii::t('expenses', 'IdExpense'),
			'expense_name' => Yii::t('expenses', 'ExpenseName'),
			'expense_number' => Yii::t('expenses', 'ExpenseNumber'),
			'expense_date' => Yii::t('expenses', 'ExpenseDate'),
			'expense_identifier' => Yii::t('expenses', 'ExpenseIdentifier'),
			'project_id' => Yii::t('expenses', 'IdProject'),
			'status_id' => Yii::t('expenses', 'IdStatus'),
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

		$criteria->compare('expense_name',$this->expense_name,true);
		$criteria->compare('expense_number',$this->expense_number,true);
		$criteria->compare('expense_identifier',$this->expense_identifier,true);
		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('status_id',$this->status_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('expense_name', 'expense_number', 'expense_date', 'expense_identifier'),
			),
		);
	}
	
	public function countExpensesByProject($expense_id, $project_id)
	{
		return Expenses::model()->count(array(
			'condition'=>'t.project_id = :project_id AND t.expense_id = :expense_id',
			'params'=>array(
				':project_id' => $project_id,
				':expense_id' => $expense_id
			)	
		));
	}
	
	public function findExpenseConceptByProject($expensesConcept_id, $expense_id, $project_id)
	{
		return Expenses::model()->with('Concepts')->count(array(
			'condition'=>'t.project_id = :project_id AND t.expense_id = :expense_id AND Concepts.expensesConcept_id = :expensesConcept_id',
			'params'=>array(
				':project_id' => $project_id,
				':expense_id' => $expense_id,
				':expensesConcept_id' => $expensesConcept_id,
			)	
		));
	}
}