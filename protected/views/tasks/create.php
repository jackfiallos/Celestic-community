<?php
$this->breadcrumbs=array(
	Yii::t('tasks', 'TitleTasks')=>array('index'),
	Yii::t('tasks', 'CreateTasks'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('tasks', 'TitleTasks')." :: ".Yii::t('tasks', 'CreateTasks');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo tasks"><?php echo Yii::t('tasks', 'CreateTasks'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('tasks', 'ListTasks'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'status'=>$status,'types'=>$types/*,'projects'=>$projects*/, 'milestones'=>$milestones, 'cases'=>$cases, 'allowEdit'=>$allowEdit, 'stages'=>$stages)); ?>
	</div>
</div>