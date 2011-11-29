<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui-1.8rc3.css" rel="stylesheet" />
	<!--[if lte IE 7]>
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/yaml/patches/patch_my_layout.css" rel="stylesheet" type="text/css" />
	<![endif]-->
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/my_layout.css" rel="stylesheet" type="text/css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<link rel="shortcut icon" type="image/x-icon" href="favicon.png" />
</head>
<body>
	<div class="container">
		<div id="content">
			<?php echo $content; ?>
		</div><!-- content -->
	</div>
	<?php
		Yii::app()->clientScript->registerCoreScript('jquery');
		Yii::app()->clientScript->registerScript('jQueryUsability','
			$(document).ready(function() {
				$(\'input.betterform, textarea.betterform, select.betterform\').focus(function(){
					$(this).parents(\'.row\').addClass("over");
				}).blur(function(){
					$(this).parents(\'.row\').removeClass("over");
				});
			});
		');
	?>
</body>
</html>