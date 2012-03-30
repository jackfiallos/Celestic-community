<?php $this->pageTitle=Yii::app()->name . ' - '.Yii::t('accounts','NewAccountRegistration'); ?>

<h2 class="login">
<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl."/images/celestic.png",CHtml::encode(Yii::app()->name).' v.'.Yii::app()->params['appVersion']),Yii::app()->createUrl('site/index')); ?>
</h2>

<?php
echo $this->renderPartial('_form', array(
	'model'=>$model,
));
?>