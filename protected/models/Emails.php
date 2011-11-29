<?php

/**
 * This is the model class for table "tb_emails".
 *
 * The followings are the available columns in table 'tb_emails':
 * @property integer $email_id
 * @property string $email_subject
 * @property string $email_body
 * @property integer $email_priority
 * @property integer $email_status
 * @property string $email_creationDate
 * @property string $email_sentDate
 * @property string $email_toName
 * @property string $email_toMail
 */
class Emails extends CActiveRecord
{
    const PRIORITY_LOW = 5;
	const PRIORITY_NORMAL = 3;
	const PRIORITY_HIGH = 1;

	/**
     * Returns the static model of the specified AR class.
     * @return Emails the static model class
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
        return 'tb_emails';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('email_subject, email_body, email_priority, email_creationDate, email_toName, email_toMail', 'required'),
            array('email_priority, email_status', 'numerical', 'integerOnly'=>true),
            array('email_subject', 'length', 'max'=>80),
            array('email_toName, email_toMail', 'length', 'max'=>100),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'email_id' => 'Email',
            'email_subject' => 'Email Subject',
            'email_body' => 'Email Body',
            'email_priority' => 'Email Priority',
            'email_status' => 'Email Status',
            'email_creationDate' => 'Email Creation Date',
            'email_sentDate' => 'Email Sent Date',
        	'email_toName' => 'Name',
        	'email_toMail' => 'Email',
        );
    }
} 