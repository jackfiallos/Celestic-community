<?php echo CHtml::beginForm();?>
<div class="controlPanel">
    <?php
		echo CHtml::ajaxLink(Yii::t('authitems','createRoles'), array('configuration/roleCreate'), array(
			'type'=>'POST',
			'update'=>'#preview',
			'data'=>array(
				'YII_CSRF_TOKEN'=>Yii::app()->request->csrfToken
			),
			'beforeSend' => 'function(){
				$("#preview").addClass("loading").css({width:"100%"});
			}',
			'complete' => 'function(){
				$("#preview").removeClass("loading").css({width:"100%"});
			}',
		));
	?>
</div>
<br />
<?php
$this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'roles-grid',
	'cssFile'=>'css/screen.css',
	'dataProvider'=>$roles,
	'summaryText'=>'',
	'columns'=>array(
		'name',
		'description',
		array(
			'type'=>'raw',
            'value'=>'CHtml::ajaxLink(CHtml::image(Yii::app()->baseUrl."/images/update.png"),Yii::app()->createUrl("configuration/showRoleDetails"), array(
				"type"=>"POST",
				"update"=>"#preview",
				"data"=>array(
					"id"=>$data->id,
					"YII_CSRF_TOKEN"=>Yii::app()->request->csrfToken
				),
				"beforeSend" => "function(){
					$(\"#preview\").addClass(\"loading\").css({width:\"100%\"});
				}",
				"complete" => "function(){
					$(\"#preview\").removeClass(\"loading\").css({width:\"100%\"});
				}",
			))',
			'headerHtmlOptions'=>array(
				'style'=>'width:60px',
			),
			'htmlOptions'=>array(
				'style'=>'text-align:center;',
			),
		),
		array(
			'type'=>'raw',
            'value'=>'CHtml::ajaxLink(CHtml::image(Yii::app()->baseUrl."/images/delete.png"),Yii::app()->createUrl("configuration/roleDelete"), array(
				"type"=>"POST",
				"update"=>"#preview",
				"data"=>array(
					"id"=>$data->id,
					"YII_CSRF_TOKEN"=>Yii::app()->request->csrfToken
				),
				"beforeSend" => "function(){
					$(\"#roles-grid\").addClass(\"loading\");
					if(!confirm(\"Are you sure you want to delete this item?\")) {
						$(\"#roles-grid\").removeClass(\"loading\");
						return false;
					}
				}",
				"complete" => "function(){
					$.fn.yiiGridView.update(\"roles-grid\");
					$(\"#preview\").empty();
				}",
			))',
			'headerHtmlOptions'=>array(
				'style'=>'width:60px;',
			),
			'htmlOptions'=>array(
				'style'=>'text-align:center;',
			),
		),
	),
));
?>
<?php echo CHtml::endForm();?>