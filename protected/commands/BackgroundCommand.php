<?php
//php yiic.php backgroundcommand actionName --param1=Value1 --param2=Value2 ...
//php /www/celestic/cron.php Background
//yiic Background
class BackgroundCommand extends CConsoleCommand 
{    
    public function actionPendingMails() 
	{
		// verify if are emails pending to send
		$emails = Emails::model()->findAll(array(
			'condition'=>'t.email_status = 0',
			'limit'=>Yii::app()->params['mailSendMultiples'],
		));
		
		if(count($emails)>0)
		{
			foreach($emails as $email)
			{
				Yii::import('application.extensions.phpMailer.yiiPhpMailer');
				$mailer = new yiiPhpMailer;
				if ($mailer->Ready($email->email_subject, $email->email_body, array('email'=>$email->email_toMail,'name'=>$email->email_toName), $email->email_priority))
				{
					$email->email_status = 1;
					$email->email_sentDate = date("Y-m-d G:i:s");
					$email->save(false);
				}
			}
		}
    }
    
    public function actionChangeBudgetDueStatus()
    {
    	//Verify all pending budgets with duedates
		$Budgets = Budgets::model()->findAll(array(
			'condition'=>'t.status_id = :status_id AND CURDATE() > t.budget_duedate',
			'params'=>array(
				':status_id'=>Status::STATUS_PENDING,
			),
		));
		
		// then change status to cancelled
		foreach($Budgets as $budget)
		{
			$budget->status_id = 2;
			$budget->save(false);
		}
    }
    
	public function actionMilestonesPending() 
	{
        $Milestones = Milestones::model()->MilestoneWithPendingTasks();
		foreach ($Milestones as $milestone)
		{
			$Tasks = Tasks::model()->findTaskByMilestone($milestone->milestone_id);
			
			$str = CBaseController::renderInternal(Yii::app()->params['templatesPath'].'/milestones/overdueMilestones.php',array(
				'user' => $milestone->Users->completeName,
				'tasks' => $Tasks,
				'applicationName' => Yii::app()->name,
				'applicationUrl' => "http://localhost/celestic/".Yii::app()->request->baseUrl,
			),true);
			
			$subject = Yii::t('email','overdueMilestone');
			
			Yii::import('application.extensions.phpMailer.yiiPhpMailer');
			$mailer = new yiiPhpMailer;
			$mailer->pushMail($subject, $str, array('name'=>$milestone->Users->CompleteName,'email'=>$milestone->Users->user_email), Emails::PRIORITY_NORMAL);
		}
    }
}