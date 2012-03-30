<?php $this->pageTitle = Yii::app()->name." - Environment Checks"; ?>
<?php
$this->breadcrumbs=array(
	ucfirst($this->module->id),
	'Environment Checks',
);
?>
<div class="container_12">
	<h1 class="pageTitle">
		Environment Checks
	</h1>
	<?php
	$counter = count($check);
	$showButton = false;
	$i = 1;
	foreach ($check as $itemCheck)
	{
		if ($i<$counter)
			echo $itemCheck."<br />";
		else
			$showButton = $itemCheck;
		$i++;
	}
	?>
	<br />
	<?php echo CHtml::link('Welcome',Yii::app()->createUrl('update', array('step'=>1))); ?>
	<?php
	if ($showButton)
		echo CHtml::link('Finish',Yii::app()->createUrl('update', array('step'=>3,'auth'=>Yii::app()->request->csrfToken)));
	?>
</div>