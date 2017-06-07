<?php
/**
 * 手机接口初始化文件
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

define('APP_ID','mobile');
define('IGNORE_EXCEPTION', true);
define('BASE_PATH',str_replace('\\','/',dirname(__FILE__)));

if (!@include(dirname(dirname(__FILE__)).'/global.php')) exit('global.php isn\'t exists!');

if (!@include(BASE_PATH.'/config/config.ini.php')){
    exit('config.ini.php isn\'t exists!');
}

define('NODE_SITE_URL',$config['node_site_url']);

//框架扩展
require(BASE_PATH.'/common/function/function.php');
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');

define('APP_SITE_URL',SHOP_SITE_URL);
define('TPL_NAME',TPL_SHOP_NAME);
define('SHOP_RESOURCE_SITE_URL',SHOP_SITE_URL.DS.'resource');
define('SHOP_SKINS_URL',SHOP_SITE_URL.'/skins/'.TPL_NAME);
define('BASE_TPL_PATH',BASE_PATH.'/skins/'.TPL_NAME);
define('MOBILE_RESOURCE_SITE_URL',MOBILE_SITE_URL.DS.'resource');
define('MOBILE_SKINS_URL',MOBILE_SITE_URL.'/skins/'.TPL_NAME);
Base::run();
