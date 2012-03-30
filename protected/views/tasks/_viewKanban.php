<div class="subcolumns">
	<?php
	$count = 0; 
	$containers = "";
	foreach($status as $state): ?>
	<?php if($state->status_id != 2):?>
	<?php $containers .= "\n$('#mcs3_container".$state->status_id."').mCustomScrollbar('vertical',900,'easeOutCirc',1.05,'auto','yes','no',0);"; ?>
	<div class="grid_2">
		<div class="event">
			<div class="eventHeading corners"><?php echo $state->status_name;?></div>
			<div class="mcs3_container" id="mcs3_container<?php echo $state->status_id; ?>">
				<div class="customScrollBox">
					<div class="container">
    					<div class="content">
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
										<div class="cdescription">
											<div class="body corners">
												<?php echo $data->task_description; ?>
											</div>
										</div>
									</li>
								<?php endwhile; ?>
							</ul>
						</div>
					</div>
					<div class="dragger_container">
    					<div class="dragger"></div>
    				</div>
				</div>
			</div>
		</div>
	</div>
	<?php endif;?>
	<?php endforeach;?>
</div>

<?php
Yii::app()->clientScript->registerCoreScript('jquery.ui');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/redmond/jquery-ui.css'); 
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/kanban.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/jquery.mCustomScrollbar.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.easing.1.3.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.mousewheel.min.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.mCustomScrollbar.js');
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
		}
	});
	$('.eventList').disableSelection();*/
	
	".$containers."
	
	/*$('li.status').click(function(){
		$(this).children('.cdescription').slideToggle('fast');
	});*/
});
");
?>