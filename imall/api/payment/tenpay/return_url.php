<?php
/**
 * 财付通返回地址
 *
 * 
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
error_reporting(7);
$_GET['act']	= 'payment';
$_GET['op']		= 'return';
$_GET['payment_code'] = 'tenpay';

//赋值，方便后面合并使用支付宝验证方法
$_GET['out_trade_no'] = $_GET['sp_billno'];
$_GET['extra_common_param'] = $_GET['attach'];
$_GET['trade_no'] = $_GET['transaction_id'];

require_once(dirname(__FILE__).'/../../../index.php');
?>