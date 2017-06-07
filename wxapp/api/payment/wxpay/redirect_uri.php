<?php
/**
 * 微信支付地址跳转
 *
 * 
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
$_GET['act']	= 'payment';
$_GET['op']		= 'returnopenid';
$_GET['payment_code'] = 'wxpay';

require_once(dirname(__FILE__).'/../../../index.php');
?>