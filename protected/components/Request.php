<?php
/**
 * Request class file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * Generic Class with begin event to set automatic timezone application
 * 
 **/ 
class Request
{
	/**
	 * Process for all begin request application
	 * used to set init aplication timezone
	 */
	function begin()
	{
		if (!Yii::app()->user->isGuest)
		{
			if(Yii::app()->user->getState('timezone_name') == null)
			{
				$timezone = Timezones::getTimezoneSelected(Yii::app()->user->Accountid);
				if (!empty($timezone->timezone_name))
				{
					Yii::app()->user->setState('timezone_name', $timezone->timezone_name);
					date_default_timezone_set($timezone->timezone_name);
				}
				else
				{
					Yii::app()->user->setState('timezone_name', Yii::app()->params['timezone']);
					date_default_timezone_set(Yii::app()->params['timezone']);
				}
			}
			else
				date_default_timezone_set(Yii::app()->user->getState('timezone_name'));
		}
		else
			date_default_timezone_set(Yii::app()->params['timezone']);
	}
}