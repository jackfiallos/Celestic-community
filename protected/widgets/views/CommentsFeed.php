<?php
$activity = $this->getActivity();
foreach($activity as $comment): ?>
	<div style="border-bottom:1px solid #ccc; padding:4px;">
		<div style="float:left;width:50px;display: inline;">
			<?php 
				$this->widget('application.extensions.VGGravatarWidget.VGGravatarWidget', 
					array(
						'email' => CHtml::encode(Yii::app()->user->getState('user_email')),
						'hashed' => false,
						'default' => 'http://'.$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl.'/images/bg-avatar.png',
						'size' => 32,
						'rating' => 'PG',
						'htmlOptions' => array('class'=>'borderCaption','alt'=>'Gravatar Icon' ),
					)
				);
			?>
		</div>
		<div>
			<?php echo CHtml::link(ECHtml::word_split($comment->comment_text,$this->lineLenght)."...", Yii::app()->createUrl($comment->Module->module_name."/view",array("id"=>$comment->comment_resourceid,'#'=>'comment-'.$comment->comment_id))); ?>
			<div style="display:block; font-size:10px">
				<?php echo ECHtml::word_split($this->findModuleTitle($comment->Module->module_className, $comment->Module->module_title, $comment->comment_resourceid),5)."... "; ?>
				<span class="bac" style="font-size:10px;background-color:#DFDFDF"><?php echo "<abbr class=\"timeago\" title=\"".CHtml::encode($comment->comment_date)."\">".CHtml::encode(Yii::app()->dateFormatter->format('dd.MM.yyyy', $comment->comment_date))."</abbr>"; ?></span>
			</div>
		</div>
	</div>
<?php endforeach; ?>