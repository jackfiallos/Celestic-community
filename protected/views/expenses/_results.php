<div class="view">
	<h3><?php echo CHtml::link(CHtml::encode($data->expense_name." No. ".$data->expense_number), array('expenses/view', 'id'=>$data->expense_id)); ?></h3>
</div>