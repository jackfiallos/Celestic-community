<?php
$this->breadcrumbs=array(
	'Addresses'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Address', 'url'=>array('index')),
);
?>

<h1>Create Address</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>