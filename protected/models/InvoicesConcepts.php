<?php

/**
 * This is the model class for table "tb_invoicesConcepts".
 *
 * The followings are the available columns in table 'tb_invoicesConcepts':
 * @property integer $invoicesConcept_id
 * @property integer $invoicesConcept_quantity
 * @property string $invoicesConcept_description
 * @property double $invoicesConcept_amount
 * @property integer $invoice_id
 */
class InvoicesConcepts extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return InvoicesConcepts the static model class
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
		return 'tb_invoicesConcepts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoicesConcept_quantity, invoicesConcept_description, invoicesConcept_amount, invoice_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('invoicesConcept_quantity, invoice_id', 'numerical', 'integerOnly'=>true),
			array('invoicesConcept_description', 'length', 'min'=>5, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('invoicesConcept_amount', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('invoicesConcept_id, invoicesConcept_quantity, invoicesConcept_description, invoicesConcept_amount, invoice_id', 'safe', 'on'=>'search'),
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
			'Invoices'=>array(self::BELONGS_TO, 'Invoices', 'invoice_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'invoicesConcept_id' => Yii::t('invoicesConcepts', 'FieldConceptId'),
			'invoicesConcept_quantity' => Yii::t('invoicesConcepts', 'FieldQuantity'),
			'invoicesConcept_description' => Yii::t('invoicesConcepts', 'FieldDescription'),
			'invoicesConcept_amount' => Yii::t('invoicesConcepts', 'FieldAmount'),
			'invoice_id' => Yii::t('invoicesConcepts', 'FieldIdInvoices'),
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

		$criteria->compare('invoicesConcept_id',$this->invoicesConcept_id);
		$criteria->compare('invoicesConcept_quantity',$this->invoicesConcept_quantity);
		$criteria->compare('invoicesConcept_description',$this->invoicesConcept_description,true);
		$criteria->compare('invoicesConcept_amount',$this->invoicesConcept_amount);
		$criteria->compare('invoice_id',$this->invoice_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('invoicesConcept_quantity', 'invoicesConcept_description', 'invoicesConcept_amount'),
			),
		);
	}
}