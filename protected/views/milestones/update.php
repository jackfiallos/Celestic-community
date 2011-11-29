<?php
$this->breadcrumbs=array(
	Yii::t('milestones', 'TitleMilestones')=>array('index'),
	$model->milestone_title=>array('view','id'=>$model->milestone_id),
	Yii::t('milestones', 'UpdateMilestones'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('milestones', 'UpdateMilestones'). " :: ".CHtml::encode($model->milestone_title);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo milestones"><?php echo $model->milestone_title; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('milestones', 'ListMilestones'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('milestones', 'ViewMilestones'), Yii::app()->controller->createUrl('view',array('id'=>$model->milestone_id)),array('class'=>'button')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model, 'users'=>$users)); ?>
	</div>
</div>