<?php $this->pageTitle = Yii::app()->name." - Update"; ?>
<?php
$this->breadcrumbs=array(
	ucfirst($this->module->id),
);
?>
<div class="container_12">
	<h1 class="pageTitle">
		Welcome Again
	</h1>
	<p>This update it's for 0.3.x version to 0.0.4, please read carefully, because has a lot of improvements:</p>
	<ul>
	  <li>Kanban design concept with scrollbar content</li>
	  <li>Added portuguese, german and spanish from spain translations</li>
	  <li>Added portlet comments for selected project</li>
	  <li>New design on tasks view</li>
	  <li>Updated hightchart library</li>
	  <li>Projects bar chart fixed</li>
	  <li>Added Comments Widget (Database updated requierd)</li>
	  <li>Effort Distribution fixed for % values</li>
	  <li>Cancelled tasks hidden in kanban view</li>
	  <li>Users and clients pagination error was fixed</li>
	  <li>Updating password fixed</li>
	  <li>And many, many bugs were fixed</li>
	</ul>
	<br />
	<p>Please, remember to backup your database.</p>
	<p>You should be done in a minute or two.</p>
	<?php echo CHtml::link('Next',Yii::app()->createUrl('update', array('step'=>2))); ?>
</div>