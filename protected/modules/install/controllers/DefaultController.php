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
					'check'=>environmentChecks::run(),
				));
				break;
			case 3:
				$authGet = Yii::app()->request->getParam("auth",'sorry');
				
				if (Yii::app()->request->csrfToken != $authGet)
					$this->render('check');
				
				databaseConfiguration::SplitSQL(Yii::app()->getModulePath().'/install/data/mysql_schema.sql');
				databaseConfiguration::SplitSQL(Yii::app()->getModulePath().'/install/data/mysql_data.sql');
					
				$this->render('finish');
				break;
			default:
				$this->render('index');
				break;
		}
	}
}