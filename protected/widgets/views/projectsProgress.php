<?php
	$projects = $this->getProjectsProgress();
	if(count($projects) > 0)
	{ 
	$this->Widget('application.extensions.highcharts.HighchartsWidget', array(
	   'options'=>array(
			'credits' => array(
				'enabled' => false
			),
			'chart'=>array(
				'defaultSeriesType' => 'bar',
				'height' => '300',
				'marginRight'=> 130,
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
				'plotLines'=>array(
					'value'=>0,
					'width'=>1,
					'color'=>'#808080',
				),
			),
			'xAxis' => array(
				'categories' => array(
					0
				),
			),
			'tooltip' => array(
				'formatter' => 'js:function(){
					return this.y + "%";
				}',
			),
			'legend'=>array(
				'layout'=>'vertical',
				'align'=> 'right',
				'verticalAlign'=> 'top',
				'x'=> -10,
				'y'=> 40,
				'backgroundColor'=> '#FFFFFF',
				'borderWidth'=>1,
			),
			'series' => $this->getProjectsProgress(),
		)
	));
	}
?>