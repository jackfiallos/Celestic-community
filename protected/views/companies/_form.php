<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'companies-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('companies','FieldsRequired'); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>

	<div class="subcolumns">
		<div class="c50l">
			<fieldset>
				<legend style="font-weight:bold"><?php echo Yii::t('companies','CompanyInformation'); ?></legend>
				<div class="row">
					<?php echo $form->labelEx($model,'company_name'); ?>
					<span>
						<?php
							echo $form->textField($model,'company_name',array('class'=>'betterform','style'=>'width:95%','maxlength'=>45,'tabindex'=>1));
							echo CHtml::label(Yii::t('companies','FieldCompanyName'), CHtml::activeId($model, 'company_name'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'company_uniqueId'); ?>
					<span>
						<?php
							echo $form->textField($model,'company_uniqueId',array('class'=>'betterform','style'=>'width:95%','maxlength'=>20,'tabindex'=>2));
							echo CHtml::label(Yii::t('companies','FieldCompanyUniqueId'), CHtml::activeId($model, 'company_uniqueId'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'company_url'); ?>
					<span>
						<?php
							echo $form->textField($model,'company_url',array('class'=>'betterform','style'=>'width:95%','maxlength'=>100,'tabindex'=>3));
							echo CHtml::label(Yii::t('companies','FieldCompanyUrl'), CHtml::activeId($model, 'company_url'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
			</fieldset>
			<fieldset>
				<legend style="font-weight:bold"><?php echo Yii::t('companies','AddressSetting'); ?></legend>
				<div class="row">
					<?php echo $form->labelEx($address,'address_text'); ?>
					<span>
						<?php
							echo $form->textField($address,'address_text',array('class'=>'betterform','tabindex'=>4,'style'=>'width:95%'));
							echo CHtml::label(Yii::t('address','FormAddressText'), CHtml::activeId($address, 'address_text'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($address,'address_postalCode'); ?>
					<span>
						<?php
							echo $form->textField($address,'address_postalCode',array('class'=>'betterform','tabindex'=>5,'style'=>'width:95%'));
							echo CHtml::label(Yii::t('address','FormAddressPostalCode'), CHtml::activeId($address, 'address_postalCode'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($address,'address_phone'); ?>
					<span>
						<?php
							echo $form->textField($address,'address_phone',array('class'=>'betterform','tabindex'=>6,'style'=>'width:95%'));
							echo CHtml::label(Yii::t('address','FormAddressPhone'), CHtml::activeId($address, 'address_phone'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($address,'address_web'); ?>
					<span>
						<?php
							echo $form->textField($address,'address_web',array('class'=>'betterform','tabindex'=>7,'style'=>'width:95%'));
							echo CHtml::label(Yii::t('address','FormAddressWeb'), CHtml::activeId($address, 'address_web'), array('class'=>'labelhelper'));
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
									'showAnim'=>'fold',
									'select'=>"js:function(event, ui) {
										$('#".CHtml::activeId($address, 'city_id')."').val(ui.item.id);
										$('#city_name').val(ui.item.label);
										return false;
									}",
								),
								'htmlOptions'=>array(
									'class'=>'betterform',
									'style'=>'width:95%',
									'maxlength'=>45,
									'tabindex'=>8,
								)
							));
							echo CHtml::label(Yii::t('address','FormAddressCity'), 'city_name', array('class'=>'labelhelper'));
							echo $form->hiddenField($address,'city_id');
						?>
					</span>
				</div>
			</fieldset>
		</div>
		<div class="c50r">
			<div class="row" align="center" style="margin:auto;">
				<?php echo CHtml::label(Yii::t('address','FormDragMark'),CHtml::activeId($address, 'company_latitude')); ?>
				<div id="map_canvas" style="width:400px; height:700px"></div>
			</div>
		</div>
	</div>

	<?php echo $form->hiddenField($model,'company_latitude',array('id'=>'cmpLatitude')); ?>
	<?php echo $form->hiddenField($model,'company_longitude',array('id'=>'cmpLongitude')); ?>

	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary','tabindex'=>9)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big','tabindex'=>10)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>
	
<?php $this->endWidget(); ?>
</div><!-- form -->

<?php
Yii::app()->clientScript->registerScriptFile('http://maps.google.com/maps?file=api&v=2&key='.Yii::app()->params['gmapsApi'].'&sensor=true');
Yii::app()->clientScript->registerScript('GoogleMapsScript','
$(document).ready(function() {
	if (GBrowserIsCompatible()) {
		var map = new GMap2(document.getElementById("map_canvas"));
		map.addControl(new GLargeMapControl());        
		
		var center = new GLatLng('.$model->company_latitude.', '.$model->company_longitude.');
		map.setCenter(center, 16);

		var marker = new GMarker(center, {draggable: true});
		map.addOverlay(marker);

		GEvent.addListener(marker, "dragend", function() {
			//marker.openInfoWindowHtml("Just bouncing along...");
			var point = marker.getPoint();
			map.panTo(point);
			$("#cmpLatitude").val(point.lat());
			$("#cmpLongitude").val(point.lng());
		});
	}
});
');
?>