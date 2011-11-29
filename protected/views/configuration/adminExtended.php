<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration'),
);
?>

<div class="portlet x12">
	<div class="portlet-header">
		<div class="portlet-title"><?php echo Yii::t('configuration', 'AccountOptionsAdministration'); ?></div>
	</div>
	<div class="portlet-content">
		<div style="padding-bottom:25px;">
			<h3 style="font-size: 13px; font-weight:bold;"><?php echo Yii::t('configuration', 'AccountPreferences'); ?></h3>
			<ul style="margin-left: 20px;">
				<li>
					<a class="icon-menu corners" href="<?php echo Yii::app()->createUrl('configuration/account'); ?>">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/layout.png"); ?>
						<span><?php echo Yii::t('configuration', 'Account'); ?></span>
					</a>
				</li>
				<li>                      
					<a class="icon-menu corners" href="<?php echo Yii::app()->createUrl('configuration/usersPermissions'); ?>">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/permissions.png"); ?>
						<span><?php echo Yii::t('configuration', 'Permissions'); ?></span>
					</a>
				</li>
				<li>                      
					<a class="icon-menu corners" href="<?php echo Yii::app()->createUrl('users'); ?>">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/users.png"); ?>
						<span><?php echo Yii::t('configuration', 'Users'); ?></span>
					</a>
				</li>
				<li>
					<a class="icon-menu corners" href="<?php echo Yii::app()->createUrl('clients'); ?>">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/clients.png"); ?>
						<span><?php echo Yii::t('configuration', 'Clients'); ?></span>
					</a>
				</li>    
				<li>    
					<a class="icon-menu corners" href="<?php echo Yii::app()->createUrl('companies'); ?>">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/companies.png"); ?>
						<span><?php echo Yii::t('configuration', 'Companies'); ?></span>
					</a>
				</li> 
				<li>                      
					<a class="icon-menu corners" href="<?php echo Yii::app()->createUrl('configuration/localization'); ?>">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/time.png"); ?>
						<span><?php echo Yii::t('configuration', 'Localization'); ?></span>
					</a>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
		
		<div style="padding-bottom:25px;">
			<h3 style="font-size: 13px; font-weight:bold;"><?php echo Yii::t('configuration', 'MailConfigurationTitle'); ?></h3>
			<ul style="margin-left: 20px;">
				<li>                      
					<a class="icon-menu corners" href="#">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/mail_server_setting.png"); ?>
						<span><?php echo Yii::t('configuration', 'MailConfiguration'); ?></span>
					</a>
				</li>
				<li>                      
					<a class="icon-menu corners" href="#">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/email_go.png"); ?>
						<span><?php echo Yii::t('configuration', 'TestEmail'); ?></span>
					</a>
				</li>
				<li>                      
					<a class="icon-menu corners" href="#">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/email_add.png"); ?>
						<span><?php echo Yii::t('configuration', 'MassMail'); ?></span>
					</a>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
		
		<div style="padding-bottom:25px;">
			<h3 style="font-size: 13px; font-weight:bold;"><?php echo Yii::t('configuration', 'AccounTemplates'); ?></h3>
			<ul style="margin-left: 20px;">
				<li>                      
					<a class="icon-menu corners" href="#">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/invoices_template.png"); ?>
						<span><?php echo Yii::t('configuration', 'InvoicesTemplates'); ?></span>
					</a>
				</li>
				<li>                      
					<a class="icon-menu corners" href="#">
						<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/icons/email_template.png"); ?>
						<span><?php echo Yii::t('configuration', 'EmailTemplates'); ?></span>
					</a>
				</li>
			</ul>
			<div class="clear"></div>
		</div>
		
		<div class="installation_details" style="padding-bottom:25px;">
			<h3 style="font-size: 13px; font-weight:bold;"><?php echo Yii::t('configuration', 'SystemInformation'); ?></h3>
			<dl style="margin-left: 20px;">
				<dt><?php echo Yii::app()->name; ?> version:</dt>
				<dd><?php echo Yii::app()->params['appVersion']; ?></dd>
				<dt><?php echo Yii::t('configuration', 'BasedOn'); ?>:</dt>
				<dd><?php echo Yii::app()->params['lastAppVersion']; ?></dd>
				<dt><?php echo Yii::t('configuration', 'Servers')." ".Yii::t('configuration', 'Apache'); ?>:</dt>
				<dd>
					<?php echo $apacheVersion; ?>
				</dd>
				<dt><?php echo Yii::t('configuration', 'Plataform'); ?>:</dt>
				<dd>
					<?php echo Yii::t('configuration', 'php'); ?> <?php echo $phpVersion; ?>, 
					<?php echo Yii::t('configuration', 'mysql'); ?> <?php echo $mysqlVersion; ?>
				</dd>
				<dt>Max filesize upload:</dt>
				<dd><?php echo $max_upload = (int)(ini_get('upload_max_filesize')); ?> MB</dd>
			</dl>
			<div class="clear"></div>
		</div>
	</div>
</div>