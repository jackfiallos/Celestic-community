<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'accounts-form',
	'htmlOptions'=>array(
		'enctype'=>'multipart/form-data',
	),
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('site', 'FieldsRequired'); ?>

	<?php echo $form->errorSummary(array($model, $address),null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<br />
	<fieldset>
		<legend style="font-weight:bold"><?php echo Yii::t('configuration','accountData'); ?></legend>
		<div class="subcolumns">
			<div class="c50l">
				<?php echo $form->hiddenField($model,'account_name'); ?>
				<div class="row">
					<?php echo $form->labelEx($model,'account_companyName'); ?>
					<span>
						<?php
							echo $form->textField($model,'account_companyName',array('class'=>'betterform','maxlength'=>45,'tabindex'=>1,'style'=>'width:95%'));
							echo CHtml::label(Yii::t('accounts','FormAccount_companyName'), CHtml::activeId($model, 'account_companyName'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'account_logo'); ?>
					<span>
						<?php
							echo $form->fileField($model, 'image');
							echo CHtml::label(Yii::t('accounts','FormAccount_logo'), CHtml::activeId($model, 'image'), array('class'=>'labelhelper','tabindex'=>3,'style'=>'width:95%'));
						?>
					</span>				
				</div>
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo $form->labelEx($model,'account_uniqueId'); ?>
					<span>
						<?php
							echo $form->textField($model,'account_uniqueId',array('class'=>'betterform','maxlength'=>20,'tabindex'=>2,'style'=>'width:95%'));
							echo CHtml::label(Yii::t('accounts','FormAccount_uniqueId'), CHtml::activeId($model, 'account_uniqueId'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'account_colorscheme'); ?>
					<span>
						<?php
							echo $form->textField($model,'account_colorscheme',array('class'=>'betterform','maxlength'=>45,'tabindex'=>4,'style'=>'width:95%','disabled'=>'disabled'));
							echo CHtml::label(Yii::t('accounts','FormAccount_colorscheme'), CHtml::activeId($model, 'account_colorscheme'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend style="font-weight:bold">Addres Settings</legend>
		<div class="subcolumns">
			<div class="c50l">
				<div class="row">
					<?php echo $form->labelEx($address,'address_text'); ?>
					<span>
						<?php
							echo $form->textField($address,'address_text',array('class'=>'betterform','tabindex'=>5,'style'=>'width:95%'));
							echo CHtml::label(Yii::t('address','FormAddressText'), CHtml::activeId($address, 'address_text'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($address,'address_phone'); ?>
					<span>
						<?php
							echo $form->textField($address,'address_phone',array('class'=>'betterform','tabindex'=>7,'style'=>'width:95%'));
							echo CHtml::label(Yii::t('address','FormAddressPhone'), CHtml::activeId($address, 'address_phone'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($address,'city_id'); ?>
					<span>
						<?php
							$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
								'name'=>'city_name', 
								'source'=>$this->createUrl('configuration/ProviderSearch'),
								'value'=>isset($address->City->city_name) ? $address->City->city_name : '',
								'options'=>array(
									'minLength' => 4,
									'showAnim'=>'fold',
									'select'=>"js:function(event, ui) {
										$('#".CHtml::activeId($address, 'city_id')."').val(ui.item.value);
										$('#city_name').val(ui.item.label);
										return false;
									}",
								),
								'htmlOptions'=>array(
									'class'=>'betterform',
									'style'=>'width:95%',
									'maxlength'=>45,
									'tabindex'=>9,
								)
							));
							echo CHtml::label(Yii::t('address','FormAddressCity'), 'city_name', array('class'=>'labelhelper'));
							echo $form->hiddenField($address,'city_id');
						?>
					</span>
				</div>
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo $form->labelEx($address,'address_postalCode'); ?>
					<span>
						<?php
							echo $form->textField($address,'address_postalCode',array('class'=>'betterform','tabindex'=>6,'style'=>'width:95%'));
							echo CHtml::label(Yii::t('address','FormAddressPostalCode'), CHtml::activeId($address, 'address_postalCode'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($address,'address_web'); ?>
					<span>
						<?php
							echo $form->textField($address,'address_web',array('class'=>'betterform','tabindex'=>8,'style'=>'width:95%'));
							echo CHtml::label(Yii::t('address','FormAddressWeb'), CHtml::activeId($address, 'address_web'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
			</div>
		</div>
	</fieldset>

	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary','tabindex'=>10)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big','tabindex'=>11)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->