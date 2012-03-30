<?php
// change the following paths if necessary
$yii=dirname(__FILE__).'/../yii-1.1.10.r3566/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',false);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

$configFile = (YII_DEBUG==true) ? 'dev.php' : 'production.php';

require_once($yii);
Yii::createWebApplication($config . $configFile)->run();
