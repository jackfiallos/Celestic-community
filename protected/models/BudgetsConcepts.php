<?php

/**
 * This is the model class for table "tb_budgetsConcepts".
 *
 * The followings are the available columns in table 'tb_budgetsConcepts':
 * @property integer $budgetsConcept_id
 * @property integer $budgetsConcept_quantity
 * @property string $budgetsConcept_description
 * @property double $budgetsConcept_amount
 * @property integer $budget_id
 */
class BudgetsConcepts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return BudgetsConcepts the static model class
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
		return 'tb_budgetsConcepts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('budgetsConcept_quantity, budgetsConcept_description, budgetsConcept_amount, budget_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('budgetsConcept_quantity, budget_id', 'numerical', 'integerOnly'=>true),
			array('budgetsConcept_description', 'length', 'min'=>5, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('budgetsConcept_amount', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('budgetsConcept_id, budgetsConcept_quantity, budgetsConcept_description, budgetsConcept_amount, budget_id', 'safe', 'on'=>'search'),
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
			'Budgets'=>array(self::BELONGS_TO, 'Budgets', 'budget_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'budgetsConcept_id' => Yii::t('budgetsConcepts', 'FieldConceptId'),
			'budgetsConcept_quantity' => Yii::t('budgetsConcepts', 'FieldQuantity'),
			'budgetsConcept_description' => Yii::t('budgetsConcepts', 'FieldDescription'),
			'budgetsConcept_amount' => Yii::t('budgetsConcepts', 'FieldAmount'),
			'budget_id' => Yii::t('budgets', 'FieldIdBudget'),
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

		$criteria->compare('budgetsConcept_id',$this->budgetsConcept_id);
		$criteria->compare('budgetsConcept_quantity',$this->budgetsConcept_quantity);
		$criteria->compare('budgetsConcept_description',$this->budgetsConcept_description,true);
		$criteria->compare('budgetsConcept_amount',$this->budgetsConcept_amount);
		$criteria->compare('budget_id',$this->budget_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('budgetsConcept_quantity', 'budgetsConcept_description', 'budgetsConcept_amount'),
			),
		);
	}
}