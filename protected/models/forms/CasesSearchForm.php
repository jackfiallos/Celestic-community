<?php
class CasesSearchForm extends CFormModel
{
	public $case_code;
	public $case_name;
	public $case_description;
	public $case_priority;
	public $case_requirements;
	public $project_id;
	public $status_id;
	private $_itemsCount;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('case_code, case_name, case_description, case_requirements', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('case_priority, project_id, status_id', 'numerical', 'integerOnly'=>true),
			array('case_code, case_name, case_description, case_priority, case_requirements', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'case_code' => Yii::t('cases','case_code'),
			'case_name' => Yii::t('cases','case_name'),
			'case_description' => Yii::t('cases','case_description'),
			'project_id' => Yii::t('cases','project_id'),
			'case_priority' => Yii::t('cases','case_priority'),
			'case_requirements' => Yii::t('cases','case_requirements'),
			'status_id' => Yii::t('cases','status_id')
		);
	}
	
	public function search()
	{
		$selected = Yii::app()->user->getState('project_selected');
		
		$criteria=new CDbCriteria;
		$criteria->compare('t.case_code',trim($this->case_code),true);
		$criteria->compare('t.case_name',trim($this->case_name),true);
		$criteria->compare('t.case_description',trim($this->case_description),true);
		$criteria->condition = 't.project_id = :project_id AND 2=2';
		$criteria->params = array(
			':project_id' => (!empty($selected)) ? $selected : $this->project_id,
		);
		$criteria->compare('t.status_id',$this->status_id);
		$criteria->compare('t.case_priority',$this->case_priority);
		$criteria->compare('t.case_requirements',trim($this->case_requirements), true);
		$criteria->order = 't.case_id DESC';
		$criteria->with = array('Status');

		$items = new CActiveDataProvider('Cases', array(
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