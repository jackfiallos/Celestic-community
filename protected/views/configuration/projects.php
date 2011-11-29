<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration') => array('configuration/admin'),
	Yii::t('configuration', 'Projects'),
);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo configuration"><?php echo Yii::t('configuration', 'ProjectsManage'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('projects', 'CreateProjects'), Yii::app()->controller->createUrl('configuration/createproject'),array('class'=>'button primary')); ?>
		</div>
		<ul class="portlet-tab-nav">
			<li class="portlet-tab-nav-active">
				
			</li>
		</ul>
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'projects-grid',
			'cssFile'=>Yii::app()->request->baseUrl."/css/screen.css",
			'dataProvider'=>$dataProvider,
			'summaryText'=>Yii::t('site','summaryText'),
			'emptyText'=>Yii::t('site','emptyText'),
			'columns'=>array(
				array(
					'name'=>'project_name',
					'type'=>'raw',
					'value' =>'CHtml::link($data->project_name,Yii::app()->controller->createUrl("projects/view",array("id"=>$data->project_id)))',
				),
				array(
					'name'=>'project_startDate',
					'type'=>'text',
					'value'=>'CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->project_startDate, "medium", false))',
				),
				array(
					'name'=>'project_endDate',
					'type'=>'text',
					'value'=>'(strtotime($data->project_endDate)!=null) ? CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->project_endDate, "medium", false)) : "N/A"',
				),
				array(
					'name'=>'project_active',
					'type'=>'boolean',
					'value' =>'$this->grid->owner->widget("widgets.CheckboxState", array("state"=>$data->project_active, "elementId"=>$data->project_id))',
				),
			),
		)); ?>
	</div>
</div>