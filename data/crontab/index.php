<?php
/**
 * 任务计划执行入口
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */


define('InIMall',true);
define('BASE_ROOT_PATH',str_replace('\\','/',dirname(__FILE__)));
// $_SERVER['argv'][1] = 'xs';
// $_SERVER['argv'][2] = 'create';

$_SERVER['argv'][1] = 'order';
$_SERVER['argv'][2] = 'create_bill';

if (empty($_SERVER['argv'][1]) || empty($_SERVER['argv'][2])) exit('parameter error');

require(dirname(__FILE__).'/../../global.php');

Base::init();

$file_name = strtolower($_SERVER['argv'][1]);

$method = $_SERVER['argv'][2].'Op';

if (!@include(dirname(__FILE__).'/include/'.$file_name.'.php')) exit($file_name.'.php isn\'t exists!');

$class_name = $file_name.'Control';
$cron = new $class_name();

if (method_exists($cron,$method)){
    $cron->$method();
}else{
    exit('method '.$method.' isn\'t exists');
}