<div class="view">
	<h3><?php echo CHtml::link("#".$data->case_id." - ".CHtml::encode($data->case_name), Yii::app()->controller->createUrl("cases/view", array("id"=>$data->case_id))); ?></h3>
	<?php
	$this->widget('widgets.HasComments',array(
		'countComments' => Logs::getCountComments(Yii::app()->controller->id,$data->case_id),
		'item_id' => $data->case_id,
	));
	?>
	<div class="moduleTextDescription corners">
		<?php echo ECHtml::word_split(CHtml::encode($data->case_description), 20)."..."; ?><br />
	</div>
	
	<div class="subcolumns">
		<div class="c33l">
			<?php if (!empty($data->case_code)): ?>
			<div class="subcolumns">
				<div class="c38l">
					<b><?php echo $data->getAttributeLabel('case_code'); ?>:</b>
				</div>
				<div class="c62r">
					<?php echo CHtml::encode($data->case_code); ?>
				</div>
			</div>
			<?php endif; ?>
			<div class="subcolumns">
				<div class="c38l">
					<b><?php echo $data->getAttributeLabel('case_date'); ?>:</b>
				</div>
				<div class="c62r">
					<?php echo CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->case_date, 'medium', false)); ?><br />
				</div>
			</div>
		</div>
		<div class="c66r">
			<div class="subcolumns">
				<div class="c20l">
					<b><?php echo $data->getAttributeLabel('case_priority'); ?>:</b>
				</div>
				<div class="c80r">
					<span class="priority pr<?php echo CHtml::encode($data->case_priority); ?>">
					<?php
					switch($data->case_priority)
					{
						case Cases::PRIORITY_LOW:
							echo Yii::t('site','lowPriority');
							break;
						case Cases::PRIORITY_MEDIUM:
							echo Yii::t('site','mediumPriority');
							break;
						case Cases::PRIORITY_HIGH:
							echo Yii::t('site','highPriority');
							break;
						default:
							echo Yii::t('site','lowPriority');
							break;
					}
					?>
					</span>
				</div>
			</div>
			<div class="subcolumns">
				<div class="c20l">
					<b><?php echo $data->getAttributeLabel('status_id'); ?>:</b>
				</div>
				<div class="c80r">
					<span class="status st<?php echo CHtml::encode($data->status_id); ?>">
						<?php echo CHtml::encode($data->Status->status_name); ?>
					</span>
				</div>
			</div>
		</div>
	</div>
	
	<div style="text-align:right">
		<b><?php echo CHtml::link(Yii::t('cases', 'ViewDetails'), array('cases/view', 'id'=>$data->case_id), array('class'=>'detailsImg')); ?></b>
	</div>
</div>