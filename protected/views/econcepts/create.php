<?php
$this->breadcrumbs=array(
	Yii::t('expenses', 'TitleExpenses')=>array('expenses/view','id'=>((isset($model->expense_id)) ? $model->expense_id : $_GET['owner'])),
	Yii::t('expensesConcepts', 'TitleExpenseConcepts')=>array('index','owner'=>$_GET['owner']),
	Yii::t('expensesConcepts', 'FieldCreate'),
);
$this->pageTitle = Yii::app()->name." ".Yii::t('expenses', 'TitleExpenses');
$this->pageTitle = Yii::app()->name." - ".Yii::t('expenses', 'TitleExpenses')." :: ".Yii::t('expenses', 'CreateExpensesConcepts');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo expenses"><?php echo Yii::t('expenses', 'CreateExpensesConcepts'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('expenses', 'ListExpensesConcepts'), Yii::app()->controller->createUrl('index', array('owner'=>((isset($model->expense_id)) ? $model->expense_id : $_GET['owner']))),array('class'=>'button primary')); ?>
		</div>
		<?php if(Yii::app()->user->hasFlash('econcepts')):?>
			<div class="info notification_success">
				<?php echo Yii::app()->user->getFlash('econcepts'); ?>
			</div>
		<?php endif; ?>
		<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>
<?php
Yii::app()->clientScript->registerScript(
   'FlashMessage',
   '$(".info").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   CClientScript::POS_READY
);
?>