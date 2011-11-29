<?php
/**
 * CSafeContentBehavior class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * This behavior could be attached to any model with declaring the attributes we would like to make them XSS safe.
 * @property array $attributes
 *
 **/
class CSafeContentBehavior extends CActiveRecordBehavior
{
    /**
	 * @var array all attributes of form passed
	 */
	public $attributes = array();
	/**
	 * @var protected object heritance from CHtmPurifier
	 */
    protected $purifier;
 
	/**
	 * Class object constructor
	 */
    function __construct(){
        $this->purifier = new CHtmlPurifier;
    }
 
	/**
	 * Invoked before saving a record (after validation, if any)
	 * @param CModelEvent $event the event parameter
	 */
    public function beforeSave($event)
    {
        foreach($this->attributes as $attribute){
            $this->getOwner()->{$attribute} = $this->purifier->purify($this->getOwner()->{$attribute});
        }
    }
}