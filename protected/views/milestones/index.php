<?php
$this->breadcrumbs=array(
	Yii::t('milestones', 'TitleMilestones'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('milestones', 'TitleMilestones');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo milestones"><?php echo Yii::t('milestones', 'TitleMilestones'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('milestones', 'CreateMilestones'), Yii::app()->controller->createUrl('create'), array('class'=>'button primary')); ?>
		</div>
		<?php if($model->ItemsCount > 0):?>
		<?php echo CHtml::link(Yii::t('milestones', 'AdvancedSearch'),'#',array('class'=>'search-button')); ?>
		<div class="search-form corners" style="display:none;">
		<?php
			$this->renderPartial('_search',array(
				'model'=>$model,
				'users'=>$users,
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
				No milestones has been created, you want to <?php echo CHtml::link(Yii::t('milestones','CreateOneMilestone'), Yii::app()->controller->createUrl('create')); ?> ?
			</p>
			<div class="aboutModuleInformation shadow corners">
				<h2 class="aboutModuleInformationBoxTitle"><?php echo Yii::t('milestones','AboutMilestones'); ?></h2>
				<ul class="aboutModuleInformationList">
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('milestones','MilestoneInformation_l1'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('milestones','MilestoneDescription_l1'); ?></span>
					</li>
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('milestones','MilestoneInformation_l2'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('milestones','MilestoneDescription_l2'); ?></span>
					</li>
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('milestones','MilestoneInformation_l3'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('milestones','MilestoneDescription_l3'); ?></span>
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