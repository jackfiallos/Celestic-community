<div style="position:absolute;right:20px;top:148px;z-index:100;">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'search-form',
		'enableAjaxValidation'=>false,
		'action'=>Yii::app()->controller->createUrl('site/results')
	)); ?>
	<ul id="menu">
		<li class="searchContainer">
			<div class="corners">
			<?php echo CHtml::textField('searchField', '', array('autocomplete'=>'off')); ?>
			<img src="css/images/magnifier.png" alt="Search" onclick="jQuery('#search-form').submit();"></div>
			<ul id="search">
				<li>
					<input id="cbxAll" type="checkbox" name="GlobalSearchForm[all]" value="1">
					<?php echo ECHtml::label(Yii::t('modules','all'),'cbxAll'); ?>
				</li>
				<?php foreach($this->getModulesToSearch() as $module): ?>
				<li>
					<?php
						echo CHtml::checkbox('GlobalSearchForm['.$module->module_name.']', false);
						echo CHtml::label(Yii::t('modules',$module->module_name), 'GlobalSearchForm_'.$module->module_name);
					?>
				</li>
				<?php endforeach;?>
			</ul>
		</li>
	</ul>
	<?php $this->endWidget(); ?>
</div>