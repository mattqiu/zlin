<?php
/**
 * 前台登录 退出操作
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class loginControl extends wxappHomeControl {

	public function __construct(){
		parent::__construct();
		$this->appId = WX_APIID;
		$this->appSecret = WX_APPSECRET;
	}

	/**
	 * 导购端登录
	 */
	public function sellerOp(){
		
	$this->code = $_POST['code'];
	if (empty($this->code)){
            output_error('获取微信小程序CODE失败，请清除微信缓存后再打开小程序');
        }
        $url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$this->appId."&secret=".$this->appSecret."&js_code=".$this->code."&grant_type=authorization_code";
        $res =json_decode($this->httpGet($url), true);
        $openid = $res['openid'];
        $client = 'wxapp'; //微信小程序
        /*
         * 思路：首先去登录令牌中查找 是否有微信小程序的登录记录，没有就根据 unionId（微信会员再所有公众号中的唯一识别码） 去找出该会员
         * 如果有 则可以直接登录
         */
        //获取该员工登录店铺令牌
        $model_mb_seller_token = Model('mb_seller_token');        
        $mb_seller_token_info = $model_mb_seller_token->getSellerTokenInfo(array('openid'=>$openid, 'client_type'=>$client));
        //查看商家是否有登录记录
        if(empty($mb_seller_token_info)){
        	//没有则去查看该店员又没会员的登录记录
        	$model_mb_user_token = Model('mb_user_token');
        	$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfo(array('openid'=>$openid, 'client'=>$client));
        	if(!empty($mb_user_token_info)){
        		//有会员登录记录，则根据会员的记录给店员添加一条商家的登录记录
        		//根据会员的登录信息，确定默认登陆的商家
        		$this->login_seller_token($mb_user_token_info['token'], $openid, $mb_user_token_info['member_id'], $client);
        	}else{
        		//都未登录的情况下，还有一种可能性。就是通过公众号等信息登录过。那么就要通过曾今获取到每个微信特有的unionId
        		//先获取到unionId
        		$encryptedData = urldecode($_POST['encryptedData']);
        		$iv = $_POST['iv'];
        		$inc_file = BASE_PATH.DS.'api'.DS.'wx'.DS.'wxBizDataCrypt.php';
        		if(!is_file($inc_file)){
        			output_error('微信校验接口不存在');
        		}
        		require($inc_file);
        		$pc = new WXBizDataCrypt($this->appId, $res['session_key']);
        		$errCode = $pc->decryptData($encryptedData, $iv, $data);
        		//file_put_contents('encrypte.txt',$data,FILE_APPEND);
        		if ($errCode == 0) {
        			$result = json_decode($data, true);//转换成数组
        			$unionId = $result['unionId'];
        		} else {
        			output_error('微信校验会员信息失败，原因：'.$errCode);
        		}
        		//根据获取到的unionId，去查找会员信息
        		$unionid = 's:7:"unionid";s:'.strlen($unionId).':"'.$unionId.'";';//拼接模糊查询的格式
        		/*
        		 * 参考mysql json格式查询：http://www.cnblogs.com/waterystone/p/5626098.html
        		 * 下面写一个mysql5.7.9以上版本支持json 查询
        		 * SELECT json_extract(member_wxinfo, '$.unionid') FROM zlin_member WHERE json_extract(member_wxinfo,'$.unionid') = 'o6AiRuOwBKVqa-DXfNQsNKk_50UA';
        		 */
        		$condition =array();
        		$condition['member_wxinfo'] = array(array('like','%'.$unionid.'%'));
        		$model_member = Model('member');
        		$member_info = $model_member->getMemberInfo($condition);
        		if(!empty($member_info)){
        			//这里证明一点，再wxapp上没有留下过记录，所以要去新增一条记录
        			//新登录生成token
        			$token = $this->login_mobile_token($member_info['member_id'], $member_info['member_name'], $client,$openid);
        		}else{
        			//没有获取到会员信息，则证明该微信账号并未再平台上注册登记过
        			output_error('抱歉该微信并未绑定平台的账号，目前是无法登录成功！');
        		}        		
        	}
        }else{
        	$token = $mb_seller_token_info['token'];
		
        }        
        if($token) {
			echo $token;
			die;
        } else {
        	output_error('未获取到正确的登录令牌，请先删除小程序后重新添加即可！');
        }       
    }
    /**
     * 登录生成token
     */
    public function login_mobile_token($member_id, $member_name, $client, $openid) {
    	if(empty($member_id)) {
    		return null;
    	}
    	$model_mb_user_token = Model('mb_user_token');
    	$condition = array();
    	//生成新的token
    	$mb_user_token_info = array();
    	$token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
    	$mb_user_token_info['member_id'] = $condition['member_id'] = $member_id;
    	$mb_user_token_info['member_name'] = $member_name;
    	$mb_user_token_info['token'] = $token;
    	if(!empty($openid)){
    		$mb_user_token_info['openid'] = $openid;
    	}
    	$mb_user_token_info['login_time'] = TIMESTAMP;
    	$mb_user_token_info['client_type'] = $condition['client_type'] = $client;
    	//重新登录后以前的令牌失效
    	//只允许单人登录,清除之前登录的信息
    	$model_mb_user_token->delMbUserToken($condition);
    	//会员登陆的同时也登陆商家
    	$this->login_seller_token($token, $openid, $member_id, $client);
    	$result = $model_mb_user_token->addMbUserToken($mb_user_token_info);
    	if($result) {
    		return $token;
    	} else {
    		return null;
    	}
    }
    /**
     * 登录商家店铺
     * @token 登录令牌
     * @openid 微信用户授权ID
     * @member_id 对应会员的ID
     * @client 登录的端口
     */
    public function login_seller_token($token, $openid, $member_id, $client='wxapp') {    	 
    	//会员登陆的同时也登陆商家
    	$model_seller = Model('seller');
    	$seller_list = $model_seller->getSellerList(array('member_id' => $member_id));
    	if(!empty($seller_list)){
    		if(count($seller_list)>1){
    			foreach ($seller_list as $skey=>$seller){
    				if($seller['is_default']){//默认登录的商家
    					$seller_info = $seller_list[$skey];
    				}else{
    					$seller_info = $seller_list[0];
    				}
    			}
    		}else{
    			$seller_info = $seller_list[0];
    		}
    		$mb_seller_token_info = array();
    		$model_mb_seller_token = Model('mb_seller_token');
    		//重新登录后以前的令牌失效 商家由于多人登录所以不可以让其他人的令牌失效,
    		//失效本人的即可，所有的店铺都需要失效
    		$sli_condition = array();
    		$sli_condition['client_type'] = $client;
    		if(empty($token)){
    			$token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
    		}else{
    			$sli_condition['token'] = $token;
    		}
    		if(!empty($openid)){
    			$sli_condition['openid'] = $mb_seller_token_info['openid'] = $openid;
    		}else{
    			$sli_condition['seller_id'] = $seller_info['seller_id'];
    		}
    		$model_mb_seller_token->delSellerToken($sli_condition);
    		
    		$mb_seller_token_info['seller_id'] = $seller_info['seller_id'];
    		$mb_seller_token_info['seller_name'] = $seller_info['seller_name'];
    		$mb_seller_token_info['token'] = $token;
    		$mb_seller_token_info['login_time'] = TIMESTAMP;
    		$mb_seller_token_info['client_type'] = $client;
    		$model_mb_seller_token->addSellerToken($mb_seller_token_info);
    	}else{
    		//没有绑定过平台会员信息，则可能是造假身份
    		output_error('抱歉该微信未绑定平台前台的账号，无法登录成功！');
    	}
    }
    /**
     * 会员端登录
     */
    public function indexOp(){
    	$this->code = $_POST['code'];
    	if (empty($this->code)){
    		output_error('获取微信小程序CODE失败，请清除微信缓存后再打开小程序');
    	}
    	$url = "https://api.weixin.qq.com/sns/jscode2session?appid=".$this->appId."&secret=".$this->appSecret."&js_code=".$this->code."&grant_type=authorization_code";
    	$res =json_decode($this->httpGet($url), true);
    	$openid = $res['openid'];
    	$client = 'wxapp'; //微信小程序
        $model_member = Model('member');
        /*
         * 思路：首先去登录令牌中查找 是否有微信小程序的登录记录，没有就根据 unionId（微信会员再所有公众号中的唯一识别码） 去找出该会员
         * 	如果有 则可以直接登录
         */
        $model_mb_user_token = Model('mb_user_token');
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfo(array('openid'=>$openid, 'client'=>$client));
        
	if(empty($mb_user_token_info)){
        	//没有，先获取到unionId
        $encryptedData = urldecode($_POST['encryptedData']);
        $iv = $_POST['iv'];
	$inc_file = BASE_PATH.DS.'api'.DS.'wx'.DS.'wxBizDataCrypt.php';
        if(!is_file($inc_file)){
            output_error('微信校验接口不存在');
        }
        require($inc_file);
	$pc = new WXBizDataCrypt($this->appId, $res['session_key']);
	$errCode = $pc->decryptData($encryptedData, $iv, $data);
        	//file_put_contents('encrypte.txt',$data,FILE_APPEND);
        	if ($errCode == 0) {
		$result = json_decode($data, true);//转换成数组
        		$unionId = $result['unionId'];
        	} else {
        		output_error('微信校验会员信息失败，原因：'.$errCode);
        	}
        	//根据获取到的unionId，去查找会员信息
        	$unionid = 's:7:"unionid";s:'.strlen($unionId).':"'.$unionId.'";';//拼接模糊查询的格式
        	/*
        	 * 参考mysql json格式查询：http://www.cnblogs.com/waterystone/p/5626098.html
        	 * 下面写一个mysql5.7.9以上版本支持json 查询
        	 * SELECT json_extract(member_wxinfo, '$.unionid') FROM zlin_member WHERE json_extract(member_wxinfo,'$.unionid') = 'o6AiRuOwBKVqa-DXfNQsNKk_50UA';
        	 */
        	$condition =array();
        	$condition['member_wxinfo'] = array(array('like','%'.$unionid.'%'));        	
        	$member_info = $model_member->getMemberInfo($condition);
        	if(!empty($member_info)){
        		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfo(array('member_id'=>$member_info['member_id'], 'client'=>$client));
	        	if (empty($mb_user_token_info)){
        	//新登录生成token
        	$token = $this->login_mobile_token($member_info['member_id'], $member_info['member_name'], $client,$openid);
	        	}else{
	        		$token = $mb_user_token_info['token'];
	        	}
	        }
        }else{
        	$token = $mb_user_token_info['token'];
        	$member_info = $model_member->getMemberInfo(array('member_id'=> $mb_user_token_info['member_id']));
        }
        
        if(!empty($member_info)){
        	if(!$member_info['member_state']){//1为启用 0 为禁用
        		output_error('你已经被禁止登录，请重新注册会员！');
        	}
        		}else{
        		//没有绑定账号的需要去绑定或者去注册 待完成
        		output_error('该微信并未绑定对应的会员信息');
        	}
        if($token) {
		echo $token;
		die;
        }else{
        	//没有绑定账号的需要去绑定或者去注册 待完成
        	output_error('该微信并未绑定对应的会员信息');
        }        
    }
	
    
	/**
	 * 小程序注册
	 */
	public function registerOp(){
		
		$model_member	= Model('member');

        $register_info = array();
        $register_info['member_name']     = $register_info['member_mobile'] = $_GET['member_mobile']; //根据客户提供的手机号，注册
        $register_info['password_confirm'] = $register_info['member_passwd']    = $this->getRandPass();
        $member_info = $model_member->register($register_info);
        //手机号重复，也有可能会提示错误。暂时不考虑
        //思路是如果客户验证了手机号，那么就去绑定。但要取消之前绑定的手机号,建议是不要去绑定了
        if(!isset($member_info['error'])) {
            $token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'token' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($member_info['error']);
        }

    }
	
    /**
     * 微信绑定登录账户
     */
    public function wx_bind_accountOp(){
    	if(!$this->check()){
			output_error('验证码错误！');
		}
    	$model_member	= Model('member');
    	$where_minfo = array();
    	$where_minfo['member_name|member_mobile'] = $_GET['mobile'];//根据客户提供的手机号，检索出对应的账号
    	$member_info = $model_member->getMemberInfo($where_minfo);
    	if(!empty($member_info)){
    		$getPassword = md5($_GET['password']);//密码为了验证，该账号是否正确
    		if($member_info['member_passwd']!=$getPassword){
    			//对比密码是否输入正确
    			output_error('密码错误！');
    		}
    		if(!empty($member_info['member_wxopenid'])){
    			output_error('账号绑定失败，您提供的账号已被绑定过！');
    		}else{
    			$update_arr = array();
    			$update_arr['member_wxopenid'] = $_GET['wx_openid'];
    			$update_arr['member_wxinfo'] = $_GET['wx_info'];
    			$edit_state	= $model_member->editMember(array('member_id'=>$member_info['member_id']),$update_arr);
    			if($edit_state) {
    				output_data('成功绑定账号：'. $member_info['member_name']);
    			}else{
    				output_error('账号绑定失败！');
    			}
    		}
    	}else{
    		output_error('用户信息不存在，请注册会员！');
    	}
    }
    /**
     * 发短信
     */
    public function send_mobileOp(){
    	$obj_validate = new Validate();
    	$mobile = $_GET["mobile"];
    	$obj_validate->validateparam = array(
    			array("input"=>$mobile, "require"=>"true", 'validator'=>'mobile',"message"=>'请正确填写手机号码'),
    	);
    	$error = $obj_validate->validate();
    	if ($error != ''){
    		output_error($error);
    	}
    	$verify_code = rand(100,999).rand(100,999);
    	$this->makeWXSeccode($verify_code); //存入session
    	$model_tpl = Model('mail_templates');
    	$tpl_info = $model_tpl->getTplInfo(array('code'=>'modify_mobile'));
    	$param = array();
    	$param['site_name'] = C('site_name');
    	$param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
    	$param['verify_code'] = $verify_code;
    	$message    = imReplaceText($tpl_info['content'],$param);
    	$sms = new Sms();
    	$result = $sms->send($mobile,$message);
    	if ($result) {
    		$output['sms_time'] = 60;
    		$output['data'] = $message;
    		output_data($output);
    	} else {
    		output_error('发送失败');
    	}
    }
    /**
     * 验证码存入缓存   
     * @return string
     */
    function makeWXSeccode($seccode){
    	setIMCookie('wxseccode', encrypt(strtoupper($seccode)."\t".(time())."\t",MD5_KEY),3600);
    	return $seccode;
    }
    /**
     * 验证验证码
     *
     * @param string $value 待验证值
     * @return boolean
     */
    function checkWXSeccode($value){
    	list($checkvalue, $checktime, $checkidhash) = explode("\t", decrypt(cookie('wxseccode'),MD5_KEY));
    	$return = $checkvalue == strtoupper($value);
    	if (!$return) setIMCookie('wxseccode','',-3600);
    	return $return;
    }
    /**
     * AJAX验证
     *
     */
    protected function check(){
    	if ($this->checkWXSeccode($_GET['seccode'])){
    		return true;
    	}else{
    		return false;
    	}
    }
	/**-------------------------------------APP客户端---------------------------------------------------**/	
	/**
	 * 登录
	 */
	public function signinOp(){
        if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败',array('error_code'=>CODE_InvalidUsernameOrPassword));
        }
		$model_member = Model('member');

        $array = array();
        $array['member_name|member_mobile']	= $_POST['username'];
        $array['member_passwd']	= md5($_POST['password']);
        $member_info = $model_member->getMemberInfo($array);

        if(!empty($member_info)) {
            $token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
				$model_member->createSession($member_info);				
		
				$user_info = array();
				$user_info['id'] = $member_info['member_id'];
				$user_info['name'] = $member_info['member_name'];
				$rank_info = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
				$user_info['rank_level'] = $rank_info['level'];//会员等级
				$user_info['rank_name'] = $rank_info['level_name'];//会员等级名称
				$user_info['formated_user_money'] = imPriceFormat($member_info['available_predeposit']);
				$user_info['user_bonus_count'] = Model('voucher')->getCurrentAvailableVoucherCount($member_info['member_id']);
				$user_info['vip_points'] = $member_info['member_points'];
				$user_info['collection_num'] = 0;
				
				$user_info['mc_id'] = $member_info['mc_id'];
				$user_info['store_id'] = $member_info['store_id'];
				
				$model_order = Model('order');
		        $condition = array();
                $condition['buyer_id'] = $member_info['member_id'];
				
				$order_num = array();
				$order_num['await_pay'] = $model_order->getOrderStateNewCount($condition);
				$order_num['await_ship'] = $model_order->getOrderStatePayCount($condition);
				$order_num['shipped'] = $model_order->getOrderStateSendCount($condition);
				$order_num['finished'] = $model_order->getOrderStateEvalCount($condition);	
				
				$user_info['order_num'] = $order_num;
				
				$session_info = array();
				$session_info['uid'] = $member_info['member_id'];
				$session_info['sid'] = $token;
				
                output_data(array('session' => $session_info, 'user' => $user_info));				
            } else {
                output_error('登录失败',array('error_code'=>CODE_InvalidUsernameOrPassword));
            }
        } else {
            output_error('用户名或密码错误',array('error_code'=>CODE_InvalidUsernameOrPassword));
        }
    }
	
	/**
	 * 微信登录
	 */
	public function signinwxOp(){
        if(empty($_POST['openid']) || empty($_POST['nickname']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败',array('error_code'=>CODE_InvalidUsernameOrPassword));
        }
		$model_member = Model('member');		
		$openid = $_POST['openid'];

        $array = array();
        $array['member_wxopenid']	= $openid;
        $member_info = $model_member->getMemberInfo($array);

        if(!empty($member_info)) {
			if(!$member_info['member_state']){//1为启用 0 为禁用			        
			    output_error('帐户被冻结，请联系管理员！',array('error_code'=>CODE_ProcessFailed));
			}
            $token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
				$model_member->createSession($member_info);				
		
				$user_info = array();
				$user_info['id'] = $member_info['member_id'];
				$user_info['name'] = $member_info['member_name'];
				$rank_info = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
				$user_info['rank_level'] = $rank_info['level'];//会员等级
				$user_info['rank_name'] = $rank_info['level_name'];//会员等级名称
				$user_info['formated_user_money'] = imPriceFormat($member_info['available_predeposit']);
				$user_info['user_bonus_count'] = Model('voucher')->getCurrentAvailableVoucherCount($member_info['member_id']);
				$user_info['vip_points'] = $member_info['member_points'];
				$user_info['collection_num'] = 0;
				
				$user_info['mc_id'] = $member_info['mc_id'];
				$user_info['store_id'] = $member_info['store_id'];
				
				$model_order = Model('order');
		        $condition = array();
                $condition['buyer_id'] = $member_info['member_id'];
				
				$order_num = array();
				$order_num['await_pay'] = $model_order->getOrderStateNewCount($condition);
				$order_num['await_ship'] = $model_order->getOrderStatePayCount($condition);
				$order_num['shipped'] = $model_order->getOrderStateSendCount($condition);
				$order_num['finished'] = $model_order->getOrderStateEvalCount($condition);	
				
				$user_info['order_num'] = $order_num;
				
				$session_info = array();
				$session_info['uid'] = $member_info['member_id'];
				$session_info['sid'] = $token;
				
                output_data(array('session' => $session_info, 'user' => $user_info));				
            } else {
                output_error('登录失败',array('error_code'=>CODE_ProcessFailed));
            }
        } else {
			$user_array	= array();
			$nickname = $_POST['nickname'];
						
			$member_info = $model_member->getMemberInfo(array('member_name'=> $nickname));			
            if(empty($member_info)) {
                $user_array['member_name'] = $nickname;
            } else {
                for ($i = 1;$i < 999;$i++) {
                    $rand += $i;
                    $member_name = $nickname.$rand;
                    $member_info = $model_member->getMemberInfo(array('member_name'=> $member_name));
                    if(empty($member_info)) {//查询为空表示当前会员名可用
                        $user_array['member_name'] = $member_name;
                        break;
                    }
                }
            }			
			$password = rand(100000, 999999);			
			$headimgurl = $_POST['headimgurl'];//用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像）  
			
		    $user_array['member_passwd']	= $password;
			$user_array['password_confirm']	= $password;
		    $user_array['member_wxopenid']	= $openid;
		    $user_array['member_wxinfo']	= serialize(array('openid'=>$openid,'nickname'=>$nickname,'headimgurl'=>$headimgurl));
			
		    $result	= $model_member->register($user_array);
			if(!empty($result['error'])) {
			    output_error($result['error'],array('error_code'=>CODE_ProcessFailed));
		    }
			//更新会员头像
			$headimgurl = substr($headimgurl, 0, -1).'132';
            $avatar = DownloadRemoteImg($headimgurl,BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_".$result['member_id'].".jpg");
			if($avatar) {
				$update_info = array();
				$update_info['member_avatar'] 	= "avatar_".$result['member_id'].".jpg";
    		    $model_member->editMember(array('member_id'=>$result['member_id']),$update_info);   
			}
			//获取会员信息
			$member_info = $model_member->getMemberInfoByID($result['member_id']);
			$client = $_GET['client']?$_GET['client']:$_POST['client'];
			$token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $client);
            if($token) {	
				$model_member->createSession($member_info);	
					
				$user_info = array();
				$user_info['id'] = $member_info['member_id'];
				$user_info['name'] = $member_info['member_name'];
				$rank_info = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
				$user_info['rank_level'] = $rank_info['level'];//会员等级
				$user_info['rank_name'] = $rank_info['level_name'];//会员等级名称
				$user_info['formated_user_money'] = imPriceFormat($member_info['available_predeposit']);
				$user_info['user_bonus_count'] = 0;
				$user_info['vip_points'] = $member_info['member_points'];
				$user_info['collection_num'] = 0;
				
				$user_info['mc_id'] = $member_info['mc_id'];
				$user_info['store_id'] = $member_info['store_id'];				
			
				$order_num = array();
				$order_num['await_pay'] = 0;
				$order_num['await_ship'] = 0;
				$order_num['shipped'] = 0;
				$order_num['finished'] = 0;	
				
				$user_info['order_num'] = $order_num;
				
				$session_info = array();
				$session_info['uid'] = $member_info['member_id'];
				$session_info['sid'] = $token;
				
                output_data(array('session' => $session_info, 'user' => $user_info));	
            } else {
                output_error('登录失败',array('error_code'=>CODE_ProcessFailed));
            }
        }		 
    }
	
	/**
	 * token登录
	 */
	public function signintokenOp(){
		$model_mb_user_token = Model('mb_user_token');
        $token = $_REQUEST['token'];
		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($token);
        if(empty($mb_user_token_info)) {
            output_error('登录失败', array('login' => '0','error_code'=>CODE_InvalidSession));
        }		
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);        
        if(empty($member_info)) {
            output_error('登录失败', array('login' => '0','error_code'=>CODE_InvalidUsernameOrPassword));
        } else {
			$member_info['client_type'] = $mb_user_token_info['client_type'];
            $member_info['openid'] = $mb_user_token_info['openid'];
            $member_info['token'] = $mb_user_token_info['token'];
		    $level_name = $model_member->getOneMemberGrade($mb_user_token_info['member_id']);
			$member_info['level_name'] = $level_name['level_name'];
            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$member_info['member_id']));
            $member_info['myself_store_id'] = $seller_info['store_id'];
			if (intval($_SESSION['is_login']) !=1){
				$model_member->createSession($member_info,true);
			}
			
			$user_info = array();
			$user_info['id'] = $member_info['member_id'];
			$user_info['name'] = $member_info['member_name'];
			$rank_info = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
			$user_info['rank_level'] = $rank_info['level'];//会员等级
			$user_info['rank_name'] = $rank_info['level_name'];//会员等级名称
			$user_info['formated_user_money'] = imPriceFormat($member_info['available_predeposit']);
			$user_info['user_bonus_count'] = Model('voucher')->getCurrentAvailableVoucherCount($member_info['member_id']);
			$user_info['vip_points'] = $member_info['member_points'];
			$user_info['collection_num'] = 0;
				
			$user_info['mc_id'] = $member_info['mc_id'];
			$user_info['store_id'] = $member_info['store_id'];
				
			$model_order = Model('order');
		    $condition = array();
            $condition['buyer_id'] = $member_info['member_id'];
				
		    $order_num = array();
			$order_num['await_pay'] = $model_order->getOrderStateNewCount($condition);
			$order_num['await_ship'] = $model_order->getOrderStatePayCount($condition);
			$order_num['shipped'] = $model_order->getOrderStateSendCount($condition);
			$order_num['finished'] = $model_order->getOrderStateEvalCount($condition);	
				
			$user_info['order_num'] = $order_num;
				
			$session_info = array();
			$session_info['uid'] = $member_info['member_id'];
			$session_info['sid'] = $mb_user_token_info['token'];
				
            output_data(array('session' => $session_info, 'user' => $user_info));
        }	
    }
    		 
    
    /**
     *	商家登陆
     */
    public function seller_loginOp() {
    	
    	if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
    		output_error('商家管理中心登录失败！');
    	}
    	
    	$model_seller = Model('seller');
    	$seller_info = $model_seller->getSellerInfo(array('seller_name' => $_POST['username']));
    	if($seller_info) {
    
    		$model_member = Model('member');
    		$member_info = $model_member->getMemberInfo(
    				array(
    						'member_id' => $seller_info['member_id'],
    						'member_passwd' => md5($_POST['password'])
    				)
    		);
    		if($member_info) {
    			
    			$token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
    			if($token) {
    				$model_member->createSession($member_info);
    			} else {
    				output_error('登录失败');
    			}
    			// 更新卖家登陆时间
    			$model_seller->editSeller(array('last_login_time' => TIMESTAMP), array('seller_id' => $seller_info['seller_id']));
    
    			$model_seller_group = Model('seller_group');
    			$seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $seller_info['seller_group_id']));
    
    			$model_store = Model('store');
    			$store_info = $model_store->getStoreInfoByID($seller_info['store_id']);
    			//会员信息
    			$_SESSION['is_login'] = '1';
    
    			//微信处理
    			if (OPEN_MODULE_WEIXIN_STATE == 1){
    				$_SESSION['weixin_active']  = $member_info['weixin_active'];
    			}
    
    			$_SESSION['M_grade_level'] 	= $model_member->getOneMemberGradeLevel($member_info['member_exppoints']);//会员等级
    			//推广员、导购员处理
    			if (OPEN_STORE_EXTENSION_STATE > 0){
    				$_SESSION['M_mc_id'] 	    = $member_info['mc_id'];//会员类型
    				$_SESSION['M_store_id'] 	= $member_info['store_id'];//会员所在的推广店铺ID
    				//缓存推广员、导购员ID
    				if ($member_info['mc_id'] ==1 || $member_info['mc_id']==2){
    					$extension=urlsafe_b64encode($member_info['member_id']);
    					setIMCookie('iMall_extension',$extension,30*24*60*60);
    				}
    			}
    
    			//店铺信息
    			$_SESSION['S_parent_id']      = $store_info['parent_id'];//上级店铺
    			$_SESSION['S_branch_op']      = intval($store_info['branch_op']); //充许开分店
    			$_SESSION['S_payment_method'] = $store_info['payment_method']; //支付方式
    			$_SESSION['S_extension_op']   = intval($store_info['extension_op']); //充许开分店
    
    			$_SESSION['grade_id'] = $store_info['grade_id'];
    			$_SESSION['seller_id'] = $seller_info['seller_id'];
    			$_SESSION['seller_name'] = $seller_info['seller_name'];
    			$_SESSION['seller_is_admin'] = intval($seller_info['is_admin']);
    			$_SESSION['store_id'] = intval($seller_info['store_id']);//会员自己的店铺ID
    			$_SESSION['goods_edit'] = intval($seller_info['goods_edit']);//商品编辑模式：0:简洁 1：完整
    			$_SESSION['store_name']	= $store_info['store_name'];
    			$_SESSION['is_own_shop'] = (bool) $store_info['is_own_shop'];
    			$_SESSION['bind_all_gc'] = (bool) $store_info['bind_all_gc'];
    			$_SESSION['seller_limits'] = explode(',', $seller_group_info['limits']);
    			if($seller_info['is_admin']) {
    				$_SESSION['seller_group_name'] = '管理员';
    				$_SESSION['seller_smt_limits'] = false;
    			} else {
    				$_SESSION['seller_group_name'] = $seller_group_info['group_name'];
    				$_SESSION['seller_smt_limits'] = explode(',', $seller_group_info['smt_limits']);
    			}
    			if(!$seller_info['last_login_time']) {
    				$seller_info['last_login_time'] = TIMESTAMP;
    			}
    			$_SESSION['seller_last_login_time'] = date('Y-m-d H:i', $seller_info['last_login_time']);
    			//管理员管理菜单及快捷菜单
    			$seller_menu = $this->getSellerMenuList($seller_info['is_admin'], explode(',', $seller_group_info['limits']));
    			$_SESSION['seller_menu'] = $seller_menu['seller_menu'];
    			$_SESSION['seller_function_list'] = $seller_menu['seller_function_list'];
    			if(!empty($seller_info['seller_quicklink'])&&$seller_info['seller_quicklink']!=='') {
    				$quicklink_array = explode(',', $seller_info['seller_quicklink']);
    				foreach ($quicklink_array as $value) {
    					$_SESSION['seller_quicklink'][$value] = $value ;
    				}
    			}
    			output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'token' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));
    		} else {
    			output_error('商家用户名或密码错误！');
    		}
    	} else {
    		output_error('商家用户名错误，该商家不存在！');
    	}
    }
    //随机获取密码
    public function getRandPass($length = 6){
    	$password = '';
    	//将你想要的字符添加到下面字符串中，默认是数字0-9和26个英文字母
    	$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    	$char_len = strlen($chars);
    	for($i=0;$i<$length;$i++){
    		$loop = mt_rand(0, ($char_len-1));
    		//将这个字符串当作一个数组，随机取出一个字符，并循环拼接成你需要的位数
    		$password .= $chars[$loop];
    	}
    	return $password;
    }
    
    //模拟提交并获取返回值
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