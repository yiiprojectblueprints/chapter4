<?php

// change the following paths if necessary
$yii='/opt/frameworks/php/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

error_reporting(-1);
ini_set('display_errors', true);
// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once(__DIR__ . '/vendor/autoload.php');
require_once($yii);
Yii::createWebApplication($config)->run();