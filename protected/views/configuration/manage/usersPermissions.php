<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('admin'),
	Yii::t('configuration', 'UserPermissions'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('configuration', 'UserPermissions');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo permission"><?php echo Yii::t('configuration', 'ManageUsers'); ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('configuration', 'UserPermissions'), Yii::app()->createUrl('configuration/usersPermissions'),array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('configuration', 'ManageRoles'), Yii::app()->createUrl('configuration/rolesManage'),array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('configuration', 'RolesAssigments'), Yii::app()->createUrl('configuration/rolesAssignments'),array('class'=>'button')); ?>
		</div>
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'manageusers-form',
			'enableAjaxValidation'=>false,
		)); ?>
		<div class="subcolumns">
			<div class="c33l">
				<div class="row">
					<h3>
					<?php
						echo CHtml::label(Yii::t('authitems','userList'),'lstUsers');
					?>
					</h3>
				</div>
				<div class="row">
					<?php
					echo CHtml::dropDownList('ManageUsers[lstUsers]','',CHtml::listData(Users::model()->findUserWithoutAccountManager(), 'user_id', 'CompleteName'), array(
							'size'=>10,
							'class'=>'dropdown',
							'id'=>'lstUsers',
							'style'=>'margin: 1em 0 0.25em',
							'ajax'=>array(
								'type'=>'POST',
								'url'=>array('getRoles'),
								'data'=>array(
									'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken,
									'YII_CSRF_USID'=>'js:$("#lstUsers").val()',
								),
								'update'=>'#roles',
								'beforeSend' => 'function(){
									$("#roles").addClass("loading").css({width:"100%",height:"200px"});
								}',
								'complete' => 'function(){
									$("#roles").removeClass("loading");
								}',
							),
						));
					?>
				</div>
			</div>
			<div class="c66r">
				<div class="row">
					<h3>
					<?php
						echo CHtml::label(Yii::t('authitems','appRoles'),'lstRoles');
					?>
					</h3>
				</div>
				<div class="row" id="roles">
					<?php echo Yii::t('authitems','pleaseSelectUsertoViewRol'); ?>
				</div>
			</div>
		</div>
		<div style="text-align:right; padding-top:15px;">
			<?php //echo CHtml::button('Save', array('type'=>'submit', 'class'=>'btn save')); ?>
			<?php echo CHtml::ajaxSubmitButton(Yii::t('site','save'),
				Yii::app()->createUrl('configuration/usersPermissions'),
				array(
					'success'=>'function(data) {
						$(\'#notify\').html(data).addClass("notification_success").fadeIn("fast").animate({opacity: 1.0}, 3000).fadeOut("slow");
					}',	
					'complete' => 'function() {
						$("#roles").removeClass("loading");
					}',
					'beforeSend' => 'function() {
						$("#roles").addClass("loading").css({width:"100%",height:"200px"});
					}',
				), array(
					'id'=>'lnkBtnSave',
					'class'=>'button big primary',
				));
			?>
		</div>
		<?php $this->endWidget(); ?>
		<br />
		<div id="notify"></div>
	</div>
</div>