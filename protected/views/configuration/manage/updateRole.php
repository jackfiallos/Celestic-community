<?php echo $this->renderPartial('manage/_form', array('model'=>$model)); ?>
<?php if(Yii::app()->user->hasFlash('updatedSuccess')):?>
    <div class="notification_success">
        <?php echo Yii::app()->user->getFlash('updatedSuccess'); ?>
    </div>
<?php endif; ?>
<?php
Yii::app()->clientScript->registerScript(
	'infoMessage',
   '$(".notification_success").animate({opacity: 1.0}, 3000).fadeOut("slow");',
   CClientScript::POS_READY
);
?>