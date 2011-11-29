<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('users', 'TitleUsers'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('users', 'TitleUsers');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo users"><?php echo Yii::t('users', 'TitleUsers'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('users', 'CreateUser'), Yii::app()->controller->createUrl('create'),array('class'=>'button primary')); ?>
		</div>
		<div class="subcolumns">
		<?php
			$this->widget('zii.widgets.CListView', array(
				'dataProvider'=>$dataProvider,
				'itemView'=>'_view',
				'template'=>'{items}',
				'summaryText'=>'',
				'itemsCssClass'=>'people-listing',
			));
		?>
		</div>
	</div>
</div>