<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('admin'),
	Yii::t('configuration', 'RolesAssigments'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('configuration', 'RolesAssigments');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo assigments"><?php echo Yii::t('configuration', 'ManageRolesAssigments'); ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('configuration', 'UserPermissions'), Yii::app()->createUrl('configuration/usersPermissions'),array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('configuration', 'ManageRoles'), Yii::app()->createUrl('configuration/rolesManage'),array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('configuration', 'RolesAssigments'), Yii::app()->createUrl('configuration/rolesAssignments'),array('class'=>'button primary')); ?>
		</div>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'manageusers-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<div class="subcolumns">
			<div class="c33l">
				<p>
					<h3><?php echo Yii::t('authitems','roles'); ?></h3>
					<?php
						echo CHtml::dropDownList('Assigments[roles]', null, CHtml::listData($listData, 'id', 'name'), array(
							'style'=>'width:80%',
							'empty'=>Yii::t('configuration', 'selectOption'),
							'ajax' => array(
								'type'=>'POST',
								'url'=>array('getTasks'),
								'update'=>'#preview',
								'beforeSend' => 'function(){
									$("#preview").addClass("loading");
								}',
								'complete' => 'function(){
									$("#preview").removeClass("loading");
								}'
							),
						));
					?>
				</p>
				<p>
					<h3><?php echo Yii::t('authitems','modules'); ?></h3>
					<?php
						$data = CHtml::listData($tasks, 'name', 'name');
						$dataOutput = array();
						foreach($data as $items => $value)
						{
							$dataOutput[$items] = CHtml::encode(Yii::t('modules',strtolower($value))); 
						}
						echo CHtml::dropDownList('Assigments[module]', null, $dataOutput, array(
							'style'=>'width:80%',
							'ajax' => array(
								'type'=>'POST',
								'url'=>array('getTasks'),
								'update'=>'#preview',
								'beforeSend' => 'function(){
									$("#preview").addClass("loading");
								}',
								'complete' => 'function(){
									$("#preview").removeClass("loading");
								}'
							),
						));
					?>
				</p>
			</div>
			<div class="c66r" id="preview">
				<?php echo Yii::t('authitems','pleaseSelectRoletoViewTask'); ?>
			</div>
		</div>
		<div style="text-align:right; padding-top:15px;">
		<?php echo CHtml::ajaxSubmitButton(Yii::t('site','save'),
			Yii::app()->createUrl('configuration/rolesOperations'),
			array(
				'data'=>'js:$(this).parents("form").serialize()',
				'success'=>'function(data) {
					$(\'#notify\').html(data).addClass("notification_success").fadeIn("fast").animate({opacity: 1.0}, 3000).fadeOut("slow");
				}',	
				'complete' => 'function() {
					$("#preview").removeClass("loading");
				}',
				'beforeSend' => 'function() {
					$("#preview").addClass("loading");
					$("#select_left").each(function(i) {
						$("#select_left option").attr("selected", "selected");
					});
					//return false;
				}',
			), array(
				'id'=>'lnkBtnSave',
				'class'=>'btn save button big primary',
			));
		?>
		</div>
		<?php $this->endWidget(); ?>
		<br />
		<div id="notify"></div>
	</div>
</div>