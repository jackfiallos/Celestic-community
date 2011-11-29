<?php
$this->breadcrumbs=array(
	Yii::t('budgets', 'TitleBudget')=>array('budgets/index'),
	$model->Budgets->budget_title=>array('budgets/view','id'=>((isset($model->budget_id)) ? $model->budget_id : $_GET['owner'])),
	Yii::t('budgetsConcepts', 'TitleBudgetConcepts')=>array('index','owner'=>$model->budget_id),
	Yii::t('budgetsConcepts', 'FieldUpdate'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('budgets', 'TitleBudget')." :: ".CHtml::encode($model->Budgets->budget_title);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo budgets"><?php echo $model->Budgets->budget_title; ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('budgets', 'ListBudgetsConcepts'), Yii::app()->controller->createUrl('index', array('owner'=>((isset($model->budget_id)) ? $model->budget_id : $_GET['owner']))), array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>