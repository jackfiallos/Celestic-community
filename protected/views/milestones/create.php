<?php
$this->breadcrumbs=array(
	Yii::t('milestones', 'TitleMilestones')=>array('index'),
	Yii::t('milestones', 'CreateMilestones'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('milestones', 'TitleMilestones')." :: ".Yii::t('milestones', 'CreateMilestones');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo milestones"><?php echo Yii::t('milestones', 'CreateMilestones'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('milestones', 'ListMilestones'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model, 'users'=>$users)); ?>
	</div>
</div>