<?php
$this->breadcrumbs=array(
	'resultados',
);
$this->pageTitle = CHtml::encode(Yii::t('site','searchResults'));
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo projects"><?php echo CHtml::encode(Yii::t('site','searchResults')); ?></h1>
		<?php if(Yii::app()->user->hasFlash('GlobalSearchForm')):?>
		    <div class="notification_warning">
		        <?php echo Yii::app()->user->getFlash('GlobalSearchForm'); ?>
		    </div>
		<?php endif; ?>
		<?php	
		foreach($dataProvider as $dt)
		{
			if ($dt->totalItemCount > 0)
			{
				echo '<hr style="border:2px solid #E59900; display:block; position: relative; float:right; width:100%;" /><span style="font-weight:bold; font-size:16px;">'.Yii::t('modules',strtolower($dt->modelClass)).'</span>';
				$this->widget('zii.widgets.CListView', array(
					'dataProvider'=>$dt,
					'itemView'=>'../'.strtolower($dt->modelClass).'/_results',
					'summaryText'=>Yii::t('site','summaryText'),
				));
			}
		}
		?>
	</div>
</div>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.highlight.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScript('jquery.highlight','
	$(".items").highlight("'.$term.'");
');
?>
