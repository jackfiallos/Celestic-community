<?php
/**
 * TaskTodoList class file
 * 
 * @author		Jackfiallos
 * @link		http://jackfiallos.com
 * @description
 *
 * @property integer $task_id
 * @property string $urlOrder
 * @property string $urlSave
 * @property string $urlNew
 * @property string $urlCheck
 * @property boolean $permission
 *
 **/
class TaskTodoList extends CWidget
{
	public $task_id = 0;
	public $urlOrder = "";
	public $urlSave = "";
	public $urlNew = "";
	public $urlCheck = "";
	public $urlDelete = "";
	public $permission = false;
	
	/**
	 * Get list of subtask or toDolist
	 * @param int $task_id
	 * @return model of todolist
	 */
	public function getTodoList($task_id)
    {
		return Todolist::model()->findActivity($task_id);
    }
	
	/**
	 * Execute the widget
	 * @return template render
	 */
    public function run()
    {
		$this->render('TaskTodoList',array(
			'task_id' => $this->task_id,
		));
    }
}
?>