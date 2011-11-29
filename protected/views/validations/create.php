<?php
$this->breadcrumbs=array(
	Yii::t('cases', 'TitleCases')=>array('index'),
	$cases->case_name=>Yii::app()->createUrl('cases/view',array('id'=>$cases->case_id)),
	Yii::t('validations', 'CreateValidations'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('cases', 'TitleCases');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo secuenses"><?php echo Yii::t('validations', 'CreateValidations'); ?></h1>
		<?php if($returnMessage != null) {  echo "<p class=\"notification_success\">".CHtml::encode($returnMessage)."</p>"; } ?>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>