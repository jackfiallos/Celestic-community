<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('companies', 'TitleCompanies')=>array('index'),
	$model->company_name=>array('view','id'=>$model->company_id),
	Yii::t('companies', 'UpdateCompany'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('companies', 'TitleCompanies');
?>

<div class="portlet">
	<div class="portlet-content">
		<h1 class="ptitleinfo companies"><?php echo $model->company_name; ?></h1>
		<div class="button-group portlet-tab-nav">		
			<?php echo CHtml::link(Yii::t('companies', 'ListCompanies'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('companies', 'CreateCompany'), Yii::app()->controller->createUrl('create'), array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('companies', 'ViewCompany'), Yii::app()->controller->createUrl('view',array('id'=>$model->company_id)), array('class'=>'button')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'address' => $address)); ?>
	</div>
</div>