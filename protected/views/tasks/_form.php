<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tasks-form',
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo Yii::t('tasks','FieldsRequired'); ?>
	
	<?php
	if (!$model->isNewRecord && !$allowEdit)
		echo CHtml::tag("div", array('class'=>'notification_warning'),Yii::t('tasks', 'NowAllowedToEdit'));
	?>

	<?php echo $form->errorSummary($model,null,null,array('class'=>'errorSummary stick'))."<br />"; ?>
	
	<fieldset>
		<legend style="font-weight:bold;"><?php echo Yii::t('tasks','Relations'); ?></legend>
		<div class="subcolumns">
			<div class="c33l">
				<div class="row">
					<?php echo $form->labelEx($model,'task_priority'); ?>
					<span>
						<?php
							echo $form->dropDownList($model,'task_priority',array('0'=>Yii::t('site','lowPriority'), '1'=>Yii::t('site','mediumPriority'), '2'=>Yii::t('site','highPriority')),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'disabled'=>!$allowEdit,'tabindex'=>1));
							echo CHtml::label(Yii::t('tasks','FormTaskPiority'), CHtml::activeId($model, 'task_priority'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'milestone_id'); ?>
					<span>
						<?php
							echo $form->dropDownList($model,'milestone_id',CHtml::listData($milestones, 'milestone_id', 'milestone_title'),array('id'=>'milestones','class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'disabled'=>!$allowEdit,'tabindex'=>4));
							echo CHtml::label(Yii::t('tasks','FormTaskMilestone')." ".CHtml::link("Details from Selected", Yii::app()->createUrl('milestones/details'), array('id'=>'milestonesDetailsLink', 'style'=>'display:none;')), CHtml::activeId($model, 'milestone_id'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
			</div>
			<div class="c33l">
				<div class="row">
					<?php echo $form->labelEx($model,'taskStage_id'); ?>
					<span>
						<?php
							echo $form->dropDownList($model,'taskStage_id',CHtml::listData($stages, 'taskStage_id', 'taskStage_name'),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'disabled'=>!$allowEdit,'tabindex'=>2));
							echo CHtml::label(Yii::t('tasks','FormTaskTaskStage'), CHtml::activeId($model, 'taskStage_id'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<div class="row">
					<?php echo $form->labelEx($model,'case_id'); ?>
					<span>
						<?php
							echo $form->dropDownList($model,'case_id',CHtml::listData($cases, 'case_id', 'CaseTitle'),array('id'=>'cases','class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'disabled'=>!$allowEdit,'tabindex'=>5));
							echo CHtml::label(Yii::t('tasks','FormTaskCase'), CHtml::activeId($model, 'case_id'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
			</div>
			<div class="c33r">
				<div class="row">
					<?php echo $form->labelEx($model,'taskTypes_id'); ?>
					<span>
						<?php
							echo $form->dropDownList($model,'taskTypes_id',CHtml::listData($types, 'taskTypes_id', 'taskTypes_name'),array('class'=>'betterform','style'=>'width:95%','empty'=>Yii::t('tasks', 'selectOption'),'disabled'=>!$allowEdit,'tabindex'=>3));
							echo CHtml::label(Yii::t('tasks','FormTaskTaskTypes'), CHtml::activeId($model, 'taskTypes_id'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>
				<!--<div class="row">
					<?php //echo $form->labelEx($model,'dependant'); ?>
					<span>
						<?php
							//echo $form->textField($model,'dependant',array('class'=>'betterform','style'=>'width:95%','disabled'=>!$allowEdit, 'tabindex'=>6));
							//echo CHtml::label(Yii::t('tasks','FormTaskTaskDependant'), CHtml::activeId($model, 'dependant'), array('class'=>'labelhelper'));
						?>
					</span>
				</div>-->
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend style="font-weight:bold;"><?php echo Yii::t('tasks','Summary'); ?></legend>
		<div class="subcolumns">
			<div class="c50l">
				<div class="row">
					<?php echo $form->labelEx($model,'task_name'); ?>
					<span>
						<?php
							echo $form->textField($model,'task_name',array('class'=>'betterform','style'=>'width:95%','maxlength'=>100,'disabled'=>!$allowEdit,'tabindex'=>7));
							echo CHtml::label(Yii::t('tasks','FormTaskName'), CHtml::activeId($model, 'task_name'), array('class'=>'labelhelper'));
						?>			
					</span>
				</div>
			</div>
			<div class="c50r">
				<div class="row">
					<?php echo $form->labelEx($model,'task_buildNumber'); ?>
					<span>
						<?php
							echo $form->textField($model,'task_buildNumber',array('class'=>'betterform','style'=>'width:95%','maxlength'=>20,'disabled'=>!$allowEdit, 'tabindex'=>8));
							echo CHtml::label(Yii::t('tasks','FormTaskBuildNumber'), CHtml::activeId($model, 'task_buildNumber'), array('class'=>'labelhelper'));
						?>
					</span>		
				</div>
			</div>
		</div>
		<div class="row">
			<?php echo $form->labelEx($model,'task_description'); ?>
			<span>
				<?php
					echo $form->textArea($model,'task_description',array('class'=>'betterform','style'=>'width:95%','rows'=>10,'cols'=>50,'disabled'=>!$allowEdit, 'tabindex'=>9));
					echo CHtml::label(Yii::t('tasks','FormTaskDescription'), CHtml::activeId($model, 'task_description'), array('class'=>'labelhelper'));
				?>
			</span>
		</div>
	</fieldset>

	<div class="row buttons subcolumns">
		<div class="c50l">
			<?php echo CHtml::button($model->isNewRecord ? Yii::t('site','create') : Yii::t('site','save'), array('type'=>'submit', 'class'=>'button big primary','disabled'=>!$allowEdit,'tabindex'=>10)); ?>
			<?php echo CHtml::button(Yii::t('site','reset'), array('type'=>'reset', 'class'=>'button big','tabindex'=>11)); ?>
		</div>
		<div class="c50r" style="text-align:right;">
			<?php echo CHtml::link(Yii::t('site','return'), Yii::app()->request->getUrlReferrer(), array('class'=>'button')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<div id="milestonesDetails"></div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.simplemodal-1.4.1.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScript('jquery.tasksview','
	var selected;
	
	$("#milestones").live("change",function(){
		selected = $("#milestones").val();
		if ((typeof(selected) != "undefined") && (selected.length > 0 ))
			$("#milestonesDetailsLink").show();
		else
			$("#milestonesDetailsLink").hide();
	});
	
	$("#milestonesDetailsLink").live("click", function(e){
		e.preventDefault();
		if ((typeof(selected) != "undefined") && (selected.length > 0 )) {
			var href = $(this).attr("href");
			$("#milestonesDetails").modal({
				opacity:80,
				onShow:function(dialog){
					$.ajax({
						url:href,
						async:false,
						type:"POST",
						data:{param:selected,YII_CSRF_TOKEN:"'.Yii::app()->request->csrfToken.'",ajax:"getDetails"},
						beforeSend:function(){
							dialog.data.parent().addClass("loading");
						},
						success:function(result){
							dialog.data.parent().removeClass("loading").children("div").html(result);
						},
					});
				},
				containerCss:{
					backgroundColor:"#fff",
					height:300,
					width:400
				},
				overlayClose:true,
			});
		}
	});
');
?>