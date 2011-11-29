<?php
$this->breadcrumbs=array(
	Yii::t('expenses', 'TitleExpenses') => array('expenses/index'),
	$model->Expenses->expense_name."No. ".$model->Expenses->expense_number => array('expenses/view','id'=>((isset($model->expense_id)) ? $model->expense_id : $_GET['owner'])),
	Yii::t('expensesConcepts', 'TitleExpenseConcepts') => array('index','owner'=>$model->expense_id),
	Yii::t('expensesConcepts', 'FieldUpdate'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('expenses', 'TitleExpenses')." :: ".CHtml::encode($model->Expenses->expense_name);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo expenses"><?php echo $model->Expenses->expense_name; ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('expenses', 'ListExpensesConcepts'), Yii::app()->controller->createUrl('index', array('owner'=>((isset($model->expense_id)) ? $model->expense_id : $_GET['owner']))),array('class'=>'button primary')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>