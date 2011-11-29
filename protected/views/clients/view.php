<?php
$this->breadcrumbs=array(
	Yii::t('configuration', 'TitleConfiguration')=>array('configuration/admin'),
	Yii::t('clients', 'TitleClients')=>array('index'),
	$model_client->Users->CompleteName,
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('clients', 'TitleClients');
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo clients"><?php echo $model_client->Users->CompleteName; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php if ($model_client->user_id == Yii::app()->user->id) : ?>
				<?php echo CHtml::link(Yii::t('users', 'ManageNotifications'), Yii::app()->controller->createUrl('notifications'), array('class'=>'button primary')); ?>
			<?php endif; ?>
			<?php echo CHtml::link(Yii::t('clients', 'ListClients'), Yii::app()->controller->createUrl('index'), array('class'=>'button')); ?>
			<?php if(Yii::app()->user->IsAdministrator):?>
				<?php echo CHtml::link(Yii::t('clients', 'CreateClient'), Yii::app()->controller->createUrl('create'), array('class'=>'button')); ?>
			<?php endif;?>
			<?php if((Yii::app()->user->IsAdministrator) || ($model_client->client_id == Yii::app()->user->id)):?>
				<?php echo CHtml::link(Yii::t('clients', 'UpdateClient'), Yii::app()->controller->createUrl('update',array('id'=>$model_client->client_id)), array('class'=>'button')); ?>
			<?php endif;?>
		</div>
		<div class="subcolumns">
			<div class="c80l">
				<?php $this->widget('zii.widgets.CDetailView', array(
					'data'=>$model_user,
					'attributes'=>array(
						array(
							'name'=>'Users.user_email',
							'type'=>'email',
							'value'=>$model_client->Users->user_email,
						),
						array(
							'name'=>'Users.user_active',
							'type'=>'raw',
							'value'=>($model_client->Users->user_active) ? Yii::t('site','yes') : Yii::t('site','no'),
						),
						array(
							'name'=>'Users.Address.address_text',
							'type'=>'raw',
							'value'=> (isset($model_client->Users->Address->address_text)) ? CHtml::encode($model_client->Users->Address->address_text) : null,
							'visible' => (isset($model_client->Users->Address->address_text)) && (!empty($model_client->Users->Address->address_text)),
						),
						array(
							'name'=>'Users.Address.address_postalCode',
							'type'=>'raw',
							'value'=> (isset($model_client->Users->Address->address_postalCode)) ? CHtml::encode($model_client->Users->Address->address_postalCode) : null,
							'visible' => (isset($model_client->Users->Address->address_postalCode)) && (!empty($model_client->Users->Address->address_postalCode)),
						),
						array(
							'name'=>'Users.Address.address_phone',
							'type'=>'raw',
							'value'=> (isset($model_client->Users->Address->address_phone)) ? CHtml::encode($model_client->Users->Address->address_phone) : null,
							'visible' => (isset($model_client->Users->Address->address_phone)) && (!empty($model_client->Users->Address->address_phone)),
						),
						array(
							'name'=>'Users.Address.address_web',
							'type'=>'html',
							'value'=> (isset($model_client->Users->Address->address_web)) ? CHtml::link($model_client->Users->Address->address_web, $model_client->Users->Address->address_web) : null,
							'visible' => (isset($model_client->Users->Address->address_web)) && (!empty($model_client->Users->Address->address_web)),
						),
						array(
							'name'=>'Users.Address.City.city_id',
							'type'=>'raw',
							'value'=> (isset($model_client->Users->Address->city_id)) ? CHtml::encode($model_client->Users->Address->City->city_name) : null,
							'visible' => (isset($model_client->Users->Address->city_id)) && (!empty($model_client->Users->Address->city_id)),
						),
						array(
							'name'=>'Users.Address.City.Country.country_id',
							'type'=>'raw',
							'value'=> (isset($model_client->Users->Address->city_id)) ? CHtml::encode($model_client->Users->Address->City->Country->country_name) : null,
							'visible' => (isset($model_client->Users->Address->city_id)) && (!empty($model_client->Users->Address->city_id)),
						),
					),
				)); ?>
			</div>
			<div class="c20r">
				<div class="wraptocenter">
					<span></span>
					<?php 
						$this->widget('application.extensions.VGGravatarWidget.VGGravatarWidget', 
							array(
								'email' => CHtml::encode($model_client->Users->user_email),
									'hashed' => false,
									'default' => 'http://'.$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl.'/images/bg-avatar.png',
									'size' => 100,
									'rating' => 'PG',
									'htmlOptions' => array('class'=>'borderCaption','alt'=>'Gravatar Icon'),
							)
						);
					?>
				</div>
			</div>
		</div>
		<br />
		<?php if (Yii::app()->user->id == $model_client->user_id) : ?>
		<div class="portlet x12">
			<div class="portlet-content">
				<h1 class="ptitle users"><?php echo Yii::t('users', 'ProjectList'); ?></h1>
			</div>
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'projects-grid',
				'cssFile'=>'css/screen.css',
				'dataProvider'=>$Projects,
				'summaryText'=>Yii::t('site','summaryText'),
				'emptyText'=>Yii::t('site','emptyText'),
				'columns'=>array(
					array(
						'name'=>'project_name',
						'type'=>'raw',
						'value' =>'CHtml::link($data->project_name,Yii::app()->controller->createUrl("projects/view",array("id"=>$data->project_id)))',
					),
					array(
						'name'=>'project_startDate',
						'type'=>'text',
						'value'=>'CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->project_startDate, "medium", false))',
					),
					array(
						'name'=>'project_endDate',
						'type'=>'text',
						'value'=>'(strtotime($data->project_endDate)!=null) ? CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->project_endDate, "medium", false)) : "N/A"',
					),
					array(
						'name'=>'project_active',
						'type'=>'boolean',
						'value' =>'$data->project_active==1',
					),
				),
			)); ?>
		</div>
		<br />
		<div class="portlet x12">
			<div class="portlet-content">
				<h1 class="ptitle companies"><?php echo Yii::t('users', 'CompanyList'); ?></h1>
			</div>
			<?php
			$this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'companies-grid',
				'cssFile'=>'css/screen.css',
				'dataProvider'=>$Companies,
				'summaryText'=>Yii::t('site','summaryText'),
				'emptyText'=>Yii::t('site','emptyText'),
				'columns'=>array(
					array(
							'name'=>'company_name',
							'type'=>'raw',
							'value' =>'CHtml::link($data->company_name,Yii::app()->controller->createUrl("companies/view",array("id"=>$data->company_id)))',
					),
					array(
							'name'=>'company_url',
							'type'=>'raw',
							'value' =>'CHtml::link(CHtml::encode($data->company_url),$data->company_url)',
					),
					'company_uniqueId',
				),
			));
			?>
		</div>
		<br />
		<?php endif; ?>
		<div class="portlet x12">
			<div class="portlet-content">
				<h1 class="ptitle tasks"><?php echo Yii::t('users', 'TasksToResolve'); ?></h1>
			</div>
			<?php $this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'tasks-grid',
				'cssFile'=>'css/screen.css',
				'dataProvider'=>$Tasks,
				'summaryText'=>Yii::t('site','summaryText'),
				'emptyText'=>Yii::t('site','emptyText'),
				'columns'=>array(
					array(
						'name'=>'task_name',
						'type'=>'raw',
						'value'=>'CHtml::link(CHtml::encode($data->task_name),Yii::app()->createUrl("tasks/view", array("id"=>$data->task_id)))',
					),
					array(
						'name'=>'task_startDate',
						'type'=>'text',
						'value'=>'CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->task_startDate, "medium", false))',
					),
					array(
						'name'=>'task_endDate',
						'type'=>'text',
						'value'=>'(strtotime($data->task_endDate)!=null) ? CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->task_endDate, "medium", false)) : "N/A"',
					),
					array(
						'name'=>'taskTypes_id',
						'type'=>'text',
						'value'=>'CHtml::encode($data->Types->taskTypes_name)',
					),
				),
			));
			?>
		</div>
	</div>
</div>