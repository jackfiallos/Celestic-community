<?php foreach($this->getActivity($this->moduleid) as $log): ?>
	<?php
		$color = "";
		if (strtotime($log->log_date) > strtotime(date("Y-m-d")))
		{
			$color = "#C7EFB3";
			echo '<div class="corners" style="text-transform:uppercase;background-color:'.$color.';font-weight:bold;color:#444444;padding:0px 15px;">';		
			if (empty($titleTimeToday)){
				$titleTimeToday = Yii::t('logs','today')."<br />";
				echo $titleTimeToday;
			}
			
		}
		elseif ((strtotime($log->log_date) < strtotime(date("Y-m-d"))) && (strtotime($log->log_date) > strtotime(date("Y-m-d", strtotime("-1 day")))))
		{
			$color = "#B8DBF6";
			echo '<div class="corners" style="text-transform:uppercase;background-color:'.$color.';font-weight:bold;color:#444444;padding:0px 15px;">';
			if (empty($titleTimeYesterday)){
				$titleTimeYesterday = Yii::t('logs','yesterday')."<br />";
				echo $titleTimeYesterday;
			}
		}
		else
		{
			$color = "#FFE59F";
			echo '<div class="corners" style="text-transform:uppercase;background-color:'.$color.';font-weight:bold;color:#444444;padding:0px 15px;">';
			if (empty($titleOtherDays)){
				$titleOtherDays = Yii::t('logs','othersdays')."<br />";
				echo $titleOtherDays;
			}
		}
	?>
	</div>
	<div class="comm_date" style="margin-top:10px; <?php echo "background-color:".$color.";"; ?>">
		<span class="data" style="padding-top:10px;">
			<?php echo CHtml::image(Yii::app()->request->baseUrl.'/images/icons/'.CHtml::encode($log->Module->module_name).'.png'); ?>
		</span>
	</div>
	<div class="logactivity">
		<?php
			$output = "";
			// Si no es comentario el enlace
			if ($log->log_commentid == 0)
			{
				// Si no es del tipo controllerConcepts
				if (strpos($log->Module->module_name, "concepts") === false)
					$output .= CHtml::link(Yii::t('logs',$log->log_activity), Yii::app()->controller->createUrl($log->Module->module_name."/view",array("id"=>$log->log_resourceid))) . " ".Yii::t('site','by')." ";
				else
					$output .= CHtml::link(Yii::t('logs',$log->log_activity), Yii::app()->controller->createUrl($log->Module->module_name."/index",array("owner"=>$log->log_resourceid))) . " ".Yii::t('site','by')." ";
			}
			else
				$output .= CHtml::link(Yii::t('logs',$log->log_activity), Yii::app()->controller->createUrl($log->Module->module_name."/view",array("id"=>$log->log_resourceid,"#"=>"comment-".$log->log_commentid))) . " ".Yii::t('site','by')." ";
			
			$output .= CHtml::link(CHtml::encode($log->User->completeName), Yii::app()->controller->createUrl("users/view",array("id"=>$log->User->user_id)))."<br />";
			
			if (strpos($log->Module->module_name, "concepts") === false)
				$output .= " ".Yii::t('logs','in')." ".CHtml::link(ECHtml::word_split($log->getTitleFromLogItem($log->log_resourceid, $log->Module->module_className, $log->Module->module_title),8), Yii::app()->controller->createUrl($log->Module->module_name."/view",array("id"=>$log->log_resourceid)))."&nbsp;<br />";
			else
				$output .= " ".Yii::t('logs','in')." ".CHtml::link(ECHtml::word_split($log->getTitleFromLogItem($log->log_resourceid, $log->Module->module_className, $log->Module->module_title),8), Yii::app()->controller->createUrl($log->Module->module_name."/index",array("owner"=>$log->log_resourceid)))."&nbsp;<br />";
			
			$output .= "&nbsp;<span class='bac' style=\"font-size:10px;background-color:".((strtotime($log->log_date)<strtotime(date("Y-m-d"))) ? '#DFDFDF' : '#C7EFB3').";\"><abbr class=\"timeago\" title=\"".CHtml::encode($log->log_date)."\">".CHtml::encode(Yii::app()->dateFormatter->format('dd.MM.yyyy', $log->log_date))."</abbr></span>";

			echo $output;
		?>
	</div>
<?php endforeach; ?>