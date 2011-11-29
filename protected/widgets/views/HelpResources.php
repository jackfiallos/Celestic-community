<?php
$countTasks = $this->getTaskCounter();
if ($countTasks > 0):
?>
<table>
	<tbody>
<?php
foreach($this->getActivityGroupedByTask() as $task):
$numstatus = isset($task->numstatus) ? $task->numstatus : 0;
if ($numstatus>0):
?>
		<tr>
			<td><?php echo Yii::t('site',$task->Status->status_name); ?></td>
			<td style="width: 60%;">
				<div class="progress progress-<?php echo $task->Status->status_id; ?>">
					<span style="width:<?php echo round(($numstatus/$countTasks)*100)."%";?>;">
						<b>
						<?php
							echo round(($numstatus/$countTasks)*100)."%";
						?>
						</b>
					</span>
				</div>
			</td>
			<td class="ar" style="width: 40px;"><?php echo $numstatus."/".$countTasks; ?></td>
		</tr>
<?php endif; ?>
<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>