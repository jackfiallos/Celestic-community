<?php
//$yii=dirname(__FILE__).'/../../yii-1.1.7.r3135/framework/yii.php';
$yii=dirname(__FILE__).'/../../yii-1.1.8.r3324/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/console.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',false);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
Yii::createConsoleApplication($config)->run();
