<?php
$this->pageTitle=Yii::app()->name . ' - Register Success';
?>

<h2 class="login">
	<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl."/images/celestic.png",CHtml::encode(Yii::app()->name).' v.'.Yii::app()->params['appVersion']),Yii::app()->createUrl('site/index')); ?>
</h2>
<div class="input-module box" style="height:200px;text-align:center;">
	<p>
		<?php echo Yii::t('site','signingup');?>
	</p>
	<p>
		<?php echo CHtml::image(Yii::app()->baseUrl."/images/bg-avatar.png"); ?>
	</p>
	<p>
		<?php echo CHtml::link("Return to login page", Yii::app()->createUrl('site/index')); ?>
	</p>
</div>