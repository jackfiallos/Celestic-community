<?php
$invoices = $this->getStatistics();
if (!empty($invoices)) :
?>
<table class="no-style full">
	<tbody>
<?php
foreach($invoices as $items):
?>
	<tr>
		<td><span class="status st<?php echo $items->Status->status_id; ?>"><?php echo Yii::t('site',$items->Status->status_name); ?></span></td>
		<td class="ar"><?php echo $items->items; ?></td>
		<td class="ar"><?php echo Yii::app()->NumberFormatter->formatCurrency(CHtml::encode($items->amount), $items->Projects->Currency->currency_code)." ".$items->Projects->Currency->currency_code; ?></td>
	</tr>
<?php endforeach; ?>
	</tbody>
</table>
<?php endif; ?>