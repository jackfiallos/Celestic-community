<?php
class ExpensesSearchForm extends CFormModel
{
	public $expense_name;
	public $expense_number;
	public $expense_identifier;
	public $project_id;
	public $status_id;
	private $_itemsCount;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('expense_name, expense_number, expense_identifier', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('status_id, project_id', 'numerical', 'integerOnly'=>true),
			array('expense_name, expense_number, expense_identifier', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'expense_name' => Yii::t('expenses', 'ExpenseName'),
			'expense_number' => Yii::t('expenses', 'ExpenseNumber'),
			'expense_identifier' => Yii::t('expenses', 'ExpenseIdentifier'),
			'status_id' => Yii::t('expenses', 'IdStatus'),
		);
	}
	
	public function search()
	{
		$selected = Yii::app()->user->getState('project_selected');
		
		$criteria=new CDbCriteria;

		$criteria->compare('expense_name',trim($this->expense_name),true);
		$criteria->compare('expense_number',trim($this->expense_number),true);
		$criteria->compare('expense_identifier',trim($this->expense_identifier),true);
		$criteria->compare('t.status_id',$this->status_id);
		$criteria->compare('project_id',(!empty($selected)) ? $selected : $this->project_id);
		$criteria->order = 'Status.status_order ASC, t.expense_id DESC';
		$criteria->with = array('Status');	

		$items = new CActiveDataProvider('Expenses', array(
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