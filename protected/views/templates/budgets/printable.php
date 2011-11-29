<style>
.even {
background-color: #DFEEFF;
}
.items{
border-spacing: 2pt;
padding:5px;
}
</style>
<div class="portlet x9">
	<div class="portlet-content">
		<div align="right"><?php echo Yii::app()->dateFormatter->format(Yii::app()->locale->getDateFormat('long'),CDateTimeParser::parse(Yii::app()->dateFormatter->formatDateTime($model->budget_date, 'medium', false), 'dd/MM/yyyy')); ?></div>
		<div align="left">
			<?php echo CHtml::encode($model->Projects->Company->company_name); ?><br />
			Ref.: <?php echo CHtml::encode($model->Projects->project_name); ?><br />
		</div>
		<br />
		<b>Alcances y Requerimientos</b><br />
		<?php echo CHtml::tag('div',array(),Yii::app()->format->html(nl2br($model->Projects->project_description))); ?>
		<hr />
		<b>Costos del Proyecto</b><br />
		<?php $this->widget('zii.widgets.grid.CGridView', array(
			'id'=>'budgets-grid',
			'dataProvider'=>$dataProvider,
			'summaryText'=>'',
			'columns'=>array(
				array(
					'type'=>'raw',
					'header'=>Yii::t('budgetsConcepts','FieldDescription'),
					'value'=>'$data->budgetsConcept_description',
					'headerHtmlOptions'=>array(
						'style'=>'text-align:left;font-weight:bold;background-color:#263849;color:#fff;',
					),
				),
				array(
					'type'=>'raw',
					'header'=>Yii::t('budgetsConcepts','FieldAmount'),
					'value'=>'"<div style=\"text-align:right;\">".Yii::app()->numberFormatter->format("$ #,##0.00",$data->budgetsConcept_amount)."</div>"',
					'headerHtmlOptions'=>array(
						'style'=>'text-align:right;font-weight:bold;background-color:#263849;color:#fff;',
					),
				),
			),
		)); ?>
		<table style="text-align:right; font-size:1.2em; font-weight:bold;">
			<tr>
				<td width="350px">TOTAL (<?php echo CHtml::encode($model->Projects->Currency->currency_code); ?>)</td>
				<td width="165px"><?php echo Yii::app()->NumberFormatter->format($model->Projects->Currency->currency_sign." #,##0.00 ", CHtml::encode($model->Cost)); ?></td>
			</tr>
		</table>
		<ul>
			<li>Los totales no incluyen I.V.A.</li>
			<li>Esta cotizaci&oacute;n ser&aacute; v&aacute;lida hasta el d&iacute;a <?php echo Yii::app()->dateFormatter->formatDateTime($model->budget_duedate, 'medium', false); ?></li>
		</ul>
		<br />
		<span style="text-align:center;padding-top:10px;">
			Atentamente,<br />
			<?php echo $model->Users->completeName; ?><br />
			Qbit Mexhico<br />
			<?php echo $model->Users->user_email; ?><br />
			(55)5435768<br />
			http://qbit.com.mx<br />
		</span>
		<hr />
		<div>
			<span style="font-weight:bold;">Notas</span><br />
			<?php echo Yii::app()->format->html(nl2br($model->budget_notes)); ?>
		</div>
	</div>
</div>