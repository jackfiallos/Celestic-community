<?php
$this->breadcrumbs=array(
	Yii::t('tasks', 'TitleTasks'),
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('tasks', 'TitleTasks');
$dataProvider = $model->search();
?>

<div class="portlet">
	<div class="portlet-content">
		<h1 class="ptitleinfo tasks"><?php echo Yii::t('tasks', 'TitleTasks'); ?></h1>
		<div class="portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('tasks', 'CreateTasks'), Yii::app()->controller->createUrl('create'),array('class'=>'button primary')); ?>
		</div>
		<?php if($model->ItemsCount > 0):?>
		<br /><?php echo CHtml::link(Yii::t('tasks', 'AdvancedSearch'),'#',array('class'=>'search-button')); ?>
		<span style="float:right;">
			<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/images/listview.png', ''), Yii::app()->controller->createUrl('index',array('view'=>'list'))); ?>
			<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/images/gridview.png', ''), Yii::app()->controller->createUrl('index',array('view'=>'grid'))); ?>
			<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/images/kanban.png', ''), Yii::app()->controller->createUrl('index',array('view'=>'kanban'))); ?>
		</span>
		<div class="search-form corners">
		<?php
			$this->renderPartial('_search',array(
				'model'=>$model,
				'status'=>$status,
				'types'=>$types,
				'stages'=>$stages,
				'milestones'=>$milestones,
				'cases'=>$cases,
				'users'=>$users,
			));
		?>
		</div>
		<?php
			if (Yii::app()->user->getState('view') == 'grid')
			{
				$this->widget('zii.widgets.grid.CGridView', array(
					'dataProvider'=>$dataProvider,
					'id'=>'tasks-grid',
					'cssFile'=>'css/screen.css',
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						array(
							'name'=>'task_id',
							'type'=>'raw',
							'value' =>'$data->task_id',
						),
						array(
							'name'=>'task_name',
							'type'=>'raw',
							'value' =>'CHtml::link($data->task_name, Yii::app()->createUrl("tasks/view", array("id"=>$data->task_id)))',
						),
						array(
							'name'=>'status_id',
							'type'=>'raw',
							'value'=>'CHtml::tag("span",array("class"=>"text status st".$data->Status->status_id),$data->Status->status_name)',
						),
						array(
							'name'=>'user_id',
							'type'=>'raw',
							'value' =>'CHtml::link($data->UserReported->completeName, Yii::app()->createUrl("users/view", array("id"=>$data->user_id)))',
						),
						array(
							'name'=>'task_startDate',
							'type'=>'raw',
							'value' =>'(strtotime($data->task_startDate)!=null) ? CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->task_startDate, "medium", false)) : null',
						),
					),
				));
			}
			elseif (Yii::app()->user->getState('view') == 'kanban')
			{
				$this->renderPartial('_viewKanban',array(
					'dataProvider'=>$dataProvider,
					'status'=>$status,
				));
			}
			else
			{
				$this->widget('zii.widgets.CListView', array(
					'dataProvider'=>$dataProvider,
					'itemView'=>'_view',
					//'ajaxUpdate'=>false,
					'summaryText'=>Yii::t('site','summaryText'),
					'afterAjaxUpdate'=>'js:function(el){
						$(".viewCollaborators").colorbox({
							"data":{
								"YII_CSRF_TOKEN":"'.Yii::app()->request->csrfToken.'"
							},
							"width":"450px",
							"height":"310px"
						});
					}',
				));
			}
		?>
		<?php else: ?>
		<div class="aboutModule">
			<p class="aboutModuleTitle">
				No tasks has been created, you want to <?php echo CHtml::link(Yii::t('tasks','CreateOneTask'), Yii::app()->controller->createUrl('create')); ?> ?
			</p>
			<div class="aboutModuleInformation shadow corners">
				<h2 class="aboutModuleInformationBoxTitle"><?php echo Yii::t('tasks','AboutTasks'); ?></h2>
				<ul class="aboutModuleInformationList">
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('tasks','TaskInformation_l1'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('tasks','TaskDescription_l1'); ?></span>
					</li>
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('tasks','TaskInformation_l2'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('tasks','TaskDescription_l2'); ?></span>
					</li>
					<li>
						<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/tick.png', '', array('class'=>'aboutModuleInformationIcon')); ?>
						<span class="aboutModuleInformationTitle"><?php echo Yii::t('tasks','TaskInformation_l3'); ?></span>
						<span class="aboutModuleInformationDesc"><?php echo Yii::t('tasks','TaskDescription_l3'); ?></span>
					</li>
				</ul>
			</div>
		</div>
		<?php endif; ?>
	</div>
</div>
<?php
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
    $('.search-form').toggle();
    return false;
});
");

$this->widget('application.extensions.YiiColorBox.Colorbox', array(
	'element'=>'.viewCollaborators',
	'options'=>array(
		'data'=>array(
			'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken,
		),
		'width'=>'450px',
		'height'=>'310px',
	),
));
?>