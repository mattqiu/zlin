<?php
/**
 * 
 *PC端微信支付
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class wxpay{
	private $curl_timeout = 30;
	private $redirect_uri = '/imall/api/payment/wxpay/redirect_uri.php';
	private $sslcert_path ='';
    private $sslkey_path = '';

    private $pay_result;
    private $order_type ;
    private $payment;
    private $order;
	
	private $appid = '';	
    private $secret = '';
	private $mch_id ='';
    private $mchkey = '';
	
	var $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";//接口链接
	
	var $parameters;//请求参数，类型为关联数组
	public $response;//微信返回的响应
	public $result;//返回参数，类型为关联数组
	
    public function __construct($payment_info,$order_info){
    	$this->wxpay($payment_info,$order_info);
    }
    public function wxpay($payment_info = array(),$order_info = array()){
    	if(!empty($payment_info) and !empty($order_info)){
    		$this->payment	= $payment_info;
    		$this->order	= $order_info;
			
			$this->appid  = $this->payment['payment_config']['wxpay_appid'];
		    $this->secret = $this->payment['payment_config']['wxpay_appsecret'];		
            $this->mch_id = $this->payment['payment_config']['wxpay_mchid'];        
            $this->mchkey = $this->payment['payment_config']['wxpay_mchkey'];
			
			$this->redirect_uri = 'http://'.$_SERVER['SERVER_NAME'].$this->redirect_uri;
            $this->notify_url = 'http://'.$_SERVER['SERVER_NAME'].'/imall/api/payment/wxpay/notify_url.php';
            $this->sslcert_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'cert'.DIRECTORY_SEPARATOR.'apiclient_cert.pem';
            $this->sslkey_path = dirname(__FILE__).DIRECTORY_SEPARATOR.'cert'.DIRECTORY_SEPARATOR.'apiclient_key.pem';
    	}
    }
	/**
	 * 获取支付表单
	 *
	 * @param 
	 * @return array
	 */
    public function get_payurl(){
		//echo '111111';  
        //将商品名称 ，商品价格(变为分)以get方式传到下面这个页面里面。。
        //在下面页面里使用这两个变量
        //然后生成二维码
         //print_r($this->order);
         //exit ;
         if($this->order['goods_name'])
         {
             $body = $this->order['goods_name']; 
         }else{
             $body = "积分充值"; 
         }     

         $out_trade_no = $this->order['pay_sn']; 
         $total_fee = ($this->order['api_pay_amount'])*100; 
         //附加数据,这里设置为order_type,分为商品购买和积分充值
         $attach = $this->order['order_type']; 
		 
		 
		 //设置统一支付接口参数
        //设置必填参数
        //appid已填,商户无需重复填写
        //mch_id已填,商户无需重复填写
        //noncestr已填,商户无需重复填写
        //spbill_create_ip已填,商户无需重复填写
        //sign已填,商户无需重复填写

        //获取商品描述，订单号，总金额,附加数据
        $body =  $body;
        $out_trade_no =  $out_trade_no;
        $total_fee =  $total_fee;
        $attach =  $attach;

        $this->parameters['body'] = $this->trimString($body);//商品描述
        $this->parameters['out_trade_no'] = $this->trimString($out_trade_no);//商户订单号 
        $this->parameters['total_fee'] = $this->trimString($total_fee);//总金额
        $this->parameters['notify_url'] = $this->trimString($this->notify_url);//通知地址 
        $this->parameters['trade_type'] = $this->trimString("NATIVE");//交易类型
        $this->parameters['attach'] = $this->trimString($attach);//附加数据

        //非必填参数，商户可根据实际情况选填
        //$this->parameters['sub_mch_id'] = "XXXX";//子商户号  
        //$this->parameters['device_info'] = "XXXX";//设备号 
        //$this->parameters['attach'] = "XXXX";//附加数据 
        //$this->parameters['time_start'] = "XXXX";//交易起始时间
        //$this->parameters['time_expire'] = "XXXX";//交易结束时间 
        //$this->parameters['goods_tag'] = "XXXX";//商品标记 
        //$this->parameters['openid'] = "XXXX";//用户标识
        //$this->parameters['product_id'] = "XXXX";//商品ID
		
		//获取统一支付接口结果
		$this->postXml();		
		$this->result = $this->xmlToArray($this->response);		
		$unifiedOrderResult = $this->result;
        
        //商户根据实际情况设置相应的处理流程
        if ($unifiedOrderResult["return_code"] == "FAIL") 
        {
          //商户自行增加处理流程
          echo "通信出错：".$unifiedOrderResult['return_msg']."<br>";
        }
        elseif($unifiedOrderResult["result_code"] == "FAIL")
        {
          //商户自行增加处理流程
          echo "错误代码：".$unifiedOrderResult['err_code']."<br>";
          echo "错误代码描述：".$unifiedOrderResult['err_code_des']."<br>";
        }elseif($unifiedOrderResult["code_url"] != NULL){
          //从统一支付接口获取到code_url
          $code_url = $unifiedOrderResult["code_url"];
          //商户自行增加处理流程
          //......
        }		 
		 //请求的URL
         $reqUrl = 'http://'.$_SERVER['SERVER_NAME']."/imall/index.php?act=weixinpay&op=index&url=$code_url";
		 return $reqUrl;		
	}

    /*
     *返回验证
     */
    public function return_verify(){
        //根据交易结果，为pay_result和order_type赋值，并返回true .
        if($_GET['result_code']=='SUCCESS')
        {
            $this->pay_result = true ;
            $this->order_type = $_GET['extra_common_param'] ;
            return true ;   
        }else{
            return false ;  
        }     
	}
	
	/**
	 * 取得订单支付状态，成功或失败
	 *
	 * @param array $param
	 * @return array
	 */
	public function getPayResult($param){
	   return $this->pay_result;
	}

    
    public function __get($name){
	    return $this->$name;
    }
	
	/**
	 * 	作用：post请求xml
	 */
	function postXml()
	{
	    $xml = $this->createXml();	
		
		$this->response = $this->postXmlCurl($xml,$this->url,$this->curl_timeout);
		return $this->response;
	}
	
	/**
	 * 	作用：以post方式提交xml到对应的接口url
	 */
	public function postXmlCurl($xml,$url,$second=30)
	{		
        //初始化curl        
       	$ch = curl_init();
		//设置超时
		curl_setopt($ch, CURLOP_TIMEOUT, $second);
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
			curl_close($ch);
			return $data;
		}
		else 
		{ 
			$error = curl_errno($ch);
			echo "curl出错，错误码:$error"."<br>"; 
			echo "<a href='http://curl.haxx.se/libcurl/c/libcurl-errors.html'>错误原因查询</a></br>";
			curl_close($ch);
			return false;
		}
	}
	
	/**
	 * 生成接口参数xml
	 */
	function createXml()
	{
		//检测必填参数
		if($this->parameters["out_trade_no"] == null) 
		{
			echo "缺少统一支付接口必填参数out_trade_no！"."<br>";
		}elseif($this->parameters["body"] == null){
			echo "缺少统一支付接口必填参数body！"."<br>";
		}elseif ($this->parameters["total_fee"] == null ) {
			echo "缺少统一支付接口必填参数total_fee！"."<br>";
		}elseif ($this->parameters["notify_url"] == null) {
			echo "缺少统一支付接口必填参数notify_url！"."<br>";
		}elseif ($this->parameters["trade_type"] == null) {
			echo "缺少统一支付接口必填参数trade_type！"."<br>";
		}elseif ($this->parameters["trade_type"] == "JSAPI" && $this->parameters["openid"] == NULL){
			echo "统一支付接口中，缺少必填参数openid！trade_type为JSAPI时，openid为必填参数！"."<br>";
		}
		$this->parameters["appid"] = $this->appid;//公众账号ID
		$this->parameters["mch_id"] = $this->mch_id;//商户号
		$this->parameters["spbill_create_ip"] = $_SERVER['REMOTE_ADDR'];//终端ip	  		  
		$this->parameters["nonce_str"] = $this->createNoncestr();//随机字符串		
		$this->parameters["sign"] = $this->getSign($this->parameters);//签名		
		return  $this->arrayToXml($this->parameters);		
	}
	
	/**
	 * 	作用：产生随机字符串，不长于32位
	 */
	public function createNoncestr( $length = 32 ) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;
	}
	
	/**
	 * 	作用：生成签名
	 */
	public function getSign($Obj)
	{
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
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
	 * 	作用：格式化参数，签名过程需要使用
	 */
	function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	
	/**
	 * 	作用：将xml转为array
	 */
	public function xmlToArray($xml)
	{		
        //将XML转为array        
        $array_data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);		
		return $array_data;
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
        	 	$xml.="<".$key.">".$val."</".$key.">"; 

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
        }
        $xml.="</xml>";
        return $xml; 
    }
	
	function trimString($value)
	{
		$ret = null;
		if (null != $value) 
		{
			$ret = $value;
			if (strlen($ret) == 0) 
			{
				$ret = null;
			}
		}
		return $ret;
	}
}