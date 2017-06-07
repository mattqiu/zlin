<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class loginControl extends mobileHomeControl {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * 登录
	 */
	public function indexOp(){		
        if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败');
        }
		$model_member = Model('member');

        $array = array();
        $array['member_name|member_mobile']	= $_POST['username'];
        $array['member_passwd']	= md5($_POST['password']);
        $member_info = $model_member->getMemberInfo($array);
        if(!empty($member_info)) {

            $token = $this->login_mobile_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            
            if($token) {
				//读取卖家信息
				$seller_info = Model('seller')->getSellerInfo(array('member_id'=>$member_info['member_id']));
				$member_info['myself_store_id'] = $seller_info['store_id'];
				
				$model_member->createSession($member_info);	
                output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'seller_name'=>$seller_info['seller_name'],'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));				
            } else {
                echo output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'seller_name'=>'demo','mc_id'=>1,'store_id'=>$member_info['store_id']));
        		die;
            }
        } else {
             echo output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'seller_name'=>'demo','mc_id'=>1,'store_id'=>$member_info['store_id']));
        		die;
        }
    }

	/**
	 * 注册
	 */
	public function registerOp(){
		$model_member	= Model('member');

        $register_info = array();
        $register_info['member_name']      = $_POST['username'];
        $register_info['member_passwd']    = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['member_email']     = $_POST['email'];
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($member_info['error']);
        }

    }
	
	/**
	 * 获取网站信息
	 */
	public function joininfoOp(){		
		$join_info = array();
        $join_info['site_name'] = C('site_name');
		
		$extension = $_GET['extension'];
		if (!empty($extension)){
		    $extension_id = urlsafe_b64decode($extension);		
		}
	    $extension_info = array();
		if ($extension_id>0){
			setIMCookie('iMall_extension',$extension,3600*24,'/');
			$extension_info = Model('member')->getMemberInfoByID($extension_id,'member_id,member_name,member_truename,member_avatar,member_sex');
		}
		if (!empty($extension_info)){
			$extension_info['member_name'] = (empty($extension_info['member_truename']))?$extension_info['member_name']:$extension_info['member_truename'];
			$extension_info['member_avatar'] = getMemberAvatar($extension_info['member_avatar']);
			if ($extension_info['member_sex'] == 1){
				$extension_info['member_sex'] = '先生';
			}else if($extension_info['member_sex'] == 2){
				$extension_info['member_sex'] = '女士';
			}else{
				$extension_info['member_sex'] = '';
			}
		}
		$join_info['extension_info'] = $extension_info;

		output_data(array('join_info' => $join_info));
    }
	
	/**
	 * 加入推广
	 */
	public function joinOp(){
		$model_member	= Model('member');

        $register_info = array();
        $register_info['member_name']      = $_POST['username'];
        $register_info['member_passwd']    = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['member_mobile']    = $_POST['member_mobile'];
        $extension = $_POST['extension'];
        if (!empty($extension)){
        	$extension_id = urlsafe_b64decode($extension);
        }else{
        	$extension = cookie('iMall_extension');
        	$extension_id = urlsafe_b64decode($extension);
        }
        $register_info['parent_id'] = $extension_id;
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
			$model_member->createSession($member_info,true);
			
            $token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'key' => $token));
                setIMCookie('iMall_extension',$member_info['member_id'],3600*24,'/');
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($member_info['error']);
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
	 * QQ登录
	 */
	public function signinqqOp(){
        if(empty($_POST['openid']) || empty($_POST['nickname']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败',array('error_code'=>CODE_InvalidUsernameOrPassword));
        }
		$model_member = Model('member');		
		$openid = $_POST['openid'];

        $array = array();
        $array['member_qqopenid']	= $openid;
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
		    $user_array['member_qqopenid']	= $openid;
		    $user_array['member_qqinfo']	= serialize(array('openid'=>$openid,'nickname'=>$nickname,'headimgurl'=>$headimgurl));
			
		    $result	= $model_member->register($user_array);
			if(!empty($result['error'])) {
				output_error($result['error'],array('error_code'=>CODE_ProcessFailed));
		    }
			//更新会员头像
			$headimgurl = substr($headimgurl, 0, -1).'132';
            $avatar = DownloadRemoteImg($headimgurl,BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_".$result['member_id']."$result.jpg");
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
	 * key登录
	 */
	public function signinkeyOp(){
		$model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
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
     * 登录生成token
     */
    public function login_mobile_token($member_id, $member_name, $client) {
    	$model_mb_user_token = Model('mb_user_token');
    
    	
    	//生成新的token
    	$mb_user_token_info = array();
    	$token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
    	$mb_user_token_info['member_id'] = $member_id;
    	$mb_user_token_info['member_name'] = $member_name;
    	$mb_user_token_info['token'] = $token;
    	$mb_user_token_info['login_time'] = TIMESTAMP;
    	$mb_user_token_info['client_type'] = $client;
    	//会员登陆的同时也登陆商家
    	$model_seller = Model('seller');
    	$seller_info = $model_seller->getSellerInfo(array('member_id' => $member_id));
    	
    	if(!empty($seller_info)){ //判断该会员是否是商家
    		
    		$model_mb_seller_token = Model('mb_seller_token');
    		//重新登录后以前的令牌失效
    		$sli_condition = array();
    		$sli_condition['seller_id'] = $seller_info['seller_id'];
    		$model_mb_seller_token->delSellerToken($sli_condition);
    		 
    		$mb_seller_token_info = array();
    		$mb_seller_token_info['seller_id'] = $seller_info['seller_id'];
    		$mb_seller_token_info['seller_name'] = $seller_info['seller_name'];
    		$mb_seller_token_info['token'] = $token;
    		$mb_seller_token_info['login_time'] = TIMESTAMP;
    		$mb_seller_token_info['client_type'] = $client;
    		$model_mb_seller_token->addSellerToken($mb_seller_token_info);
    	}
    	
    	$result = $model_mb_user_token->addMbUserToken($mb_user_token_info);
    
    	if($result) {
    		return $token;
    	} else {
    		 
    		return null;
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
    			output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));
    		} else {
    			output_error('商家用户名或密码错误！');
    		}
    	} else {
    		output_error('商家用户名错误，该商家不存在！');
    	}
    }
    
}