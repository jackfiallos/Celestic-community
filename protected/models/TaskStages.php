<?php

/**
 * This is the model class for table "tb_taskStages".
 *
 * The followings are the available columns in table 'tb_taskStages':
 * @property integer $taskStage_id
 * @property string $taskStage_name
 */
class TaskStages extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return TaskStages the static model class
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
		return 'tb_taskStages';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('taskStage_name', 'required'),
			array('taskStage_name', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('taskStage_id, taskStage_name', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'taskStage_id' => 'Task Stage',
			'taskStage_name' => 'Task Stage Name',
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

		$criteria->compare('taskStage_id',$this->taskStage_id);
		$criteria->compare('taskStage_name',$this->taskStage_name,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function afterFind()
	{
		$this->taskStage_name = Yii::t('taskStage',$this->taskStage_name);
		return parent::afterFind();
	}
}