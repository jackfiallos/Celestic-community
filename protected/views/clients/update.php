<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('clients', 'TitleClients')=>array('index'),
	$model->CompleteName=>array('view','id'=>$model->Clients->client_id),
	Yii::t('clients', 'UpdateClient'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('clients', 'TitleClients');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo clients"><?php echo $model->CompleteName; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('clients', 'ListClients'), Yii::app()->controller->createUrl('index'),array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('clients', 'CreateClient'), Yii::app()->controller->createUrl('create'),array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('clients', 'ViewClient'), Yii::app()->controller->createUrl('view',array('id'=>$model->Clients->client_id)),array('class'=>'button')); ?>
		</div>
		<ul class="portlet-tab-nav">
			<li class="portlet-tab-nav-active">
				
			</li>
			<li class="portlet-tab-nav-active">
				
			</li>
			<li class="portlet-tab-nav-active">
				
			</li>
		</ul>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'address' => $address)); ?>
	</div>
</div>