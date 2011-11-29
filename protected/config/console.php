<?php
/**
 * cron configuration file
 * 
 * @author		Jackfiallos
 * @link		http://qbit.com.mx/labs/celestic
 * @copyright 	Copyright (c) 2009-2011 Qbit Mexhico
 * @license 	http://qbit.com.mx/labs/celestic/license/
 * @description
 * This is the cron Web application configuration
 *
 **/

// Load db config file
$db = include_once('db.php');
 
$console = array(	
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Celestic Console',
	'preload'=>array('log'),
    'import'=>array(
        'application.components.*',
        'application.models.*',
    ),
	'components'=>array(
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'caseSensitive'=>false,
			'rules'=>array(),
		), 
		'log'=>array(
            'class'=>'CLogRouter',
            'routes'=>array(
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'cron.log',
                    'levels'=>'error, warning, info',
                ),
                array(
                    'class'=>'CFileLogRoute',
                    'logFile'=>'cron_trace.log',
                    'levels'=>'trace',
                ),
            ),
        ),
	),
	// parameters
	'params'=>array(
		'templatesPath'=>'/home/jack/Public/celestic-community/protected/views/templates',
	),
);

return CMap::mergeArray($console, $db);
