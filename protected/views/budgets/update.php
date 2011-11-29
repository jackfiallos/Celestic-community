<?php
$this->breadcrumbs=array(
	Yii::t('budgets', 'TitleBudget')=>array('index'),
	$budget->budget_title=>array('view','id'=>$budget->budget_id),
	Yii::t('budgets', 'UpdateBudget'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('budgets', 'UpdateBudget')." :: ".CHtml::encode($budget->budget_title);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo budgets"><?php echo $budget->budget_title; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('budgets', 'ListBudget'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('budgets', 'CreateBudget'), Yii::app()->controller->createUrl('create'), array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('budgets', 'ViewBudget'), Yii::app()->controller->createUrl('view', array('id'=>$budget->budget_id)), array('class'=>'button')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('budget'=>$budget)); ?>
	</div>
</div>