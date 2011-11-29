<?php
$this->breadcrumbs=array(
	'Addresses'=>array('index'),
	$model->address_id=>array('view','id'=>$model->address_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Address', 'url'=>array('index')),
	array('label'=>'Create Address', 'url'=>array('create')),
	array('label'=>'View Address', 'url'=>array('view', 'id'=>$model->address_id)),
);
?>

<h1>Update Address <?php echo $model->address_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>