<?php
/**
 * Optiontransferselect class file.
 *
 * @author jackfiallos
 * @original sharehua http://www.yiiframework.com/forum/index.php?/user/303-sharehua/
 * @version 0.0.1
 * @link original version taken from http://www.yiiframework.com/extension/optiontransferselect/
 * @copyright Copyright &copy; 2008-2010 by Yii Software LLC.
 * @license
 *
 * Copyright &copy; 2008-2010 by Yii Software LLC. All rights reserved.
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
 * Optiontransferselect generates an alternative listbox with multiselect items for change between another listbox.
 *
 * The Optiontransferselect widget is implemented with this jQuery plugin:
 * (see {@link http://code.google.com/p/jqmultiselects/}).
 * 
 *
 * @package application.extensions.optiontransferselect
 * @since 0.0.1
 */
class Optiontransferselect extends CWidget{
	/**
	 * Titulo de las etiquetas para los listbox
	 * opcional
	 * @var string
	 */
	public $leftTitle;
	public $rightTitle;
	/**
	 * Prefijo paralos listbox
	 * requerido
	 * @var string
	 */
	public $listName;
	/**
	 * Arreglo de datos (value=>display)
	 * requerido
	 * @var array
	 */
	public $LeftDataList;
	public $RightDataList;
	/**
	 * Arreglo para las opciones html (value=>display)
	 * opcional
	 * @var array
	 */
	public $LeftListHtmlOptions = array();
	public $RightListHtmlOptions = array();
	
	//***************************************************************************
	// Registrar libreria jquery.multiselects
	//***************************************************************************
	protected function registerClientScript()
	{
		//Yii::app()->clientScript->registerCoreScript('jquery');
		$file2=dirname(__FILE__).DIRECTORY_SEPARATOR.'jquery.multiselects.js';
		$jsFile2=Yii::app()->getAssetManager()->publish($file2);
		$cs=Yii::app()->clientScript;
		$cs->registerScriptFile($jsFile2);
	}
	//***************************************************************************
	// Inicializacion y validacion
	//***************************************************************************
	public function init(){
		if(!isset($this->listName)){
			throw new CHttpException(500,'"listName" have to be set!');
		}
		if(!isset($this->LeftDataList)){
			throw new CHttpException(500,'"LeftDataList" have to be set!');
		}
		if(!isset($this->RightDataList)){
			throw new CHttpException(500,'"RightDataList" have to be set!');
		}
	}
	//***************************************************************************
	// Run
	//***************************************************************************
	public function run()
	{
		echo "<div class=\"subcolumns\">\n";
		echo "	<div class=\"c38l\">\n";
			if(isset($this->leftTitle))
				echo CHtml::label($this->leftTitle,'select_left')."<br />";
			$this->LeftListHtmlOptions = array_merge($this->LeftListHtmlOptions, array('id'=>'select_left','multiple'=>'multiple'));
			echo CHtml::listBox($this->listName."[itemsleft]", '', $this->LeftDataList, $this->LeftListHtmlOptions);
		echo "	</div>\n";
		echo "	<div class=\"c20l\" style=\"text-align:center;padding-top:5%\">\n";
			echo CHtml::button('<', array('id'=>'options_left', 'class'=>'btnMove'))."<br /><br />";
			echo CHtml::button('>', array('id'=>'options_right', 'class'=>'btnMove'))."<br /><br />";
			echo CHtml::button('<<', array('id'=>'options_left_all', 'class'=>'btnMove'))."<br /><br />";
			echo CHtml::button('>>', array('id'=>'options_right_all', 'class'=>'btnMove'))."<br /><br />";
		echo "	</div>\n";
		echo "	<div class=\"c38r\">\n";
			if(isset($this->rightTitle))
				echo CHtml::label($this->rightTitle,'select_right')."<br />";;
			$this->RightListHtmlOptions = array_merge($this->RightListHtmlOptions, array('id'=>'select_right','multiple'=>'multiple'));
			echo CHtml::listBox($this->listName."[itemsright]", '', $this->RightDataList, $this->RightListHtmlOptions);
		echo "	</div>\n";
		echo "</div>\n";
		
		//$this->registerClientScript();
		
		$file2=dirname(__FILE__).DIRECTORY_SEPARATOR.'jquery.multiselects.js';
		$jsFile2=Yii::app()->getAssetManager()->publish($file2);
		$cs = Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile($jsFile2);
		
		$cs->registerScript(get_class($this).'_optTransfer','
			$(function() {
				$("#select_left").multiSelect("#select_right", {trigger:"#options_right"});
				$("#select_right").multiSelect("#select_left", {trigger:"#options_left"});
				$("#select_left").multiSelect("#select_right", {allTrigger:"#options_right_all"});
				$("#select_right").multiSelect("#select_left", {allTrigger:"#options_left_all"});
				
				$(".btnMove").click(function(e) {
					$("#select_left option").each(function(i) {
						$(this).attr("selected", "selected");
					});
				});				
			});
		',CClientScript::POS_READY);		
		
		parent::init();
	}

	protected function renderContent(){
	}
}
?>