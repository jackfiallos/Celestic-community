<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('users', 'TitleUsers')=>array('index'),
	Yii::t('users', 'CreateUser'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('users', 'TitleUsers');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo users"><?php echo Yii::t('users', 'CreateUser'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('users', 'ListUsers'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'allowEdit'=>$allowEdit,'userManager'=>$userManager,'address' => $address)); ?>
	</div>
</div>