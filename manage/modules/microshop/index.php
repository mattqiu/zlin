<?php
/**
 * 商城板块初始化文件
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

define('BASE_PATH',str_replace('\\','/',dirname(dirname(dirname(__FILE__)))));
define('MODULES_BASE_PATH',str_replace('\\','/',dirname(__FILE__)));
require __DIR__ . '/../../../global.php';

define('TPL_NAME',TPL_ADMIN_NAME);
define('ADMIN_SKINS_URL',ADMIN_SITE_URL.'/skins/'.TPL_NAME);
define('ADMIN_RESOURCE_URL',ADMIN_SITE_URL.'/resource');
define('SHOP_SKINS_URL',SHOP_SITE_URL.'/skins/'.TPL_NAME);
define('BASE_TPL_PATH',MODULES_BASE_PATH.'/skins/'.TPL_NAME);
define('MODULE_NAME', 'microshop');
if (!@include(BASE_PATH.'/control/control.php')) exit('control.php isn\'t exists!');
$system='microshop';

Base::runadmin($system);
