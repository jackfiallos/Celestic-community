<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="subcolumns">
		<div class="c25l">
			<div class="row">
				<?php echo $form->labelEx($model,'document_name'); ?>
				<span>
					<?php
						echo $form->textField($model,'document_name',array('class'=>'betterform','style'=>'width:90%','maxlength'=>45,'tabindex'=>1));
						echo CHtml::label(Yii::t('documents','FormDocumentName'), CHtml::activeId($model, 'document_name'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c25l">
			<div class="row">
				<?php echo $form->labelEx($model,'document_revision'); ?>
				<span>
					<?php
						echo $form->textField($model,'document_revision',array('class'=>'betterform','style'=>'width:90%','maxlength'=>45,'tabindex'=>2));
						echo CHtml::label(Yii::t('documents','FormDocumentRevision'), CHtml::activeId($model, 'document_revision'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c25l">
			<div class="row">
				<?php echo $form->labelEx($model,'document_description'); ?>
				<span>
					<?php
						echo $form->textField($model,'document_description',array('class'=>'betterform','style'=>'width:90%','maxlength'=>45,'tabindex'=>3));
						echo CHtml::label(Yii::t('documents','FormDocumentDescription'), CHtml::activeId($model, 'document_description'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c25r">
			<div class="row">
				<?php echo $form->labelEx($model,'document_type'); ?>
				<span>
					<?php
						echo $form->textField($model,'document_type',array('class'=>'betterform','style'=>'width:90%','maxlength'=>45,'tabindex'=>4));
						echo CHtml::label(Yii::t('documents','FormDocumentType'), CHtml::activeId($model, 'document_type'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>	

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site','search'), array( 'tabindex'=>5, 'class'=>'button primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->