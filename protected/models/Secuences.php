<?php

/**
 * This is the model class for table "tb_secuences".
 *
 * The followings are the available columns in table 'tb_secuences':
 * @property integer $secuence_id
 * @property integer $secuence_step
 * @property string $secuence_action
 * @property integer $case_id
 * @property integer $secuenceTypes_id
 *
 * The followings are the available model relations:
 */
class Secuences extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Secuences the static model class
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
		return 'tb_secuences';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('secuence_step, secuence_action, case_id, secuenceTypes_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('secuence_step, case_id, secuenceTypes_id', 'numerical', 'integerOnly'=>true),
			array('secuence_action, secuence_responsetoAction', 'length', 'max'=>100, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('secuence_action', 'length', 'min'=>5, 'message'=>Yii::t('inputValidations','MinValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('secuence_id, secuence_step, secuence_action, secuence_responsetoAction, case_id, secuenceTypes_id', 'safe', 'on'=>'search'),
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
			'SecuenceTypes'=>array(self::BELONGS_TO, 'SecuenceTypes', 'secuenceTypes_id'),
			'Cases'=>array(self::BELONGS_TO, 'Cases', 'case_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'secuence_id' => Yii::t('secuences','secuence_id'),
			'secuence_step' => Yii::t('secuences','secuence_step'),
			'secuence_action' => Yii::t('secuences','secuence_action'),
			'secuence_responsetoAction' => Yii::t('secuences','secuence_responsetoAction'),
			'case_id' => Yii::t('secuences','case_id'),
			'secuenceTypes_id' => Yii::t('secuences','secuenceTypes_id'),
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

		$criteria->compare('secuence_id',$this->secuence_id);
		$criteria->compare('secuence_step',$this->secuence_step);
		$criteria->compare('secuence_action',$this->secuence_action,true);
		$criteria->compare('secuence_responsetoAction',$this->secuence_responsetoAction,true);
		$criteria->compare('case_id',$this->case_id);
		$criteria->compare('secuenseTypes_id',$this->secuenseTypes_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('secuence_step', 'secuence_action', 'secuence_responsetoAction'),
			),
		);
	}
}