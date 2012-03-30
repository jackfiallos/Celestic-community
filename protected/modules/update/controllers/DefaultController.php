<?php
class DefaultController extends Controller
{
	public $layout='//layouts/column1';
	
	public function actionIndex()
	{
		$step = Yii::app()->request->getParam("step",1);
		switch ($step) {
			case 1:
				$this->render('index');
				break;
			case 2:
				$this->render('check',array(
					'check'=>environmentChecksUpdate::run(),
				));
				break;
			case 3:
				$authGet = Yii::app()->request->getParam("auth",'sorry');
				
				if (Yii::app()->request->csrfToken != $authGet)
					$this->render('check');
				
				databaseConfiguration::SplitSQL(Yii::app()->getModulePath().'/update/data/0_4/mysql_update.sql');
				$updated = array();
				$comments = Comments::model()->findAll();
				foreach ($comments as $comment) 
				{
					if (!in_array($comment->comment_resourceid.",".$comment->module_id, $updated))
					{
						$module = Modules::model()->findByPk($comment->module_id)->module_className;
						$modelClass = new $module();
						$project = $modelClass::model()->findByPk($comment->comment_resourceid)->project_id;
						Comments::model()->updateAll(
							array(
								'project_id'=>$project
							),
							'comment_resourceid = :resourceid AND module_id = :moduleid',
							array(
								':resourceid' => $comment->comment_resourceid,
								':moduleid' => $comment->module_id
							)
						);
						array_push($updated, $comment->comment_resourceid.",".$comment->module_id);
					}
				}
					
				$this->render('finish');
				break;
			default:
				$this->render('index');
				break;
		}
	}
}