<?php
/**
* Notify class file.
*
* @author jackfiallos
* @original https://github.com/ehynds/jquery-notify
* @version 0.0.1
* @copyright Copyright &copy; 2008-2010 by Yii Software LLC.

* The Notify widget is implemented with this jQuery plugin:
*
*
* @package application.extensions.YiiNotify
* @since 0.0.1
*/
class Notify extends CWidget
{
	public $element;
	public $options = array();
	
	protected function publishAssets()
    {
       $assets =  dirname(__FILE__).DIRECTORY_SEPARATOR.'notify/';
	   return Yii::app()->assetManager->publish($assets,false,-1,true);
    }
	
    public function run()
	{                                     
        $id=$this->getId();
		$cs=Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');  
        $assetPath=$this->publishAssets();        
        $cs->registerCssFile($assetPath.'/notify.css');         
        $cs->registerScriptFile($assetPath.'/jquery.notify.js',CClientScript::POS_HEAD);   
		
		$options=(count($this->options)>0) ? CJavaScript::encode($this->options) : '';
		Yii::app()->clientScript->registerScript(
			__CLASS__.'#'.$id, 
			"$('".$this->element."').notify(".$options.");",
			CClientScript::POS_READY
		);
    }
}