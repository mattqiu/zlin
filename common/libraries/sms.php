<?php
/**
 * 手机短信类
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class Sms {
    /**
     * 发送手机短信
     * @param unknown $mobile 手机号
     * @param unknown $content 短信内容
     */
    public function send($mobile,$content,$token='admin',$send_time='', $charset='utf-8',$id_code = '') {
		
		//云通讯短信接口
		$statusCode = $this->_sendYuntongxun($mobile,$content,$send_time);
		
		return $statusCode;
		
		//检查用户余额是否够用		
		//if ($token=='admin'){
		//	$thisUser=array('id'=>0);
		//	$thisWxUser=array('uid'=>0,'token'=>$this->token);
		//}else{
		//	$thisWxUser=Model('weixin_wxuser')->where(array('token'=>Sms::_safe_replace($token)))->find();
		//    $thisUser=Model('weixin_users')->where(array('member_id'=>$thisWxUser['uid']))->find();
		//	if ($thisUser['smscount']<1){
		//		//用户短信余额小于1
		//	}
		//}
		//$companyid=0;
		//if(!(strpos($token,'_') === FALSE)){
		//	$sarr=explode('_',$token);
		//	$token=$sarr[0];
		//	$companyid=intval($sarr[1]);
		//}
		//if (!$mobile){
		//	$companyWhere=array();
		//	$companyWhere['token']=$token;
		//	if ($companyid){
		//		$companyWhere['id']=$companyid;
		//	}
		//	$company=Model('Company')->where($companyWhere)->find();
		//	$mobile=$company['mp'];
		//}		
		//创瑞短信接口
		
        //$statusCode = $this->_sendChuangRui($mobile,$content,$send_time);
		//$statusCode = $this->_sendEmay($mobile,$content);
		//if ($statusCode==true){
			//更新短信使用记录
		    //$row=array('uid'=>$thisUser['member_id'],'token'=>$thisWxUser['token'],'time'=>time(),'mp'=>$mobile,'text'=>$content,'status'=>$this->statuscode,'price'=>C('sms_price'));
		    //Model('weixin_Sms_record')->insert($row);
		    //if (intval($return)==0&&$token!='admin'){
			//    Model('weixin_users')->where(array('member_id'=>$thisWxUser['uid']))->setDec('smscount');
		    //}
		//}else{
			
		//}
		
		
    }

    /**
     * 亿美短信发送接口
     * @param unknown $mobile 手机号
     * @param unknown $content 短信内容
     */
    private function _sendEmay($mobile,$content) {
        set_time_limit(0);
        define('SCRIPT_ROOT',  BASE_DATA_PATH.'/api/sms/emay/');
        require_once SCRIPT_ROOT.'include/Client.php';
        /**
         * 网关地址
         */
        $gwUrl = C('sms_gwUrl');
        /**
         * 序列号,请通过亿美销售人员获取
         */
        $serialNumber = C('sms_key');
        /**
         * 密码,请通过亿美销售人员获取
         */
        $password = C('sms_pwd');
        /**
         * 登录后所持有的SESSION KEY，即可通过login方法时创建
         */
        $sessionKey = C('sms_username');
        /**
         * 连接超时时间，单位为秒
         */
        $connectTimeOut = 2;
        /**
         * 远程信息读取超时时间，单位为秒
         */
        $readTimeOut = 10;
        /**
         $proxyhost		可选，代理服务器地址，默认为 false ,则不使用代理服务器
         $proxyport		可选，代理服务器端口，默认为 false
         $proxyusername	可选，代理服务器用户名，默认为 false
         $proxypassword	可选，代理服务器密码，默认为 false
         */
        $proxyhost = false;
        $proxyport = false;
        $proxyusername = false;
        $proxypassword = false;

        $client = new Client($gwUrl,$serialNumber,$password,$sessionKey,$proxyhost,$proxyport,$proxyusername,$proxypassword,$connectTimeOut,$readTimeOut);
        /**
         * 发送向服务端的编码，如果本页面的编码为GBK，请使用GBK
        */
        $client->setOutgoingEncoding("UTF-8");
        $statusCode = $client->login();
        if ($statusCode!=null && $statusCode=="0") {
        } else {
            //登录失败处理
        //    echo "登录失败,返回:".$statusCode;exit;
        }
        $statusCode = $client->sendSMS(array($mobile),$content);
        if ($statusCode!=null && $statusCode=="0") {
            return true;
        } else {
            return false;
             print_R($statusCode);
             echo "处理状态码:".$statusCode;
        }
    }
	
	/**
     * 创瑞短信发送接口
     * @param unknown $mobile 手机号
     * @param unknown $content 短信内容
     */
    private function _sendChuangRui($mobile,$content,$send_time='') {
        $post_data = array();
        $post_data['name'] = C('sms_username');
        $post_data['pwd'] = C('sms_pwd');
        $post_data['content'] = strip_tags($content); //短信内容需要用urlencode编码下
		$post_data['mobile'] = $mobile;
        $post_data['stime'] = $send_time!=''?date("Y-m-d H:i:s",$send_time):'';
		$post_data['sign'] = C('site_name');
		$post_data['type'] = 'pt';
		$post_data['extno'] = '';
        $smsapi_senturl = C('sms_gwUrl');
        $o='';
        foreach ($post_data as $k=>$v)
        {
            $o.="$k=".urlencode($v).'&';
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL,$smsapi_senturl);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。

        $result = curl_exec($ch);

		$receive = explode("\r\n\r\n",$result);
		unset($receive[0]);
			
		$return=implode("",$receive);
	    $arr=explode(",",$return);
		if ($arr[0]==0){
			return true;
		}else{
			return false;
		}
    }
    
    /**
     * 云通讯短信发送接口
     * @param unknown $mobile 手机号 短信接收端手机号码集合，用英文逗号分开，每批发送的手机号数量不得超过100个
     * @param unknown $content 短信内容
     */
    private function _sendYuntongxun($mobile,$content,$send_time='') {
    	
    	set_time_limit(0);
    	define('YTX_SCRIPT_ROOT',  BASE_DATA_PATH.'/api/sms/yuntongxun/');
    	require_once YTX_SCRIPT_ROOT.'CCPRestSmsSDK.php';
    	//主帐号,对应开官网发者主账号下的 ACCOUNT SID
    	$accountSid= C('sms_username');
    	//主帐号令牌,对应官网开发者主账号下的 AUTH TOKEN
    	$accountToken= C('sms_pwd');
    	//应用Id，在官网应用列表中点击应用，对应应用详情中的APP ID
    	//在开发调试的时候，可以使用官网自动为您分配的测试Demo的APP ID
    	$appId = C('sms_key');
    	//请求地址
    	//沙盒环境（用于应用开发调试）：sandboxapp.cloopen.com
    	//生产环境（用户应用上线使用）：app.cloopen.com
    	$serverIP='app.cloopen.com';    	
    	//请求端口，生产环境和沙盒环境一致
    	$serverPort='8883';    	
    	//REST版本号，在官网文档REST介绍中获得。
    	$softVersion='2013-12-26';
    	
    	$rest = new REST($serverIP,$serverPort,$softVersion);
    	$rest->setAccount($accountSid,$accountToken);
    	$rest->setAppId($appId);
    	// 发送模板短信
    	$datas = array($content,'5');
    	$tempArr = array('107728','107937','107941','107942','107943','107944','107945','107946','107947','107948','107951');
    	$tempKey = array_rand($tempArr); //模板ID
    	$tempId = $tempArr[$tempKey];
    	$result = $rest->sendTemplateSMS($mobile,$datas,$tempId);
    	if($result == NULL ) {
    		//echo "result error!";
    		break;
	    }
    	if($result->statusCode!=0) {
    		//echo "error code :" . $result->statusCode . "<br>";
    		//echo "error msg :" . $result->statusMsg . "<br>";
    		//TODO 添加错误处理逻辑
    		return $result->statusMsg;
    	}else{
    		//echo "Sendind TemplateSMS success!<br/>";
    		// 获取返回信息
    		//$smsmessage = $result->TemplateSMS;
    		//echo "dateCreated:".$smsmessage->dateCreated."<br/>";
    		//echo "smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
    		//TODO 添加成功处理逻辑
    		return true;
    	}
    }
}
