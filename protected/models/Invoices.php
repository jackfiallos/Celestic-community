<?php

/**
 * This is the model class for table "tb_invoices".
 *
 * The followings are the available columns in table 'tb_invoices':
 * @property integer $invoice_id
 * @property string $invoice_number
 * @property string $invoice_date
 * @property integer $project_id
 * @property integer $status_id
 * @property integer $budget_id
 */
class Invoices extends CActiveRecord
{
	public $items;
	public $amount;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Invoices the static model class
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
		return 'tb_invoices';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('invoice_number, invoice_date, project_id, status_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('project_id, status_id, budget_id', 'numerical', 'integerOnly'=>true),
			array('invoice_number', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			//array('invoice_number', 'unique', 'message'=>Yii::t('inputValidations','UniqueValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('invoice_id, invoice_number, invoice_date, project_id, status_id', 'safe', 'on'=>'search'),
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
			'Concepts'=>array(self::HAS_MANY, 'InvoicesConcepts', 'invoice_id'),
			'Cost'=>array(self::STAT,'InvoicesConcepts','invoice_id','select'=>'SUM(t.invoicesConcept_amount)'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'invoice_id' => Yii::t('invoices', 'IdInvoice'),
			'invoice_number' => Yii::t('invoices', 'InvoicesNumber'),
			'invoice_date' => Yii::t('invoices', 'InvoiceDate'),
			'project_id' => Yii::t('invoices', 'InvoiceProject'),
			'status_id' => Yii::t('invoices', 'InvoiceStatus'),
			'budget_id' => Yii::t('invoices', 'InvoiceBudget'),
			'amount' => 'amount',
			'items' => 'items',
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

		$criteria->compare('invoice_number',$this->invoice_number,true);
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
				'attributes' => array('invoice_number', 'invoice_date'),
			),
		);
	}
	
	public function countInvoicesByProject($invoice_id, $project_id)
	{
		return Invoices::model()->count(array(
			'condition'=>'t.project_id = :project_id AND t.invoice_id = :invoice_id',
			'params'=>array(
				':project_id' => $project_id,
				':invoice_id' => $invoice_id
			)	
		));
	}
	
	public function findInvoiceConceptByProject($invoicesConcept_id, $invoice_id, $project_id)
	{
		return Invoices::model()->with('Concepts')->count(array(
			'condition'=>'t.project_id = :project_id AND t.invoice_id = :invoice_id AND Concepts.invoicesConcept_id = :invoicesConcept_id',
			'params'=>array(
				':project_id' => $project_id,
				':invoice_id' => $invoice_id,
				':invoicesConcept_id' => $invoicesConcept_id,
			)	
		));
	}
	
	public function getLastUsed()
	{
		$model = Invoices::model()->find(array(
			'select'=>'t.invoice_number',
			'order'=>'t.invoice_id DESC',
			'limit'=>'1',
		));
		
		if ($model===null)
			$model->invoice_number = 0;
		
		return $model->invoice_number;
	}
	
	public function getInvoicesStatistics($project_id)
	{
		$criteria = new CDbCriteria;
		$criteria->select = 'count(t.status_id) AS items, SUM(
			(SELECT SUM(ic.invoicesConcept_amount) 
			FROM tb_invoicesConcepts ic 
			WHERE (ic.invoice_id = t.invoice_id))
		) as amount';
		
		// Si se ha seleccionado un proyecto
		/*if (!empty($project_id))
		{
			$criteria->condition = 't.project_id IN (:project_id)';
			$criteria->params = array(
				'project_id'=>$project_id,
			);
		}*/
		
		/*$criteria->condition = 'Cusers.user_id = :user_id';
		$criteria->params = array(
			':user_id' => Yii::app()->user->id,
		);*/
		$criteria->condition = 't.project_id IN ('.$project_id.')';
		$criteria->group = 't.status_id';
		$criteria->together = true;
		$criteria->with = array(
			'Status',
			'Projects.Currency',
		);
		
		return Invoices::model()->findAll($criteria);
	}
}