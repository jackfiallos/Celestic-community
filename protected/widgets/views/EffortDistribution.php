<?php
	$this->getProjectDataEffort();
	if (count($this->seriesStages) > 0)
	{
		$this->Widget('application.extensions.highcharts.HighchartsWidget', array(
		   'options'=>array(
				'credits' => array(
					'enabled' => false
				),
				'chart'=>array(
					'height' => '300',
					'marginRight'=> 130,
				),
				'title' => array('text' => ''),
				'plotArea' => array(
					'shadow' => null,
					'borderWidth' => null,
					'backgroundColor' => null,
				),
				'tooltip' => array(
					'formatter' => 'js:function(){
						return "<b>"+ this.point.name +"</b>: "+ this.y +"%";
					}',
				),
				'plotOptions' => array(	
					'pie' => array(
						'allowPointSelect' => true,
						'cursor' => 'pointer',
						'dataLabels' => array(
							'enabled' => true,
							'color' => '#353535',
							'formatter' => 'js:function(){
								return "<b>"+ this.y +"%</b>";
							}',
						),
						'showInLegend' => true
					),
				),
				'legend'=>array(
					'layout'=>'horizontal',
					'verticalAlign'=> 'bottom',
					'backgroundColor'=> '#FFFFFF',
					'borderWidth'=>1,
				),
				'series' => array(
					array(
						'type'=>'pie',
						'name'=>'BR SH',
						'data'=>$this->seriesStages,
						'size'=>220,
					),
				),
			)
		));
	}
?>