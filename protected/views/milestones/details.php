<h1 class="ptitleinfo milestones"><?php echo $model->milestone_title; ?></h1>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name'=>'milestone_description',
			'type'=>'ntext',
			'value'=>Yii::app()->format->html(nl2br(ECHtml::createLinkFromString(CHtml::encode($model->milestone_description)))),
		),
		array(
			'name'=>'milestone_duedate',
			'type'=>'text',
			'value'=>CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->milestone_duedate, "medium", false)),
		),
		array(
			'name'=>'user_id',
			'type'=>'raw',
			'value'=>isset($model->user_id) ? CHtml::link(CHtml::encode($model->Users->completeName),Yii::app()->controller->createUrl("users/view",array("id"=>$model->user_id))) : null,
		),
	),
)); ?>
<br />