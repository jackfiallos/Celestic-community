<?php
/**
 * CommentsController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for Comments 
 * @property string $layout
 * @property CActiveRecord $_model
 *
 **/
class CommentsController extends Controller
{
	const FOLDERIMAGES='resources/';
	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	
	/**
	 * @var string temporal filename
	 */
	private $tmpFileName='';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			array(
				'application.filters.YXssFilter',
				'clean'   => '*',
				'tags'    => 'strict',
				'actions' => 'create'
			)
		);
	}
	
	/**
	 * Especify access control rights
	 * @return array access rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', 
				'actions'=>array(
					'update',
					'create'
				),
				'users'=>array('@'),
				'expression'=>'!$user->isGuest',
			),
			array('deny',
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Updates a particular model.
	 * @param integer $_GET['id'] the ID of the model to be updated
	 * @return updated comment text
	 */
	public function actionUpdate()
	{
		Yii::app()->end(); //disalow updates
		
		// get Comments object from $id parameter
		$model=$this->loadModel($_GET['id']);
		
		// if Comments form exist and was called via ajax
        if ((isset($_POST['Comments'])) && (isset($_POST['ajax'])))
        {
            // set form elements to Users model attributes
        	$model->attributes=$_POST['Comments'];
        	
        	// clear tag from text
			Yii::import('application.extensions.InputFilter.InputFilter');
			$filter = new InputFilter(array('br','pre'));
			$model->comment_text = $filter->process($model->comment_text);
			
			// update comment
            $model->save(false);
			echo $model->comment_text;
        }
		Yii::app()->end();
	}
	
	/**
	 * Creates a new model.
	 */
	public function actionCreate()
	{
		// create Comments Object
		$model = new Comments;
		
		// if Comments form exist
		if(isset($_POST['Comments']))
		{
			// set form elements to Comments model attributes
			$model->attributes=$_POST['Comments'];
			$module = Modules::model()->find(array(
				'condition'=>'t.module_name = :module_name',
				'params'=>array(
					':module_name'=>$model->module_id, //module_id meaning module_name in view form
				),
			));
			
			// set module_id finded to model
			$model->module_id = $module->module_id;
			$project = Yii::app()->user->getState('project_selected');
			
			// validate and save
			if($model->save())
			{
				// create an instance of file uploaded
				$image = CUploadedFile::getInstancesByName('Comment');
				
				// if file upload exist
				if (count($image>0))
				{
					// for each file uploaded
					for($i=0; $i<count($image); $i++)
					{
						// create an Document object
						$modeldocs=new Documents;
						$modeldocs->image = $image[$i];
						
						if (!$modeldocs->image->getError())
						{
							// name is formed by date(day+month+year+hour+minutes+seconds+dayofyear+microtime())
							$this->tmpFileName = str_replace(" ","",date('dmYHis-z-').microtime());
							// set the extension file uploaded
							$extension = $modeldocs->image->getExtensionName();
							
							// if no error saving file
							if ($modeldocs->image->saveAs(self::FOLDERIMAGES.$this->tmpFileName.'.'.$extension))
							{
								$modeldocs->project_id = $project;
								$modeldocs->document_name = substr($modeldocs->image->getName(),0,30);
								$modeldocs->document_description = $model->comment_text;
								$modeldocs->document_path = self::FOLDERIMAGES.$this->tmpFileName.'.'.$extension;
								$modeldocs->document_revision = '1';
								$modeldocs->document_uploadDate = date("Y-m-d");
								$modeldocs->document_type = $modeldocs->image->getType();
								$modeldocs->document_baseRevision = date('dmYHis');
								$modeldocs->user_id = Yii::app()->user->id;
								$modeldocs->comment_id = $model->primaryKey;
								
								// save file uploaded as document
								if ($modeldocs->save())
									Yii::app()->user->setFlash('CommentMessageSuccess',$modeldocs->image->getName()." ".Yii::t('comments','UploadOk'));
								else
									Yii::app()->user->setFlash('CommentMessage', $modeldocs->getErrors());
							}
							else
								Yii::app()->user->setFlash('CommentMessage',$modeldocs->image->getName()." ".Yii::t('comments','UploadError'));
						}
						else
							Yii::app()->user->setFlash('CommentMessage',$modeldocs->image->error." ".Yii::t('comments','UploadCheckErrors'));
					}
				}
				
				// save log
				$attributes = array(
					'log_date' => date("Y-m-d G:i:s"),
					'log_activity' => 'CommentPosted',
					'log_resourceid' => $model->comment_resourceid,
					'log_type' => Logs::LOG_COMMENTED,
					'log_commentid' => $model->primaryKey,
					'user_id' => Yii::app()->user->id,
					'module_id' => $module->module_name,
					'project_id' => $project,
				);
				Logs::model()->saveLog($attributes);
				
				// find project managers to sent comment via mail
				$recipientsList = array();
				$ProjectManagers = Projects::model()->findManagersByProject($project);
				$managersArray = array();
				foreach($ProjectManagers as $manager)
				{
					$managersArray['email'] = $manager->user_email;
					$managersArray['name'] = $manager->CompleteName;
					array_push($recipientsList, $managersArray);
				}
				
				// find task owners to send comment via mail
				if ($module->module_name == 'tasks')
				{
					$collaborators = Projects::model()->findAllUsersByProject($project);
					$ColaboratorsArray = array();
					foreach($collaborators as $colaborator)
					{
						$ColaboratorsArray['email'] = $colaborator->user_email;
						$ColaboratorsArray['name'] = $colaborator->CompleteName;
						// avoid to repeat email address
						if(!in_array($ColaboratorsArray, $recipientsList))
							array_push($recipientsList, $ColaboratorsArray);
					}
				}
				
				// finding resource title
				switch ($module->module_name)
				{
					case "budgets":
						$resourceModelTitle = Budgets::model()->findByPk($model->comment_resourceid)->budget_title;
						break;
					case "invoices":
						$resourceModelTitle = Invoices::model()->findByPk($model->comment_resourceid)->invoice_number;
						break;
					case "expenses":
						$resourceModelTitle = Expenses::model()->findByPk($model->comment_resourceid)->expense_name;
						break;
					case "documents":
						$resourceModelTitle = Documents::model()->findByPk($model->comment_resourceid)->document_name;
						break;
					case "milestones":
						$resourceModelTitle = Milestones::model()->findByPk($model->comment_resourceid)->milestone_title;
						break;
					case "cases":
						$resourceModelTitle = Cases::model()->findByPk($model->comment_resourceid)->case_name;
						break;
					case "tasks":
						$resourceModelTitle = Tasks::model()->findByPk($model->comment_resourceid)->task_name;
						break;
					default:
						$resourceModelTitle = "{empty}";
						break;
				}
				
				// get project information
				$project = Projects::model()->findByPk($project);
				
				// prepare template to send via email
				$str = $this->renderPartial('//templates/comments/created',array(
					'model' => $model,
					'projectName'=>$project->project_name,
					'userposted'=>Yii::app()->user->CompleteName,
					'resourceTitle'=>$resourceModelTitle,
					'moduleName'=>Yii::t('modules',$module->module_name),
					'applicationName' => Yii::app()->name,
					'applicationUrl' => Yii::app()->createAbsoluteUrl($module->module_name.'/view', array('id'=>$model->comment_resourceid)),
				),true);
				
				Yii::import('application.extensions.phpMailer.yiiPhpMailer');
				$mailer = new yiiPhpMailer;
				$subject = Yii::t('email','CommentPosted')." - ".$project->project_name." - ".Yii::t('modules',$module->module_name);
				$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
			}
			else
				Yii::app()->user->setFlash('CommentMessage',Yii::t('comments','RequiredComment'));
				
		}
		
		$this->redirect(
			Yii::app()->createUrl(
				$_GET['module'].'/'.$_GET['action'],array(
					'id'=>$_GET['id'],
					'#'=>'comment-'.$model->primaryKey,
				)
			)
		);
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @return CActiveRecord Cases
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_REQUEST['id']))
				$this->_model=Comments::model()->findbyPk($_REQUEST['id']);
			if($this->_model===null)
				throw new CHttpException(404, Yii::t('site', '404_Error'));
		}
		return $this->_model;
	}
}