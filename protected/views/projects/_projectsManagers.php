<ul id="gallery">
	<li>
		<?php
			if (((bool)Yii::app()->user->IsManager) && (Yii::app()->user->id != $data->user_id))
				echo CHtml::link('',Yii::app()->createUrl('projects/RemoveManager'), array('class'=>'deleteUser','data'=>$data->user_id));
		?>
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