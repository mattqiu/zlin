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
 
class connect_wxControl extends mobileControl{
	protected $appId = '';
	protected $appSecret = '';
	protected $code = '';
	protected $openid = '';
	
	public function __construct() {
        parent::__construct();
        $agent = $_SERVER['HTTP_USER_AGENT']; 
		if (strpos($agent, "MicroMessenger") && $_GET["act"]=='connect_wx') {	
			$this->appId = C('wx_appid');
			$this->appSecret = C('wx_appkey');			
        }  
    }
	
	public function loginOp(){
	    $redirect_uri = MOBILE_SITE_URL."/index.php?act=connect_wx&op=checkAuth&ref=".$_GET['ref'];//暂时频闭当初为了绑定店铺而设计."&store_id=".$_GET['store_id'];
	    $code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appId."&redirect_uri=".urlencode($redirect_uri)."&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect"; //获取code	
		
		if(!empty($_COOKIE['key']) && !empty($_COOKIE['new_cookie'])){ //已经登陆
			$ref=WAP_SITE_URL;
			$model_mb_user_token = Model('mb_user_token');
			$model_member = Model('member');
			$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($_COOKIE['key']);
			$member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);
			if(empty($member_info)){
				$client = 'wap';
				$token = $model_member->get_mobile_token($mb_user_token_info['member_id'], $mb_user_token_info['member_name'], $client);
				
				setcookie('username',$member_info["member_name"],time()+3600*24,'/');
				setcookie('key',$token,time()+3600*24,'/');
				setcookie('unionid',$token,time()+3600*24,'/');
				setcookie('new_cookie','100',time()+3600*24,'/');
				//推广员、导购员处理
				if (OPEN_STORE_EXTENSION_STATE > 0){
					$_SESSION['M_mc_id'] 	    = $member_info['mc_id'];//会员类型
					$_SESSION['M_store_id'] 	= $member_info['store_id'];//会员所在的推广店铺ID
					//缓存推广员、导购员ID
					if ($member_info['mc_id'] ==1 || $member_info['mc_id']==2){
						$extension=urlsafe_b64encode($member_info['member_id']);
						setIMCookie('iMall_extension',$extension,3600*24,'/');
					}
				}
				if($extension){
					$code_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$this->appId."&redirect_uri=".urlencode($redirect_uri.'&extension='.$extension)."&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect"; //获取code
				}
				header('Location:'.$code_url);exit;
			}			
			header('Location:'.$ref);exit;	
		}else{			
	    		header("location:".$code_url);
		}		
	}	

	public function checkAuthOp(){
		
		/**
		 * code 可以通过微信授权获取
		 * 获取code的方式是  https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect即可
		 * 
		 */
		if (isset($_GET['code'])){				
			$this->code = $_GET['code'];
			$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$this->appId."&secret=".$this->appSecret."&code=".$this->code."&grant_type=authorization_code";  		
			$res =json_decode($this->httpGet($url), true);
			$this->openid = $res['openid']; 		
    		
			$model_member = Model('member');
           	$member_info = $model_member->getMemberInfo(array('member_wxopenid'=> $this->openid));
			if(!empty($member_info)){
				if(!$member_info['member_state']){//1为启用 0 为禁用			        
				    header('Location:'.WAP_SITE_URL.'/tmpl/member/login.html');exit;	
			    }
			    $client = 'wap';
			    $token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $client);
                if($token) {
				    $model_member->createSession($member_info);	
					setcookie('username',$member_info["member_name"],time()+3600*24,'/');
				    setcookie('key',$token,time()+3600*24,'/');
				    setcookie('new_cookie','100',time()+3600*24,'/');
				    //推广员、导购员处理
				    if (OPEN_STORE_EXTENSION_STATE > 0){
				    	$_SESSION['M_mc_id'] 	    = $member_info['mc_id'];//会员类型
				    	$_SESSION['M_store_id'] 	= $member_info['store_id'];//会员所在的推广店铺ID
				    	//缓存推广员、导购员ID
				    	if ($member_info['mc_id'] ==1 || $member_info['mc_id']==2){
				    		$extension=urlsafe_b64encode($member_info['member_id']);
				    		setIMCookie('iMall_extension',$extension,3600*24,'/');
				    	}
				    }
					if (!empty($_GET['ref'])){
						if(strstr($_GET['ref'],"?")){
							$url_extension = '&extension='.$extension;
						}else{
							$url_extension = '?extension='.$extension;
						}
						header('Location:'.$_GET['ref'].$url_extension);exit;
					}else{
						//echo '<script type="text/javascript">history.go(-3);</script>';exit;
					    header('Location:'.WAP_SITE_URL.'/tmpl/member/member.html?key='.$token.'&username='.$member_info['member_name'].$_SERVER["HTTP_REFERER"]);exit;
					}
                } else {
                    header('Location:'.WAP_SITE_URL.'/tmpl/member/login.html');exit;
                }
			}else{
				//去简易注册 
				//$accessToken5 = $this->getAccessToken();	    		
    		    //$url ="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$accessToken5."&openid=".$res['openid']."&lang=zh_CN";	//获取用户信息	
			    $url  ="https://api.weixin.qq.com/sns/userinfo?" . "access_token=" . $res['access_token'] . "&openid=" . $res['openid'] . "&format=json&lang=zh_CN";				    		
    		   	$weixin_info=json_decode($this->httpGet($url), true);
				$_SESSION['openid'] = $this->openid;
				$_SESSION['weixin_info'] = serialize($weixin_info);
				$_SESSION['mstore_id'] = $res['store_id'];
				
				header('Location:'.WAP_SITE_URL.'/tmpl/member/connect_wx.html?ref='.$_GET['ref']);exit;
			}			
		}else{
			header('Location:'.WAP_SITE_URL.'/tmpl/member/login.html');exit;
		}
	}
	
	/**
	 * 绑定微信后自动登录
	 */
	public function autologinOp(){
		if (isset($_SESSION['openid'])){
		    //查询是否已经绑定该微信,已经绑定则直接跳转
		    $model_member	= Model('member');
		    $array	= array();
		    $array['member_wxopenid']	= $_SESSION['openid'];
		    $member_info = $model_member->getMemberInfo($array);
		    if (is_array($member_info) && count($member_info)>0){
			    if(!$member_info['member_state']){//1为启用 0 为禁用
			        unset($_SESSION['openid']);
				    unset($_SESSION['weixin_info']);
				    output_error("用户被锁，请联系管理员解锁!");
			    }
			    $client = $_GET['client']?$_GET['client']:$_POST['client'];
			    $token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $client);
                if($token) {
				    $model_member->createSession($member_info);	
                    output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));				
                } else {
                    output_error('登录失败');
                }
		    }		
		    //获取微信账号信息
		    $weixin_info = unserialize($_SESSION['weixin_info']);
		
		    //处理微信账号信息
		    $user_name = trim($weixin_info['nickname']);
		    $user_passwd = rand(100000, 999999);
			
		    $data_info = array();
		    $data_info['weixin_info'] = $weixin_info;			
		    $data_info['user_name'] = $user_name;
		    $data_info['user_passwd'] = $user_passwd;
		    $data_info['store_id'] = !empty($member_info['store_id'])?$member_info['store_id']:$_GET['store_id'];
		    
		    output_data($data_info);
		}else{
			output_error('非法操作');
		}
	}
	
	public function registerOp($user_info){
		//实例化模型
		$model_member	= Model('member');

		if (empty($_SESSION['openid']) && empty($_SESSION["weixin_info"])){
			output_error('获取微信个人信息失败!');
		}
		$member_name = trim($_POST["reg_user_name"]);
		$member_passwd = trim($_POST["reg_password"]);			
		$mstore_id = trim($_POST["store_id"]);
		$extension = $_POST["extension"];
		$member_mobile = trim($_POST["member_mobile"]);
		if(empty($extension) && C('invite_open')==1){
			output_error('您没有邀请人无法注册!');
		}
		$user_array	= array();
		$user_array['member_name']		= $member_name;
		$user_array['member_passwd']	= $member_passwd;
		$user_array['password_confirm']	= $member_passwd;
		$user_array['member_mobile']	= $member_mobile;
		$user_array['member_wxopenid']	= $_SESSION['openid'];
		$user_array['member_wxinfo']	= $_SESSION["weixin_info"];
		$user_array['store_id']			= $mstore_id;
		$user_array['parent_id'] 		= urlsafe_b64decode($extension);
		
		$result	= $model_member->register($user_array);
		if(!empty($result['error'])) {
			output_error($result['error'],array('error_code'=>CODE_ProcessFailed));
		}
		    		
		$weixin_info = unserialize($_SESSION["weixin_info"]);
		$headimgurl = $weixin_info['headimgurl'];//用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像）
        $headimgurl = substr($headimgurl, 0, -1).'132';
		$avatar	= DownloadRemoteImg($headimgurl,BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_".$result['member_id'].".jpg");			
		if($avatar) {
			$update_info = array();
			$update_info['member_avatar'] 	= "avatar_".$result['member_id'].".jpg";
			$update_info['store_id'] = $mstore_id;
    		$model_member->editMember(array('member_id'=>$result['member_id']),$update_info);   
		}

		$member_info = $model_member->getMemberInfoByID($result['member_id']);
		$client = $_GET['client']?$_GET['client']:$_POST['client'];
		$token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $client);
        if($token) {		
			unset($_SESSION['openid']);
		    unset($_SESSION['weixin_info']);		
			$model_member->createSession($member_info);	
			setcookie('username',$member_name,time()+3600*24,'/');
			setcookie('key',$token,time()+3600*24,'/');
			setcookie('new_cookie','100',time()+3600*24,'/');
			//推广员、导购员处理
			if (OPEN_STORE_EXTENSION_STATE > 0){
				$_SESSION['M_mc_id'] 	    = $member_info['mc_id'];//会员类型
				$_SESSION['M_store_id'] 	= $member_info['store_id'];//会员所在的推广店铺ID
				//缓存推广员、导购员ID
				//if ($member_info['mc_id'] ==1 || $member_info['mc_id']==2){
					$extension=urlsafe_b64encode($member_info['member_id']);
					setIMCookie('iMall_extension',$extension,3600*24,'/');
				//}
			}
            output_data(array('userid' => $member_info['member_id'],'username' => $member_info['member_name'], 'key' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));				
        } else {
            output_error('登录失败');
        }
    }
	
	/**
	 * 微信绑定已有用户
	 */
	public function bindingOp(){		
		if (empty($_SESSION['openid']) && empty($_SESSION["weixin_info"])){
			output_error('获取微信个人信息失败!');
		}
		//实例化模型
		$model_member	= Model('member');
		
		$member_name = trim($_POST["user_name"]);
		$member_passwd = md5($_POST["password"]);
		$mstore_id = trim($_POST["store_id"]);
		$extension = $_POST["extension"];
		$parent_id = urlsafe_b64decode($extension);
		$array	= array();
		$array['member_name|member_mobile']	= $member_name;
		$array['member_passwd']			= $member_passwd;
		
		$member_info = $model_member->getMemberInfo($array);
		if (empty($member_info)){
			output_error('用户名或密码错误!');
		}
		if(!$member_info['member_state']){
			output_error("用户被锁，请联系管理员解锁!");
		}
		/**
		 * 判断会员的上级推广员是否存在，并且推广员的ID不等于会员ID
		 * 存在：不更新上级推广员
		 * 不存在：填充上级推广员
		 */
		if(!empty($member_info['parent_id'])&&$member_info['member_id']!=$parent_id){
			$edit_state	= $model_member->editMember(array('member_id'=>$member_info['member_id']), array('member_wxopenid'=>$_SESSION['openid'], 'member_wxinfo'=>$_SESSION["weixin_info"],'store_id'=> $mstore_id));
		}else{
			$edit_state	= $model_member->editMember(array('member_id'=>$member_info['member_id']), array('member_wxopenid'=>$_SESSION['openid'], 'member_wxinfo'=>$_SESSION["weixin_info"],'store_id'=> $mstore_id,'parent_id'=> $parent_id));
		}
		
		if ($edit_state){
			$client = $_GET['client']?$_GET['client']:$_POST['client'];
			$token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $client);
            if($token) {	
			    unset($_SESSION['openid']);
				unset($_SESSION['weixin_info']);			
				$model_member->createSession($member_info);	
				setcookie('username',$member_name,time()+3600*24,'/');
				setcookie('key',$token,time()+3600*24,'/');
				setcookie('new_cookie','100',time()+3600*24,'/');
				//推广员、导购员处理
				if (OPEN_STORE_EXTENSION_STATE > 0){
					$_SESSION['M_mc_id'] 	    = $member_info['mc_id'];//会员类型
					$_SESSION['M_store_id'] 	= $member_info['store_id'];//会员所在的推广店铺ID
					//缓存推广员、导购员ID
					if ($member_info['mc_id'] ==1 || $member_info['mc_id']==2){
						$extension=urlsafe_b64encode($member_info['member_id']);
						setIMCookie('iMall_extension',$extension,3600*24,'/');
					}
				}
                output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));				
            } else {
                output_error('登录失败');
            }
		}else {
			output_error('绑定QQ失败');//'绑定QQ失败'
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