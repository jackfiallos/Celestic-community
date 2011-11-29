<?php
$this->breadcrumbs=array(
	'Addresses'=>array('index'),
	$model->address_id,
);

$this->menu=array(
	array('label'=>'List Address', 'url'=>array('index')),
	array('label'=>'Create Address', 'url'=>array('create')),
	array('label'=>'Update Address', 'url'=>array('update', 'id'=>$model->address_id)),
	array('label'=>'Delete Address', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->address_id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1>View Address #<?php echo $model->address_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'address_id',
		'address_text',
		'address_postalCode',
		'address_phone',
		'address_web',
		'city_id',
	),
)); ?>
