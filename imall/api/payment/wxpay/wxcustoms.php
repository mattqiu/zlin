<?php
/**
 * 微信海关接口类
 *
 * 
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class wxcustoms{
    private $code = 'wxpay';
    private $gatewayUrl = "http://api.mch.tenpay.com/cgi-bin/mch_custom_declare.cgi";
    private $format = "xml";
    private $sign_type = "MD5";    
    private $service_version = "1.0";    
    private $charset = 'GBK';//'utf-8';

    public function __construct() {
		
	}
  	/**
  	 * 海关申报订单接口
  	 */
	function declareorder($params,$mchkey,$paraSign){
		
		$formatPara = $this->formatBizQueryParaMap($params, false);
		$formatParaSignTemp = $formatPara."&key=".$mchkey;
		echo "md5:".$formatParaSignTemp;
		$sign = md5($formatParaSignTemp);
		$params['sign'] = strtoupper($sign);
		print_r($params);
		$paramsUrl = $this->arrayToUrl($params);
		$resp = $this->getGatewayUrl($paramsUrl,$this->gatewayUrl);
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
		var_dump($respObject);
		return $respObject;
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
    
    /**
     *  作用：格式化参数，签名过程需要使用
     */
    function formatBizQueryParaMap($paraMap, $urlencode)
    {
    	$buff = "";
    	ksort($paraMap);
    	foreach ($paraMap as $k => $v)
    	{
    		if(!empty($v)||$v==0){ //不为空的参数参加签名
    		if($urlencode)
    		{
    			$v = urlencode($v);
    		}
    		$buff .= $k . "=" . $v . "&";
    		}
    	}
    	$reqPar = '';
    	if (strlen($buff) > 0)
    	{
    		$reqPar = substr($buff, 0, strlen($buff)-1);
    	}
    	return $reqPar;
    }
    
    /**
     *  作用：生成签名
     */
    public function getSign($Obj)
    {
    	foreach ($Obj as $k => $v)
    	{
    		$Parameters[$k] = $v;
    	}
    	//签名步骤一：按字典序排序参数
    	ksort($Parameters, SORT_STRING);
    	$String = $this->formatBizQueryParaMap($Parameters, false);
    	//签名步骤二：在string后加入KEY
    	$String = $String."&key=".$this->mchkey;
    	//签名步骤三：MD5加密
    	$String = md5($String);
    	//签名步骤四：所有字符转为大写
    	$result_ = strtoupper($String);
    	return $result_;
    }
    /**
     *  作用：将xml转为array
     */
    public function xmlToArray($xml)
    {
    	//将XML转为array
    	$array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    	return $array_data;
    }
    
    
    /**
     *  作用：post请求xml
     */
    function postXml()
    {
    	$xml = $this->createXml();
    	$this->response = $this->postXmlCurl($xml,$this->pay_url,$this->curl_timeout);
    	return $this->response;
    }
    
    /**
     *  作用：设置标配的请求参数，生成签名，生成接口参数xml
     */
    function createXml()
    {
    	$this->parameters["sign"] = $this->getSign($this->parameters);//签名
    	return  $this->arrayToXml($this->parameters);
    }
    
    /**
     * 	作用：array转url
     */
    function arrayToUrl($arr,$urlencode)
    {
    	$url = "";
    	foreach ($arr as $k => $v)
    	{
    		if($urlencode)
    		{
    			$v = urlencode($v);
    		}
    		$url .= $k . "=" . $v . "&";
    	}
    	$reqUrl = '';
    	if (strlen($url) > 0)
    	{
    		$reqUrl = substr($url, 0, strlen($url)-1);
    	}
    	return $reqUrl;
    }
    
    /**
     *  作用：以get方式请求到对应的接口
     */
    public function getGatewayUrl($params,$gatewayUrl)
    {
    	if(strstr($gatewayUrl,"?")){
    		$url = $gatewayUrl.$params;
    	}else{
    		$url = $gatewayUrl."?".$params;
    	}
    	$data = file_get_contents($url);
    	//返回结果
    	if($data)
    	{
    		return $data;
    	}
    	else
    	{
    		return false;
    	}
    }
    /**
     * 	作用：array转xml
     */
    function arrayToXml($arr)
    {
    	$xml = "<xml>";
    	foreach ($arr as $key=>$val)
    	{
    		if (is_numeric($val))
    		{
    			$xml=$xml."<".$key.">".$val."</".$key.">";
    
    		}
    		else
    			$xml=$xml."<".$key."><![CDATA[".$val."]]></".$key.">";
    	}
    	$xml=$xml."</xml>";
    	return $xml;
    }
    
    
    /**
     *  作用：以post方式提交xml到对应的接口url
     */
    public function postXmlCurl($xml,$url,$second=30)
    {       
    	//echo $xml,'-',$url,'-',$second;die;
    	//初始化curl
    
    	$ch = curl_init();
    	//设置超时
    	curl_setopt($ch, CURLOPT_TIMEOUT, $second);
    	//这里设置代理，如果有的话
    	//curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
    	//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    	curl_setopt($ch,CURLOPT_URL, $url);
    	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
    	//设置header
    	curl_setopt($ch, CURLOPT_HEADER, FALSE);
    	//要求结果为字符串且输出到屏幕上
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    	//post提交方式
    	curl_setopt($ch, CURLOPT_POST, TRUE);
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
    	//运行curl
    	$data = curl_exec($ch);
    	curl_close($ch);
    	//返回结果    
    	if($data)
    	{   
	    	//var_dump($data);die('||');
	    	//curl_close($ch);
	    	return $data;
    	}
    	else
    	{
	    	//$error = curl_errno($ch);
	    	//echo "curl出错，错误码:$error"."<br>";
	    	//echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
	    	//curl_close($ch);
	    	//var_dump($error);die;
	    	return false;
    	}
    }
    
    /**
     *  作用：使用证书，以post方式提交xml到对应的接口url
     */
    function postXmlSSLCurl($xml,$url,$second=30)
    {
    	$ch = curl_init();
    	//超时时间
    	curl_setopt($ch,CURLOPT_TIMEOUT,$second);
    	//这里设置代理，如果有的话
    	//curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
    	//curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
    	curl_setopt($ch,CURLOPT_URL, $url);
    	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    	curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,FALSE);
    	//设置header
    	curl_setopt($ch,CURLOPT_HEADER,FALSE);
    	//要求结果为字符串且输出到屏幕上
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
    	//设置证书
    	//使用证书：cert 与 key 分别属于两个.pem文件
    	//默认格式为PEM，可以注释
    	curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
    	curl_setopt($ch,CURLOPT_SSLCERT, WxPayConf_pub::SSLCERT_PATH);
    	//默认格式为PEM，可以注释
    	curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
    	curl_setopt($ch,CURLOPT_SSLKEY, WxPayConf_pub::SSLKEY_PATH);
    	//post提交方式
    	curl_setopt($ch,CURLOPT_POST, true);
    	curl_setopt($ch,CURLOPT_POSTFIELDS,$xml);
    	$data = curl_exec($ch);
    	//返回结果
    	if($data){
    		curl_close($ch);
    		return $data;
    	} else {
    		$error = curl_errno($ch);
    		echo "curl出错，错误码:$error"."<br>";
    		echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
            curl_close($ch);
    		return false;
    	}
    }
    
    /**
    *  作用：产生随机字符串，不长于32位
    */
    function createNoncestr($length = 32)
    {
	    $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
	    $str ="";
	    for ( $i = 0; $i < $length; $i++ )  {
	    	$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);
	    }
    	return $str;
    }
    
    /**
	 * 将xml数据返回微信
     */
  	function returnXml()
	{
		//$returnXml = $this->createXml();
		//return $returnXml;
		$arr = $this->returnParameters;
		$xml = "<xml>";
		foreach ($arr as $key=>$val)
    	{
			if (is_numeric($val)){
    			$xml.="<".$key.">".$val."</".$key.">";
    		}else{
    			$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
    		}
    	}
    	$xml.="</xml>";
    	return $xml;
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
