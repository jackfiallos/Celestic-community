<?php
$this->breadcrumbs=array(
	Yii::t('budgets', 'TitleBudget')=>array('index'),
	Yii::t('budgets', 'CreateBudget'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('budgets', 'TitleBudget')." :: ".Yii::t('budgets', 'CreateBudget');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo budgets"><?php echo Yii::t('budgets', 'CreateBudget'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('budgets', 'ListBudget'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('budget'=>$budget)); ?>
	</div>
</div>