<?php
$this->breadcrumbs=array(
	'Diagrams'=>array('index'),
	$model->diagram_id,
);

$this->menu=array(
	array('label'=>'List Diagrams', 'url'=>array('index')),
	array('label'=>'Create Diagrams', 'url'=>array('create')),
	array('label'=>'Update Diagrams', 'url'=>array('update', 'id'=>$model->diagram_id)),
	array('label'=>'Delete Diagrams', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->diagram_id),'confirm'=>'Are you sure you want to delete this item?')),
);
?>

<h1 class="ptitleinfo diagrams">View Diagrams #<?php echo $model->diagram_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'diagram_id',
		'diagram_name',
		'project_id',
		'status_id',
	),
)); ?>
