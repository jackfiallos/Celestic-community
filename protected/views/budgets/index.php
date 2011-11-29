<?php
$this->breadcrumbs=array(
	Yii::t('budgets', 'TitleBudget'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('budgets', 'TitleBudget');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo budgets"><?php echo Yii::t('budgets', 'TitleBudget'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('budgets', 'CreateBudget'), Yii::app()->controller->createUrl('create'), array('class'=>'button primary')); ?>
		</div>
		<?php if($model->ItemsCount > 0):?>
		<?php echo CHtml::link(Yii::t('budgets', 'AdvancedSearch'),'#',array('class'=>'search-button')); ?>
		<div class="search-form corners" style="display:block;">
		<?php
			$this->renderPartial('_search',array(
				'model'=>$model,
				'status'=>$status
			));
		?>
		</div>
		<?php
			$this->widget('zii.widgets.CListView', array(
				'dataProvider'=>$model->search(),
				'itemView'=>'_view',
				'summaryText'=>Yii::t('site','summaryText'),
			));
		?>
		<?php else: ?>
		<div class="aboutModule">
			<p class="aboutModuleTitle">
				<?php echo Yii::t('budgets','NoBudgetCreatedYet');?> <?php echo CHtml::link(Yii::t('budgets','CreateOneBudget'), Yii::app()->controller->createUrl('create')); ?>
			</p>
			<div class="aboutModuleInformation shadow corners">
				<h2 class="aboutModuleInformationBoxTitle"><?php echo Yii::t('budgets','AboutBudgets'); ?></h2>
				<ul class="aboutModuleInformationList">
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('budgets','BudgetInformation_l1'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('budgets','BudgetDescription_l1'); ?></span>
					</li>
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('budgets','BudgetInformation_l2'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('budgets','BudgetDescription_l2'); ?></span>
					</li>
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('budgets','BudgetInformation_l3'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('budgets','BudgetDescription_l3'); ?></span>
					</li>
				</ul>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
");
?>