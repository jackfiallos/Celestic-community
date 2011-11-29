<div class="subcolumns">
	<?php
	$count = 0; 
	foreach($status as $state): ?>
	<div class="grid_2">
		<div class="event">
			<div class="eventHeading corners"><?php echo $state->status_name;?></div>
			<ul class="eventList connectedSortable" change="<?php echo $state->status_id ?>">
			  <?php
			  $cambio = false;
			  while($cambio==false && $count < count($dataProvider->data) && $state->status_id == $dataProvider->data[$count]->status_id):
			  	//echo $data->Users[0]->user_id;
			  	$data = $dataProvider->data[$count];
			  	$count = $count+1;?>
				    <li class="milestone status st<?php echo $data->status_id;?> corners">
						<span title="Milestone" class="icon"></span>
						<?php echo CHtml::link($data->task_name, Yii::app()->createUrl("tasks/view", array("id"=>$data->task_id)));?>
						<div class="content">
							<div class="body corners">
								<?php echo $data->task_description; ?>
							</div>
						</div>
					</li>
				<?php endwhile; ?>
			</ul>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/redmond/jquery-ui.css'); 
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/kanban.css');
Yii::app()->clientScript->registerScript('kanban', "
$(function() {
	/*$('ul.eventList').sortable({
		connectWith: '.connectedSortable',
		placeholder: 'ui-state-highlight stateplaceholder corners',
		cancel: '.st2',
		items: 'li:not(.ui-state-disabled)',
		stop: function( event, ui ) {
			var item = $(this);
			ui.item.removeClass().addClass('milestone status corners').addClass('st'+ui.item.parent().attr('change'));
		},
	});
	$('.eventList').disableSelection();*/
	$('li.status').click(function(){
		$(this).children('.content').slideToggle('fast');
	});
});
");
?>