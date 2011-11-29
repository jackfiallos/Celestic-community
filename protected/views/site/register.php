<?php $this->pageTitle=Yii::app()->name . ' - '.Yii::t('accounts','NewAccountRegistration'); ?>

<h2 class="login">
<?php echo CHtml::link(Yii::app()->name,Yii::app()->createUrl('site/index')); ?>
</h2>

<?php
echo $this->renderPartial('_form', array(
	'model'=>$model,
));
?>