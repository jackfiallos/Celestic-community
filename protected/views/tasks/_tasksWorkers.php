<ul id="gallery">
	<li>
		<?php if($canDelete):?>
		<?php
			if ((bool)Yii::app()->user->IsManager)
				echo CHtml::link('',Yii::app()->createUrl('tasks/RelatedDelete'), array('class'=>'deleteUser','data'=>$data->user_id));
			elseif (($data->user_id == Yii::app()->user->id) && ($data->Tasks['0']->status_id == Status::STATUS_PENDING))
				echo CHtml::link('',Yii::app()->createUrl('tasks/RelatedDelete'), array('class'=>'deleteUser','data'=>$data->user_id));
		?>
		<?php endif;?>
		<?php 
			$this->widget('application.extensions.VGGravatarWidget.VGGravatarWidget', 
				array(
					'email' => CHtml::encode($data->user_email),
					'hashed' => false,
					'default' => 'http://'.$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl.'/images/bg-avatar.png',
					'size' => 62,
					'rating' => 'PG',
					'htmlOptions' => array('class'=>'borderCaption','alt'=>'Gravatar Icon','width'=>'62', 'height'=>'62'),
				)
			);
		?>
		<p><?php echo CHtml::Link(CHtml::encode($data->CompleteName), Yii::app()->createUrl('users/view', array('id'=>$data->user_id)), array('style'=>'font-size:10px')); ?></p>
	</li>
</ul>