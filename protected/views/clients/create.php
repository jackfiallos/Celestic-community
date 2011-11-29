<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('clients', 'TitleClients')=>array('index'),
	Yii::t('clients', 'CreateClient'),
);

$this->menu=array(
	array('label'=>'List Clients', 'url'=>array('index')),
);

$this->pageTitle = Yii::app()->name." - ".Yii::t('clients', 'TitleClients');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo clients"><?php echo Yii::t('clients', 'CreateClient'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('clients', 'ListClients'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'address' => $address)); ?>
	</div>
</div>