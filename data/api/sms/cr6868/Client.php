<?php
final class Client {
	/**
	 * 
	 * 初始化接口类
	 * @param int $userid 用户id
	 * @param int $productid 产品id
	 * @param string $sms_key 密钥
	 */
	public function __construct() {
		
	}
	
	public function checkmobile($mobilephone) {
		$mobilephone = trim($mobilephone);
		if(CheckMobileValidator($mobilephone)){
			return  $mobilephone;
		} else {
			return false;
		}
	}    	
	/**
	 * 
	 * 批量发送短信
	 * @param array $mobile 手机号码
	 * @param string $content 短信内容
	 * @param datetime $send_time 发送时间
	 * @param string $charset 短信字符类型 gbk / utf-8
	 * @param string $id_code 唯一值 、可用于验证码
	 */
	public function sendSms($mobile,$content,$send_time='') {	
		
		//短信发送状态
		if(is_array($mobile)){
			$mobile = implode(",", $mobile);
		}
		
		$content = Client::_safe_replace($content);		
		//创瑞短信平台
		//$account = '0000';
		//$pwd = '000000000D';
        //$smsapi_senturl = 'http://web.cr6868.com/asmx/smsservice.aspx';
		//一信息平台
		//wyg1
		//CEE4D6CC34577FB24D1726F8AFEB
		$smsapi_senturl = C('sms_gwUrl');//'http://sms.1xinxi.cn/asmx/smsservice.aspx';
		$data = array(
	        'name'    => C('sms_username'),
			'pwd'     => C('sms_pwd'),
			'content' => $content,
			'mobile'  => $mobile,			
			'stime'   => $send_time!=''?date("Y-m-d H:i:s",$send_time):'',
			'sign'    => '签名',
			'type'    => 'pt',
			'extno'   => '',			
		);	
		
		//拇指短信平台
		//账户：hzc106
        //密码：456456 
        //id：9555
		/*$smsapi_senturl = 'http://www.qf106.com/sms.aspx?action=send';
		$data = array(
	        'userid'  => C('sms_key'),
			'account' => C('sms_username'),
			'password'=> C('sms_pwd'),
			'content' => $content,
			'mobile'  => '13974554946', //$mobile,			
			'stime'   => $send_time!=''?date("Y-m-d H:i:s",$send_time):'',	
		);	*/	
		//小猪平台
		//$smsapi_senturl = 'http://up.pigcms.cn/oa/admin.php?m=sms&c=sms&a=send';
            
		$post = '';
		foreach($data as $k=>$v) {
			$post .= rawurlencode($k).'='.rawurlencode($v).'&';
		}
		$post_data=substr($post,0,-1);
		
		//小猪平台
		//$return = Client::_post($smsapi_senturl, 0, $post_data); 
		//创瑞短信平台
		//一信息平台
			
		$return = $this->postSMS($smsapi_senturl, $post_data); 
			
		//拇指短信平		
		//$return = Client::CurlPost($smsapi_senturl, $post_data);				
		if ($return==0){
			return true;
		}else{
			return false;
		}
	}
		
	/**
    * POST提交短信数据
    */
    private function postSMS($url,$post_data=''){
		
	    $row = parse_url($url);				
	    $host = $row['host'];		
	    $port = !empty($row['port']) ? $row['port']:80;			
	    $file = $row['path'];
		
	    $len = strlen($post_data);		
	    $fp = @fsockopen($host ,$port, $errno, $errstr, 10);
		
	    if (!$fp) {
		    return "$errstr ($errno)\n";
	    } else {
		    $receive = '';
		    $out = "POST $file HTTP/1.1\r\n";
		    $out .= "Host: $host\r\n";
		    $out .= "Content-type: application/x-www-form-urlencoded\r\n";
		    $out .= "Connection: Close\r\n";
		    $out .= "Content-Length: $len\r\n\r\n";
		    $out .= $post_data;
		    fwrite($fp, $out);
		    while (!feof($fp)) {
			    $receive .= fgets($fp, 128);
		    }
		    fclose($fp);
		    $receive = explode("\r\n\r\n",$receive);
		    unset($receive[0]);
			
		    $return=implode("",$receive);
			$arr=explode(",",$return);			
			return $arr[0];
	    }
    }
	
