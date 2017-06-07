<?php
/**
*微信相关接口功能测试
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
class autoControl extends mobileMemberControl{
	protected $appId = '';
	protected $appSecret = '';
	protected $code = '';
	protected $openid = '';
	
	public function __construct() {
        parent::__construct();
        $agent = $_SERVER['HTTP_USER_AGENT']; 
		if (strpos($agent, "MicroMessenger") && $_GET["act"]=='auto') {	
			$this->appId = C('wx_appid');
			$this->appSecret = C('wx_appkey');			
        }  
    }
	
	public function loginOp(){
		$redirect_uri = MOBILE_SITE_URL."/index.php?act=auto&op=checkAuth&ref=".$_GET['ref'];
	    $code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appId."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect"; // 获取code
		if(!empty($_COOKIE['key']) && !empty($_COOKIE['new_cookie'])){ //已经登陆
			$ref=WAP_SITE_URL;
			$model_mb_user_token = Model('mb_user_token');
			$model_member = Model('member');
			$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($_COOKIE['key']);
			$member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);
			if(empty($member_info)){
				setcookie('username',$member_info["member_name"],time()-3600*24,'/');
				setcookie('key',$token,time()-3600*24,'/');
				setcookie('unionid',$token,time()-3600*24,'/');
				setcookie('new_cookie','100',time()-3600*24,'/');
				header('Location:'.$code_url);exit;
			}			
			header('Location:'.$ref);exit;	
		}else{
	    	header("location:".$code_url);
		}		
	}	

	public function checkAuthOp(){
		$ref = $_GET['ref'];
		if(empty($ref)){
			$ref=WAP_SITE_URL;
		}
		if (isset($_GET['code'])){				
			$this->code = $_GET['code'];
    		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appId."&secret=".$this->appSecret."&code=".$this->code."&grant_type=authorization_code";  		
    		$res =json_decode($this->httpGet($url), true);
    		$this->openid = $res['openid']; 
					
			$_SESSION['openid'] = $res['openid'];	
			$accessToken5 = $res['access_token'];		
    		//$accessToken5 = $this->getAccessToken();	    		
    		//$url ="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$accessToken5."&openid=".$res['openid']."&lang=zh_CN";	//获取用户信息				
			$model_member = Model('member');
            $member_info = $model_member->getMemberInfo(array('member_wxopenid'=> $_SESSION['openid']));
			if(!empty($member_info)){
				if(!$member_info['member_state']){//1为启用 0 为禁用			        
				    output_error("用户被锁，请联系管理员解锁!");
			    }
			    $client = 'wap';
			    $token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $client);
                if($token) {
				    $model_member->createSession($member_info);	
					setcookie('username',$member_info["member_name"],time()+3600*24,'/');
				    setcookie('key',$token,time()+3600*24,'/');
				    setcookie('new_cookie','100',time()+3600*24,'/');
                    output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));				
                } else {
                    output_error('登录失败');
                }
			}else{	
			    $url  ="https://api.weixin.qq.com/sns/userinfo?" . "access_token=" . $accessToken5 . "&openid=" . $res['openid'] . "&format=json&lang=zh_CN";				    		
    		    $res5=json_decode($this->httpGet($url), true);
						
				if($this->register($res5)){
					header('Location:'.$ref);
				}
			}			
		}else{
			header('Location:'.$ref);
		}
	}
	
	private function register($user_info){
        $unionid = $user_info['unionid'];
        $nickname = $user_info['nickname'];
        if(!empty($unionid)) {
            $rand = rand(100, 899);
			if(empty($nickname))$nickname = 'aldgo_'.$rand;
            if(strlen($nickname) < 3) $nickname = $nickname.$rand;
            $member_name = $nickname;
            $model_member = Model('member');
            $member_info = $model_member->getMemberInfo(array('member_name'=> $member_name));
            $password = rand(100000, 999999);
            $member = array();
            $member['member_passwd']    = $password;
			$member['password_confirm'] = $password;
            $member['member_email']     = '';
            $member['weixin_unionid']   = $unionid;
            $member['weixin_info']      = $user_info['weixin_info'];
			
            if(empty($member_info)) {
                $member['member_name'] = $member_name;
                $result = $model_member->register($member);
            } else {
                for ($i = 1;$i < 999;$i++) {
                    $rand += $i;
                    $member_name = $nickname.$rand;
                    $member_info = $model_member->getMemberInfo(array('member_name'=> $member_name));
                    if(empty($member_info)) {//查询为空表示当前会员名可用
                        $member['member_name'] = $member_name;
                        $result = $model_member->register($member);
                        break;
                    }
                }
            }
			if(!empty($result['error'])) {
				return false;
			}
            //用户头像
            $headimgurl = $user_info['headimgurl'];//用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像）
            $headimgurl = substr($headimgurl, 0, -1).'132';
			$member_avatar = BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_".$result['member_id'].".jpg";
			
            $avatar	= DownloadRemoteImg($headimgurl,$member_avatar);	
            if($avatar) {
                $model_member->editMember(array('member_id'=> $result),array('member_avatar'=> "avatar_".$result['member_id'].".jpg"));
            }
			
            $member = $model_member->getMemberInfoByID($result['member_id']);
			if(!empty($member_info)) {
				//$unionid = $member_info['unionid'];
				$token = $model_member->get_mobile_token($result, $member_name, 'wap');
				setcookie('username',$member_name);
				setcookie('key',$token);
				return true;
			} else {
				return false;
			}
        }
    }

	//校验AccessToken 是否可用及返回新的
	private function getAccessToken() {
		$data = json_decode(file_get_contents("../access_token.json"));
		$check_token_url="https://api.weixin.qq.com/sns/auth?access_token=$data->access_token&openid=$this->appId";
		$check_res = json_decode($this->httpGet($check_token_url));		
		if ($data->expire_time < time() || $cike_url->errcode>0) {
			$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
			$res = json_decode($this->httpGet($url));
			$access_token = $res->access_token;
			if ($access_token) {
				$data->expire_time = time() + 6500;
				$data->access_token = $access_token;
				$fp = fopen("../access_token.json", "w");
				fwrite($fp, json_encode($data));
				fclose($fp);
			}
		} else {
			$access_token = $data->access_token;
		}
		return $access_token;
	}

	public function httpGet($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);	
		$res = curl_exec($curl);
		curl_close($curl);	
		return $res;
	}
}