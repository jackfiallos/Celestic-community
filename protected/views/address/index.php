<?php
$this->breadcrumbs=array(
	'Addresses',
);

$this->menu=array(
	array('label'=>'Create Address', 'url'=>array('create')),
);
?>

<h1>Addresses</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
