<?php
/**
 * EAsmSelectEx class file.
 *
 * @author ironic
 * @version 1.3
 * @link http://www.yiiframework.com/extension/asmselectex/
 * @copyright Copyright &copy; 2009 ironic
 * @license
 *
 * Copyright © 2009 by ironic. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * - Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 * - Neither the name of ironic nor the names of its contributors may
 *   be used to endorse or promote products derived from this software without
 *   specific prior written permission.
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
 * EAsmSelectEx generates a alternative multiple Select Box (with optgroup support!).
 *
 * The EAsmSelectEx widget is implemented based this jQuery plugin:
 * (see {@link http://code.google.com/p/jquery-asmselect/}).
 *
 * Credits for the javascript OptGroup support goes to: 
 * Google-Code User: http://code.google.com/u/ideaoforder/
 * (details: http://code.google.com/p/jquery-asmselect/issues/detail?id=8)
 * 
 * This widget is way more useful as a <select multiple> (the default mode)
 *
 * @author ironic
 * @package application.extensions.asmselectex
 * @since 1.0
 */
class EAsmSelectEx extends CInputWidget
{
//***************************************************************************
// Properties
//***************************************************************************
   /**
    * The internal ID of the widget
	* (used as html id & jquery selector)
    *
    * @var string
    */
	private $_widgetId;
	
   /**
    * The internal Name of the widget
	* (used as html name, when no model 
	*  is passed to the widget)
    *
    * @var string
    */
	private $_widgetName;
	
   /**
    * The Data
    *
    * @var array
    */	
	private $_data = array();

   /**
    * The selected option tags
	* (when no model/attribute is
	*  passed to the widget)
    *
    * @var array
    */		
	private $_selected = array();
	
   /**
    * The stylesheet file for the widget
	* (overwrite to use your own css)
    *
    * @var string
    */
	private $_cssFile = "asmselect.css";	
   
   /**
    * The options for the jquery.asmselectex script 
    *
    * @var array
    */	
   private $_scriptOptions = array();
   
   /**
    * The avaible/valid options for the jquery.asmselectex script 
    *
    * @var array
    */
	private $_asmOptions = array(
		'listType'		=> array('ol', 'ul'), 			
		'sortable'		=> array(true, false), 
		'highlight'		=> array(true, false), 			
		'animate'		=> array(true, false), 
		'hideWhenAdded' => array(true, false), 		
		'addItemTarget' => array('top', 'bottom'),
		'debugMode'		=> array(true, false), 			
		'removeLabel',
		'highlightAddedLabel',	
		'highlightRemovedLabel',
		'containerClass',		
		'selectClass',
		'listClass', 			
		'listSortableClass',
		'listItemClass', 		
		'listItemLabelClass',
		'removeClass', 			
		'highlightClass'
	);

   /**
    * The javascript files shipped with the widget 
    *
	* jquery.ui.js (v1.5.3 compatible with jQuery v1.3)
	* jquery.tinysort.min.js (v1.0.2)
	* jquery.asmselectex.js (v1.0.2 beta "extended")
    *
    * @var array
    */	
	private $_scriptFiles = array(
		//'jquery.ui.js',
		'jquery.tinysort.min.js',
		//'jquery.asmselectex.js'
		'jquery.asmselect.js'
	);
							   
