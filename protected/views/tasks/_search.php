<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<div class="subcolumns">
		<div class="c33l">
			<div class="row">
				<?php echo $form->labelEx($model,'task_name'); ?>
				<span>
					<?php
						echo $form->textField($model,'task_name',array('class'=>'betterform','style'=>'width:90%','maxlength'=>60,'tabindex'=>1));
						echo CHtml::label(Yii::t('tasks','FormTaskName'), CHtml::activeId($model, 'task_name'), array('class'=>'labelhelper'));
					?>			
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'status_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'status_id',CHtml::listData($status, 'status_id', 'status_name'),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'tabindex'=>4));
						echo CHtml::label(Yii::t('tasks','FormTaskStatus'), CHtml::activeId($model, 'status_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'case_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'case_id',CHtml::listData($cases, 'case_id', 'CaseTitle'),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'tabindex'=>7));
						echo CHtml::label(Yii::t('tasks','FormTaskCase'), CHtml::activeId($model, 'case_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c33l">
			<div class="row">
				<?php echo $form->labelEx($model,'task_participant'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'task_participant',CHtml::listData($users, 'user_id', 'CompleteName'),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'tabindex'=>2));
						echo CHtml::label(Yii::t('tasks','task_participant'), CHtml::activeId($model, 'task_participant'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'taskTypes_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'taskTypes_id',CHtml::listData($types, 'taskTypes_id', 'taskTypes_name'),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'tabindex'=>5));
						echo CHtml::label(Yii::t('tasks','FormTaskTaskTypes'), CHtml::activeId($model, 'taskTypes_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'taskStage_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'taskStage_id',CHtml::listData($stages, 'taskStage_id', 'taskStage_name'),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'tabindex'=>8));
						echo CHtml::label(Yii::t('tasks','FormTaskTaskStage'), CHtml::activeId($model, 'taskStage_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
		<div class="c33r">
			<div class="row">
				<?php echo $form->labelEx($model,'task_priority'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'task_priority',array(Cases::PRIORITY_LOW=>Yii::t('site','lowPriority'), Cases::PRIORITY_MEDIUM=>Yii::t('site','mediumPriority'), Cases::PRIORITY_HIGH=>Yii::t('site','highPriority')),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'tabindex'=>3));
						echo CHtml::label(Yii::t('tasks','FormTaskPiority'), CHtml::activeId($model, 'task_priority'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
			<div class="row">
				<?php echo $form->labelEx($model,'milestone_id'); ?>
				<span>
					<?php
						echo $form->dropDownList($model,'milestone_id',CHtml::listData($milestones, 'milestone_id', 'milestone_title'),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'tabindex'=>6));
						echo CHtml::label(Yii::t('tasks','FormTaskMilestone'), CHtml::activeId($model, 'milestone_id'), array('class'=>'labelhelper'));
					?>
				</span>
			</div>
		</div>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('site','search'), array( 'tabindex'=>9, 'class'=>'button primary')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->