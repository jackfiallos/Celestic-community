<?php
$this->Widget('application.extensions.highcharts.HighchartsWidget', array(
	'options'=>array(
		'credits' => array(
			'enabled' => false
		),
		'chart'=>array(
			'defaultSeriesType' => 'column'
		),
		'title' => array('text' => Yii::t('projects','TasksStatus')),
		'subtitle' => array('text' => Yii::t('projects','BasedOn').' '.$TaskAll.' '.Yii::t('projects','notasks')),
		'tooltip' => array(
			'formatter' => 'js:function(){
				return this.y +" '.Yii::t('projects','notasks').'";
			}',
		),
		'yAxis' => array(
			'title' => array('text' => Yii::t('projects','numberOfTasks')),
			'min'=>0,
		),
		'xAxis' => array(
			'labels' => array(
				'enabled' => false
			),
		),
		'series' => $seriesStatus,
	)
));
?>