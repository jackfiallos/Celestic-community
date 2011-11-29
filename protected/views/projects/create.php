<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('configuration', 'Projects')=>array('configuration/projects'),
	Yii::t('projects', 'CreateProjects'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('projects', 'TitleProjects')." :: ".Yii::t('projects', 'CreateProjects');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo projects"><?php echo Yii::t('projects', 'CreateProjects'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('projects', 'ListProjects'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('../projects/_form', array('model'=>$model,'companies'=>$companies,'currencies'=>$currencies)); ?>
	</div>
</div>