   /**
    * The exceptions thrown by the widget 
    *
    * @var array
    */	
	private $_exceptions = array(
		'string'      => '{c}: Invalid type. Property "{r}" must be a string.',
		'array'       => '{c}: Invalid type. Property "{r}" must be an array.',
		'empty'       => '{c}: Invalid value. Property "{r}" can not be empty.',
		'invalid'     => '{c}: Invalid "{r}" for jquery.asmselectex.js.',
		'missing'     => '{c}: Missing a required resource. {f} in directory: {d}',
	);	

//***************************************************************************
// Initializes the widget
//***************************************************************************
	public function init()
	{
		list($this->_widgetName, $this->_widgetId) = $this->resolveNameID();		

		if(!is_string($this->_widgetName) || !is_string($this->_widgetId))
			$this->e($this->_exceptions['string'], "name");	

		if(empty($this->_widgetName) || empty($this->_widgetId))
			$this->e($this->_exceptions['empty'], "name");	
	
		if(!is_array($this->htmlOptions))
			$this->e($this->_exceptions['array'], "htmlOptions");
	
		if(YII_DEBUG)
		{
			foreach($this->_scriptFiles as $scriptFile)
			{
				if(!file_exists($this->jsFolder.$scriptFile))
					$this->e($this->_exceptions['missing'], array('{f}'=>$scriptFile, 
																  '{d}'=>$this->jsFolder));
			}	
		}	
		parent::init();
	}

//***************************************************************************
// Run the widget
//***************************************************************************
	public function run()
	{
		$this->registerClientScript();	

		$this->htmlOptions['id'] = $this->_widgetId;
		$this->htmlOptions['multiple'] = "multiple";

		$html = ($this->hasModel())
		? CHtml::activeDropDownList($this->model, $this->attribute.'[]', $this->_data, $this->htmlOptions)
		: CHtml::dropDownList($this->_widgetName.'[]', $this->_selected, $this->_data, $this->htmlOptions);
					
		echo $html;	 
	}
	
//***************************************************************************
// register clientside widget files
//***************************************************************************
	public function registerClientScript()
	{
		// publish the assets folder
		$baseUrl = Yii::app()->getAssetManager()->publish($this->assetsFolder);
		
		// get an Instance of CClientScript
		$cs = Yii::app()->getClientScript();

		// register the required javascript files
		$cs->registerCoreScript('jquery');
		
		foreach($this->_scriptFiles as $scriptFile)
			$cs->registerScriptFile($baseUrl.'/js/'.$scriptFile);
		
		// register the current Stylesheet	
		$cs->registerCssFile($baseUrl.'/css/'.$this->_cssFile);			
		
		// encode the scriptOptions
		$scrOptions = (!empty($this->_scriptOptions))
					  ? CJavaScript::encode($this->_scriptOptions) 
					  : '';
		// register the document.ready function		
		$script = '$("#'.$this->_widgetId.'").asmSelect('.$scrOptions.');';
		$cs->registerScript(get_class($this).'_'.$this->_widgetId, $script, CClientScript::POS_READY);
	}
	
//***************************************************************************
// setters and getters for the widgets properties
//***************************************************************************
// read-only properties
	public function getWidgetId()
	{
		return $this->_widgetId;
	}
	
	public function getWidgetName()
	{
		return $this->_widgetName;
	}

	public function getAssetsFolder()
	{
		return dirname(__FILE__).DIRECTORY_SEPARATOR.'assets';
	}
	
	public function getCssFolder()
	{
		return $this->assetsFolder.DIRECTORY_SEPARATOR.'css'.DIRECTORY_SEPARATOR;
	}	

	public function getJsFolder()
	{
		return $this->assetsFolder.DIRECTORY_SEPARATOR.'js'.DIRECTORY_SEPARATOR;
	}	
	
// read and writeable properties
	public function setData($data)
	{
		if(!is_array($data))
			$this->e($this->_exceptions['array'], "data");	

		$this->_data = $data;
	}
			
	public function getData()
	{
		return $this->_data;
	}
	
	public function setSelected($selected)
	{
		if(!is_array($selected))
			$this->e($this->_exceptions['array'], "selected");	

		$this->_selected = $selected;
	}
	
	public function getSelected()
	{
		return $this->_selected;
	}
	
	public function setCssFile($cssFile)
	{
		if(!is_string($cssFile))
			$this->e($this->_exceptions['string'], "cssFile");

		if(empty($cssFile))
			$this->e($this->_exceptions['empty'], "cssFile");
		
		if(YII_DEBUG)
		{
			if(!file_exists($this->cssFolder.$cssFile))
				$this->e($this->_exceptions['missing'], array('{f}'=>$cssFile, 
															  '{d}'=>$this->cssFolder));			
		}
		$this->_cssFile = $cssFile;
	}	

	public function getCssFile()
	{
		return $this->_cssFile;
	}

	public function setScriptOptions($scriptOptions)
	{
		if(!is_array($scriptOptions))
			$this->e($this->_exceptions['array'], "scriptOptions");	
		
		foreach($scriptOptions as $k => $v)
		{
			if(!$this->isScriptOption($k, $v))
				$this->e($this->_exceptions['invalid'], "scriptOption");
		}
		$this->_scriptOptions = $scriptOptions;
	}

	public function getScriptOptions()
	{
		return $this->_scriptOptions;
	}	

//***************************************************************************
// Helper functions
//***************************************************************************
// applies a single Script Option
	public function applyScriptOption($key, $value)
	{
		($this->isScriptOption($key, $value))
		? $this->_scriptOptions[$key]=$value
		: $this->e($this->_exceptions['invalid'], "scriptOption");
	}	

// check if a scriptOption is valid for jquery.asmselectex.js
	public function isScriptOption($key, $value="")
	{
		/*if(is_array($this->_asmOptions[$key]))
		{
			return in_array($value, $this->_asmOptions[$key], TRUE);
		}
		else
			return in_array($key, $this->_asmOptions);*/
			return true;
	}
	
// wrapper for the widget´s exceptions
	private function e($msg, $replace)
	{
		if(is_string($replace))
		{
			$tmp['{r}'] = $replace;
			$replace = $tmp;
		}	
		if(is_array($replace) && !empty($replace))
		{
			$replace['{c}'] = get_class($this);
			throw new CException(Yii::t(get_class($this), $msg, $replace));
		}
		else
			throw new CException(Yii::t(get_class($this), $msg));
	}	
}
?>