	/**
    * POST提交短信数据
    */
    private function CurlPost($url,$post_data=''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        //curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。		
        $result = curl_exec($ch);
		return $result;
    }	
	/**
	 *  post数据
	 *  @param string $url		post的url
	 *  @param int $limit		返回的数据的长度
	 *  @param string $post		post数据，字符串形式username='dalarge'&password='123456'
	 *  @param string $cookie	模拟 cookie，字符串形式username='dalarge'&password='123456'
	 *  @param string $ip		ip地址
	 *  @param int $timeout		连接超时时间
	 *  @param bool $block		是否为阻塞模式
	 *  @return string			返回字符串
	 */
	
	private function _post($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 15, $block = true) {
		$return = '';
		$url=str_replace('&amp;','&',$url);
		$matches = parse_url($url);
		$host = $matches['host'];
		$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
		$port = !empty($matches['port']) ? $matches['port'] : 80;
		$siteurl = Client::_get_url();
		if($post) {
			$out = "POST $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: ".$siteurl."\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n" ;
			$out .= 'Content-Length: '.strlen($post)."\r\n" ;
			$out .= "Connection: Close\r\n" ;
			$out .= "Cache-Control: no-cache\r\n" ;
			$out .= "Cookie: $cookie\r\n\r\n" ;
			$out .= $post ;
		} else {
			$out = "GET $path HTTP/1.1\r\n";
			$out .= "Accept: */*\r\n";
			$out .= "Referer: ".$siteurl."\r\n";
			$out .= "Accept-Language: zh-cn\r\n";
			$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Cookie: $cookie\r\n\r\n";
		}
		$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
		if(!$fp) return '';
		
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
	
		if($status['timed_out']) return '';	
		while (!feof($fp)) {
			if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n"))  break;				
		}
		
		$stop = false;
		while(!feof($fp) && !$stop) {
			$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
			$return .= $data;
			if($limit) {
				$limit -= strlen($data);
				$stop = $limit <= 0;
			}
		}
		@fclose($fp);
		//var_export($return);
		//exit();		
		//部分虚拟主机返回数值有误，暂不确定原因，过滤返回数据格式
		$return_arr = explode("\n", $return);
		if(isset($return_arr[1])) {
			$return = trim($return_arr[1]);
		}
		unset($return_arr);
		
		//$arr = explode('#',$return);
		//$this->statuscode = $arr[0];
		return $return;
	}

	/**
	 * 获取当前页面完整URL地址
	 */
	private function _get_url() {
		$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
		$php_self = $_SERVER['PHP_SELF'] ? Client::_safe_replace($_SERVER['PHP_SELF']) : Client::_safe_replace($_SERVER['SCRIPT_NAME']);
		$path_info = isset($_SERVER['PATH_INFO']) ? Client::_safe_replace($_SERVER['PATH_INFO']) : '';
		$relate_url = isset($_SERVER['REQUEST_URI']) ? Client::_safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.Client::_safe_replace($_SERVER['QUERY_STRING']) : $path_info);
		return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
	}
	
	/**
	 * 安全过滤函数
	 *
	 * @param $string
	 * @return string
	 */
	private function _safe_replace($string) {
		$string = str_replace('%20','',$string);
		$string = str_replace('%27','',$string);
		$string = str_replace('%2527','',$string);
		$string = str_replace('*','',$string);
		$string = str_replace('"','&quot;',$string);
		$string = str_replace("'",'',$string);
		$string = str_replace('"','',$string);
		$string = str_replace(';','',$string);
		$string = str_replace('<','&lt;',$string);
		$string = str_replace('>','&gt;',$string);
		$string = str_replace("{",'',$string);
		$string = str_replace('}','',$string);
		$string = str_replace('\\','',$string);
		return $string;
	}
}
?>