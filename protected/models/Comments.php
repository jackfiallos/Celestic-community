<?php

/**
 * This is the model class for table "tb_comments".
 *
 * The followings are the available columns in table 'tb_comments':
 * @property integer $comment_id
 * @property string $comment_date
 * @property string $comment_text
 * @property string $comment_resourceid 
 * @property integer $module_id
 * @property integer $user_id
 * @property integer $user_id
 *
 * The followings are the available model relations:
 */
class Comments extends CActiveRecord
{
	public $image;
	/**
	 * Returns the static model of the specified AR class.
	 * @return Comments the static model class
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
		return 'tb_comments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('comment_date, comment_text, user_id, module_id, comment_resourceid', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('user_id, module_id, comment_resourceid', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('comment_id, comment_date, comment_text, user_id, module_id, comment_resourceid', 'safe', 'on'=>'search'),
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
			'User'=>array(self::BELONGS_TO, 'Users', 'user_id'),
			'Module'=>array(self::BELONGS_TO, 'Modules', 'module_id'),
			'Documents'=>array(self::HAS_MANY, 'Documents', 'comment_id', 'joinType'=>'INNER JOIN'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'comment_id' => Yii::t('comments','Comment'),
			'comment_date' => Yii::t('comments','Date'),
			'comment_text' => Yii::t('comments','Text'),
			'comment_resourceid' => Yii::t('comments','Resource'),
			'user_id' => Yii::t('comments','User'),
			'module_id' => Yii::t('comments','Module'),
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

		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('comment_date',$this->comment_date,true);
		$criteria->compare('comment_text',$this->comment_text,true);
		$criteria->compare('comment_resourceid',$this->comment_resourceid,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('module_id',$this->module_id);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function findComments($module, $resource)
    {
        return Comments::model()->with('Module')->findAll(array(
            'condition'=>'Module.module_name = :module AND t.comment_resourceid = :resource',
			'params'=>array(
				':module'=>$module,
				':resource'=>$resource,
			),
            'order'=>'t.comment_id ASC',
			'together' => true,
        ));
    }
	
	public function findAttachments($comment_id)
	{
		return Documents::model()->findAll(array(
			'condition'=>'t.comment_id = :comment_id',
			'params'=>array(
				'comment_id'=>$comment_id,
			),
            'order'=>'t.document_id',
        ));
	}
	
	public function CommentPropietary($user_id, $comment_id)
	{
		$isPropietary = self::model()->count(array(
			'condition'=>'user_id = :user_id AND comment_id = :comment_id',
			'params'=>array(
				':user_id'=>$user_id,
				':comment_id'=>$comment_id,
			),
			'limit'=>1,
		));
		return (bool)$isPropietary;
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('comment_date', 'comment_text', 'comment_resourceid', 'user_id', 'module_id'),
			),
		);
	}
}