<?php
$this->breadcrumbs=array(
	Yii::t('documents', 'TitleDocuments')=>array('index'),
	$model->document_name=>array('view','id'=>$model->document_id),
	Yii::t('documents', 'UpdateDocuments'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('documents', 'UpdateDocuments')." :: ".CHtml::encode($model->document_name);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo documents"><?php echo $model->document_name; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('documents', 'ListDocuments'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('documents', 'CreateDocuments'), Yii::app()->controller->createUrl('create'), array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('documents', 'ViewDocuments'), Yii::app()->controller->createUrl('view',array('id'=>$model->document_id)), array('class'=>'button')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model/*,'projects'=>$projects*/)); ?>
	</div>
</div>