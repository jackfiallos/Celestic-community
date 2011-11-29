<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('admin'),
	$model->account_name=>array('account'),
	Yii::t('accounts','updateAccount'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('configuration', 'AccountConfiguration');
?>

<div class="portlet grid_12">
	<div class="portlet-content">
		<h1 class="ptitleinfo configuration"><?php echo Yii::t('configuration', 'AccountConfiguration'); ?></h1>
		<?php echo $this->renderPartial('accounts/_form', array('model'=>$model,'address'=>$address)); ?>
	</div>
</div>