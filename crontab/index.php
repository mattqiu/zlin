<?php
/**
 * 队列
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
 
//$_SERVER['argv'][1] = 'month';
//$_SERVER['argv'][2] = 'index';
 //立即结算
//$_SERVER['argv'][1] = 'order';
//$_SERVER['argv'][2] = 'create_bill';
//win2003计划任务
$_SERVER['argv'][1] = $_GET['act'];
$_SERVER['argv'][2] = $_GET['op'];

if (empty($_SERVER['argv'][1])) exit('Access Invalid1!');

define('APP_ID','crontab');
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
define('TRANS_MASTER',true);
if (!@include(dirname(dirname(__FILE__)).'/global.php')) exit('global.php isn\'t exists!');

if (PHP_SAPI == 'cli') {
    $_GET['act'] = $_SERVER['argv'][1];
    $_GET['op'] = empty($_SERVER['argv'][2]) ? 'index' : $_SERVER['argv'][2];
}
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

Base::run();
?>