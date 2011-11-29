<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('companies', 'TitleCompanies')
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('companies', 'TitleCompanies');
?>

<div class="portlet">
	<div class="portlet-content">
		<h1 class="ptitleinfo companies"><?php echo Yii::t('companies', 'TitleCompanies'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('companies', 'CreateCompany'), Yii::app()->controller->createUrl('create'),array('class'=>'button primary')); ?>
		</div>
		<?php
			$this->widget('zii.widgets.CListView', array(
				'dataProvider'=>$dataProvider,
				'itemView'=>'_view',
			));
		?>
	</div>
</div>