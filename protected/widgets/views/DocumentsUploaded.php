<?php foreach($this->getDocuments() as $document): ?>
	<div class="comm_date">
		<span class="data" style="padding-top:10px;">
			<?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/images/icons/documents.png'),Yii::app()->controller->createUrl("documents/download",array("id"=>$document->document_id)), array('alt'=>'Download')); ?>
		</span>
	</div>
	<div class="logactivity">
	<?php
		echo CHtml::link(ECHtml::word_split(CHtml::encode($document->Projects->project_name),3), Yii::app()->createUrl("projects/view",array("id"=>$document->Projects->project_id)))."<br />";
		echo CHtml::link(CHtml::encode($document->document_name." - Revision #".$document->document_revision), Yii::app()->createUrl("documents/view",array("id"=>$document->document_id)));
		//echo "&nbsp;" . CHtml::link("Download",Yii::app()->controller->createUrl("documents/download",array("id"=>$document->document_id))).CHtml::image(Yii::app()->request->baseUrl.'/images/icons/download-12.png');
	?>
	</div>
	<br />
<?php endforeach; ?>