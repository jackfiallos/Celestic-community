<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('admin'),
	Yii::t('configuration', 'AccountConfiguration'),
);

$this->pageTitle = Yii::app()->name." - ".Yii::t('configuration', 'AccountConfiguration');
?>

<div class="portlet grid_12">
	<div class="portlet-content">
		<h1 class="ptitleinfo configuration"><?php echo Yii::t('configuration', 'UpdateAccount'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('configuration', 'UpdateAccount'), Yii::app()->controller->createUrl('accountUpdate'), array('class'=>'button primary')); ?>
		</div>
		<div class="subcolumns">
			<div class="c66l">
				<?php $this->widget('zii.widgets.CDetailView', array(
					'data'=>$model,
					'attributes'=>array(
						'account_name',
						array(
							'name'=>'account_companyName',
							'type'=>'raw',
							'value'=>$model->account_companyName,
						),
						array(
							'name'=>'account_uniqueId',
							'type'=>'raw',
							'value'=>$model->account_uniqueId,
						),
						array(
							'name'=>'Address.address_text',
							'type'=>'raw',
							'value'=>($model->address_id != null) ? CHtml::encode($model->Address->address_text) : null,
							'visible' => (isset($model->Address->address_text)) && (!empty($model->Address->address_text)),
						),
						array(
							'name'=>'Address.address_postalCode',
							'type'=>'raw',
							'value'=>($model->address_id != null) ? CHtml::encode($model->Address->address_postalCode) : null,
							'visible' => (isset($model->Address->address_postalCode)) && (!empty($model->Address->address_postalCode)),
						),
						array(
							'name'=>'Address.address_phone',
							'type'=>'raw',
							'value'=>($model->address_id != null) ? CHtml::encode($model->Address->address_phone) : null,
							'visible' => (isset($model->Address->address_phone)) && (!empty($model->Address->address_phone)),
						),
						array(
							'name'=>'Address.address_web',
							'type'=>'html',
							'value'=>($model->address_id != null) ? CHtml::link($model->Address->address_web, $model->Address->address_web) : null,
							'visible' => (isset($model->Address->address_web)) && (!empty($model->Address->address_web)),
						),
						array(
							'name'=>'Address.City.city_id',
							'type'=>'raw',
							'value'=>($model->address_id != null) ? CHtml::encode($model->Address->City->city_name) : null,
							'visible' => (isset($model->Address->city_id)) && (!empty($model->Address->city_id)),
						),
						array(
							'name'=>'Address.City.Country.country_id',
							'type'=>'raw',
							'value'=>($model->address_id != null) ? CHtml::encode($model->Address->City->Country->country_name) : null,
							'visible' => (isset($model->Address->city_id)) && (!empty($model->Address->city_id)),
						),
					),
				)); ?>
			</div>
			<div class="c33r">
				<div class="wraptocenter">
					<span></span>
					<?php
						if (!empty($model->account_logo))
							echo CHtml::image(Yii::app()->request->baseUrl."/".$model->account_logo, 'Company Logo', array('class'=>'borderCaption','width'=>'180px'));
						else
							echo CHtml::image('http://'.$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl.'/images/celestic.png', 'Company Logo', array('class'=>'borderCaption'))
					?>
				</div>
			</div>
		</div>
	</div>
</div>