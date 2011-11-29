<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('projects', 'TitleProjects')=>array('index'),
	$model->project_name=>array('view','id'=>$model->project_id),
	Yii::t('projects', 'UpdateProjects'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('projects', 'UpdateProjects')." :: ".CHtml::encode($model->project_name);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo projects"><?php echo $model->project_name; ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('projects', 'ViewProjects'), Yii::app()->controller->createUrl('view',array('id'=>$model->project_id)),array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'companies'=>$companies,'currencies'=>$currencies)); ?>
	</div>
</div>