<?php
class DocumentsSearchForm extends CFormModel
{
	public $document_name;
	public $document_description;
	public $document_revision;
	public $document_type;
	public $project_id;
	private $_itemsCount;
	private $extensions = array(
		'sql'	=> 'application/octet-stream',
		'txt'	=> 'text/plain',
		'png'	=> 'image/png',
		'jpeg'	=> 'image/jpeg',
		'jpg'	=> 'image/jpeg',
		'gif'	=> 'image/gif',
		'bmp'	=> 'image/bmp',
		'svg'	=> 'image/svg+xml',
		'zip'	=> 'application/zip',
		'rar'	=> 'application/x-rar-compressed',
		'wav'	=> 'audio/mpeg',
		'bz'	=>	'application/x-bzip',
		'bz2'	=> 'application/x-bzip2',
		'z'		=> 'application/x-compress',
		'tar' 	=> 'application/x-tar',
		'ogg'	=> 'audio/x-ogg',
		'pdf'	=> 'application/pdf',
		'psd'	=> 'image/vnd.adobe.photoshop',
		'ai'	=> 'application/postscript',
		'doc'	=> 'application/msword',
		'rtf'	=> 'application/rtf',
		'xls'	=> 'application/vnd.ms-excel',
		'ppt'	=> 'application/vnd.ms-powerpoint',
		'odt'	=> 'application/vnd.oasis.opendocument.text',
		'ods'	=> 'application/vnd.oasis.opendocument.spreadsheet'
	);
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('document_name, document_description, document_revision, document_type', 'length', 'max'=>45, 'message'=>Yii::t('inputValidations','MaxValidation')),
			array('project_id', 'numerical', 'integerOnly'=>true),
			array('document_name, document_description, document_revision, document_type', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'document_name' => Yii::t('documents','document_name'),
			'document_description' => Yii::t('documents','document_description'),
			'document_revision' => Yii::t('documents','document_revision'),
			'document_type' => Yii::t('documents','document_type')
		);
	}
	
	public function search()
	{
		$selected = Yii::app()->user->getState('project_selected');
		
		$criteria=new CDbCriteria;
		$criteria->compare('document_name',trim($this->document_name),true);
		$criteria->compare('document_description',trim($this->document_description),true);
		$criteria->compare('document_revision',trim($this->document_revision),true);
		$criteria->compare('project_id',(!empty($selected)) ? $selected : ($this->project_id));
		$criteria->compare('document_type',isset($this->extensions[trim($this->document_type)]) ? $this->extensions[trim($this->document_type)] : null, true);
		/*$criteria->join = 'LEFT JOIN tb_documents dt ON t.document_baseRevision = dt.document_baseRevision AND t.document_id < dt.document_id';
		$criteria->condition = 'dt.document_id IS NULL AND t.project_id = :project_id';
		$criteria->condition = 't.project_id = :project_id';
		$criteria->params = array(
			':project_id' => $selected,
		);*/
		$criteria->order = 't.document_id DESC';

		$items = new CActiveDataProvider('Documents', array(
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