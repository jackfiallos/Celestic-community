<?php

/**
 * This is the model class for table "tb_diagrams".
 *
 * The followings are the available columns in table 'tb_diagrams':
 * @property integer $diagram_id
 * @property string $diagram_name
 * @property integer $project_id
 * @property integer $status_id
 */
class Diagrams extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Diagrams the static model class
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
		return 'tb_diagrams';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('diagram_name, project_id, status_id', 'required'),
			array('project_id, status_id', 'numerical', 'integerOnly'=>true),
			array('diagram_name', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('diagram_id, diagram_name, project_id, status_id', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'diagram_id' => 'Diagram',
			'diagram_name' => 'Diagram Name',
			'project_id' => 'Project',
			'status_id' => 'Status',
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

		$criteria->compare('diagram_id',$this->diagram_id);
		$criteria->compare('diagram_name',$this->diagram_name,true);
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
				'attributes' => array('diagram_name'),
			),
		);
	}
}