<?php
	$this->widget('application.extensions.optiontransferselect.Optiontransferselect', array(
		'leftTitle'=>'Assigned Operations',
		'rightTitle'=>'Unassigned Operations',
		'listName'=>'Assigments',
		'LeftDataList'=>CHtml::listData($selected, 'id', 'name'),
		'RightDataList'=>CHtml::listData($authitems, 'id', 'name'),
		'LeftListHtmlOptions'=>array('size'=>'12','style'=>'width:98%'),
		'RightListHtmlOptions'=>array('size'=>'12','style'=>'width:98%'),
	));
?>