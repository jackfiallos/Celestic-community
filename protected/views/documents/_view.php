<div class="view">
	<h3><?php echo CHtml::link("#".$data->document_id." - ".CHtml::encode($data->document_name), array('documents/view', 'id'=>$data->document_id)); ?></h3>
	<?php
	$countComments = Logs::getCountComments(Yii::app()->controller->id,$data->document_id);
	if ($countComments > 0) :
	?>
	<span class="jewelCount">
		<span id="jewelRequestCount">
			<?php echo CHtml::link($countComments." ".Yii::t('site','comments'),array('view', 'id'=>$data->document_id,'#'=>'comments'),array('title'=>Yii::t('site','comments'))); ?>
		</span>
	</span>
	<?php endif; ?>
	<div class="moduleTextDescription corners">
		<?php echo ECHtml::word_split(CHtml::encode($data->document_description),20)."..."; ?>
	</div>
	<div style="text-align:right">
		<b><?php echo CHtml::link(Yii::t('documents','ViewDetails'), array('documents/view', 'id'=>$data->document_id), array('class'=>'detailsImg')); ?></b>
	</div>
</div>