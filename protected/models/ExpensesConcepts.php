<?php

/**
 * This is the model class for table "tb_expensesConcepts".
 *
 * The followings are the available columns in table 'tb_expensesConcepts':
 * @property integer $expensesConcept_id
 * @property integer $expensesConcept_quantity
 * @property string $expensesConcept_description
 * @property double $expensesConcept_amount
 * @property integer $expense_id
 */
class ExpensesConcepts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return ExpensesConcepts the static model class
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
		return 'tb_expensesConcepts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('expensesConcept_quantity, expensesConcept_description, expensesConcept_amount, expense_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('expensesConcept_quantity, expense_id', 'numerical', 'integerOnly'=>true),
			array('expensesConcept_description', 'length', 'min'=>5, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('expensesConcept_amount', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('expensesConcept_id, expensesConcept_quantity, expensesConcept_description, expensesConcept_amount, expense_id', 'safe', 'on'=>'search'),
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
			'Expenses'=>array(self::BELONGS_TO, 'Expenses', 'expense_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'expensesConcept_id' => Yii::t('expensesConcepts','FieldConceptId'),
			'expensesConcept_quantity' => Yii::t('expensesConcepts','FieldQuantity'),
			'expensesConcept_description' => Yii::t('expensesConcepts','FieldDescription'),
			'expensesConcept_amount' => Yii::t('expensesConcepts','FieldAmount'),
			'expense_id' => Yii::t('expensesConcepts','FieldIdExpenses'),
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

		$criteria->compare('expensesConcept_id',$this->expensesConcept_id);
		$criteria->compare('expensesConcept_quantity',$this->expensesConcept_quantity);
		$criteria->compare('expensesConcept_description',$this->expensesConcept_description,true);
		$criteria->compare('expensesConcept_amount',$this->expensesConcept_amount);
		$criteria->compare('expense_id',$this->expense_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('expensesConcept_quantity', 'expensesConcept_description', 'expensesConcept_amount'),
			),
		);
	}
}