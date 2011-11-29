<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->address_id), array('view', 'id'=>$data->address_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_text')); ?>:</b>
	<?php echo CHtml::encode($data->address_text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_postalCode')); ?>:</b>
	<?php echo CHtml::encode($data->address_postalCode); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_phone')); ?>:</b>
	<?php echo CHtml::encode($data->address_phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('address_web')); ?>:</b>
	<?php echo CHtml::encode($data->address_web); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city_id')); ?>:</b>
	<?php echo CHtml::encode($data->city_id); ?>
	<br />


</div>