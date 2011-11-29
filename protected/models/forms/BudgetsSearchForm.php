<?php
class BudgetsSearchForm extends CFormModel
{
	public $budget_title;
	public $budget_notes;
	public $project_id;
	public $status_id;
	private $_itemsCount;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('budget_title, budget_notes', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('status_id, project_id', 'numerical', 'integerOnly'=>true),
			array('budget_title, budget_notes, status_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'budget_title' => Yii::t('budgets', 'FieldTitleBudget'),
			'budget_notes' => Yii::t('budgets', 'FieldNotesBudget'),
			'status_id' => Yii::t('budgets', 'FieldStatusBudget'),
		);
	}
	
	public function search()
	{
		$selected = Yii::app()->user->getState('project_selected');
		
		$criteria=new CDbCriteria;
		$criteria->compare('budget_title',trim($this->budget_title),true);
		$criteria->compare('budget_notes',trim($this->budget_notes),true);
		$criteria->compare('t.status_id',$this->status_id);
		$criteria->compare('project_id',(!empty($selected)) ? $selected : $this->project_id);
		$criteria->order = 'Status.status_order ASC, t.budget_date DESC';
		$criteria->with = array('Status');

		$items = new CActiveDataProvider('Budgets', array(
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