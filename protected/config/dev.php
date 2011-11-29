<?php
/**
 * dev configuration file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright Copyright (c) 2009-2011 Qbit Mexhico
 * @license http://qbit.com.mx/labs/celestic/license/
 * @description
 * This is the development Web application configuration
 *
 **/

// Load main config file
$main = include_once('main.php');

// Development configurations
$development = array(
	'modules'=>array(
        'gii'=>array(
            'class'=>'system.gii.GiiModule',
            'password'=>'jack',
        ),
    ),
	'components' => array(
		'log' => array(
        	'class' => 'CLogRouter',
            'routes' => array(
            	array(
					'class'=>'CWebLogRoute',
					'levels'=>'error, warning, trace',
					'categories'=>'system.*',
				),
				array(
					'class'=>'CFileLogRoute',
					'logFile'=>'appinfo.log',
					'levels'=>'info',
					'categories'=>'',
                ),
                array(
					'class'=>'CFileLogRoute',
					'logFile'=>'apptrace.log',
					'levels'=>'trace',
					'categories'=>'system.*',
                ),
                array(
					'class'=>'CFileLogRoute',
					'logFile'=>'apperror.log',
					'levels'=>'error, warning',
					'categories'=>'error.*',
                ),
                array(
                	'class'=>'CProfileLogRoute',
                ),
			),
		),
	),
	'params'=>array(
		'gmapsApi'=>'ABQIAAAAwWe7xC5drhMLwJMCd1Z2HRT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRn22vyAtqvJm6E4gzLmuVCrtY-lQ',
	),
);
//merge both configurations and return them
return CMap::mergeArray($main, $development);