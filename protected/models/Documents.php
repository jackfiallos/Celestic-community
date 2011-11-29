<?php

/**
 * This is the model class for table "tb_documents".
 *
 * The followings are the available columns in table 'tb_documents':
 * @property integer $document_id
 * @property integer $project_id
 * @property string $document_name
 * @property string $document_description
 * @property string $document_path
 * @property integer $document_revision
 * @property string $document_uploadDate
 * @property string $document_type
 * @property string $comment_id
 * @property string $user_id
 */
class Documents extends CActiveRecord
{
	public $image;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Documents the static model class
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
		return 'tb_documents';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('project_id, document_name, document_description, image, user_id', 'required', 'message'=>Yii::t('inputValidations','RequireValidation')),
			array('project_id, document_revision, document_baseRevision, comment_id, user_id', 'numerical', 'integerOnly'=>true),
			array('document_name, document_type', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('document_path', 'length', 'max'=>255, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('document_name', 'length', 'min'=>8, 'message'=>Yii::t('inputValidations','MinValidation')),
			array('image', 'file', 'types'=>'doc, docx, rtf, ppt, pptx, odt, ods, xls, xlsx, sql, wav, ogg, pdf, psd, ai, txt, bmp, jpg, jpeg, gif, png, svg, zip, rar, bz, bz2, z, tar', 'message'=>Yii::t('inputValidations','FileTypeValidation')),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('document_id, project_id, document_name, document_description, document_path, document_revision, document_uploadDate, document_type, document_baseRevision, comment_id, user_id', 'safe', 'on'=>'search'),
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
			'Comment'=>array(self::BELONGS_TO, 'Comments', 'comment_id'),
			'User'=>array(self::BELONGS_TO, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'document_id' => Yii::t('documents','document_id'),
			'project_id' => Yii::t('documents','project_id'),
			'document_name' => Yii::t('documents','document_name'),
			'document_description' => Yii::t('documents','document_description'),
			'document_path' => Yii::t('documents','document_path'),
			'document_revision' => Yii::t('documents','document_revision'),
			'document_uploadDate' => Yii::t('documents','document_uploadDate'),
			'document_type' => Yii::t('documents','document_type'),
			'document_baseRevision' => Yii::t('documents','document_baseRevision'),
			'comment_id' => Yii::t('documents','comment_id'),
			'user_id' => Yii::t('documents','user_id'),
			'image' => Yii::t('documents','image'),
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
		$criteria->compare('document_description',$this->document_description,true);
		$criteria->compare('document_revision',$this->document_revision);
		$criteria->compare('document_type',$this->document_type,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}
	
	public function behaviors(){
		return array(
			'CSafeContentBehavor' => array( 
				'class' => 'application.components.CSafeContentBehavior',
				'attributes' => array('document_name', 'document_description', 'document_path', 'document_revision', 'document_uploadDate', 'document_type'),
			),
		);
	}
	
	public function findDocuments($project_id)
    {
		return Documents::model()->findAll(array(
			'condition'=>'t.document_id IN(
					SELECT MAX( t.document_id ) 
					FROM `tb_documents` `t` 
					WHERE t.project_id = :project_id
					GROUP BY t.document_baseRevision
				)',
			'params'=>array(
				':project_id'=>(!empty($project_id)) ? $project_id : 0,
			),
			'order'=>'t.document_id DESC',
			'limit'=>5,
		));
    }
    
	public function countDocumentsByProject($document_id, $project_id)
	{
		return Documents::model()->count(array(
			'condition'=>'t.project_id = :project_id AND t.document_id = :document_id',
			'params'=>array(
				':project_id' => $project_id,
				':document_id' => $document_id
			)
		));
	}
}