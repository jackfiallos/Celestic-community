<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/error.css');
$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<div class="errorLabel<?php echo $code; ?>"></div>
<div class="contentMiddle corners">
	<div class="leftContent">
		<div class="smiley"></div>
	</div>
	<div class="rightContent">
		<h1>OOOPS...</h1>
		<h3><?php echo CHtml::encode($message); ?></h3>
		<p class="jerr"><?php echo Yii::t('site', 'errorMsg'); ?></p>
		<h3><?php //echo Yii::t('site', 'errorTitleExp'); ?></h3>
		<?php //echo Yii::t('site', 'errorExplanation'); ?>
	</div>
</div>