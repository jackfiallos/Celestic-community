<?php
$this->breadcrumbs=array(
	Yii::t('cases', 'TitleCases')=>array('index'),
	$model->case_name=>array('view','id'=>$model->case_id),
	Yii::t('cases', 'UpdateCases'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('cases', 'UpdateCases')." :: ".CHtml::encode($model->case_name);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo cases"><?php echo $model->case_name; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('cases', 'ListCases'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('cases', 'ViewCases'), Yii::app()->controller->createUrl('view',array('id'=>$model->case_id)),array('class'=>'button')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>