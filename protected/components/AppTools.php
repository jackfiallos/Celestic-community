<?php
/**
 * AppTools class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Tool contains helper methods
 * 
 **/ 
class AppTools {
	private function AppTools() { /* Evita que se instancie */ }
	
	public static function DBallreadyInstalled()
	{
		$result = false;
		
		$reader = Yii::app()->db->createCommand("show tables")->query();
		if (count($reader)>0)
			$result = true;
		
		return $result;
	}
	
	public static function masterAdmin()
	{
		$result = false;
		
		$reader = Accounts::model()->findAll();
		if (count($reader)>0)
			$result = true;
		
		return $result;
	}
}