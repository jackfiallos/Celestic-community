<?php

/**
 * This is the model class for table "tb_projects_has_tb_users".
 *
 * The followings are the available columns in table 'tb_projects_has_tb_users':
 * @property integer $project_id
 * @property integer $user_id
 * @property boolean $isManager
 *
 * The followings are the available model relations:
 */
class ProjectsHasUsers extends CActiveRecord
{
	public function primaryKey()
	{
		return 'projects_has_users_id';
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return ProjectsHasUsers the static model class
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
		return 'tb_projects_has_tb_users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id, user_id, isManager', 'required'),
			array('projects_has_users_id, project_id, user_id, isManager', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('project_id, user_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array();
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'projects_has_users_id' => 'PHU',
			'project_id' => 'Project',
			'user_id' => 'User',
			'isManager' => 'Is Manager',
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

		$criteria->compare('project_id',$this->project_id);
		$criteria->compare('user_id',$this->user_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function findBeforeDelete($user_id, $project_id)
	{
		return ProjectsHasUsers::model()->find(array(
			'condition'=>'t.user_id = :user_id AND t.project_id = :project_id',
			'params'=>array(
				':user_id'=>$user_id,
				':project_id'=>$project_id
			),
		));
	}
}