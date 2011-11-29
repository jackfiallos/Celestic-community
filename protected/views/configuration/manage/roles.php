<?php			 
//CHtml::hiddenField('user_id', );
try
{
	$this->widget('application.extensions.asmselectex.EAsmSelectEx',
		array(
			'name' => 'ManageUsers[asmSelect2]',
			'data' => CHtml::listData($accountRoles, 'name', 'name'),
			'selected' => $userRoles,			
			'htmlOptions' => array(
				'title'=>Yii::t('configuration', 'selectOption'),
			),
			'scriptOptions' => array(
				'addItemTarget'=>'bottom',
				'animate'=>true,
				'highlight'=>true,
				'sortable'=>false,
				'removeLabel'=>'Revoke',
				'highlightAddedLabel'=>'Role Added: ',
				'highlightRemovedLabel'=>'Revoked: ',
			)
		)
	);
} 
catch (Exception $e) 
{
	echo 'Caught Exception: ' .  $e->getMessage() . "<br />\n";
}
?>
