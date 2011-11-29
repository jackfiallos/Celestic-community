<?php
/**
* Colorbox class file.
*
* @author jackfiallos
* @original http://colorpowered.com/colorbox/
* @version 0.0.1
* @copyright Copyright &copy; 2008-2010 by Yii Software LLC.
* @license
*
* Copyright &copy; 2008-2010 by Yii Software LLC. All rights reserved.
*
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
* - Redistributions of source code must retain the above copyright notice, this
* list of conditions and the following disclaimer.
* - Redistributions in binary form must reproduce the above copyright notice,
* this list of conditions and the following disclaimer in the documentation
* and/or other materials provided with the distribution.
* - Neither the name of ironic nor the names of its contributors may
* be used to endorse or promote products derived from this software without
* specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
* AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
* IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
* ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
* LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
* CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
* SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
* INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
* CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
* ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
* POSSIBILITY OF SUCH DAMAGE.
*/

/**
* YiiColorBox generates an alternative lightbox for callback or inline content.
*
* The Colorbox widget is implemented with this jQuery plugin:
* (see {@link https://github.com/jackmoore/colorbox}).
*
*
* @package application.extensions.Colorbox
* @since 0.0.1
*/
class Colorbox extends CWidget
{
	public $element;
	public $options = array();
	
	protected function publishAssets()
    {
       $assets =  dirname(__FILE__).DIRECTORY_SEPARATOR.'colorbox/';
	   return Yii::app()->assetManager->publish($assets,false,-1,true);
    }
	
    public function run()
	{                                     
        $id=$this->getId();
		$cs=Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');  
        $assetPath=$this->publishAssets();        
        $cs->registerCssFile($assetPath.'/colorbox.css');         
        $cs->registerScriptFile($assetPath.'/jquery.colorbox.js',CClientScript::POS_HEAD);   
		
		$options=(count($this->options)>0) ? CJavaScript::encode($this->options) : '';
		Yii::app()->clientScript->registerScript(
			__CLASS__.'#'.$id, 
			"$('".$this->element."').colorbox(".$options.");",
			CClientScript::POS_READY
		);
    }
}