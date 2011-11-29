<?php
$this->breadcrumbs=array(
	Yii::t('expenses', 'TitleExpenses')=>array('index'),
	$expense->expense_name=>array('view','id'=>$expense->expense_id),
	Yii::t('expenses', 'UpdateExpenses'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('expenses', 'UpdateExpenses')." :: ".CHtml::encode($expense->expense_name);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo expenses"><?php echo $expense->expense_name; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('expenses', 'ListExpenses'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('expenses', 'CreateExpenses'), Yii::app()->controller->createUrl('create'), array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('expenses', 'ViewExpenses'), Yii::app()->controller->createUrl('view', array('id'=>$expense->expense_id)), array('class'=>'button')); ?>
		</div>
		<?php echo $this->renderPartial('_form', array('expense'=>$expense)); ?>
	</div>
</div>