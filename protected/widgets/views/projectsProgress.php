<?php
	$projects = $this->getProjectsProgress();
	if(count($projects) > 0)
	{ 
		$this->Widget('application.extensions.highcharts.HighchartsWidget', array(
		   'options'=>array(
				'credits' => array('enabled' => false),
				'exporting' => array('enabled' => false),
				'chart'=>array(
					'defaultSeriesType' => 'column',
					'type'=>'column'
				),
				'title' => array(
					'text' => 'Progreso del proyecto'
				),
				'yAxis' => array(
					'title' => array(
						'text' => '% de avance'
					),
					'label' => array(
						'formatter' => 'js:function(){
							return this.value/1000+"k";
						}'
					),
					'min'=>0, 
					'max'=>100
				),
				'xAxis' => array(
					'categories' => array(
						0
					)
				),
				'tooltip' => array(
					'formatter' => 'js:function(){
						return this.y + "%";
					}'
				),
				'legend'=>array(
					'backgroundColor'=> '#FFFFFF',
					'borderWidth'=>1
				),
				'series' => $projects
			)
		));
	}
?>