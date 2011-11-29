<?php
/**
 * GlobalSearch class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property integer $countComments
 * @property integer $item_id
 *
 **/
class GlobalSearch extends CWidget
{
    /**
	 * Return all modules alloweds to search
	 * @return model list of modules
	 */
	public function getModulesToSearch()
	{
		return Modules::model()->searchOn();
	}
	
	/**
	 * Execute the widget
	 * @return template render
	 */
	public function run()
    {
		$this->render('GlobalSearch');
    }
}
?>