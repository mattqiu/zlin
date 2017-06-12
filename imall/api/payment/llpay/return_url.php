<?php
/**
 * 连连支付页面跳转同步通知页面
 *
 * 
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
$_GET['act']	= 'payment';
$_GET['op']		= 'return';
$_GET['payment_code'] = 'llpay';

$_GET['extra_common_param'] = 'real_order';
$_GET['out_trade_no'] = $_POST['no_order' ];
$_GET['trade_no'] = $_POST['no_order' ];
require_once(dirname(__FILE__).'/../../../index.php');
?>
