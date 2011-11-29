<?php
$this->breadcrumbs=array(
	Yii::t('milestones', 'TitleMilestones')=>array('index'),
	$model->milestone_title,
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('milestones', 'TitleMilestones')." :: ".CHtml::encode($model->milestone_title);
?>
<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo milestones"><?php echo $model->milestone_title; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('milestones', 'ListMilestones'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('milestones', 'CreateMilestones'), Yii::app()->controller->createUrl('create'), array('class'=>'button')); ?>
			<?php if(Yii::app()->user->IsManager):?>
				<?php echo CHtml::link(Yii::t('milestones', 'UpdateMilestones'), Yii::app()->controller->createUrl('update',array('id'=>$model->milestone_id)), array('class'=>'button')); ?>
			<?php endif;?>
		</div>
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
				array(
					'name'=>'milestone_description',
					'type'=>'ntext',
					'value'=>Yii::app()->format->html(ECHtml::createLinkFromString(CHtml::encode($model->milestone_description))),
				),
				array(
					'name'=>'milestone_duedate',
					'type'=>'text',
					'value'=>CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->milestone_duedate, 'medium', false)),
				),
				array(
					'name'=>'user_id',
					'type'=>'raw',
					'value'=>isset($model->user_id) ? CHtml::link(CHtml::encode($model->Users->completeName),Yii::app()->controller->createUrl("users/view",array("id"=>$model->user_id))) : null,
				),
				array(
					'label'=>'',
					'type'=>'raw',
					'value'=>'<div class="prgrss-container" style="float:left;"><div style="width:'.round($percent,2).'%"><span>'.round($percent,2).'%</span></div></div>',
				),
			),
		)); ?>
		<br />
		<div id="content" class="container_12">
			<div class="portlet grid_6">
				<div class="portlet-content">
					<h1 class="ptitle tasks"><?php echo Yii::t('milestones','TasksGraphByType'); ?></h1>
					<?php
						$this->Widget('application.extensions.highcharts.HighchartsWidget', array(
						   'options'=>array(
								'credits' => array(
									'enabled' => false
								),
								'chart'=>array(
									'defaultSeriesType' => 'column',
									'height' => '220',
								),
								'title' => array(
									'text' => ''
								),
								'yAxis' => array(
									'title' => array(
										'text' => 'No. de tareas'
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
										return this.y + " Tareas";
									}',
								),
								'plotOptions' => array(
									'column' => array(
										'dataLabels' => array(
											'enabled'=>true,
										),
									),
								),
								'legend'=>array(
									'layout'=>'horizontal',
									'align'=> 'center',
									'verticalAlign'=> 'bottom',
									'backgroundColor'=> '#FFFFFF',
									'borderWidth'=>1,
								),
								'series' => $TasksStatus,
							)
						));
					?>
				</div>
			</div>
			<div class="portlet grid_6">
				<div class="portlet-content">
					<h1 class="ptitle tasks"><?php echo Yii::t('milestones','TasksGraphByPriority'); ?></h1>
					<?php
						$this->Widget('application.extensions.highcharts.HighchartsWidget', array(
						   'options'=>array(
								'credits' => array(
									'enabled' => false
								),
								'chart'=>array(
									'height' => '220',
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
												return "<b>"+ this.y +"</b>";
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
										'data'=>$TasksPriority,
										'size'=>160,
									),
								),
							)
						));
					?>
				</div>
			</div>
		</div>
		<div class="clear">&nbsp;</div>
		<div class="portlet x12">
			<div class="portlet-content">
				<h1 class="ptitle tasks"><?php echo Yii::t('milestones','RelatedTasks'); ?></h1>
			</div>
			<?php
				$now = strtotime(date("Y-m-d"));
				$milestone_start = strtotime($model->milestone_startdate);
				$milestone_end = strtotime($model->milestone_duedate);
				$allowSort = false;
				if (($now > $milestone_start) && ($now < $milestone_end))
					$allowSort = true;
				echo $this->renderPartial('_tasksSorter', array('tasks'=>$dataProviderTasks, 'milestone'=>$model, 'allowSort'=>$allowSort));
			?>
		</div>		
		<div class="result">
		<?php $this->widget('widgets.ListComments',array(
			'resourceid'=>$model->milestone_id,
			'moduleid'=>Yii::app()->controller->id,
		)); ?>
		</div>
	</div>
</div>