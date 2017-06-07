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
	 *连连支付网关地址
	 */
	var $llpay_gateway_new = 'https://cashier.lianlianpay.com/payment/bankgateway.htm';
	
	//支付接口配置信息
    var $llpay_config;
    //订单信息
    var $order_info;
	//订单ID
	var $order_id;
	
	public function __construct($payment_info = array(),$order_info = array()){
		if(!empty($payment_info) && !empty($order_info)){	
		    		
			foreach($order_info['order_list'] as $key=>$v){
				$this->order_id = $v['order_id'];
				if ($v['extend_order_common']['reciver_info']){
					$this->receive['receive_name'] = $v['extend_order_common']['reciver_name'];
					$this->receive['receive_address'] = $v['extend_order_common']['reciver_info']['address'];
					$this->receive['receive_zip'] = $v['extend_order_common']['reciver_info']['zip'];
					$this->receive['receive_phone'] = $v['extend_order_common']['reciver_info']['phone'];
					$this->receive['receive_mobile'] = $v['extend_order_common']['reciver_info']['receive_mobile'];					
					break;
				}				
			}			
		    
    	    $this->order_info	  = $order_info;
		    if ($order_info['order_type'] == 'real_order'){
			    $this->order_info['order_type'] = '109001';
		    //}else if($order_info['order_type'] == 'vr_order'){
			//    $this->order_info['order_type'] = '101001';
// 		    }else if($order_info['order_type'] == 'pd_order'){
// 			    $this->order_info['order_type'] = '108001';
		    }else{
		    	 $this->order_info['order_type'] = '109001';
		    }
			$this->llpay_config = $payment_info['payment_config'];
			
			$this->llpay_config['payment_state'] = $payment_info['payment_state'];
			//版本号
			$this->llpay_config['version'] = '1.0';
			//防钓鱼ip 可不传或者传下滑线格式 
			$this->llpay_config['userreq_ip'] = '10_10_246_110';
			//证件类型
			$this->llpay_config['id_type'] = '0';
			//签名方式 不需修改
			$this->llpay_config['sign_type'] = empty($this->llpay_config['llpay_encrypt'])?strtoupper('RSA'):strtoupper($this->llpay_config['llpay_encrypt']);
			//订单有效时间  分钟为单位，默认为10080分钟（7天） 
			$this->llpay_config['valid_order'] ="10080";
			//字符编码格式 目前支持 gbk 或 utf-8
			$this->llpay_config['input_charset'] = strtolower('utf-8');
			//访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
			$this->llpay_config['transport'] = 'http';
		}		
	}
	
	/**
     * 获取支付接口的请求地址
     *
     * @return string
     */
    public function get_payurl(){
		date_default_timezone_set('PRC');
		//构造要请求的参数数组
    	$this->parameter = array(
		    "version" => $this->llpay_config['version'], //版本号
	        "oid_partner" => trim($this->llpay_config['llpay_partner']), //商户编号
	        "sign_type" => $this->llpay_config['sign_type'], //签名方式
	        "userreq_ip" => $this->llpay_config['userreq_ip'], //防钓鱼ip 可不传或者传下滑线格式 
	        "id_type" => $this->llpay_config['id_type'], //证件类型
	        "valid_order" => $this->llpay_config['valid_order'], //订单有效时间  分钟为单位，默认为10080分钟（7天） 
	        "user_id" => $this->order_info['buyer_id'], //用户唯一编号
	        "timestamp" => $this->local_date('YmdHis', time()), //时间戳
	        "busi_partner" => $this->order_info['order_type'], //商户业务类型 虚拟商品：101001 实物商品：109001 账户充值：108001
	        "no_order" => $this->order_info['pay_sn'], //商户唯一订单号
	        "dt_order" => $this->local_date('YmdHis', time()), //订单时间
	        "name_goods" => $this->order_info['subject'], //商品名称
	        "info_order" => $this->order_info['subject'], //订单描述
	        "money_order" => $this->order_info['api_pay_amount'], //交易金额
	        "notify_url" => SHOP_SITE_URL."/api/payment/llpay/notify_url.php",	//异步通知URL
	        "url_return" => SHOP_SITE_URL."/api/payment/llpay/return_url.php",	//同步通知URL
	        "url_order" => SHOP_SITE_URL.'/index.php?act=member_order&op=show_order&order_id='.$this->order_id,	//订单地址
	        "bank_code" => '', //银行网银编号
	        "pay_type" => '', //支付方式
	        "no_agree" => '', //协议号
	        "shareing_data" => '', //分账信息数据
	        "risk_item" => '', //风险控制参数
	        "id_no" => '', //身份证号
	        "acct_name" => '', //姓名
	        "flag_modify" => 0, //修改标记 0-可以修改   1不允许修改  (默认为0)
	        "card_no" => '', //卡号 银行卡号前置,卡号可以在商户的页面输入
	        "back_url" => '' //返回修改信息地址 银行卡卡号前置，需要修改卡号时,用户点击返回的url
        );	
		return $this->create_url();
		
        //建立请求
        //$html_text = $this->buildRequestForm($parameter, "post", "确认");
        //echo $html_text;
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
	
	
	
	/**
	 * 通知地址验证
	 *
	 * @return bool
	 */
	public function notify_verify() {
		$param	= $_POST;
		$param['key']	= $this->payment['payment_config']['alipay_key'];
		$veryfy_url = $this->llpay_gateway_new. "partner=" .$this->payment['payment_config']['alipay_partner']. "&notify_id=".$param["notify_id"];
		$veryfy_result  = $this->getHttpResponse($veryfy_url);
		$mysign = $this->sign($param);
		if (preg_match("/true$/i",$veryfy_result) && $mysign == $param["sign"])  {
			return true;
		} else {
			return false;
		}
	}
	
	/*
     *返回验证
     */
    public function return_verify(){
        //根据交易结果，为pay_result和order_type赋值，并返回true .
	
        if(!empty($this->llpay_config['payment_state']))
        {
            $this->pay_result = true ;
            $this->order_type = $_GET['extra_common_param'] ;
            return true ;   
        }else{
            return false ;  
        }     
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
        //风控值去斜杠
        $para['risk_item'] =stripslashes( $para['risk_item']);
		$sHtml = "<form id='llpaysubmit' name='llpaysubmit' action='" . $this->llpay_gateway_new . "' method='" . $method . "'>";
		$sHtml .= "<input type='hidden' name='version' value='" . $para['version'] . "'/>";
		$sHtml .= "<input type='hidden' name='oid_partner' value='" . $para['oid_partner'] . "'/>";
		$sHtml .= "<input type='hidden' name='user_id' value='" . $para['user_id'] . "'/>";
		$sHtml .= "<input type='hidden' name='timestamp' value='" . $para['timestamp'] . "'/>";
		$sHtml .= "<input type='hidden' name='sign_type' value='" . $para['sign_type'] . "'/>";
		$sHtml .= "<input type='hidden' name='sign' value='" . $para['sign'] . "'/>";
		$sHtml .= "<input type='hidden' name='busi_partner' value='" . $para['busi_partner'] . "'/>";
		$sHtml .= "<input type='hidden' name='no_order' value='" . $para['no_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='dt_order' value='" . $para['dt_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='name_goods' value='" . $para['name_goods'] . "'/>";
		$sHtml .= "<input type='hidden' name='info_order' value='" . $para['info_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='money_order' value='" . $para['money_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='notify_url' value='" . $para['notify_url'] . "'/>";
		$sHtml .= "<input type='hidden' name='url_return' value='" . $para['url_return'] . "'/>";
		$sHtml .= "<input type='hidden' name='userreq_ip' value='" . $para['userreq_ip'] . "'/>";
		$sHtml .= "<input type='hidden' name='url_order' value='" . $para['url_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='valid_order' value='" . $para['valid_order'] . "'/>";
		$sHtml .= "<input type='hidden' name='bank_code' value='" . $para['bank_code'] . "'/>";
		$sHtml .= "<input type='hidden' name='pay_type' value='" . $para['pay_type'] . "'/>";
		$sHtml .= "<input type='hidden' name='no_agree' value='" . $para['no_agree'] . "'/>";
		$sHtml .= "<input type='hidden' name='shareing_data' value='" . $para['shareing_data'] . "'/>";
		$sHtml .= "<input type='hidden' name='risk_item' value='" . $para['risk_item'] . "'/>";
		$sHtml .= "<input type='hidden' name='id_type' value='" . $para['id_type'] . "'/>";
		$sHtml .= "<input type='hidden' name='id_no' value='" . $para['id_no'] . "'/>";
		$sHtml .= "<input type='hidden' name='acct_name' value='" . $para['acct_name'] . "'/>";
		$sHtml .= "<input type='hidden' name='flag_modify' value='" . $para['flag_modify'] . "'/>";
		$sHtml .= "<input type='hidden' name='card_no' value='" . $para['card_no'] . "'/>";
		$sHtml .= "<input type='hidden' name='back_url' value='" . $para['back_url'] . "'/>";
		//submit按钮控件请不要含有name属性
		$sHtml = $sHtml . "<input type='submit' value='" . $button_name . "'></form>";
		$sHtml = $sHtml."<script>document.forms['llpaysubmit'].submit();</script>";
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
			$para_sort[$key] = $value;
		}
		return $para_sort;
		//return urldecode(json_encode($para_sort));
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
}
?>