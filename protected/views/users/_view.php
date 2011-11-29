<div class="listitem corners">
	<div class="avatar">
		<?php 
			$this->widget('application.extensions.VGGravatarWidget.VGGravatarWidget', 
				array(
					'email' => CHtml::encode($data->user_email),
					'hashed' => false,
					'default' => 'http://'.$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl.'/images/bg-avatar.png',
					'size' => 60,
					'redirect' => Yii::app()->createUrl('users/view', array('id'=>$data->user_id)), 
					'rating' => 'PG',
					'htmlOptions' => array('class'=>'borderCaption','alt'=>'Gravatar Icon' ),
				)
			);
		?>
	</div>
	<div class="people-detail">
		<h5><?php echo CHtml::encode($data->CompleteName); ?></h5>
		<?php echo CHtml::mailto(CHtml::encode($data->user_email), CHtml::encode($data->user_email)); ?><br />
		<?php echo CHtml::encode($data->getAttributeLabel('user_admin')).": "; ?>
		<?php echo ($data->user_admin==1) ? Yii::t('site', 'yes') : Yii::t('site', 'no'); ?><br />
		<?php echo CHtml::encode($data->getAttributeLabel('user_active')).": "; ?>
		<?php echo ($data->user_active==1) ? Yii::t('site', 'yes') : Yii::t('site', 'no'); ?><br />
		<div style="width:100%; text-align:right;"><?php echo CHtml::link(Yii::t('users','ViewDetails'), array('view', 'id'=>$data->user_id)); ?></div>
	</div>
</div>