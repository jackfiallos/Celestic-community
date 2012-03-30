<?php
	$cs = Yii::app()->getClientScript();
	$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/redmond/jquery-ui.css'); 
	Yii::app()->clientScript->registerCoreScript('jquery.ui');
	$this->widget('application.extensions.fullcalendar.FullcalendarGraphWidget', 
		array(
			'options'=>array(
				'editable'=> false,
				'theme' => true,
				'header' => array(
					'left' => 'prev,next today',
					'right' => 'title'
				),
				'buttonText' => array(
					'today' => Yii::t('site','calendarButtonToday')
				),
				'events' => $this->getCalendarEvents(),
				'monthNames' => Yii::t('site','calendarMonths'),
				'dayNamesShort' => Yii::t('site','calendarSortDays'),
				'eventClick' => 'js:function(calEvent, jsEvent, view) {
					alert(calEvent.title);
					//$("#myDialog").dialog();
				}',
				'eventRender' => 'js:function(event, element) {
        			element.find(".fc-event-title").html("").append($("<span class=\"fc-event-icons\"><img src=\"'.Yii::app()->request->baseUrl.'/images/portlets/charts.png\"></span>"));
    			}',
			),		
			'htmlOptions' => array(
				'style'=>'width:98%;margin: 0 auto;'
			),
		)
	);
?>
<!-- <div id="myDialog"></div> -->