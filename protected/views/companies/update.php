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
		<ul class="portlet-tab-nav">
			<li class="portlet-tab-nav-active">
				<?php echo CHtml::link(Yii::t('companies', 'ListCompanies'), Yii::app()->controller->createUrl('index')); ?>
			</li>
			<li class="portlet-tab-nav-active">
				<?php echo CHtml::link(Yii::t('companies', 'CreateCompany'), Yii::app()->controller->createUrl('create')); ?>
			</li>
			<li class="portlet-tab-nav-active">
				<?php echo CHtml::link(Yii::t('companies', 'ViewCompany'), Yii::app()->controller->createUrl('view',array('id'=>$model->company_id))); ?>
			</li>
		</ul>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'address' => $address)); ?>
	</div>
</div>