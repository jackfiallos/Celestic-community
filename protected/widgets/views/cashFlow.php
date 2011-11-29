<?php
	$this->Widget('application.extensions.highcharts.HighchartsWidget', array(
	   'options'=>array(
			'credits' => array(
				'enabled' => false
			),
			'chart'=>array(
				'defaultSeriesType' => 'column',
				'height' => '300',
				'marginRight'=> 130,
			),
			'title' => array(
				'text' => 'Flujo de Dinero'
			),
			'yAxis' => array(
				'title' => array(
					'text' => '$ MXN'
				),
				'min'=>0,
			),
			'xAxis' => array(
				'categories' => array(
					'Ene', 'Feb', 'Mar', 'Abr'
				),
			),
			'tooltip' => array(
				'formatter' => 'js:function() {
            		return "$ "+ Highcharts.numberFormat(this.y, 0) +" MXN";
         		}',
			),
			'legend'=>array(
				'layout'=>'vertical',
				'align'=> 'left',
				'verticalAlign'=> 'top',
				'x'=> 100,
				'y'=> 70,
				'floating'=> true,
				'shadow'=> true,
				'backgroundColor'=> '#FFFFFF',
				'borderWidth'=>1,
			),
			'series' => array(
				array(
					'name'=>'Entradas',
					'data'=>array(13000,7500,18000,33000)
				),
				array(
					'name'=>'Salidas',
					'data'=>array(3400,100,7200,28000)
				),
			),
		)
	));
?>