<?php
$this->breadcrumbs=array(
	Yii::t('cases', 'TitleCases')=>array('index'),
	$cases->case_name=>Yii::app()->createUrl('cases/view',array('id'=>$cases->case_id)),
	Yii::t('secuences', 'CreateSecuences'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('cases', 'TitleCases');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo secuenses"><?php echo Yii::t('secuences', 'CreateSecuences'); ?></h1>
		<?php if($returnMessage != null) {  echo "<p class=\"notification_success\">".$returnMessage."</p>"; } ?>
		<?php echo $this->renderPartial('_form', array('model'=>$model, 'types'=>$types)); ?>
	</div>
</div>