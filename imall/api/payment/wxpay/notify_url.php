<?php
/**
 * 微信支付通知地址
 *
 * 
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */

//存储微信的回调
$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
//获取交易支付id,附加数据,交易结果
$postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
$pay_sn = $postObj->out_trade_no;  //可以这样获取XML里面的信息 
$attach = $postObj->attach;
$result_code = $postObj->result_code ;

//终于到最后一步了。
$_GET['act']	= 'payment';
$_GET['op']	= 'return';
$_GET['payment_code'] = 'wxpay';
//获取订单交易类型，一个是商品购买，一个是积分,这里取返回的附加数据
$_GET['extra_common_param'] = $attach; 
//交易号，这里取返回的pay_sn即可
$_GET['out_trade_no'] = $pay_sn;
$_GET['result_code'] = $result_code ;

require_once(dirname(__FILE__).'/../../../index.php');
?>