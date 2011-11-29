<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="subcolumns">
		<div class="c33l">
			<div class="row">
				<?php echo $form->labelEx($model,'case_name'); ?>
				<span>
					<?php
						echo $form->textField($model,'case_name',array('class'=>'betterform','style'=>'width:90%','maxlength'=>60,'tabindex'=>1));
						echo CHtml::label(Yii::t('cases','FormCaseName'), CHtml::activeId($model, 'case_name'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c33l">
			<div class="row">
				<?php echo $form->labelEx($model,'case_code'); ?>
				<span>
					<?php
						echo $form->textField($model,'case_code',array('class'=>'betterform','style'=>'width:90%','maxlength'=>60,'tabindex'=>2));
						echo CHtml::label(Yii::t('cases','FormCaseCode'), CHtml::activeId($model, 'case_code'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c33r">
			<div class="row">
				<?php echo $form->labelEx($model,'case_priority'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'case_priority',array(Cases::PRIORITY_LOW=>Yii::t('site','lowPriority'), Cases::PRIORITY_MEDIUM=>Yii::t('site','mediumPriority'), Cases::PRIORITY_HIGH=>Yii::t('site','highPriority')),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'tabindex'=>3));
						echo CHtml::label(Yii::t('cases','FormCasePiority'), CHtml::activeId($model, 'case_priority'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>
	<div class="subcolumns">
		<div class="c33l">
			<div class="row">
				<?php echo $form->labelEx($model,'case_description'); ?>
				<span>
					<?php
						echo $form->textField($model,'case_description',array('class'=>'betterform','style'=>'width:95%','maxlength'=>60,'tabindex'=>4));
						echo CHtml::label(Yii::t('cases','FormCaseDescription'), CHtml::activeId($model, 'case_description'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c33l">
			<div class="row">
				<?php echo $form->labelEx($model,'case_requirements'); ?>
				<span>
					<?php
						echo $form->textField($model,'case_requirements',array('class'=>'betterform','style'=>'width:90%','maxlength'=>60,'tabindex'=>5));
						echo CHtml::label(Yii::t('cases','FormCaseRequirements'), CHtml::activeId($model, 'case_requirements'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c33r">
			<div class="row">
				<?php echo $form->labelEx($model,'status_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'status_id',CHtml::listData($status, 'status_id', 'status_name'),array('class'=>'betterform','empty'=>Yii::t('cases', 'selectOption'),'style'=>'width:90%','tabindex'=>6));
						echo CHtml::label(Yii::t('cases', 'FormStatusCases'), CHtml::activeId($model, 'status_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site','search'), array( 'tabindex'=>7, 'class'=>'button primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->