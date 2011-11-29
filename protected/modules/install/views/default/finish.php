<?php $this->pageTitle = Yii::app()->name." - Success Installation"; ?>
<?php
$this->breadcrumbs=array(
	ucfirst($this->module->id),
	'Success Installation',
);
?>
<div class="container_12">
	<h1 class="pageTitle">Finish Him :D</h1>
	<p>Installation process:</p>
	    &#10004; Database connection has been established successfully<br />
	    &#10004; The tables were created successfully<br />
	    &#10004; Initial data were imported into database successfully<br />
	<p>
	<h1>Success!</h1>
	<p>
		You have installed Celestic <strong>successfully</strong>.<br />
		Go to <?php echo CHtml::link(Yii::app()->createAbsoluteUrl(''),Yii::app()->createAbsoluteUrl(''));?> and start managing your projects (Celestic will ask you to create administrator user and provide some details about your company first).
	</p>
	<p>
		<strong>Visit <a href="http://qbit.com.mx/labs/celestic">http://qbit.com.mx/labs/celestic</a> for news, updates and support</strong>.<br />
		Don't forget to visit <a href="http://qbit.com.mx/labs/celestic/forum">Celestic forum</a> and join to contribute to the project. Thank you!
	</p>
</div>