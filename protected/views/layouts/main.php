<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Pragma" content="no-cache" />
<meta http-equiv="expires" content="0" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" type="text/css" rel="stylesheet" media="screen" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/base.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/simplemodal.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/buttons.css" rel="stylesheet" type="text/css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/grid.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" type="image/x-icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.png" />
</head>
<body>
	<div id="wrapper" class="clearfix">
		<div id="top">
			<div id="header">
				<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl."/images/celestic.png",CHtml::encode(Yii::app()->name).' v.'.Yii::app()->params['appVersion']),Yii::app()->createUrl('site'), array('title'=>CHtml::encode(Yii::app()->name).' v.'.Yii::app()->params['appVersion']));?>
				<?php if (!Yii::app()->user->isGuest){ ?>
				<div id="info">
					<h4><?php echo Yii::t('site','WelcomeMessage'); ?></h4>
					<p>
						<?php echo Yii::t('site','LoggedAs')." ".CHtml::link(CHtml::encode(Yii::app()->user->CompleteName),Yii::app()->createUrl('users/view', array('id'=>Yii::app()->user->id))); ?>
						<br />
						<?php
							if (strtotime(Yii::app()->user->getState('user_lastLogin')) != null)
								echo Yii::t('users','user_lastLogin')." ".Yii::app()->dateFormatter->format('dd.MM.yyyy', Yii::app()->user->getState('user_lastLogin'))." - ";
						?>
						<?php echo (!Yii::app()->user->isGuest) ? CHtml::link('Logout', Yii::app()->createUrl('site/logout')) : ''; ?>
					</p>
					<?php 
						$this->widget('application.extensions.VGGravatarWidget.VGGravatarWidget', 
							array(
								'email' => CHtml::encode(Yii::app()->user->getState('user_email')),
								'hashed' => false,
								'default' => 'http://'.$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl.'/images/bg-avatar.png',
								'size' => 65,
								'rating' => 'PG',
								'htmlOptions' => array('class'=>'borderCaption','alt'=>'Gravatar Icon' ),
							)
						);
					?>
				</div>
				<?php } ?>
				<?php
				if(!Yii::app()->user->isGuest)
					$this->widget('widgets.GlobalSearch');
				?>
			</div>
			
			<div id="nav">
				<?php
					$selected = null;
					if(!Yii::app()->user->isGuest)
					{
						// Navbar menu items
						$items = array();
						// Projects with access
						$projectsItems = array();
						foreach(Yii::app()->user->getProjects() as $project)
							array_push($projectsItems, array('label'=>$project->project_name, 'url'=>Yii::app()->createUrl('site/index', array('infoproject'=>$project->project_id)), 'itemOptions'=>array('class'=>'subnav')));
						array_push($projectsItems, array('label'=>Yii::t('projects','CreateProjects'), 'url'=>Yii::app()->createUrl('configuration/createproject'), 'itemOptions'=>array('class'=>'subnav')));
						
						$selected = Yii::app()->user->getState('project_selected');
						if (isset($selected))
						{
							$items = array(
								array('label'=>Yii::t('budgets', 'TitleBudget'), 'itemOptions'=>array('class'=>'mega'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/budgets'), 'active'=>(in_array(Yii::app()->controller->id,array('budgets','bconcepts'))), 'visible'=>Yii::app()->user->checkAccess('viewBudgets')),
								array('label'=>Yii::t('invoices', 'TitleInvoices'), 'itemOptions'=>array('class'=>'mega'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/invoices'), 'active'=>(in_array(Yii::app()->controller->id,array('invoices','iconcepts'))), 'visible'=>Yii::app()->user->checkAccess('viewInvoices')),
								array('label'=>Yii::t('expenses', 'TitleExpenses'), 'itemOptions'=>array('class'=>'mega'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/expenses'), 'active'=>(in_array(Yii::app()->controller->id,array('expenses','econcepts'))), 'visible'=>Yii::app()->user->checkAccess('viewExpenses')),
								array('label'=>Yii::t('documents', 'TitleDocuments'), 'itemOptions'=>array('class'=>'mega'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/documents'), 'active'=>(Yii::app()->controller->id=='documents'), 'visible'=>Yii::app()->user->checkAccess('viewDownloads')),
								array('label'=>Yii::t('milestones', 'TitleMilestones'), 'itemOptions'=>array('class'=>'mega'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/milestones'), 'active'=>(Yii::app()->controller->id=='milestones'), 'visible'=>Yii::app()->user->checkAccess('viewMilestones')),
								array('label'=>Yii::t('cases', 'TitleCases'), 'itemOptions'=>array('class'=>'mega'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/cases'), 'active'=>(Yii::app()->controller->id=='cases'), 'visible'=>Yii::app()->user->checkAccess('viewCases')),
								array('label'=>Yii::t('tasks', 'TitleTasks'), 'itemOptions'=>array('class'=>'mega'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/tasks'), 'active'=>(Yii::app()->controller->id=='tasks'), 'visible'=>Yii::app()->user->checkAccess('viewTasks')),
								array('label'=>Yii::t('projects', 'TitleProjects'), 'itemOptions'=>array('class'=>'mega'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/projects'), 'active'=>(Yii::app()->controller->id=='projects'), 'visible'=>Yii::app()->user->checkAccess('viewProjects'), 'items'=>$projectsItems),
								array('label'=>Yii::t('configuration', 'TitleConfiguration'), 'itemOptions'=>array('class'=>'mega megaright'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/configuration/admin'), 'visible'=>!Yii::app()->user->isGuest, 'active'=>(Yii::app()->controller->id=='configuration')),
							);
						}
						else
						{
							$items = array(
								array('label'=>Yii::t('projects', 'TitleProjects'), 'itemOptions'=>array('class'=>'mega'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/'), 'active'=>(Yii::app()->controller->id=='projects'), 'visible'=>Yii::app()->user->checkAccess('viewProjects'), 'items'=>$projectsItems),
								array('label'=>Yii::t('configuration', 'TitleConfiguration'), 'itemOptions'=>array('class'=>'mega megaright'), 'linkOptions'=>array('class'=>'mega-link'), 'url'=>array('/configuration/admin'), 'visible'=>!Yii::app()->user->isGuest, 'active'=>(Yii::app()->controller->id=='configuration')),
							);
						}
				
						$this->widget('zii.widgets.CMenu',array(
							'htmlOptions'=>array('class'=>'mega-container mega-grey'),
							'items'=>$items,
							'encodeLabel'=>false
						));
					}
				?>
			</div>
			
			<?php
				$this->widget('zii.widgets.CBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
					'homeLink'=>($selected!=null) ? CHtml::link(Yii::app()->user->getState('project_selectedName'), Yii::app()->createUrl('site/index')) : CHtml::link(Yii::t('site','Home'), Yii::app()->createUrl('site/index')),
					'encodeLabel'=>false
				));
			?>

		</div>
		<div id="content" class="container_12">
			<?php echo $content; ?>
		</div>
		<div id="footer">
			<div class="footerContent rounded">
				<div style="float:right;">
					<ul class="icons">
						<li>
							<a href="http://qbit.com.mx/labs/celestic" title="Celestic Homepage" rel="external">
								<?php echo CHtml::image(Yii::app()->request->baseUrl."/favicon.png","Homepage",array('width'=>'28px','height'=>'28px'));?>
							</a>
						</li>
						<li>
							<a href="http://twitter.com/celesticMX" title="Twitter" rel="external">
								<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/sm-twitter.png","Twitter");?>
							</a>
						</li>
						<li>
							<a href="http://www.facebook.com/pages/Celestic/189892971069509" title="Facebook" rel="external">
								<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/sm-facebook.png","Facebook");?>
							</a>
						</li>
						<li>
							<a href="http://qbit.com.mx/labs/celestic/forum" title="Forum" rel="external">
								<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/vanilla.png","Forum");?>
							</a>
						</li>
						<li>
							<a href="http://qbit.com.mx/labs/celestic/blog" title="Blog" rel="external">
								<?php echo CHtml::image(Yii::app()->request->baseUrl."/images/wordpress.png","Blog");?>
							</a>
						</li>
					</ul>
				</div>
				<ul>
					<li class="language"><?php echo CHtml::link('Espa&ntilde;ol-MX', Yii::app()->createUrl(Yii::app()->controller->id."/".Yii::app()->controller->action->id, CMap::mergeArray(Yii::app()->controller->getActionParams(),array('lc'=>'es_mx'))), array('title'=>'Español México')); ?></li>
					<li class="language"><?php echo CHtml::link('Portugues', Yii::app()->createUrl(Yii::app()->controller->id."/".Yii::app()->controller->action->id, CMap::mergeArray(Yii::app()->controller->getActionParams(),array('lc'=>'pt_br'))), array('title'=>'Português')); ?></li>
					<li class="language"><?php echo CHtml::link('English', Yii::app()->createUrl(Yii::app()->controller->id."/".Yii::app()->controller->action->id, CMap::mergeArray(Yii::app()->controller->getActionParams(),array('lc'=>'en_us'))), array('title'=>'English')); ?></li>
					<li class="language"><?php echo CHtml::link('Espa&ntilde;ol-ES', Yii::app()->createUrl(Yii::app()->controller->id."/".Yii::app()->controller->action->id, CMap::mergeArray(Yii::app()->controller->getActionParams(),array('lc'=>'es_es'))), array('title'=>'Español España')); ?></li>
					<li class="language"><?php echo CHtml::link('Deutsch', Yii::app()->createUrl(Yii::app()->controller->id."/".Yii::app()->controller->action->id, CMap::mergeArray(Yii::app()->controller->getActionParams(),array('lc'=>'de_de'))), array('title'=>'Deutsch')); ?></li>
				</ul>
				<div class="mod footerRibbon">
					<?php echo CHtml::encode(Yii::app()->name).' v.'.Yii::app()->params['appVersion']; ?> - 
					Developed by <a href="http://qbit.com.mx" rel="external" title="Qbit Mexhico">Qbit Mexhico</a><br />
					<?php echo Yii::powered(); ?> | Icons by <a href="http://www.fatcow.com/free-icons/" rel="external" title="Fatcow">Fatcow</a>.<br />
					<?php echo CHtml::link('Privacy', Yii::app()->createUrl('site/page', array('view'=>'privacy'))); ?> |  
					<?php echo CHtml::link('Community', 'http://qbit.com.mx/labs/celestic/forum'); ?> | 
					<?php echo CHtml::link('Blog', 'http://qbit.com.mx/labs/celestic/blog'); ?>
				</div>
			</div>
		</div>
	</div>
<?php
Yii::app()->clientScript->registerCoreScript('jquery');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.timeago.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.highlight.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScript('jquery.site','
$(document).ready(function() {
	$(\'input.betterform, textarea.betterform, select.betterform\').focus(function(){
		$(this).parents(\'.row\').addClass("over");
		$(this).addClass("focusField"); 
	}).blur(function(){
		$(this).parents(\'.row\').removeClass("over");
		$(this).removeClass("focusField"); 
	});
	'.Yii::t('site','jquery.timeago.configuration').'
	jQuery("abbr.timeago").timeago();
	$(":input").each(function() {
		if ($(this).attr("tabindex") == 1){
			this.focus();
			return false;
		}
	});
	$("li.mega ul").parent().append("<span></span>");
	$("ul.mega-container li span").click(function() {
		$(this).parent().find("ul").slideDown("fast").show();
		$(this).parent().hover(function() {
		}, function(){
			$(this).parent().find("ul").slideUp("slow");
		});
		}).hover(function() {
			$(this).addClass("subhover");
		}, function() {
			$(this).removeClass("subhover");
	});
	var $ui = $("#menu");
	$ui.find("#searchField").bind("focus click",function(){
		$ui.find("#search").show();
	});
	$ui.bind("mouseleave",function(){
		$ui.find("#search").hide();
	});
	$ui.find("#cbxAll").bind("click",function(){
		$(this).parent().siblings().find(":checkbox").attr("checked",this.checked).attr("disabled",this.checked);
	});
});
');
?>
</body>
</html>