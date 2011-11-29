<?php

/**
 * This is the model class for table "tb_status".
 *
 * The followings are the available columns in table 'tb_status':
 * @property integer $status_id
 * @property string $status_name
 * @property int $statur_order
 * @property int $status_value
 * @property boolean $status_required
 */
class Status extends CActiveRecord
{
	const STATUS_PENDING	=	1;
	const STATUS_CANCELLED	=	2;
	const STATUS_ACCEPTED	=	3;
	const STATUS_CLOSED		=	4;
	const STATUS_TOTEST		=	5;
	const STATUS_INPROGRESS	=	6;
	const STATUS_TOREVIEW	=	7;
	
	const STATUS_PENDING_WORK		=	0;
	const STATUS_CANCELLED_WORK		=	100;
	const STATUS_ACCEPTED_WORK		=	25;
	const STATUS_CLOSED_WORK		=	100;
	const STATUS_TOTEST_WORK		=	75;
	const STATUS_INPROGRESS_WORK	=	50;
	
	const STATUS_COMMENT = 'StatusChanged';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Status the static model class
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
		return 'tb_status';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('status_name', 'required'),
			array('status_name', 'length', 'max'=>45),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'status_id' => 'Status',
			'status_name' => 'Status Name',
			'status_order' => 'Status Order',
			'status_value' => 'Status Value',
			'status_required' => 'Status Required',
		);
	}
	
	public function behaviors()
	{
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('status_name'),
			),
		);
	}
	
	public function afterFind()
	{
		$this->status_name = Yii::t('status',$this->status_name);
		return parent::afterFind();
	}
	
	public function findAllOrdered()
	{
		return Status::model()->findAll(array(
			'order'=>'t.status_order ASC'
		));
	}
	
	public function findAllRequired($required)
	{
		return Status::model()->findAll(array(
			'order'=>'t.status_order ASC',
			'condition'=>(!(bool)$required) ? 't.status_id != 1' : 't.status_required = '.$required,
		));
	}
}