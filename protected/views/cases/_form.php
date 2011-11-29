<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cases-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('cases','FieldsRequired'); ?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<fieldset>
		<legend style="font-weight:bold"><?php echo Yii::t('cases','CaseConceptualization'); ?></legend>
		<div class="subcolumns">
			<div class="c50l">
				<div class="row">
					<?php echo $form->labelEx($model,'case_name'); ?>
					<span>
						<?php
							echo $form->textField($model,'case_name',array('class'=>'betterform','style'=>'width:95%','maxlength'=>100,'tabindex'=>1));
							echo CHtml::label(Yii::t('cases','FormCaseName'), CHtml::activeId($model, 'case_name'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'case_actors'); ?>
					<span>
						<?php
							echo $form->textField($model,'case_actors',array('class'=>'betterform','style'=>'width:95%','maxlength'=>100,'tabindex'=>3));
							echo CHtml::label(Yii::t('cases','FormCaseActors'), CHtml::activeId($model, 'case_actors'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo $form->labelEx($model,'case_code'); ?>
					<span>
						<?php
							echo $form->textField($model,'case_code',array('class'=>'betterform','style'=>'width:95%','maxlength'=>15,'tabindex'=>2));
							echo CHtml::label(Yii::t('cases','FormCaseCode'), CHtml::activeId($model, 'case_code'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'case_priority'); ?>
					<span>
						<?php
							echo $form->dropDownList($model,'case_priority',array(Cases::PRIORITY_LOW=>Yii::t('site','lowPriority'), Cases::PRIORITY_MEDIUM=>Yii::t('site','mediumPriority'), Cases::PRIORITY_HIGH=>Yii::t('site','highPriority')),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'tabindex'=>4));
							echo CHtml::label(Yii::t('cases','FormCasePiority'), CHtml::activeId($model, 'case_priority'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
			</div>
		</div>
	</fieldset>

	<fieldset>
		<legend style="font-weight:bold"><?php echo Yii::t('cases','CaseFunctionality'); ?></legend>
		<div class="row">
			<?php echo $form->labelEx($model,'case_description'); ?>
			<span>
				<?php
					echo $form->textArea($model,'case_description',array('class'=>'betterform','rows'=>10, 'cols'=>50,'style'=>'width:95%','tabindex'=>5));
					echo CHtml::label(Yii::t('cases','FormCaseDescription'), CHtml::activeId($model, 'case_description'), array('class'=>'labelhelper'));
				?>
			</span>
		</div>
		
		<div class="row">
			<?php echo $form->labelEx($model,'case_requirements'); ?>
			<span>
				<?php
					echo $form->textArea($model,'case_requirements',array('rows'=>10, 'cols'=>50,'class'=>'betterform','style'=>'width:95%','tabindex'=>6));
					echo CHtml::label(Yii::t('cases','FormCaseRequirements'), CHtml::activeId($model, 'case_requirements'), array('class'=>'labelhelper'));
				?>
			</span>
		</div>
	</fieldset>

	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary','tabindex'=>7)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big','tabindex'=>8)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->