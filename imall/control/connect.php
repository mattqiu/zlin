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

class connectControl extends BaseHomeControl{
	
	public function __construct(){
		parent::__construct();
		Language::read("home_login_register,home_login_index,home_qqconnect");
		/**
		 * 判断qq互联功能是否开启
		 */
		if (C('qq_isuse') != 1){  
			showMessage(Language::get('home_qqconnect_unavailable'),'index.php','html','error');//'系统未开启QQ互联功能'
		}
		/**
		 * 初始化测试数据
		 */
		if (!$_SESSION['openid']){
			showMessage(Language::get('home_qqconnect_error'),'index.php','html','error'); //'系统错误' 
		}
		
		Tpl::output('hidden_sidetoolbar', 1);
		
		Tpl::setLayout('login_layout');
	}
	/**
	 * 首页
	 */
	public function indexOp(){
		/**
		 * 检查登录状态
		 */
		if($_SESSION['is_login'] == '1') {
			//qq绑定
			$this->bindqqOp();
		}else {
			$this->autologin();
			$this->registerOp();
		}
	}
	/**
	 * qq绑定新用户
	 */
	public function registerOp(){
		//实例化模型
		$model_member	= Model('member');
		if (chksubmit()){
			if (empty($_SESSION['openid']) || empty($_SESSION["qquser_info"])){
				showDialog('获取QQ信息失败!','','error');
			}
			$member_name = trim($_POST["reg_user_name"]);
			$member_passwd = trim($_POST["reg_password"]);
			$member_email = $_POST["reg_email"];
			
			$check_member_name  = $model_member->getMemberInfo(array('member_name'=>$member_name)); 
			if (!empty($check_member_name)){
				showDialog('用户名已注册!','','error');
			}
			
			$user_array	= array();
			$user_array['member_name']		= $member_name;
			$user_array['member_passwd']	= $member_passwd;
			$user_array['member_email']		= $member_email;
			$user_array['member_qqopenid']	= $_SESSION['openid'];//qq openid
			$user_array['member_qqinfo']	= $_SESSION["qquser_info"];//qq 信息
			
			$result	= $model_member->addMember($user_array);
			if($result) {	
			    /*				    		
				$qquser_info = unserialize($_SESSION["qquser_info"]);
				$avatar	= @copy($qquser_info['figureurl_qq_2'],BASE_UPLOAD_PATH.'/'.ATTACH_AVATAR."/avatar_$result.jpg");
				$update_info	= array();
				if($avatar) {
				    $update_info['member_avatar'] 	= "avatar_$result.jpg";
    				$model_member->editMember(array('member_id'=>$result),$update_info);   
    				$user_array['member_avatar'] 	= "avatar_$result.jpg";
				}
				*/
				$user_array['member_id']		= $result;				
				$model_member->createSession($user_array);
				$urlreferer = cookie('callback');
				showDialog('绑定成功！',$urlreferer);
			} else {
				showDialog(Language::get('login_usersave_regist_fail'),SHOP_SITE_URL.'/index.php?act=login&op=register','html','error');//"会员注册失败"
			}			
		}else {
			//检查登录状态
			$model_member->checkloginMember();
			//获取qq账号信息
			require_once (BASE_PATH.'/api/qq/user/get_user_info.php');
			$qquser_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);
			Tpl::output('qquser_info',$qquser_info);
			$_SESSION["qquser_info"] = serialize($qquser_info);

			//处理qq账号信息
			$user_name = trim($qquser_info['nickname']);
			$user_passwd = rand(100000, 999999);
			
			Tpl::output('user_name',$user_name);
			Tpl::output('user_passwd',$user_passwd);
			Tpl::showpage('connect_register');
		}
	}
	
	/**
	 * qq绑定已有用户
	 */
	public function bindingOp(){		
		if (chksubmit()){
			if (empty($_SESSION['openid']) || empty($_SESSION["qquser_info"])){
				showDialog('获取QQ信息失败!','','error');
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
				showDialog('用户名不存在!','','error');
			}
			if(!$member_info['member_state']){
			    showDialog($lang['login_index_account_stop'],''.'error',$script);
			}
			$edit_state		= $model_member->editMember(array('member_id'=>$member_info['member_id']), array('member_qqopenid'=>$_SESSION['openid'], 'member_qqinfo'=>$_SESSION["qquser_info"]));
		    if ($edit_state){
				$model_member->createSession($member_info);
				$urlreferer = cookie('callback');
			    showDialog('绑定成功！',$urlreferer);
		    }else {
			    showDialog(Language::get('home_qqconnect_binding_fail'),'index.php?act=member_connect&op=qqbind','html','error');//'绑定QQ失败'
		    }
		}else{
			showDialog('非法操作!','','error');
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
			showMessage(Language::get('home_qqconnect_binding_exist'),'index.php?act=member_connect&op=qqbind','html','error');//'该QQ账号已经绑定其他商城账号,请使用其他QQ账号与本账号绑定'
		}
		//获取qq账号信息
		require_once (BASE_PATH.'/api/qq/user/get_user_info.php');
		$qquser_info = get_user_info($_SESSION["appid"], $_SESSION["appkey"], $_SESSION["token"], $_SESSION["secret"], $_SESSION["openid"]);
		$edit_state		= $model_member->editMember(array('member_id'=>$_SESSION['member_id']), array('member_qqopenid'=>$_SESSION['openid'], 'member_qqinfo'=>serialize($qquser_info)));
		if ($edit_state){
			showMessage(Language::get('home_qqconnect_binding_success'),'index.php?act=member_connect&op=qqbind');
		}else {
			showMessage(Language::get('home_qqconnect_binding_fail'),'index.php?act=member_connect&op=qqbind','html','error');//'绑定QQ失败'
		}
	}
	/**
	 * 绑定qq后自动登录
	 */
	public function autologin(){
		//查询是否已经绑定该qq,已经绑定则直接跳转
		$model_member	= Model('member');
		$array	= array();
		$array['member_qqopenid']	= $_SESSION['openid'];
		$member_info = $model_member->getMemberInfo($array);
		if (is_array($member_info) && count($member_info)>0){
			if(!$member_info['member_state']){//1为启用 0 为禁用
				showMessage(Language::get('im_notallowed_login'),'','html','error');
			}
			$model_member->createSession($member_info);
			$success_message = Language::get('login_index_login_success');
			$urlreferer = cookie('callback');
			showMessage($success_message,$urlreferer);
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