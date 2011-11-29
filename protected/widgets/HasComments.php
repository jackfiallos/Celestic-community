<?php
/**
 * HasComments class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property integer $countComments
 * @property integer $item_id
 *
 **/
class HasComments extends CWidget
{
	public $countComments = 0;
	public $item_id = 0;
	
	/**
	 * Execute the widget
	 * @return template render
	 */
    public function run()
    {
		$this->render('HasComments',array(
			'countComments' => $this->countComments,
			'item_id' => $this->item_id,
		));
    }
}
?>