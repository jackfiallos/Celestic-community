<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('companies', 'TitleCompanies')=>array('index'),
	Yii::t('companies', 'CreateCompany'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('companies', 'TitleCompanies');
?>

<div class="portlet">
	<div class="portlet-content">
		<h1 class="ptitleinfo companies"><?php echo Yii::t('companies', 'CreateCompany'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('companies', 'ListCompanies'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
		</div>
		<ul class="portlet-tab-nav">
			<li class="portlet-tab-nav-active">
				
			</li>
		</ul>
		<?php echo $this->renderPartial('_form', array('model'=>$model,'address' => $address)); ?>
	</div>
</div>