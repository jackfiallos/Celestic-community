<?php
$this->breadcrumbs=array(
	'Diagrams'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Diagrams', 'url'=>array('index')),
);
?>

<h1 class="ptitleinfo diagrams">Create Diagrams</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>