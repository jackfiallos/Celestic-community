<?php
/**
 * DocumentsController class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Controller handler for Documents 
 * @property string $layout
 * @property CActiveRecord $_model
 * @property string $tmpFileName
 *
 **/
class DocumentsController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;
	
	/**
	 * @var resourceFolder saved inside all uploaded images
	 */
	const FOLDERIMAGES='resources/';
	
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
				'actions' => 'all'
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
					'index',
					'view',
					'create',
					'update',
					'download'
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
	 * Find a file with params sent and return download link
	 * @throws CHttpException
	 */
	public function actionDownload()
	{
		// verify if user has permissions to downloadDownloads
		if(Yii::app()->user->checkAccess('downloadDownloads'))
		{
			// find document item with param $_GET['id']
			$model = $this->loadModel();
				
			// download file
			$this->renderPartial('download',array(
				'model'=>$model,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Displays a particular model.
	 */
	public function actionView()
	{
		// verify if user has permissions to viewDownloads
		if(Yii::app()->user->checkAccess('viewDownloads'))
		{
			// find document with GET params
			$model = $this->loadModel();
			
			// Dataprovider to find all documents revisions
			$DocumentDataProvider = new CActiveDataProvider('Documents', array(
				'criteria'=>array(
					'condition'=>'t.document_baseRevision = :document_baseRevision',
					'params'=>array(
						':document_baseRevision'=>$model->document_baseRevision
					),
					'order'=>'t.document_revision DESC',
				),
			));
			
			// render page
			$this->render('view',array(
				'model'=>$model,
				'dataProvider'=>$DocumentDataProvider,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		// verify if user has permissions to createDownloads
		if(Yii::app()->user->checkAccess('createDownloads'))
		{
			// create object model Documents
			$model=new Documents;
			
			// verify _POST['Documents'] exist
			if (isset($_POST['Documents']))
			{
				// set form elements to Documents model attributes
				$model->attributes=$_POST['Documents'];
				$model->project_id = Yii::app()->user->getState('project_selected');
				$model->user_id = Yii::app()->user->id;
				
				// verify file upload exist
				if ((isset($_FILES['Documents']['name']['image'])) && (!empty($_FILES['Documents']['name']['image'])))
				{
					// create an instance of uploaded file
					$model->image = CUploadedFile::getInstance($model,'image');
					if (!$model->image->getError())
					{
						// name is formed by date(day+month+year+hour+minutes+seconds+dayofyear+microtime())
						$this->tmpFileName = str_replace(" ","",date('dmYHis-z-').microtime());
						// get the file extension
						$extension=$model->image->getExtensionName();
						if($model->image->saveAs(DocumentsController::FOLDERIMAGES.$this->tmpFileName.'.'.$extension))
						{
							// set other attributes
							$model->document_path = DocumentsController::FOLDERIMAGES.$this->tmpFileName.'.'.$extension;
							$model->document_revision = '1';
							$model->document_uploadDate = date("Y-m-d");
							$model->document_type = $model->image->getType();
							$model->document_baseRevision = date('dmYHis');
							$model->user_id = Yii::app()->user->id;
							
							// create email object
							Yii::import('application.extensions.phpMailer.yiiPhpMailer');
							$mailer = new yiiPhpMailer;
							$subject = Yii::t('email','newDocumentUpload')." - ".$model->document_name;

							// find users managers to send email
							$Users = Projects::model()->findManagersByProject($model->project_id);
							
							// create array of users destinations
							$recipientsList = array();
							foreach($Users as $client)
							{			
								$recipientsList[] = array(
									'name'=>$client->CompleteName,
									'email'=>$client->user_email,
								);				
							}
							
							// set layout then send
							$str = $this->renderPartial('//templates/documents/newUpload',array(
								'document' => $model,
								'urlToDocument' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->createUrl('documents/view',array('id'=>$model->document_id)),
								'applicationName' => Yii::app()->name,
								'applicationUrl' => "http://".$_SERVER['SERVER_NAME'].Yii::app()->request->baseUrl,
							),true);
							
							$mailer->pushMail($subject, $str, $recipientsList, Emails::PRIORITY_NORMAL);
						}
					}
					else
						$model->addError('image',$model->image->getError());
				}
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'DocumentCreated',
						'log_resourceid' => $model->primaryKey,
						'log_type' => 'created',
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					$this->redirect(array('view','id'=>$model->document_id));
				}					
			}

			// response with create view
			$this->render('create',array(
				'model'=>$model,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		// verify if user has permissions to updateDownloads
		if(Yii::app()->user->checkAccess('updateDownloads'))
		{
			// find item object using param _id
			$modelUpdate = $this->loadModel();

			// verify Documents _POST exist
			if(isset($_POST['Documents']))
			{
				// set form elements to Documents model attributes
				$modelUpdate->attributes=$_POST['Documents'];
				// another model is required to create a revision
				$model = new Documents;
				$model->attributes=$_POST['Documents'];
				$model->project_id = $modelUpdate->project_id;
				$model->document_id = $modelUpdate->document_id;
				
				// verify file upload exist 
				if ((isset($_FILES['Documents']['tmp_name']['image'])) && (!empty($_FILES['Documents']['tmp_name']['image'])))
				{
					// create an instance of uploaded file
					$model->image = CUploadedFile::getInstance($model,'image');
					if (!$model->image->getError())
					{
						// name is formed by date(day+month+year+hour+minutes+seconds+dayofyear+microtime())
						$this->tmpFileName = str_replace(" ","",date('dmYHis-z-').microtime());
						$extension=explode(".",$model->image->getName());
						$model->image->saveAs(DocumentsController::FOLDERIMAGES.$this->tmpFileName.'.'.$extension[1]);
						$model->document_path = DocumentsController::FOLDERIMAGES.$this->tmpFileName.'.'.$extension[1];
						$model->document_revision = $modelUpdate->document_revision + 1;
						$model->document_uploadDate = date("Y-m-d");
						$model->document_type = $model->image->getType();
						$model->document_baseRevision = $modelUpdate->document_baseRevision;
						$model->user_id = Yii::app()->user->id;
						$model->document_id = null;
					}
					else
						$model->addError('image',$model->image->getError());
				}				
				
				// validate and save
				if($model->save())
				{
					// save log
					$attributes = array(
						'log_date' => date("Y-m-d G:i:s"),
						'log_activity' => 'DocumentUpdated',
						'log_resourceid' => $model->document_id,
						'log_type' => 'updated',
						'user_id' => Yii::app()->user->id,
						'module_id' => Yii::app()->controller->id,
						'project_id' => $model->project_id,
					);
					Logs::model()->saveLog($attributes);
					
					$this->redirect(array('view','id'=>$model->document_id));
				}
			}

			// response with update view
			$this->render('update',array(
				'model'=>(isset($_POST['Documents'])) ? $model : $modelUpdate,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Lists all documents.
	 */
	public function actionIndex()
	{
		// verify if user has permissions to indexDownloads
		if(Yii::app()->user->checkAccess('indexDownloads'))
		{
			// create object Documents search form
			$model=new DocumentsSearchForm;
			$model->search();
			$model->unsetAttributes();  // clear any default values
			
			// if form DocumentsSearchForm was used
			if(isset($_GET['DocumentsSearchForm']))
				$model->attributes=$_GET['DocumentsSearchForm'];
			
			$this->render('index',array(
				'model'=>$model,
			));
		}
		else
			throw new CHttpException(403, Yii::t('site', '403_Error'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param $id to compare with budget_id
	 * @return CActiveRecord Documents
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Documents::model()->with('Projects','User')->together()->findbyPk($_GET['id']);
			if($this->_model===null)
				throw new CHttpException(404, Yii::t('site', '404_Error'));
		}
		return $this->_model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 * @return CModel validation to form elements
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='documents-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * This method is invoked right before an action is to be executed (after all possible filters.)
	 * @param CAction $action the action to be executed.
	 * @return boolean whether the action should be executed
	 */
	public function beforeAction($action)
	{
		$response = false;
		if (Yii::app()->user->getState('project_selected') != null)
		{
			if (in_array($action->id, array('view','update','download')))
				$response = (Documents::model()->countDocumentsByProject((int)$_GET['id'], Yii::app()->user->getState('project_selected')) > 0) ? true : false;
			else
				$response = true;
		}
		else
			$response = false;
		
		if(!$response)
			throw new CHttpException(403, Yii::t('site', '403_Error'));
		else
			return $response;
	}
}
