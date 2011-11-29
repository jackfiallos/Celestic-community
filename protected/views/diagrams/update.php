<?php
$this->breadcrumbs=array(
	'Diagrams'=>array('index'),
	$model->diagram_id=>array('view','id'=>$model->diagram_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Diagrams', 'url'=>array('index')),
	array('label'=>'Create Diagrams', 'url'=>array('create')),
	array('label'=>'View Diagrams', 'url'=>array('view', 'id'=>$model->diagram_id)),
);
?>

<h1 class="ptitleinfo diagrams">Update Diagrams <?php echo $model->diagram_id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>