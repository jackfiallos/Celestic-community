<?php
$this->breadcrumbs=array(
	Yii::t('invoices', 'TitleInvoices'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('invoices', 'TitleInvoices');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo invoices"><?php echo Yii::t('invoices', 'TitleInvoices'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('invoices', 'CreateInvoices'), Yii::app()->controller->createUrl('create'), array('class' => 'button primary')); ?>
		</div>
		<?php if($model->ItemsCount > 0):?>
		<?php echo CHtml::link(Yii::t('invoices', 'AdvancedSearch'),'#',array('class'=>'search-button')); ?>
		<div class="search-form corners" style="display:none;">
		<?php
			$this->renderPartial('_search',array(
				'model'=>$model,
				'status'=>$status,
				'budgets'=>$budgets,
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
				No invoices has been created, you want to <?php echo CHtml::link(Yii::t('invoices','CreateOneInvoice'), Yii::app()->controller->createUrl('create')); ?> ?
			</p>
			<div class="aboutModuleInformation shadow corners">
				<h2 class="aboutModuleInformationBoxTitle"><?php echo Yii::t('invoices','AboutInvoices'); ?></h2>
				<ul class="aboutModuleInformationList">
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('invoices','InvoiceInformation_l1'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('invoices','InvoiceDescription_l1'); ?></span>
					</li>
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('invoices','InvoiceInformation_l2'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('invoices','InvoiceDescription_l2'); ?></span>
					</li>
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('invoices','InvoiceInformation_l3'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('invoices','InvoiceDescription_l3'); ?></span>
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