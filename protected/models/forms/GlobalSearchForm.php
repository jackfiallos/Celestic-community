<?php
class GlobalSearchForm extends CFormModel
{
	public $search;
	public $searchModels = array(
		'budgets'=>array(
			'budget_title',
			'budget_notes',
		),
		'invoices' => array(
			'invoice_number',
		),
		'expenses' => array(
			'expense_name',
			'expense_number',
		),
		'documents' => array(
			'document_name',
			'document_description',
			'document_revision',
			'document_type'
		),
		'milestones' => array(
			'milestone_title',
			'milestone_description'
		),
		'cases' => array(
			'case_code',
			'case_name',
			'case_description',
			'case_requirements'
		),
		'tasks' => array(
			'task_name',
			'task_description'
		),
		'projects' => array(
			'project_id',
			'project_name',
			'project_description',
			'project_scope',
			'project_restrictions',
			'project_plataform',
			'project_swRequirements',
			'project_hwRequirements',
			'project_functionalReq',
			'project_performanceReq',
			'project_additionalComments',
			'project_userInterfaces',
			'project_hardwareInterfaces',
			'project_softwareInterfaces',
			'project_communicationInterfaces',
			'project_backupRecovery',
			'project_dataMigration',
			'project_userTraining',
			'project_installation',
			'project_assumptions',
			'project_outReach',
			'project_responsibilities',
			'project_warranty',
			'project_additionalCosts'
		),
	);
	
	public function search($searchAttributes, $term)
	{
		$selected = Yii::app()->user->getState('project_selected');
		
		if(empty($selected))
			Yii::app()->user->setFlash('GlobalSearchForm', Yii::t('site','selectOneProject'));
		
		$items = array();
		foreach($searchAttributes as $key => $value)
		{
			array_push($items, $key);
		}
		
		$modules = Modules::model()->findAll(array(
			'condition'=>'t.module_name IN ("'.implode('","', $items).'")',
		));
		
		// array donde se guardaran los resultados
		$dataproviders = array();
		foreach($modules as $module)
		{
			$criteria = new CDbCriteria;
			if(in_array($module->module_name, array_keys($this->searchModels)))
			{
				foreach($this->searchModels[$module->module_name] as $attr)
				{
					$criteria->compare($attr, $term, true, 'OR');
					$criteria->compare('project_id',(!empty($selected)) ? $selected : -1);
				}
				
				$dataproviders[$module->module_name] = new CActiveDataProvider($module->module_className, array('criteria'=>$criteria));
			}
		}

		return $dataproviders;
	}
}