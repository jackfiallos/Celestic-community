<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('admin'),
	Yii::t('configuration', 'ManageRoles'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('configuration', 'ManageRoles');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo roles"><?php echo Yii::t('configuration', 'ManageRoles'); ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('configuration', 'UserPermissions'), Yii::app()->createUrl('configuration/usersPermissions'),array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('configuration', 'ManageRoles'), Yii::app()->createUrl('configuration/rolesManage'),array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('configuration', 'RolesAssigments'), Yii::app()->createUrl('configuration/rolesAssignments'),array('class'=>'button')); ?>
		</div>
		<div id="content">
			<div id="wizard">
				<div id="list">
					<?php
					echo $this->renderPartial('manage/list', array(
						'roles'=>$roles
					));
					?>
				</div>
				<fieldset style="padding-left:15px; border-top:1px solid #ccc; padding-bottom:20px;">
					<legend style="padding:0 6px 0 6px;font-weight:bold;font-size:18px;">Details</legend>
					<div id="preview"></div>
				</fieldset>
			</div>
		</div>
	</div>
</div>