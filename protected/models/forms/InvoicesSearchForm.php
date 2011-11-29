<?php
class InvoicesSearchForm extends CFormModel
{
	public $invoice_number;
	public $budget_id;
	public $project_id;
	public $status_id;
	private $_itemsCount;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('invoice_number', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('status_id, project_id, budget_id', 'numerical', 'integerOnly'=>true),
			array('invoice_number', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'invoice_number' => Yii::t('invoices', 'InvoicesNumber'),
			'status_id' => Yii::t('invoices', 'InvoiceStatus'),
			'budget_id' => Yii::t('invoices', 'InvoiceBudget'),
		);
	}
	
	public function search()
	{
		$selected = Yii::app()->user->getState('project_selected');
		
		$criteria=new CDbCriteria;

		$criteria->compare('invoice_number',trim($this->invoice_number),true);
		$criteria->compare('t.status_id',$this->status_id);
		$criteria->compare('budget_id',$this->budget_id);
		$criteria->compare('project_id',(!empty($selected)) ? $selected : $this->project_id);
		$criteria->order = 'Status.status_order ASC, t.invoice_date DESC';
		$criteria->with = array('Status');	

		$items = new CActiveDataProvider('Invoices', array(
			'criteria'=>$criteria,
		));
		
		$this->_itemsCount = $items->totalItemCount;
		return $items;
	}
	
	public function getItemsCount()
	{
		return $this->_itemsCount;
	}
}