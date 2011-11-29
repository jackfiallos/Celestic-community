<?php
$this->breadcrumbs=array(
	Yii::t('documents', 'TitleDocuments')=>array('index'),
	Yii::t('documents', 'CreateDocuments'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('documents', 'TitleDocuments')." :: ".Yii::t('documents', 'CreateDocuments');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo documents"><?php echo Yii::t('documents', 'CreateDocuments'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('documents', 'ListDocuments'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model/*,'projects'=>$projects*/)); ?>
	</div>
</div>