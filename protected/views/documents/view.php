<?php
$this->breadcrumbs=array(
	Yii::t('documents', 'TitleDocuments')=>array('index'),
	$model->document_name,
);
$this->pageTitle = Yii::app()->name." - ".Yii::t('documents', 'TitleDocuments')." :: ".CHtml::encode($model->document_name);
?>

<div class="portlet x9">
	<div class="portlet-content">
		<h1 class="ptitleinfo documents"><?php echo $model->document_name; ?></h1>
		<div class="button-group portlet-tab-nav">
			<?php echo CHtml::link(Yii::t('documents', 'ListDocuments'), Yii::app()->controller->createUrl('index'), array('class'=>'button primary')); ?>
			<?php echo CHtml::link(Yii::t('documents', 'CreateDocuments'), Yii::app()->controller->createUrl('create'), array('class'=>'button')); ?>
			<?php echo CHtml::link(Yii::t('documents', 'UpdateDocuments'), Yii::app()->controller->createUrl('update',array('id'=>$model->document_id)), array('class'=>'button')); ?>
		</div>
		<?php $this->widget('zii.widgets.CDetailView', array(
			'data'=>$model,
			'attributes'=>array(
				'document_name',
				array(
					'name'=>'document_description',
					'type'=>'raw',
					'value'=>Yii::app()->format->html(nl2br(ECHtml::createLinkFromString(CHtml::encode($model->document_description)))),
				),
				array(
					'name'=>'document_uploadDate',
					'type'=>'raw',
					'value'=>CHtml::encode(Yii::app()->dateFormatter->formatDateTime($model->document_uploadDate, "medium", false)),
				),
				'document_revision',
				array(
					'name'=>'user_id',
					'type'=>'raw',
					'value'=>CHtml::encode($model->User->CompleteName),
				),
			),
		)); ?>
		<br /><hr />
		<div class="portlet x12">
			<div class="portlet-content">
				<h1 class="ptitle"><?php echo Yii::t('documents','lastRevision'); ?></h1>
				<?php $this->widget('zii.widgets.grid.CGridView', array(
					'id'=>'documents-grid',
					'cssFile'=>'Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl."/css/screen.css")',
					'dataProvider'=>$dataProvider,
					'summaryText'=>Yii::t('site','summaryText'),
					'emptyText'=>Yii::t('site','emptyText'),
					'columns'=>array(
						//'document_name',
						array(
							'name'=>'document_revision',
							'type'=>'raw',
							'htmlOptions'=>array(
								'width'=>'5%',
								'style'=>'text-align:center',
							),
							'value'=>'$data->document_revision',
						),
						'document_description',
						array(
							'name'=>'document_type',
							'type'=>'raw',
							'htmlOptions'=>array(
								'width'=>'10%',
								'style'=>'text-align:center',
							),
							'value'=>'CHtml::image(Yii::app()->request->baseUrl."/images/filetypes/file_extension_".end(explode("/", $data->document_type)).".png")',
						),
						array(
							'name'=>'document_uploadDate',
							'type'=>'raw',
							'htmlOptions'=>array(
								'width'=>'10%',
								'style'=>'text-align:center',
							),
							'value'=>'CHtml::encode(Yii::app()->dateFormatter->formatDateTime($data->document_uploadDate, "medium", false))',
						),
						array(
							'name'=>Yii::t('documents','download'),
							'type'=>'raw',
							'htmlOptions'=>array(
								'width'=>'15%',
								'style'=>'text-align:center',
							),
							'value'=>'CHtml::link(Yii::t("documents","downloadFile"),Yii::app()->controller->createUrl("documents/download",array("id"=>$data->document_id)), array("target"=>"_blank","class"=>(in_array($data->document_type,array("image/png","image/jpeg","image/gif","image/bmp")))?"lnkdownloadimage":"lnkdownloadfile"))',
						),
					),
				)); ?>
				<?php		
					$this->widget('application.extensions.YiiColorBox.Colorbox', array(
						'element'=>'.lnkdownloadimage',
						'options'=>array(
							'width'=>'800px',
							'height'=>'450px',
						),
					));
				?>
			</div>
		</div>
		<br />
		<div class="result">
		<?php $this->widget('widgets.ListComments',array(
			'resourceid'=>$model->document_id,
			'moduleid'=>Yii::app()->controller->id,
		)); ?>
		</div>
	</div>
</div>