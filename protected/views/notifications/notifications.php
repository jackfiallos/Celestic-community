<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('users', 'ListUsers')=>array('index'),
	Yii::t('users', 'ManageNotifications'),
);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo users"><?php echo Yii::t('users', 'ManageNotifications'); ?></h1>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'notifications-form',
			'enableAjaxValidation'=>false,
		));
		?>
		
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'notification-grid',
			'cssFile'=>'css/screen.css',
			'dataProvider'=>$dataProvider,
			'summaryText'=>'<div style="padding:15px;"><h3>'.CHtml::label('Select a Project to configure Notifications', 'Project', array('class'=>'labelhelper')).'</h3>'.CHtml::dropDownList('Project','',CHtml::listData($projectsList, 'project_id', 'project_name')).'</div>',
			'columns'=>array(
				array(
					'header'=>'Module Name',
					'type'=>'raw',
					'value' =>'$data->module_name',
				),
				array(
					'header'=>'<div style="text-align:center">On Create</div>',
					'type'=>'raw',
					'value' =>'CHtml::checkBox("Notifications[".$data->module_id."][".Notifications::ON_CREATE."]")',
					'htmlOptions'=>array(
						'style'=>'width:10%; text-align:center',
					)
				),
				array(
					'header'=>'<div style="text-align:center">On Update</div>',
					'type'=>'raw',
					'value' =>'CHtml::checkBox("Notifications[".$data->module_id."][".Notifications::ON_UPDATE."]")',
					'htmlOptions'=>array(
						'style'=>'width:10%; text-align:center',
					)
				),
				array(
					'header'=>'<div style="text-align:center">On Delete</div>',
					'type'=>'raw',
					'value' =>'CHtml::checkBox("Notifications[".$data->module_id."][".Notifications::ON_DELETE."]")',
					'htmlOptions'=>array(
						'style'=>'width:10%; text-align:center',
					)
				),
				array(
					'header'=>'<div style="text-align:center">On Comment</div>',
					'type'=>'raw',
					'value' =>'CHtml::checkBox("Notifications[".$data->module_id."][".Notifications::ON_COMMENT."]")',
					'htmlOptions'=>array(
						'style'=>'width:10%; text-align:center',
					)
				),
			),
		)); ?>
		
		<div class="row buttons subcolumns">
			<div class="c50l">
				<?php
					echo CHtml::ajaxSubmitButton("Save", Yii::app()->createUrl('notifications/save'),array(
						'data'=>'js:$(this).parents("form").serialize()',
						'success'=>'js:function(data) {
							
						}',
						'beforeSend' => 'function(){
							$("#notification-grid").addClass("loading");
						}',
						'complete' => 'function(){
							$("#notification-grid").removeClass("loading");
						}',
					), array(
						'class'=>'btn save'
					));
				?>
				<?php echo CHtml::button('Reset', array('type'=>'reset', 'class'=>'btn reset')); ?>
			</div>
			<div class="c50r" style="text-align:right;">
				<?php echo CHtml::link('Return', Yii::app()->request->getUrlReferrer(), array('class'=>'btn return')); ?>
			</div>
		</div>
	
		<?php $this->endWidget(); ?>
	</div>
</div>
<?php
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerScript('jQueryCheckboxSelect','
	$(document).ready(function() {
		$(".chkhead")
	});
');
//$cs = Yii::app()->getClientScript();  
//$cs->registerCssFile(Yii::app()->request->baseUrl.'/css/redmond/jquery-ui.css');
?>