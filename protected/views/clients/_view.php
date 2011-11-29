<div class="listitem">
	<div class="avatar">
		<?php 
			$this->widget('application.extensions.VGGravatarWidget.VGGravatarWidget', 
				array(
					'email' => CHtml::encode($data->Users->user_email),
					'hashed' => false,
					'default' => 'http://'.$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl.'/images/bg-avatar.png',
					'size' => 60,
					'redirect' => Yii::app()->createUrl('clients/view', array('id'=>$data->client_id)),
					'rating' => 'PG',
					'htmlOptions' => array('class'=>'borderCaption','alt'=>'Gravatar Icon' ),
				)
			);
		?>
	</div>
	<div class="people-detail">
		<h5><?php echo CHtml::encode($data->Users->user_name)." ".CHtml::encode($data->Users->user_lastname); ?></h5>
		<?php echo CHtml::mailto(CHtml::encode($data->Users->user_email), CHtml::encode($data->Users->user_email)); ?><br />
		<?php echo CHtml::encode($data->getAttributeLabel('Users.user_active')).": "; ?>
		<?php echo ($data->Users->user_active==1) ? Yii::t('site', 'yes') : Yii::t('site', 'no'); ?><br />
		<div style="width:100%; text-align:right;"><?php echo CHtml::link(Yii::t('clients','ViewDetails'), array('view', 'id'=>$data->client_id)); ?></div>
	</div>
</div>