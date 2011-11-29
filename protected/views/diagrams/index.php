<?php
$this->breadcrumbs=array(
	'Diagrams',
);

$this->menu=array(
	array('label'=>'Create Diagrams', 'url'=>array('create')),
);
?>

<h1 class="ptitleinfo diagrams">Diagrams</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
