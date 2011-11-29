<?php
/**
* YiiEditableText class file.
*
* @author jackfiallos
* @original http://valums.com/edit-in-place/
* @version 0.0.1
* @copyright Copyright &copy; 2008-2010 by Yii Software LLC.
* @description
* YiiEditableText generates an alternative lightbox for callback or inline content.
*
* The YiiEditableText widget is implemented with this jQuery plugin:
* (see {@link https://github.com/valums/editableText}).
*
*
* @package application.extensions.YiiEditableText
* @since 0.0.1
*/
class YiiEditableText extends CWidget
{
	public $element;
	public $options = array();
	
	protected function publishAssets()
    {
       $assets =  dirname(__FILE__).DIRECTORY_SEPARATOR.'editableText/';
	   return Yii::app()->assetManager->publish($assets,false,-1,true);
    }
	
    public function run()
	{                                     
        $id=$this->getId();
		$cs=Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');  
        $assetPath=$this->publishAssets();        
		$cs->registerScriptFile($assetPath.'/jquery.editinplace.js',CClientScript::POS_HEAD);
		$options=(count($this->options)>0) ? CJavaScript::encode($this->options) : '';		
		Yii::app()->clientScript->registerScript(
			__CLASS__.'#'.$id, 
			"
			$('".$this->element."').inlineEdit(".$options.").attr('title','".$this->options['tooltip']."');",
			CClientScript::POS_READY
		);
    }
}