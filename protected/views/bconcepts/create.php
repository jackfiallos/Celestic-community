<?php
$this->breadcrumbs=array(
	Yii::t('budgets', 'TitleBudget')=>array('budgets/view','id'=>((isset($model->budget_id)) ? $model->budget_id : $_GET['owner'])),
	Yii::t('budgetsConcepts', 'TitleBudgetConcepts')=>array('index','owner'=>$_GET['owner']),
	Yii::t('budgetsConcepts', 'FieldCreate'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('budgets', 'TitleBudget')." :: ".Yii::t('budgets', 'CreateBudgetsConcepts');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo budgets"><?php echo Yii::t('budgets', 'CreateBudgetsConcepts'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('budgets', 'ListBudgetsConcepts'), Yii::app()->controller->createUrl('index', array('owner'=>((isset($model->budget_id)) ? $model->budget_id : $_GET['owner']))), array('class'=>'button primary')); ?>
		</div>
		<?php if(Yii::app()->user->hasFlash('bconcepts')):?>
			<div class="info notification_success">
				<?php echo Yii::app()->user->getFlash('bconcepts'); ?>
			</div><br />
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