<?php
/**
 * QQ互联登录
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class connect_qqControl extends mobileHomeControl{
	
	public function __construct(){
		parent::__construct();
		/**
		 * 判断qq互联功能是否开启
		 */
		if (C('qq_isuse') != 1){  
			output_error('系统未开启QQ互联功能');
		}
		/**
		 * 初始化测试数据
		 */
		if (!$_SESSION['openid']){
			output_error('系统错误');
		}
	}
	
	/**
	 * 已有用户绑定QQ
	 */
	public function bindqqOp(){
		$model_member	= Model('member');
		//验证QQ账号用户是否已经存在
		$array	= array();
		$array['member_qqopenid']	= $_SESSION['openid'];
		$member_info = $model_member->getMemberInfo($array);
		if (is_array($member_info) && count($member_info)>0){
			unset($_SESSION['openid']);
			output_error('该QQ账号已经绑定其他商城账号,请使用其他QQ账号与本账号绑定');
		}
		//获取qq账号信息
		require_once (BASE_ROOT_PATH.DS.DIR_SHOP.'/api/qq/user/get_user_info.php');
		$qquser_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);
		//更新会员信息
		$update_info = array();
		$update_info['member_qqopenid'] = $_SESSION['openid'];
		$update_info['member_qqinfo']   = serialize($qquser_info);
		//复制头像文件
		/*
		$avatar	= @copy($qquser_info['figureurl_qq_2'],BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_".$_SESSION['member_id'].".jpg");
		if($avatar) {
		    $update_info['member_avatar'] 	= "avatar_".$_SESSION['member_id'].".jpg";
		}
		*/
		$edit_state	= $model_member->editMember(array('member_id'=>$_SESSION['member_id']), $update_info);
		
		if ($edit_state){
			output_data('QQ绑定成功');
		}else {
			output_error('绑定QQ失败');
		}
	}
	
	/**
	 * 绑定qq后自动登录
	 */
	public function autologinOP(){
		//查询是否已经绑定该qq,已经绑定则直接跳转
		$model_member	= Model('member');
		$array	= array();
		$array['member_qqopenid']	= $_SESSION['openid'];
		$member_info = $model_member->getMemberInfo($array);
		if (is_array($member_info) && count($member_info)>0){
			if(!$member_info['member_state']){//1为启用 0 为禁用
			    unset($_SESSION['openid']);
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
		
		//获取qq账号信息
		require_once (BASE_ROOT_PATH.DS.DIR_SHOP.'/api/qq/user/get_user_info.php');
		$qquser_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);
		$_SESSION["qquser_info"] = serialize($qquser_info);
		
		//处理qq账号信息
		$user_name = trim($qquser_info['nickname']);
		$user_passwd = rand(100000, 999999);
			
		$data_info = array();
		$data_info['qquser_info'] = $qquser_info;			
		$data_info['user_name'] = $user_name;
		$data_info['user_passwd'] = $user_passwd;
			
		output_data($data_info);		
	}
	/**
	 * qq绑定新用户
	 */
	public function registerOp(){
		//实例化模型
		$model_member	= Model('member');

		if (empty($_SESSION['openid']) || empty($_SESSION["qquser_info"])){
			output_error('获取QQ信息失败!');
		}
		$member_name = trim($_POST["reg_user_name"]);
		$member_passwd = trim($_POST["reg_password"]);
			
		$check_member_name  = $model_member->getMemberInfo(array('member_name'=>$member_name)); 
		if (!empty($check_member_name)){
			output_error('用户名已注册!');
		}
			
		$user_array	= array();
		$user_array['member_name']		= $member_name;
		$user_array['member_passwd']	= $member_passwd;
		$user_array['password_confirm']	= $member_passwd;
		$user_array['member_qqopenid']	= $_SESSION['openid'];//qq openid
		$user_array['member_qqinfo']	= $_SESSION["qquser_info"];//qq 信息
			
		$result	= $model_member->register($user_array);
		if(!empty($result['error'])) {
			output_error($result['error']);
		}
	    		
		$qquser_info = unserialize($_SESSION["qquser_info"]);			
		$avatar	= DownloadRemoteImg($qquser_info['figureurl_qq_2'],BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_".$result['member_id'].".jpg");
		if($avatar) {
			$update_info = array();
			$update_info['member_avatar'] 	= "avatar_".$result['member_id'].".jpg";
    		$model_member->editMember(array('member_id'=>$result['member_id']),$update_info);   
		}

		$member_info = $model_member->getMemberInfoByID($result['member_id']);
		$client = $_GET['client']?$_GET['client']:$_POST['client'];
		$token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $client);
        if($token) {				
			$model_member->createSession($member_info);	
            output_data(array('userid' => $member_info['member_id'],'username' => $member_info['member_name'], 'key' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));				
        } else {
            output_error('登录失败');
        }			
	}
	
	/**
	 * qq绑定已有用户
	 */
	public function bindingOp(){		
		if (empty($_SESSION['openid']) || empty($_SESSION["qquser_info"])){
			output_error('获取QQ信息失败!');
		}
		//实例化模型
		$model_member	= Model('member');
		
		$member_name = trim($_POST["user_name"]);
		$member_passwd = md5($_POST["password"]);
			
		$array	= array();
		$array['member_name|member_mobile']	= $member_name;
		$array['member_passwd']	= $member_passwd;
		$member_info = $model_member->getMemberInfo($array);
		if (empty($member_info)){
			output_error('用户名或密码错误!');
		}
		if(!$member_info['member_state']){
			output_error("用户被锁，请联系管理员解锁!");
		}
		$edit_state	= $model_member->editMember(array('member_id'=>$member_info['member_id']), array('member_qqopenid'=>$_SESSION['openid'], 'member_qqinfo'=>$_SESSION["qquser_info"]));
		if ($edit_state){
			$client = $_GET['client']?$_GET['client']:$_POST['client'];
			$token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $client);
            if($token) {				
				$model_member->createSession($member_info);	
                output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));				
            } else {
                output_error('登录失败');
            }
		}else {
			output_error('绑定QQ失败');//'绑定QQ失败'
		}	
	}
		
	/**
	 * 更换绑定QQ号码
	 */
	public function changeqqOp(){
		//如果用户已经登录，进入此链接则显示错误
		if($_SESSION['is_login'] == '1') {
			showMessage(Language::get('home_qqconnect_error'),'index.php','html','error');//'系统错误'
		}
		unset($_SESSION['openid']);
		@header('Location:'.SHOP_SITE_URL.'/api.php?act=toqq');
		exit;
	}
}