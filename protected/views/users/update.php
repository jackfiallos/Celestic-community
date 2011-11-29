<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('users', 'TitleUsers')=>array('index'),
	$model->CompleteName=>array('view','id'=>$model->user_id),
	Yii::t('users', 'UpdateUser'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('users', 'TitleUsers');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo users"><?php echo $model->CompleteName; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('users', 'ListUsers'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('users', 'CreateUser'), Yii::app()->controller->createUrl('create'),array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('users', 'ViewUser'), Yii::app()->controller->createUrl('view',array('id'=>$model->user_id)),array('class'=>'button')); ?>
		</div>
		<ul class="portlet-tab-nav">
			<li class="portlet-tab-nav-active">
				
			</li>
			<li class="portlet-tab-nav-active">
				
			</li>
			<li class="portlet-tab-nav-active">
				
			</li>
		</ul>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'allowEdit'=>$allowEdit,'userManager'=>$userManager,'address' => $address)); ?>
	</div>
</div>