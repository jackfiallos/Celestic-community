<?php
	$this->getProjectDataEffort();
	if (count($this->seriesStages) > 0)
	{
		$this->Widget('application.extensions.highcharts.HighchartsWidget', array(
		   'options'=>array(
				'credits' => array('enabled' => false),
				'exporting' => array('enabled' => false),
				'chart'=>array(
					'height' => '300',
					//'marginRight'=> 130
				),
				'title' => array('text' => null),
				'plotArea' => array(
					'shadow' => null,
					'borderWidth' => null,
					'backgroundColor' => null,
				),
				'tooltip' => array(
					'formatter' => 'js:function(){
						return "<b>"+ this.point.name +"</b>: "+ Math.round(this.percentage) +"%";
					}',
				),
				'plotOptions' => array(	
					'pie' => array(
						'allowPointSelect' => true,
						'cursor' => 'pointer',
						'dataLabels' => array(
							'enabled' => false
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
						//'name'=>'BR SH',
						'data'=>$this->seriesStages,
						'size'=>180,
					),
				),
			)
		));
	}
?>