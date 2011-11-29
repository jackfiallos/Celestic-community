<?php $this->pageTitle = Yii::app()->name." - Installation"; ?>
<?php
$this->breadcrumbs=array(
	ucfirst($this->module->id),
);
?>
<div class="container_12">
	<h1 class="pageTitle">
		Welcome
	</h1>
	<p>Celestic is a open source free collaboration and project management system:</p>
	<ul>
	  <li><strong>Easy to use</strong> - basic set of tools that just work</li>
	  <li><strong>Easy to install</strong> - here you are, just follow the instructions</li>
	  <li><strong>100% free</strong> - free for all, even for commercial use</li>
	  <li><strong>Web based</strong> - after installation the only thing you'll need is a web browser</li>
	</ul>
	<br />
	<h2>Installation steps:</h2>
	<ol>
	  <li>Welcome</li>
	  <li>Enviroment Check</li>
	  <li>Finish</li>
	</ol>
	<p>You should be done in a minute or two.</p>
	<?php echo CHtml::link('Next',Yii::app()->createUrl('install', array('step'=>2))); ?>
</div>