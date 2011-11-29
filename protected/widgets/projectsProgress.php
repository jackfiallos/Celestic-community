<?php
/**
 * projectsProgress class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property array $htmlOptions
 *
 **/ 
Yii::import('zii.widgets.CPortlet');
class projectsProgress extends CPortlet
{
	public $htmlOptions;
	
	/*
	 * Class object initialization
	 */
	public function init()
	{
		$this->title = CHtml::image(Yii::app()->request->baseUrl.'/images/portlets/charts.png')." ".Yii::t('portlet', 'ProjectsProgressTitle');
		$this->decorationCssClass = "portlet-header";
		$this->titleCssClass = "portlet-title";
		parent::init();
	}
	
	public function getProjectsProgress()
	{
		if(Yii::app()->user->getState('project_selected') == null)
		{
			// seleccionar todos los proyectos del usuario
			$ProjectsList = Projects::model()->findMyProjects(Yii::app()->user->id);
			
			$data = array();
			
			// iterar cada proyecto
			foreach($ProjectsList as $project)
			{
				// buscando todas las tareas relacionadas al proyecto iterado
				$TasksList = Projects::model()->getProjectProgress($project->project_id);
				
				$data[] = array(
					'name' => ECHtml::word_split($project->project_name, 10),
					'data' => array(!empty($TasksList->progress) ? round($TasksList->progress, 2) : 0),
				);
			}
		}
		else {
			// buscando todas las tareas relacionadas al proyecto seleccionado
			$TasksList = Projects::model()->getProjectProgress(Yii::app()->user->getState('project_selected'));
			
			$data[] = array(
				'name' => ECHtml::word_split(Projects::model()->findByPk(Yii::app()->user->getState('project_selected'))->project_name, 10),
				'data' => array(!empty($TasksList->progress) ? round($TasksList->progress, 2) : 0),
			);
		}
		
		return $data;
	}
	
	/**
	 * Render the main content of the portlet
	 * @return template render
	 */
    protected function renderContent()
    {
        $this->render('projectsProgress');
    }
}
?>