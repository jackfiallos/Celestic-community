<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'projects-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('projects','FieldsRequired'); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>

	<fieldset>
		<legend style="font-weight:bold;">
			<?php echo Yii::t('projects','ProjectDefinition'); ?>
		</legend>
		<div class="row">
			<?php echo $form->labelEx($model,'project_name'); ?>
			<span>
				<?php
					echo $form->textField($model,'project_name',array('class'=>'betterform','maxlength'=>100,'style'=>'width:95%','tabindex'=>1));
					echo CHtml::label(Yii::t('projects','FormProject_name'), CHtml::activeId($model, 'project_name'), array('class'=>'labelhelper'));
				?>
			</span>
		</div>		
		<div class="subcolumns">
			<div class="c50l">
				<div class="row">
					<?php echo $form->labelEx($model,'project_startDate'); ?>
					<span>
						<?php
							$this->widget('zii.widgets.jui.CJuiDatePicker', array(
								'options'=>array(
									'showAnim'=>'fold',
								),
								'model'=>$model,
								'attribute'=>'project_startDate',
								'htmlOptions'=>array(
									'class'=>'betterform',
									'style'=>'width:95%',
									'tabindex'=>2
								),
								'options'=>array(
									'dateFormat'=>'yy-mm-dd',
									'showButtonPanel'=>true,
									'changeMonth'=>true,
									'changeYear'=>true,
									'defaultDate'=>'+1w',
									'onSelect'=>'js:function(selectedDate){
										var option = "minDate",
										instance = $(this).data("datepicker");
										date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
										$("#'.CHtml::activeId($model, 'project_endDate').'").datepicker("option", option, date);
									}'
								),
							));
							echo CHtml::label(Yii::t('projects','FormProject_startDate'), CHtml::activeId($model, 'project_startDate'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'company_id'); ?>
					<span>
						<?php
							echo $form->dropDownList($model,'company_id',CHtml::listData($companies, 'company_id', 'company_name'),array('style'=>'width:95%','class'=>'betterform','empty'=>Yii::t('projects', 'selectOption'),'tabindex'=>4));
							echo CHtml::label(Yii::t('projects','FormProject_company_id'), CHtml::activeId($model, 'company_id'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo $form->labelEx($model,'project_endDate'); ?>
					<span>
						<?php
							$this->widget('zii.widgets.jui.CJuiDatePicker', array(
								'options'=>array(
									'showAnim'=>'fold',
								),
								'model'=>$model,
								'attribute'=>'project_endDate',
								'htmlOptions'=>array(
									'class'=>'betterform',
									'style'=>'width:92%',
									'tabindex'=>3
								),
								'options'=>array(
									'dateFormat'=>'yy-mm-dd',
									'showButtonPanel'=>true,
									'changeMonth'=>true,
									'changeYear'=>true,
									'defaultDate'=>'+1w',
									'onSelect'=>'js:function(selectedDate){
										var option = "maxDate",
										instance = $(this).data("datepicker");
										date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
										$("#'.CHtml::activeId($model, 'project_startDate').'").datepicker("option", option, date);
									}'
								),
							));
							echo CHtml::label(Yii::t('projects','FormProject_endDate'), CHtml::activeId($model, 'project_endDate'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'currency_id'); ?>
					<span>
						<?php
							echo $form->dropDownList($model,'currency_id',CHtml::listData($currencies, 'currency_id', 'currency_code'),array('style'=>'width:95%','class'=>'betterform','empty'=>Yii::t('projects', 'selectOption'),'tabindex'=>5));
							echo CHtml::label(Yii::t('projects','FormProject_currency_id'), CHtml::activeId($model, 'budget_title'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend class="fieldsettoggle">
			<b>&raquo;</b>&nbsp;<?php echo Yii::t('projects','ProjectAnalisisParameters'); ?>
		</legend>
		<div class="<?php (!isset($model->errors['project_description'])) ? 'fieldContent' : null; ?>">
			<div class="row">
				<?php echo $form->labelEx($model,'project_description'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_description',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>6));
						echo CHtml::label(Yii::t('projects','FormProject_description'), CHtml::activeId($model, 'project_description'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_restrictions'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_restrictions',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>7));
						echo CHtml::label(Yii::t('projects','FormProject_restrictions'), CHtml::activeId($model, 'project_restrictions'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_scope'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_scope',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>8));
						echo CHtml::label(Yii::t('projects','FormProject_scope'), CHtml::activeId($model, 'project_scope'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_responsibilities'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_responsibilities',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>9));
						echo CHtml::label(Yii::t('projects','FormProject_responsibilities'), CHtml::activeId($model, 'project_responsibilities'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_additionalCosts'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_additionalCosts',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>10));
						echo CHtml::label(Yii::t('projects','FormProject_additionalCosts'), CHtml::activeId($model, 'project_additionalCosts'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend class="fieldsettoggle">
			<b>&raquo;</b>&nbsp;<?php echo Yii::t('projects','ProjectDesignParameters'); ?>
		</legend>
		<div class="fieldContent">
			<div class="row">
				<?php echo $form->labelEx($model,'project_plataform'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_plataform',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>11));
						echo CHtml::label(Yii::t('projects','FormProject_plataform'), CHtml::activeId($model, 'project_plataform'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>		
			<div class="row">
				<?php echo $form->labelEx($model,'project_swRequirements'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_swRequirements',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>12));
						echo CHtml::label(Yii::t('projects','FormProject_swRequirements'), CHtml::activeId($model, 'project_swRequirements'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>		
			<div class="row">
				<?php echo $form->labelEx($model,'project_hwRequirements'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_hwRequirements',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>13));
						echo CHtml::label(Yii::t('projects','FormProject_hwRequirements'), CHtml::activeId($model, 'project_hwRequirements'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend class="fieldsettoggle">
			<b>&raquo;</b>&nbsp;<?php echo Yii::t('projects','ExternalInterfaces'); ?>
		</legend>
		<div class="fieldContent">
			<div class="row">
				<?php echo $form->labelEx($model,'project_userInterfaces'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_userInterfaces',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>14));
						echo CHtml::label(Yii::t('projects','FormProject_userInterfaces'), CHtml::activeId($model, 'project_userInterfaces'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_hardwareInterfaces'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_hardwareInterfaces',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>15));
						echo CHtml::label(Yii::t('projects','FormProject_hardwareInterfaces'), CHtml::activeId($model, 'project_hardwareInterfaces'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_softwareInterfaces'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_softwareInterfaces',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>16));
						echo CHtml::label(Yii::t('projects','FormProject_softwareInterfaces'), CHtml::activeId($model, 'project_softwareInterfaces'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_communicationInterfaces'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_communicationInterfaces',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>17));
						echo CHtml::label(Yii::t('projects','FormProject_communicationInterfaces'), CHtml::activeId($model, 'project_communicationInterfaces'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend class="fieldsettoggle">
			<b>&raquo;</b>&nbsp;<?php echo Yii::t('projects','SpecificRequirements'); ?>
		</legend>
		<div class="fieldContent">
			<div class="row">
				<?php echo $form->labelEx($model,'project_functionalReq'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_functionalReq',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>18));
						echo CHtml::label(Yii::t('projects','FormProject_functionalReq'), CHtml::activeId($model, 'project_functionalReq'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_performanceReq'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_performanceReq',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>19));
						echo CHtml::label(Yii::t('projects','FormProject_performanceReq'), CHtml::activeId($model, 'project_performanceReq'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_additionalComments'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_additionalComments',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>20));
						echo CHtml::label(Yii::t('projects','FormProject_additionalComments'), CHtml::activeId($model, 'project_additionalComments'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend class="fieldsettoggle">
			<b>&raquo;</b>&nbsp;<?php echo Yii::t('projects','SpecialUserRequirements'); ?>
		</legend>
		<div class="fieldContent">
			<div class="row">
				<?php echo $form->labelEx($model,'project_backupRecovery'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_backupRecovery',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>21));
						echo CHtml::label(Yii::t('projects','FormProject_backupRecovery'), CHtml::activeId($model, 'project_backupRecovery'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_dataMigration'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_dataMigration',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>22));
						echo CHtml::label(Yii::t('projects','FormProject_dataMigration'), CHtml::activeId($model, 'project_dataMigration'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_userTraining'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_userTraining',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>23));
						echo CHtml::label(Yii::t('projects','FormProject_userTraining'), CHtml::activeId($model, 'project_userTraining'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_installation'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_installation',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>24));
						echo CHtml::label(Yii::t('projects','FormProject_installation'), CHtml::activeId($model, 'project_installation'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend class="fieldsettoggle">
			<b>&raquo;</b>&nbsp;<?php echo Yii::t('projects','SpecialConsiderations'); ?>
		</legend>
		<div class="fieldContent">
			<div class="row">
				<?php echo $form->labelEx($model,'project_assumptions'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_assumptions',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>25));
						echo CHtml::label(Yii::t('projects','FormProject_assumptions'), CHtml::activeId($model, 'project_assumptions'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_warranty'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_warranty',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>26));
						echo CHtml::label(Yii::t('projects','FormProject_warranty'), CHtml::activeId($model, 'project_warranty'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'project_outReach'); ?>
				<span>
					<?php
						echo $form->textArea($model,'project_outReach',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>27));
						echo CHtml::label(Yii::t('projects','FormProject_outReach'), CHtml::activeId($model, 'project_outReach'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</fieldset>

	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary','tabindex'=>28)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big','tabindex'=>29)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('jquery.togglefieldset','
	$(".fieldsettoggle").click(function(){
		$(this).next().slideToggle("fast");
		$(this).children("b").toggleClass("sortoggledown");
	});
');
?>