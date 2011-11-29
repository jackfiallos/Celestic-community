<?php
class MilestonesSearchForm extends CFormModel
{
	public $milestone_title;
	public $milestone_description;
	public $user_id;
	public $project_id;
	private $_itemsCount;
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('milestone_title, milestone_description', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('user_id, project_id', 'numerical', 'integerOnly'=>true),
			array('milestone_title, milestone_description, user_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'milestone_title' => Yii::t('milestones','milestone_title'),
			'milestone_description' => Yii::t('milestones','milestone_description'),
			'user_id' => Yii::t('milestones','user_id'),
		);
	}
	
	public function search()
	{
		$selected = Yii::app()->user->getState('project_selected');
		
		$criteria=new CDbCriteria;
		$criteria->compare('milestone_title',trim($this->milestone_title),true);
		$criteria->compare('milestone_description',trim($this->milestone_description),true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('project_id',(!empty($selected)) ? $selected : ($this->project_id));
		$criteria->select = '*, (
			SELECT (SUM(Status.status_value)/COUNT(Tasks.task_id))
			FROM `tb_tasks` Tasks 
			LEFT OUTER JOIN `tb_status` Status ON Tasks.status_id = Status.status_id
			WHERE Tasks.milestone_id = t.milestone_id 
			) as percent';
		$criteria->order = 't.milestone_duedate DESC, percent ASC';

		$items = new CActiveDataProvider('Milestones', array(
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