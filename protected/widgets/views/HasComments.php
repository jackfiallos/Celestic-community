<?php
if ($countComments > 0) :
?>
<span class="jewelCount">
	<span class="jewelRequestCount">
		<?php echo CHtml::link($countComments." ".(($countComments==1) ? substr(Yii::t('site','comments'),0,-1) : Yii::t('site','comments')),array('view', 'id'=>$item_id,'#'=>'comments'), array('class'=>'commentsview','title'=>Yii::t('site','comments'))); ?>
	</span>
</span>
<?php endif; ?>