<?php
/**
 * production configuration file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright Copyright (c) 2009-2011 Qbit Mexhico
 * @license http://qbit.com.mx/labs/celestic/license/
 * @description
 * This is the production Web application configuration
 *
 **/
error_reporting(0);
// Load main config file
$main = include_once('main.php'); 
 
// Production configurations
$production = array(
	'components' => array(
		'db' =>  array(
			'class' => 'CDbConnection',
			'emulatePrepare' => true,
			'charset' => 'latin1',
            'tablePrefix' => '',
            'emulatePrepare' => true,
            'enableProfiling' => true,
            'schemaCacheID' => 'cache',
            'schemaCachingDuration' => 3600
		),
		'log' => array(
	    	'class' => 'CLogRouter',
	    	'routes' => array(
	         	array(
					'class' => 'CEmailLogRoute',
					'levels'=>'error, warning',
					'emails' => array('admin@emailaddrss.com'),
					'sentFrom' => 'webmaster@emailaddrss.com',
					'subject' => 'Celestic Error',
					'categories'=>'system.*',
	            ),
			),
		),
	),
	// http://code.google.com/intl/es-ES/apis/maps/documentation/javascript/v2/
	'params'=>array(
		'gmapsApi'=>'ABQIAAAAwWe7xC5drhMLwJMCd1Z2HRTktFru5cKE7RvI4QnZJoss-T14gRQqsoe9k4rG3D7OyIVmWWnwmitOmA',
	),
);

//merge both configurations and return them
return CMap::mergeArray($main, $production);