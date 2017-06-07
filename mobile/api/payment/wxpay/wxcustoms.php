<?php
/**
 * 微信海关接口类
 *
 * 
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class wxcustoms{
    private $code = 'wxpay';
    private $gatewayUrl = "http://api.mch.tenpay.com/cgi-bin/mch_custom_declare.cgi";
    private $format = "json";
    
    /** 是否打开入参check**/
    private $checkRequest = true;
    
    private $sign_type = "MD5";    
    private $service_version = "1.0";    
    private $charset = 'GBK';//'utf-8';
	
	private $appid = '';	
    private $secret = '';
	private $mch_id ='';
    private $mchkey = '';
    private $prepay_id ='';
    
    var $returnParameters;//返回参数，类型为关联数组

    var $parameters;//请求参数，类型为关联数组

    public function __construct() {

        $condition = array();
        $condition['payment_code'] = $this->code;
        $model_mb_payment = Model('mb_payment');
        $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
        if(!$mb_payment_info) {
            output_error('系统不支持选定的支付方式');
        }
        $this->appid = $mb_payment_info['payment_config']['wxpay_appid'];
        $this->secret = $mb_payment_info['payment_config']['wxpay_appsecret'];
        $this->mch_id = $mb_payment_info['payment_config']['wxpay_mchid'];
        $this->mchkey = $mb_payment_info['payment_config']['wxpay_mchkey'];

	}
  
    public function execute($sysParams, $session = null){
    	//系统参数放入GET请求串
    	$requestUrl = $this->gatewayUrl;
    	// 		$requestUrl = $this->gatewayUrl . "?";
    	// 		foreach ($sysParams as $sysParamKey => $sysParamValue)
    		// 		{
    		// 			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
    		// 		}
    	// 		$requestUrl = substr($requestUrl, 0, -1);
    
    	//发起HTTP请求
    	try
    	{
    		$resp = $this->curl($requestUrl, $sysParams);
    	}
    	catch (Exception $e)
    	{
    		$this->logCommunicationError($sysParams["method"],$requestUrl,"HTTP_ERROR_" . $e->getCode(),$e->getMessage());
    		$result->code = $e->getCode();
    		$result->msg = $e->getMessage();
    		return $result;
    	}
    	//解析TOP返回结果
    	$respWellFormed = false;
    	if ("json" == $this->format)
    	{
    		$respObject = json_decode($resp, true);
    		if (null !== $respObject)
    		{
    			$respWellFormed = true;
    			//foreach ($respObject as $propKey => $propValue)
    			//{
    			//	$respObject = $propValue;
    			//}
    		}
    	}
    	else if("xml" == $this->format)
    	{
    		$respObject = @simplexml_load_string($resp);
    		if (false !== $respObject)
    		{
    			$respWellFormed = true;
    		}
    	}
    	file_put_contents("wxcustoms_log.txt","转义后:".$respObject."\n", FILE_APPEND);
    	return $respObject;
    }
    
    protected function generateSign($params)
    {
    	//ksort($params);
    
    	$stringToBeSigned = $this->secretKey;
    	// 		foreach ($params as $k => $v)
    		// 		{
    		// 			if("@" != substr($v, 0, 1))
    			// 			{
    			// 				$stringToBeSigned .= "$k$v";
    			// 			}
    		// 		}
    	// 		unset($k, $v);
    	$stringToBeSigned .= $params;
    
    	return md5($stringToBeSigned);
    }
    
    /**
     * 跨进淘接口注意事项
     * curl 注意 'Content-Type:application/x-www-form-urlencoded'
     */
    public function curl($url, $postFields = null)
    {
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_FAILONERROR, false);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	//https 请求
    	if(strlen($url) > 5 && strtolower(substr($url,0,5)) == "https" ) {
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    	}
    	$header = array ();
    	$header [] = 'Content-Type:application/x-www-form-urlencoded';
    	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    
    	if (is_array($postFields) && 0 < count($postFields))
    	{
    		$postBodyString = "";
    		$postMultipart = false;
    		foreach ($postFields as $k => $v)
    		{
    			if("@" != substr($v, 0, 1))//判断是不是文件上传
    			{
    				$postBodyString .= "$k=" . urlencode($v) . "&";
    			}
    			else//文件上传用multipart/form-data，否则用www-form-urlencoded
    			{
    				$postMultipart = true;
    			}
    		}
    		unset($k, $v);
    		curl_setopt($ch, CURLOPT_POST, true);
    		if ($postMultipart)
    		{
    			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
    		}
    		else
    		{
    			curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
    		}
    	}
    	$reponse = curl_exec($ch);
    
    	if (curl_errno($ch))
    	{
    		throw new Exception(curl_error($ch),0);
    	}
    	else
    	{
    		$httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    		if (200 !== $httpStatusCode)
    		{
    			throw new Exception($reponse,$httpStatusCode);
    		}
    	}
    	curl_close($ch);
    	return $reponse;
    }
    
    /**
     * 对变量进行 JSON 编码
     * @param mixed value 待编码的 value ，除了resource 类型之外，可以为任何数据类型，该函数只能接受 UTF-8 编码的数据
     * @return string 返回 value 值的 JSON 形式
     */
    function json_encode_ex($value)
    {
    	if (version_compare(PHP_VERSION,'5.4.0','<'))
    	{
    		$str = json_encode($value);
    		$str = preg_replace_callback(
    				"#\\\u([0-9a-f]{4})#i",
    				function($matchs)
    				{
    					return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
    				},
    				$str
    		);
    		return $str;
    	}
    	else
    	{
    		return json_encode($value, JSON_UNESCAPED_UNICODE);
    	}
    }
    
}