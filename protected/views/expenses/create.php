<?php
$this->breadcrumbs=array(
	Yii::t('expenses', 'TitleExpenses')=>array('index'),
	Yii::t('expenses', 'CreateExpenses'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('expenses', 'TitleExpenses')." :: ".Yii::t('expenses', 'CreateExpenses');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo expenses"><?php echo Yii::t('expenses', 'CreateExpenses'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('expenses', 'ListExpenses'), Yii::app()->controller->createUrl('index'),array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('expense'=>$expense)); ?>
	</div>
</div>