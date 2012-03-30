<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('clients', 'TitleClients'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('clients', 'TitleClients');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo clients"><?php echo Yii::t('clients', 'TitleClients'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('clients', 'CreateClient'), Yii::app()->createUrl('clients/create'),array('class'=>'button primary')); ?>
		</div>
		<?php if($dataProvider->itemCount > 0):?>
		<div class="subcolumns">
		<?php
			$this->widget('zii.widgets.CListView', array(
				'dataProvider'=>$dataProvider,
				'itemView'=>'_view',
				//'template'=>'{items}',
				'summaryText'=>'',
				'itemsCssClass'=>'people-listing',
			));
		?>
		</div>
		<?php else: ?>
		<div class="aboutModule">
			<p class="aboutModuleTitle">
				<?php echo Yii::t('clients','NoClientCreatedYet');?> <?php echo CHtml::link(Yii::t('clients','CreateOneClient'), Yii::app()->controller->createUrl('create')); ?>
			</p>
			<div class="aboutModuleInformation shadow corners">
				<h2 class="aboutModuleInformationBoxTitle"><?php echo Yii::t('clients','AboutClients'); ?></h2>
				<ul class="aboutModuleInformationList">
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('clients','ClientInformation_l1'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('clients','ClientDescription_l1'); ?></span>
					</li>
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('clients','ClientInformation_l2'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('clients','ClientDescription_l2'); ?></span>
					</li>
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('clients','ClientInformation_l3'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('clients','ClientDescription_l3'); ?></span>
					</li>
				</ul>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>