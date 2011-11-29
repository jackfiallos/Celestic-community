<?php

/**
 * This is the model class for table "tb_projects_has_tb_users".
 *
 * The followings are the available columns in table 'tb_projects_has_tb_users':
 * @property integer $project_id
 * @property integer $user_id
 * @property integer $client_id
 *
 * The followings are the available model relations:
 */
class ProjectsHasUsersForm extends CFormModel
{
	public $project_id;
	public $user_id;
	public $client_id;
	public $isManager;
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id, user_id, client_id', 'required'),
			array('project_id, user_id, client_id', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('project_id, user_id, client_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'project_id' => Yii::t('ProjectsHasUsers', 'project_id'),
			'user_id' => Yii::t('ProjectsHasUsers', 'user_id'),
			'client_id' => Yii::t('ProjectsHasUsers', 'client_id'),
		);
	}
	
	public function saveUser()
	{
		$model = new ProjectsHasUsers;
		$model->project_id = $this->project_id;
		$model->user_id = $this->user_id;
		$model->isManager = $this->isManager;
		return $model->save();
	}
	
	public function saveClient()
	{
		$model = new ProjectsHasUsers;
		$model->project_id = $this->project_id;
		$model->user_id = $this->client_id;
		$model->isManager = $this->isManager;
		return $model->save();
	}
}