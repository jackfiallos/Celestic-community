<?php
/**
 * CheckboxState class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 * http://devgrow.com/iphone-style-switches/
 *
 **/
class CheckboxState extends CWidget
{
	public $state = true;
	public $elementId = 0;
	/**
	 * Execute the widget
	 * @return template render
	 */
	public function run()
    {
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerCss('CheckboxState',"
			.cb-enable, .cb-disable, .cb-enable span, .cb-disable span { background: url(images/switch.gif) repeat-x; display: block; float: left; }
		    .cb-enable span, .cb-disable span { line-height: 30px; display: block; background-repeat: no-repeat; font-weight: bold; }
		    .cb-enable span { background-position: left -90px; padding: 0 10px; }
		    .cb-disable span { background-position: right -180px;padding: 0 10px; }
		    .cb-disable.selected { background-position: 0 -30px; }
		    .cb-disable.selected span { background-position: right -210px; color: #fff; }
		    .cb-enable.selected { background-position: 0 -60px; }
		    .cb-enable.selected span { background-position: left -150px; color: #fff; }
		    .switch label { cursor: pointer; }
		    .switch input { display: none; }
		");
    	Yii::app()->clientScript->registerScript('jquery.CheckboxState','
		    $(".switch").parent().contents().filter(function() {
			    return this.nodeType == 3; //Node.TEXT_NODE
			}).remove();
    		$(".cb-enable").click(function(){
		        var parent = $(this).parents(".switch");
		        $(".cb-disable",parent).removeClass("selected");
		        $(this).addClass("selected");
		        $.ajax({
					type:"POST",
					url:"'.Yii::app()->createUrl('configuration/changeStatus').'",
					data:({id: parent.attr("id"),YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'"}),
				});
		    });
		    $(".cb-disable").click(function(){
		        var parent = $(this).parents(".switch");
		        $(".cb-enable",parent).removeClass("selected");
		        $(this).addClass("selected");
		        $.ajax({
					type:"POST",
					url:"'.Yii::app()->createUrl('configuration/changeStatus').'",
					data:({id: parent.attr("id"),YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'"}),
				});
		    });
		');
    	$this->render('CheckboxState');
    }
}
?>