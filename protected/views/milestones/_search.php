<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="subcolumns">
		<div class="c50l">
			<div class="row">
				<?php echo $form->labelEx($model,'milestone_title'); ?>
				<span>
					<?php
						echo $form->textField($model,'milestone_title',array('class'=>'betterform','style'=>'width:90%','maxlength'=>45,'tabindex'=>1));
						echo CHtml::label(Yii::t('milestones','FormMilestoneTitle'), CHtml::activeId($model, 'milestone_title'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c50r">
			<div class="row">
				<?php echo $form->labelEx($model,'user_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'user_id',CHtml::listData($users, 'user_id', 'completeName'),array('style'=>'width:90%','class'=>'betterform','empty'=>Yii::t('milestones', 'selectOption'),'tabindex'=>2));
						echo CHtml::label(Yii::t('milestones','FormMilestoneUser'), CHtml::activeId($model, 'user_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'milestone_description'); ?>
		<span>
			<?php
				echo $form->textField($model,'milestone_description',array('class'=>'betterform','style'=>'width:95%','tabindex'=>3));
				echo CHtml::label(Yii::t('milestones','FormMilestoneDescription'), CHtml::activeId($model, 'milestone_description'), array('class'=>'labelhelper'));
			?>
		</span>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site','search'), array( 'tabindex'=>4, 'class'=>'button primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->