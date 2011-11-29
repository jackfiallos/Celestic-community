<?php
$this->breadcrumbs=array(
	Yii::t('tasks', 'TitleTasks')=>array('index'),
	$model->task_name=>array('view','id'=>$model->task_id),
	Yii::t('tasks', 'UpdateTasks'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('tasks', 'UpdateTasks')." :: ".CHtml::encode($model->task_name);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo tasks"><?php echo $model->task_name; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('tasks', 'ListTasks'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('tasks', 'ViewTasks'), Yii::app()->controller->createUrl('view',array('id'=>$model->task_id)),array('class'=>'button')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'status'=>$status,'types'=>$types/*,'projects'=>$projects*/, 'milestones'=>$milestones, 'cases'=>$cases, 'allowEdit'=>$allowEdit, 'stages'=>$stages)); ?>
	</div>
</div>