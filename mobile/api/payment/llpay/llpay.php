<?php
/* *
 * 功能：连连支付WEB交易接口接入页
 *
 * 
 * @copyright  Copyright (c) 2007-2016 ZhenYang Inc. (http://www.qcmmt.com)
 * @license    http://www.qcmmt.com
 * @link       http://www.qcmmt.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class llpay{
	/**
	 *连连认证支付网关地址
	 */
	var $llpay_gateway_new = 'https://yintong.com.cn/llpayh5/payment.htm';
	
	//支付接口配置信息
    var $llpay_config;
	//订单ID
	var $order_id;
	//异步回调返回参数
	var $notifyResp = array();
	//同步返回结果
	var $pay_result = false;
	//收货信息
	private $receive = array();
	//买家信息
	private $buyer_info = array();

	/**
     * 获取支付接口的请求地址
     *
     * @return string
     */
    public function submit($param = array()){
		if(!empty($param)){
			$this->order_id = $v['order_sn'];		    
		    if ($param['order_type'] == 'r'){
			    $param['order_type'] = '109001';
// 		    }else if($param['order_type'] == 'v'){
// 			    $param['order_type'] = '101001';
// 		    }else if($param['order_type'] == 'p'){
// 			    $param['order_type'] = '108001';
		    }else{
			    $param['order_type'] = '109001';
		    }
			$this->llpay_config = $param;
			//版本号
			$this->llpay_config['version'] = '1.2';
			//请求应用标识 为wap版本，不需修改 
			$this->llpay_config['app_request'] = '3';
			//签名方式 不需修改
			$this->llpay_config['sign_type'] = empty($this->llpay_config['llpay_encrypt'])?strtoupper('RSA'):strtoupper($this->llpay_config['llpay_encrypt']);
			//订单有效时间  分钟为单位，默认为10080分钟（7天） 
			$this->llpay_config['valid_order'] ="10080";
			//字符编码格式 目前支持 gbk 或 utf-8
			$this->llpay_config['input_charset'] = strtolower('utf-8');
			//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
			$this->llpay_config['transport'] = 'http';
		}		

		$risk_item  = '{\"frms_ware_category\":\"4001\"'; //商品类目
		$risk_item .= ',\"user_info_mercht_userno\":\"'.$param['buyer_info']['member_id'].'\"'; //商户用户唯一标识
		$risk_item .= ',\"user_info_bind_phone\":\"'.$param['buyer_info']['member_mobile'].'\"'; //绑定手机号
		$risk_item .= ',\"user_info_dt_register\":\"'.date("YmdHis",$param['buyer_info']['member_time']).'\"'; //注册时间
		$risk_item .= ',\"delivery_addr_province\":\"'.$param['reciver_info']['reciver_province_id'].'\"'; //收货地址省级编码
		$risk_item .= ',\"delivery_addr_city\":\"'.$param['reciver_info']['reciver_city_id'].'\"'; //收货地址市级编码
		$risk_item .= ',\"delivery_phone\":\"'.$param['reciver_info']['receive_mobile'].'\"'; //收货人联系手机
		$risk_item .= ',\"delivery_mode\":\"2\"'; //物流方式
		$risk_item .= ',\"delivery_cycle\":\"72h\"'; //发货时间
		$risk_item .= ',\"risk_state\":\"1\"}';

		//构造要请求的参数数组
    	$this->parameter = array(
	        "oid_partner" => trim($this->llpay_config['llpay_partner']), //商户编号
			"app_request" => trim($this->llpay_config['app_request']), 
	        "sign_type" => $this->llpay_config['sign_type'], //签名方式
	        "valid_order" => $this->llpay_config['valid_order'], //订单有效时间  分钟为单位，默认为10080分钟（7天） 
	        "user_id" => $param['buyer_id'], //用户唯一编号
	        "busi_partner" => $param['order_type'], //商户业务类型 虚拟商品：101001 实物商品：109001 账户充值：108001
	        "no_order" => $param['order_sn'], //商户唯一订单号
	        "dt_order" => $this->local_date('YmdHis', time()), //订单时间
	        "name_goods" => $param['subject'], //商品名称
	        "info_order" => $param['subject'], //订单描述
	        "money_order" => $param['order_amount'], //交易金额
	        "notify_url" => MOBILE_SITE_URL."/api/payment/llpay/notify_url.php",	//异步通知URL
	        "url_return" => MOBILE_SITE_URL."/api/payment/llpay/return_url.php",	//同步通知URL
			"card_no" => '', //卡号 银行卡号前置,卡号可以在商户的页面输入
			"acct_name" => '', //姓名
			"id_no" => '', //身份证号
			"no_agree" => '', //协议号
			"risk_item" => $risk_item //风险控制参数
        );
		$html_text = $this->buildRequestForm($this->parameter, "post", "正在跳转支付页面...");		
        return $html_text;
    }
	
	/**
     * 制作支付接口的请求地址
     *
     * @return string
     */
    private function create_url() {
		$url = $this->llpay_gateway_new;
		//待请求参数数组字符串		
		$arg = $this->buildRequestParaToString($this->parameter);
		$url.= '?'.$arg;
		if (trim($this->llpay_config['input_charset']) != '') {
		    $url = $url."&input_charset=".$this->llpay_config['input_charset'];
	    }
		return $url;
	}
	/*-----------------------------------------------------Submit模块-----------------------------------------------------//
	/**
	 * 建立请求，以模拟远程HTTP的POST请求方式构造并获取连连支付的处理结果
	 * @param $para_temp 请求参数数组
	 * @return 连连支付处理结果
	 */
	public function buildRequestHttp($para_temp) {
		$sResult = '';

		//待请求参数数组字符串
		$request_data = $this->buildRequestPara($para_temp);

		//远程获取数据
		$sResult = $this->getHttpResponsePOST($this->llpay_gateway_new, $this->llpay_config['cacert'], $request_data, trim(strtolower($this->llpay_config['input_charset'])));

		return $sResult;
	}

	
	/**
	 * 建立请求，以表单HTML形式构造（默认）
	 * @param $para_temp 请求参数数组
	 * @param $method 提交方式。两个值可选：post、get
	 * @param $button_name 确认按钮显示文字
	 * @return 提交表单HTML文本
	 */
	public function buildRequestForm($para_temp, $method, $button_name) {			 
		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);
		$sHtml = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html>
				<head>
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
					<title>连连支付即时到账交易接口接口</title>
				</head>';
		$sHtml .= "<form id='llpaysubmit' name='llpaysubmit' action='" . $this->llpay_gateway_new . "' method='" . $method . "'>";
		$sHtml .= "<input type='hidden' name='req_data' value='" . $para . "'/>";
		//submit按钮控件请不要含有name属性
		$sHtml .= "<input type='submit' value='" . $button_name . "'></form>";
		$sHtml .= "<script>document.forms['llpaysubmit'].submit();</script>";	
		$sHtml .= '</body></html>';
		return $sHtml;
	}	

	/**
	 * 建立请求，以模拟远程HTTP的POST请求方式构造并获取连连支付的处理结果，带文件上传功能
	 * @param $para_temp 请求参数数组
	 * @param $file_para_name 文件类型的参数名
	 * @param $file_name 文件完整绝对路径
	 * @return 连连支付返回处理结果
	 */
	public function buildRequestHttpInFile($para_temp, $file_para_name, $file_name) {

		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);
		$para[$file_para_name] = "@" . $file_name;

		//远程获取数据
		$sResult = $this->getHttpResponsePOST($this->llpay_gateway_new, $this->llpay_config['cacert'], $para, trim(strtolower($this->llpay_config['input_charset'])));

		return $sResult;
	}
	
	/**
	 * 生成签名结果
	 * @param $para_sort 已排序要签名的数组
	 * return 签名结果字符串
	 */
	public function buildRequestMysign($para_sort) {
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);
		$mysign = "";
		//PHP5.3 版本以上 风控参数去斜杠
        $prestr =stripslashes($prestr);
        //file_put_contents("log.txt","新的签名:".$prestr."\n", FILE_APPEND);
		
		switch (strtoupper(trim($this->llpay_config['sign_type']))) {
			case "MD5" :
				$mysign = $this->md5Sign($prestr, $this->llpay_config['llpay_md5_key']);
				break;
			case "RSA" :
				$mysign = $this->RsaSign($prestr, $this->llpay_config['llpay_rsa_key']);
				break;
			default :
				$mysign = "";
		}
		//file_put_contents("log.txt","签名:".$mysign."\n", FILE_APPEND);
		return $mysign;
	}

	/**
	 * 生成要请求给连连支付的参数数组
	 * @param $para_temp 请求前的参数数组
	 * @return 要请求的参数数组
	 */
	public function buildRequestPara($para_temp) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter($para_temp);
		//对待签名参数数组排序
		$para_sort = $this->argSort($para_filter);
		//生成签名结果
		$mysign = $this->buildRequestMysign($para_sort);
		//签名结果与签名方式加入请求提交参数组中
		$para_sort['sign'] = $mysign;
		$para_sort['sign_type'] = strtoupper(trim($this->llpay_config['sign_type']));
		foreach ($para_sort as $key => $value) {
			$para_sort[$key] = urlencode($value);
		}
		return urldecode(json_encode($para_sort));
	}

	/**
	 * 生成要请求给连连支付的参数数组
	 * @param $para_temp 请求前的参数数组
	 * @return 要请求的参数数组字符串
	 */
	public function buildRequestParaToString($para_temp) {
		//待请求参数数组
		$para = $this->buildRequestPara($para_temp);
		//把参数组中所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
		$request_data = $this->createLinkstringUrlencode($para);
		return $request_data;
	}
	
	/**
	 * 用于防钓鱼，调用接口query_timestamp来获取时间戳的处理函数
	 * 注意：该功能PHP5环境及以上支持，因此必须服务器、本地电脑中装有支持DOMDocument、SSL的PHP配置环境。建议本地调试时使用PHP开发软件
	 * return 时间戳字符串
	 */
	public function query_timestamp() {
		$url = $this->llpay_gateway_new . "service=query_timestamp&partner=" . trim(strtolower($this->llpay_config['partner'])) . "&_input_charset=" . trim(strtolower($this->llpay_config['input_charset']));
		$encrypt_key = "";

		$doc = new DOMDocument();
		$doc->load($url);
		$itemEncrypt_key = $doc->getElementsByTagName("encrypt_key");
		$encrypt_key = $itemEncrypt_key->item(0)->nodeValue;

		return $encrypt_key;
	}
	/*-----------------------------------------------------CORE模块-----------------------------------------------------//
	/**
    * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
    * @param $para 需要拼接的数组
    * return 拼接完成以后的字符串
    */
    public function createLinkstring($para) {
	    $arg  = "";
	    while (list ($key, $val) = each ($para)) {
		    $arg.=$key."=".$val."&";
	    }
	    //去掉最后一个&字符
	    $arg = substr($arg,0,count($arg)-2);
	    //file_put_contents("log.txt","转义前:".$arg."\n", FILE_APPEND);
	    //如果存在转义字符，那么去掉转义
	    if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	    //file_put_contents("log.txt","转义后:".$arg."\n", FILE_APPEND);
	    return $arg;
    }
    /**
     * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串，并对字符串做urlencode编码
     * @param $para 需要拼接的数组
     * return 拼接完成以后的字符串
    */
    public function createLinkstringUrlencode($para) {
	    $arg  = "";	
		foreach ($para as $key=>$val) {	
		    $arg.=$key."=".urlencode($val)."&";
	    }
	    //去掉最后一个&字符
	    $arg = substr($arg,0,count($arg)-2);
	
	    //如果存在转义字符，那么去掉转义
	    if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
	
	    return $arg;
    }
    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
    */
    public function paraFilter($para) {
	    $para_filter = array();
	    while (list ($key, $val) = each ($para)) {
		    if($key == "sign" || $val == "")continue;
		    else	$para_filter[$key] = $para[$key];
	    }		
	    return $para_filter;
    }
    /**
     * 对数组排序
     * @param $para 排序前的数组
     * return 排序后的数组
    */
    public function argSort($para) {
	    ksort($para);
	    reset($para);
	    return $para;
    }
    /**
     * 写日志，方便测试（看网站需求，也可以改成把记录存入数据库）
     * 注意：服务器需要开通fopen配置
     * @param $word 要写入日志里的文本内容 默认值：空值
    */
    public function logResult($word='') {
	    $fp = fopen("log.txt","a");
	    flock($fp, LOCK_EX) ;
	    fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n");
	    flock($fp, LOCK_UN);
	    fclose($fp);
    }

    /**
     * 远程获取数据，POST模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * @param $para 请求的数据
     * @param $input_charset 编码格式。默认值：空值
     * return 远程输出的数据
     */
    public function getHttpResponsePOST($url, $cacert_url, $para, $input_charset = '') {
	    if (trim($input_charset) != '') {
		    $url = $url."_input_charset=".$input_charset;
	    }
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
	    curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
	    curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
	    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
	    curl_setopt($curl,CURLOPT_POST,true); // post传输数据
	    curl_setopt($curl,CURLOPT_POSTFIELDS,$para);// post传输数据
	    $responseText = curl_exec($curl);
	    //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	    curl_close($curl);
	
	    return $responseText;
    }

    /**
     * 远程获取数据，GET模式
     * 注意：
     * 1.使用Crul需要修改服务器中php.ini文件的设置，找到php_curl.dll去掉前面的";"就行了
     * 2.文件夹中cacert.pem是SSL证书请保证其路径有效，目前默认路径是：getcwd().'\\cacert.pem'
     * @param $url 指定URL完整路径地址
     * @param $cacert_url 指定当前工作目录绝对路径
     * return 远程输出的数据
     */
    public function getHttpResponseGET($url,$cacert_url) {
	    $curl = curl_init($url);
	    curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
	    curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
	    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
	    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
	    curl_setopt($curl, CURLOPT_CAINFO,$cacert_url);//证书地址
	    $responseText = curl_exec($curl);
	    //var_dump( curl_error($curl) );//如果执行curl过程中出现异常，可打开此开关，以便查看异常内容
	    curl_close($curl);
	
	    return $responseText;
    }

    /**
     * 实现多种字符编码方式
     * @param $input 需要编码的字符串
     * @param $_output_charset 输出的编码格式
     * @param $_input_charset 输入的编码格式
     * return 编码后的字符串
     */
    public function charsetEncode($input,$_output_charset ,$_input_charset) {
	    $output = "";
	    if(!isset($_output_charset) )$_output_charset  = $_input_charset;
	    if($_input_charset == $_output_charset || $input ==null ) {
		    $output = $input;
	    } elseif (function_exists("mb_convert_encoding")) {
		    $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
	    } elseif(function_exists("iconv")) {
		    $output = iconv($_input_charset,$_output_charset,$input);
	    } else die("sorry, you have no libs support for charset change.");
	    return $output;
    }
    /**
     * 实现多种字符解码方式
     * @param $input 需要解码的字符串
     * @param $_output_charset 输出的解码格式
     * @param $_input_charset 输入的解码格式
     * return 解码后的字符串
     */
    public function charsetDecode($input,$_input_charset ,$_output_charset) {
	    $output = "";
	    if(!isset($_input_charset) )$_input_charset  = $_input_charset ;
	    if($_input_charset == $_output_charset || $input ==null ) {
		    $output = $input;
	    } elseif (function_exists("mb_convert_encoding")) {
		    $output = mb_convert_encoding($input,$_output_charset,$_input_charset);
	    } elseif(function_exists("iconv")) {
		    $output = iconv($_input_charset,$_output_charset,$input);
	    } else die("sorry, you have no libs support for charset changes.");
	    return $output;
    }

    //格式化时间戳
    public function local_date($format, $time = NULL)
    {
        if ($time === NULL){
            $time = gmtime();
        }elseif ($time <= 0){
            return '';
        }
        return date($format, $time);
    }
	
	public function getJsonVal($json, $k){
	    if(isset($json->{$k})){
		    return trim($json->{$k});
	    }
	    return "";	
    }
	
	/*-----------------------------------------------------MD5模块-----------------------------------------------------//
	/**
     * 签名字符串
     * @param $prestr 需要签名的字符串
     * @param $key 私钥
     * return 签名结果
    */
    public function md5Sign($prestr, $key) {
	    $logstr = $prestr."&key=************************";
	    $prestr = $prestr ."&key=". $key;
	    //file_put_contents("log.txt","签名原串:".$logstr."\n", FILE_APPEND);
	    return md5($prestr);
    }	
    
    /**
     * 验证签名
     * @param $prestr 需要签名的字符串
     * @param $sign 签名结果
     * @param $key 私钥
     * return 签名结果
     */
    public function md5Verify($prestr, $sign, $key) {
	    $logstr = $prestr."&key=************************";
	    $prestr = $prestr ."&key=". $key;
	    //file_put_contents("log.txt","prestr:".$logstr."\n", FILE_APPEND);
	    $mysgin = md5($prestr);
	    //file_put_contents("log.txt","1:".$mysgin."\n", FILE_APPEND);
	    if($mysgin == $sign) {
		    return true;
	    }else {
		    return false;
	    }
    }
	/*-----------------------------------------------------RSA模块-----------------------------------------------------//
	/**RSA签名
     * $data签名数据(需要先排序，然后拼接)
     * 签名用商户私钥，必须是没有经过pkcs8转换的私钥
     * 最后的签名，需要用base64编码
     * return Sign签名
     */
    public function Rsasign($data,$priKey) {
	    //转换为openssl密钥，必须是没有经过pkcs8转换的私钥
        $res = openssl_get_privatekey($priKey);
	    //调用openssl内置签名方法，生成签名$sign
        openssl_sign($data, $sign, $res,OPENSSL_ALGO_MD5);
	    //释放资源
        openssl_free_key($res);    
	    //base64编码
	    $sign = base64_encode($sign);
	    //file_put_contents("log.txt","签名原串:".$data."\n", FILE_APPEND);
        return $sign;
    }

    /**RSA验签
     * $data待签名数据(需要先排序，然后拼接)
     * $sign需要验签的签名,需要base64_decode解码
     * 验签用连连支付公钥
     * return 验签是否通过 bool值
     */
    public function Rsaverify($data, $sign)  {
	    //读取连连支付公钥文件
	    $pubKey = file_get_contents('key/llpay_public_key.pem');
	    //转换为openssl格式密钥
        $res = openssl_get_publickey($pubKey);
	    //调用openssl内置方法验签，返回bool值
        $result = (bool)openssl_verify($data, base64_decode($sign), $res,OPENSSL_ALGO_MD5);	
	    //释放资源
        openssl_free_key($res);
	    //返回资源是否成功
        return $result;
    }
	
	/*-----------------------------------------------------异步返回处理-----------------------------------------------------*/
	function object_to_array($obj)
    {
        $_arr = is_object($obj) ? get_object_vars($obj) : $obj;
        foreach ($_arr as $key => $val)
        {
            $val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
            $arr[$key] = $val;
        }
        return $arr;
    }
	/**
     * 获取return信息
     */
    public function getReturnInfo($payment_config) {
		$res_data = html_entity_decode($_POST["res_data"]);
        $var = json_decode(stripslashes($res_data),true);

		$this->llpay_config = $payment_config;
		//签名方式 不需修改
		$this->llpay_config['sign_type'] = empty($this->llpay_config['llpay_encrypt'])?strtoupper('RSA'):strtoupper($this->llpay_config['llpay_encrypt']);
		
        $verify = $this->return_verify($var);

        if($verify) {
            return array(
                //商户订单号
                'out_trade_no' => $var['no_order'],
                //支付宝交易号
                'trade_no' => $var['oid_paybill'],
            );
        }
        return false;
    }
	
	/**
	 * 针对return_url验证消息是否是连连支付发出的合法消息
	 * @return 验证结果
	 */
	function return_verify($var = array()) {
		$this->pay_result = false;
		if (empty ($var)) { //判断POST来的数组是否为空
			return false;
		} else {
			//首先对获得的商户号进行比对
			if (trim($var['oid_partner' ]) != $this->llpay_config['llpay_partner']) {
				//商户号错误
				return false;
			}

			//生成签名结果
			$parameter = array (
				'oid_partner' => $var['oid_partner' ], //商户编号
				'sign_type' => $var['sign_type'], //签名方式
				'dt_order' => $var['dt_order' ], //商户订单时间
				'no_order' =>  $var['no_order' ], //商户订单号
				'oid_paybill' => $var['oid_paybill' ], //支付单号
				'money_order' => $var['money_order' ], //交易金额
				'result_pay' =>  $var['result_pay'], //支付结果
				'settle_date' => $var['settle_date'], //清算日期
				'info_order' =>$var['info_order'], //订单描述
				'pay_type'=>$var['pay_type'], //支付方式
				'bank_code'=>$var['bank_code'], //银行编号
			);
			if (!$this->getSignVeryfy($parameter, trim(htmlentities($var['sign'])))) {					
				return false;
			}
			//支付结果
	        $result_pay =  $var['result_pay'];
			if($result_pay == 'SUCCESS') {
		        //判断该笔订单是否在商户网站中已经做过处理
		        //如果没有做过处理，根据订单号（no_order）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
		        //如果有做过处理，不执行商户的业务程序
				$this->pay_result = true ;
            }
			return $this->pay_result;
		}
	}
	
	/**
     * 获取notify信息
     */
    public function getNotifyInfo($payment_config) {
		$this->llpay_config = $payment_config;
		//签名方式 不需修改
		$this->llpay_config['sign_type'] = empty($this->llpay_config['llpay_encrypt'])?strtoupper('RSA'):strtoupper($this->llpay_config['llpay_encrypt']);
		
        $verify = $this->notify_verify();

        if($verify) {
            return array(
                //商户订单号
                'out_trade_no' => $_POST['no_order'],
                //支付宝交易号
                'trade_no' => $_POST['oid_paybill'],
            );
        }
        return false;
    }
	
	/**
	 * 针对notify_url验证消息是否是连连支付发出的合法消息
	 * @return 验证结果
	 */
	function notify_verify() {
		//生成签名结果
		$is_notify = true;
		
		$oid_partner = $_POST['oid_partner'];
		$sign_type = $_POST['sign_type'];
		$sign = $_POST['sign'];
		$dt_order = $_POST['dt_order'];
		$no_order = $_POST['no_order'];
		$oid_paybill = $_POST['oid_paybill'];
		$money_order = $_POST['money_order'];
		$result_pay = $_POST['result_pay'];
		$settle_date = $_POST['settle_date'];
		$info_order = $_POST['info_order'];
		$pay_type = $_POST['pay_type'];
		$bank_code = $_POST['bank_code'];
		$no_agree = $_POST['no_agree'];
		$id_type = $_POST['id_type'];
		$id_no = $_POST['id_no'];
		$acct_name = $_POST['acct_name'];
		
		//首先对获得的商户号进行比对
		if ($oid_partner != $this->llpay_config['llpay_partner']) {
			//商户号错误
			return false;
		}
		$parameter = array (
			'oid_partner' => $oid_partner,
			'sign_type' => $sign_type,
			'dt_order' => $dt_order,
			'no_order' => $no_order,
			'oid_paybill' => $oid_paybill,
			'money_order' => $money_order,
			'result_pay' => $result_pay,
			'settle_date' => $settle_date,
			'info_order' => $info_order,
			'pay_type' => $pay_type,
			'bank_code' => $bank_code,
			'no_agree' => $no_agree,
			'id_type' => $id_type,
			'id_no' => $id_no,
			'acct_name' => $acct_name
		);
		if (!$this->getSignVeryfy($parameter, $sign)) {
			return false;
		}
		
		$this->notifyResp = $parameter;
		return true;
	}

	
	
	/**
	 * 
	 * 取得订单支付状态，成功或失败
	 * @param array $param
	 * @return array
	 */
	public function getPayResult($param){
		return $this->pay_result;
	}


	/**
	 * 获取返回时的签名验证结果
	 * @param $para_temp 通知返回来的参数数组
	 * @param $sign 返回的签名结果
	 * @return 签名验证结果
	 */
	function getSignVeryfy($para_temp, $sign) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = $this->paraFilter($para_temp);

		//对待签名参数数组排序
		$para_sort = $this->argSort($para_filter);

		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = $this->createLinkstring($para_sort);

		//file_put_contents("log.txt", "原串:" . $prestr . "\n", FILE_APPEND);
		//file_put_contents("log.txt", "sign:" . $sign . "\n", FILE_APPEND);
		$isSgin = false;
		switch (strtoupper(trim($this->llpay_config['sign_type']))) {
			case "MD5" :
				$isSgin = $this->md5Verify($prestr, $sign, $this->llpay_config['llpay_md5_key']);
				break;
			case "RSA" :
				$isSgin = $this->Rsaverify($prestr, $sign);
				break;
			default :
				$isSgin = false;
		}

		return $isSgin;
	}
	
	function addslashes_deep_obj($obj){
	    if (is_object($obj) == true)
	    {
	        foreach ($obj AS $key => $val)
	        {
	            $obj->$key = $this->addslashes_deep($val);
	        }
	    }
	    else
	    {
	        $obj = $this->addslashes_deep($obj);
	    }
	
	    return $obj;
	}
	
	/**
     * 递归方式的对变量中的特殊字符进行转义
     *
     * @access  public
     * @param   mix     $value
     * array_map 将用户自定义函数作用到数组中的每个值上,并返回用户自定义函数作用后的带有新值的数组
     * @return  mix
     */
    function addslashes_deep($value)
    {
        if (empty($value))
        {
            return $value;
        }
        else
        {
            return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
        }
    }

}
?>