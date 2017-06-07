<?php
/**
 * 连连支付服务器异步通知页面
 *
 * 
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
function addslashes_deep_obj($obj){
	if (is_object($obj) == true)
	{
	    foreach ($obj AS $key => $val)
	    {
	        $obj->$key = addslashes_deep($val);
	    }
	}else{
	    $obj = addslashes_deep($obj);
	}
	
	return $obj;
}

function addslashes_deep($value)
{
    if (empty($value))
    {
        return $value;
    }else{
        return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
    }
}

function getJsonVal($json, $k){
	if(isset($json->{$k})){
		return trim($json->{$k});
	}
	return "";	
}

$str = file_get_contents("php://input");
$val = addslashes_deep_obj(json_decode(stripslashes($str),FALSE));

$_POST['oid_partner'] = getJsonVal($val,'oid_partner' );
$_POST['sign_type'] = getJsonVal($val,'sign_type' );
$_POST['sign'] = getJsonVal($val,'sign' );
$_POST['dt_order'] = getJsonVal($val,'dt_order' );
$_POST['no_order'] = getJsonVal($val,'no_order' );
$_POST['oid_paybill'] = getJsonVal($val,'oid_paybill' );
$_POST['money_order'] = getJsonVal($val,'money_order' );
$_POST['result_pay'] = getJsonVal($val,'result_pay' );
$_POST['settle_date'] = getJsonVal($val,'settle_date' );
$_POST['info_order'] = getJsonVal($val,'info_order');
$_POST['pay_type'] = getJsonVal($val,'pay_type' );
$_POST['bank_code'] = getJsonVal($val,'bank_code' );
$_POST['no_agree'] = getJsonVal($val,'no_agree' );
$_POST['id_type'] = getJsonVal($val,'id_type' );
$_POST['id_no'] = getJsonVal($val,'id_no' );
$_POST['acct_name'] = getJsonVal($val,'acct_name' );

$_GET['act']	= 'payment';
$_GET['op']		= 'notify';
$_GET['payment_code'] = 'llpay';

require_once(dirname(__FILE__).'/../../../index.php');
?>