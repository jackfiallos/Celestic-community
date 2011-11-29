<?php
$this->breadcrumbs=array(
	Yii::t('cases', 'TitleCases')=>array('index'),
	$model->Cases->case_name=>Yii::app()->createUrl('cases/view',array('id'=>$model->case_id)),
	Yii::t('validations', 'UpdateValidations'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('cases', 'TitleCases');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo validations"><?php echo Yii::t('validations', 'UpdateValidations'); ?></h1>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>