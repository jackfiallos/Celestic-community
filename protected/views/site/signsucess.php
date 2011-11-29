<?php
$this->pageTitle=Yii::app()->name . ' - Register Success';
?>

<h2 class="login">
	<?php echo CHtml::link(Yii::app()->name,Yii::app()->createUrl('site/index')); ?>
</h2>
<div class="input-module box" style="height:200px;text-align:center;">
	<p>
		<?php echo Yii::t('site','signingup');?>
	</p>
	<?php echo CHtml::image(Yii::app()->baseUrl."/images/bg-avatar.png"); ?>
</div>