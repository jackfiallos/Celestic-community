<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('admin'),
	Yii::t('configuration', 'Localization'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('localization', 'TitleLocalization');
?>


<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo localization"><?php echo Yii::t('configuration', 'DateTimeSettings'); ?></h1>
		<div class="form">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'manageusers-form',
				'enableAjaxValidation'=>false,
			)); ?>
			<?php echo Yii::t('localization', 'FieldsRequired'); ?>
			<?php echo $form->errorSummary($model)."<br />"; ?>
			<?php if(Yii::app()->user->hasFlash('updatedLocalizationSuccess')):?>
				<div class="notification_success">
					<?php echo Yii::app()->user->getFlash('updatedLocalizationSuccess'); ?>
				</div><br />
			<?php endif; ?>
			<div style="padding-bottom: 25px;">
				<h3 style="font-size: 13px; font-weight: bold; background-color:#b8dcf6; padding:8px;" class="corners"><?php echo Yii::t('localization','TimezomeSelection'); ?></h3>
				<div class="row">
					<?php echo $form->labelEx($model,'timezone'); ?>
					<span>
						<?php
							echo $form->dropDownList($model,'timezone',CHtml::listData($timezones, 'timezone_id', 'timezone_name'),array('style'=>'width:95%','class'=>'betterform','empty'=>Yii::t('localization', 'selectOption'),'tabindex'=>1));
							echo CHtml::label(Yii::t('localization','FormTimezoneTimezone'), CHtml::activeId($model, 'timezone'), array('class'=>'labelhelper'));
						 ?>
					</span>
				</div>
				<div class="clear"></div>
			</div>
			<div class="row buttons subcolumns">
				<div class="c50l">
					<?php echo CHtml::button(Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary', 'tabindex'=>4)); ?>
					<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big', 'tabindex'=>5)); ?>
				</div>
				<div class="c50r" style="text-align:right;">
					<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
				</div>
			</div>
			<?php $this->endWidget(); ?>
		</div>
		<br />
		<div id="notify"></div>
	</div>
</div>