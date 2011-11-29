<?php
$this->breadcrumbs=array(
	Yii::t('cases', 'TitleCases')=>array('index'),
	Yii::t('cases', 'CreateCases'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('cases', 'TitleCases')." :: ".Yii::t('cases', 'CreateCases');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo cases"><?php echo Yii::t('cases', 'CreateCases'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('cases', 'ListCases'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>