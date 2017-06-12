<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class loginControl extends BaseHomeControl {

	public function __construct(){
		parent::__construct();
		setIMCookie('callback',getReferer());
		Tpl::output('hidden_sidetoolbar', 1);
		
		Tpl::setLayout('login_layout');
	}

	/**
	 * 登录操作
	 *
	 */
	public function indexOp(){
		Language::read("home_login_index");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');
		//检查登录状态
		$model_member->checkloginMember();
		if ($_GET['inajax'] == 1 && C('captcha_status_login') == '1'){
		    $script = "document.getElementById('codeimage').src='".APP_SITE_URL."/index.php?act=seccode&op=makecode&imhash=".getIMhash()."&t=' + Math.random();";
		}
		$result = chksubmit(true,C('captcha_status_login'),'num');
		if ($result !== false){
			if ($result === -11){
				showDialog($lang['login_index_login_illegal'],'','error',$script);
			}elseif ($result === -12){
				showDialog($lang['login_index_wrong_checkcode'],'','error',$script);
			}
			if (process::islock('login')) {
				showDialog($lang['im_common_op_repeat'],SHOP_SITE_URL,'','error',$script);
			}
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["user_name"],		"require"=>"true", "message"=>$lang['login_index_username_isnull']),
				array("input"=>$_POST["password"],		"require"=>"true", "message"=>$lang['login_index_password_isnull']),
			);			
			$error = $obj_validate->validate();
			if ($error != ''){
			    showDialog($error,SHOP_SITE_URL,'error',$script);
			}
			$array	= array();
			$array['member_name|member_mobile']	= $_POST['user_name'];
			$array['member_passwd']	= md5($_POST['password']);
			$member_info = $model_member->getMemberInfo($array);
			if(is_array($member_info) and !empty($member_info)) {
				if(!$member_info['member_state']){
			        showDialog($lang['login_index_account_stop'],''.'error',$script);
				}
			}else{
			    process::addprocess('login');
			    showDialog($lang['login_index_login_fail'],'','error',$script);
			}			
    		$model_member->createSession($member_info);
			process::clear('login');
			// cookie中的cart存入数据库
			Model('cart')->mergecart($member_info,$_SESSION['store_id']);

			// cookie中的浏览记录存入数据库
			Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);

			if ($_GET['inajax'] == 1){
				if (OPEN_STORE_EXTENSION_STATE == 1 && ($member_info['mc_id']==1 || $member_info['mc_id']==2)){
					//单店推广模式
					$url = urlShop('show_store','index',array('store_id'=>$member_info['store_id']));
				}else{
					$url = $_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'];
				}
			    showDialog('',$url,'js');
			} else {
				if (OPEN_STORE_EXTENSION_STATE == 1 && ($member_info['mc_id']==1 || $member_info['mc_id']==2)){
					//单店推广模式
					redirect(urlShop('show_store','index',array('store_id'=>$member_info['store_id'])));
				}else{					
			        redirect($_POST['ref_url']);
				}
			}
		}else{

			//登录表单页面
			$_pic = @unserialize(C('login_pic'));
			if ($_pic[0] != ''){
				Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
			}else{
				Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
			}

			if(empty($_GET['ref_url'])) {
			    $ref_url = getReferer();
			    if (!preg_match('/act=login&op=logout/', $ref_url)) {
			     $_GET['ref_url'] = $ref_url;
			    }
			}
			Tpl::output('html_title',C('site_name').' - '.$lang['login_index_login']);
			if ($_GET['inajax'] == 1){
				Tpl::showpage('login_inajax','null_layout');
			}else{
				Tpl::showpage('login');
			}
		}
	}

	/**
	 * 退出操作
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function logoutOp(){
		Language::read("home_login_index");
		$lang	= Language::getLangContent();
		// 清理消息COOKIE
		setIMCookie('msgnewnum'.$_SESSION['member_id'],'',-3600);
		session_unset();
		session_destroy();
		setIMCookie('cart_goods_num','',-3600);
		if(empty($_GET['ref_url'])){
			$ref_url = getReferer();
		}else {
			$ref_url = $_GET['ref_url'];
		}
		//redirect('index.php?act=login&ref_url='.urlencode($ref_url));
		header('Location: '.$ref_url);exit();
	}

	/**
	 * 会员注册页面
	 *
	 * @param
	 * @return
	 */
	public function registerOp() {
		Language::read("home_login_register");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');
		$model_member->checkloginMember();
		
		Tpl::output('invite_code',cookie('iMall_extension'));
		Tpl::output('html_title',C('site_name').' - '.$lang['login_register_join_us']);
		Tpl::showpage('register');
	}

	/**
	 * 会员添加操作
	 *
	 * @param
	 * @return
	 */
	public function usersaveOp() {		
		//重复注册验证
		if (process::islock('reg')){
			showDialog(Language::get('im_common_op_repeat'),'','error');
		}
		$register_info = array();
		//邀请码验证 zhangchao
		if(C('invite_open')==1){
			//检验邀请码,并获取上级ID
			$pid = $this->check_inviteCode($_POST['invite_code']);
			if(!empty($pid)){
				$register_info['parent_id'] = $pid;
			}
		}
		Language::read("home_login_register");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');		
		$model_member->checkloginMember();		
		$result = chksubmit(true,C('captcha_status_register'),'num');
		if ($result){
			if ($result === -11){
				showDialog($lang['invalid_request'],'','error');
			}elseif ($result === -12){
				showDialog($lang['login_usersave_wrong_code'],'','error');
			}
		} else {
		    showDialog($lang['invalid_request'],'','error');
		}
        
        $register_info['member_name'] = $_POST['user_name'];
        $register_info['member_passwd'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $model_member->createSession($member_info,true);
			process::addprocess('reg');
			// cookie中的cart存入数据库
			Model('cart')->mergecart($member_info,$_SESSION['store_id']);
			// cookie中的浏览记录存入数据库
			Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);
			
			if ($_GET['inajax'] == 1){
			    showDialog('',$_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'],'js');
			}else{
			    $_POST['ref_url']	= (strstr($_POST['ref_url'],'logout')=== false && !empty($_POST['ref_url']) ? $_POST['ref_url'] : 'index.php?act=member_information&op=member');			
			    redirect($_POST['ref_url']);
			}
        } else {
			showDialog($member_info['error'],'','error');
        }
	}
	/**
	 * 会员名称检测
	 *
	 * @param
	 * @return
	 */
	public function check_memberOp() {
		/**
		 * 实例化模型
		 */
	    $model_member	= Model('member');

		$check_member_name	= $model_member->getMemberInfo(array('member_name'=>$_GET['user_name']));
		if(is_array($check_member_name) and count($check_member_name)>0) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	/**
	 * 电子邮箱检测
	 *
	 * @param
	 * @return
	 */
	public function check_emailOp() {
		$model_member = Model('member');
		$check_member_email	= $model_member->getMemberInfo(array('member_email'=>$_GET['email']));
		if(is_array($check_member_email) and count($check_member_email)>0) {
			echo 'false';
		} else {
			echo 'true';
		}
	}
	
	/**
	 * 手机号检测
	 *
	 * @param
	 * @return
	 */
	public function check_mobileOp() {
		$model_member = Model('member');
		$check_member_mobile	= $model_member->getMemberInfo(array('member_mobile'=>$_GET['mobile'],'member_mobile_bind'=>1));
		if(is_array($check_member_mobile) and count($check_member_mobile)>0) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	/**
	 * 忘记密码页面
	 */
	public function forget_passwordOp(){
		/**
		 * 读取语言包
		 */
		Language::read('home_login_register');
		$_pic = @unserialize(C('login_pic'));
		if ($_pic[0] != ''){
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
		}else{
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
		}
		Tpl::output('html_title',C('site_name').' - '.Language::get('login_index_find_password'));
		Tpl::showpage('find_password');
	}

	/**
	 * 找回密码的发邮件处理
	 */
	public function find_passwordOp(){
		Language::read('home_login_register');
		$lang	= Language::getLangContent();

		$result = chksubmit(true,true,'num');
		if ($result !== false){
		    if ($result === -11){
		        showDialog('非法提交');
		    }elseif ($result === -12){
		        showDialog('验证码错误');
		    }
		}

		if(empty($_POST['username'])){
			showDialog($lang['login_password_input_username']);
		}

		if (process::islock('forget')) {
		    showDialog($lang['im_common_op_repeat'],'reload');
		}

		$member_model	= Model('member');
		$member	= $member_model->getMemberInfo(array('member_name'=>$_POST['username']));
		if(empty($member) or !is_array($member)){
		    process::addprocess('forget');
			showDialog($lang['login_password_username_not_exists'],'reload');
		}

		if(empty($_POST['email'])){
			showDialog($lang['login_password_input_email'],'reload');
		}

		if(strtoupper($_POST['email'])!=strtoupper($member['member_email'])){
		    process::addprocess('forget');
			showDialog($lang['login_password_email_not_exists'],'reload');
		}
		process::clear('forget');
		//产生密码
		$new_password	= random(15);
		if(!($member_model->editMember(array('member_id'=>$member['member_id']),array('member_passwd'=>md5($new_password))))){
			showDialog($lang['login_password_email_fail'],'reload');
		}

		$model_tpl = Model('mail_templates');
		$tpl_info = $model_tpl->getTplInfo(array('code'=>'reset_pwd'));
		$param = array();
		$param['site_name']	= C('site_name');
		$param['user_name'] = $_POST['username'];
		$param['new_password'] = $new_password;
		$param['site_url'] = SHOP_SITE_URL;
		$subject	= imReplaceText($tpl_info['title'],$param);
		$message	= imReplaceText($tpl_info['content'],$param);

		$email	= new Email();
		$result	= $email->send_sys_email($_POST["email"],$subject,$message);
		showDialog('新密码已经发送至您的邮箱，请尽快登录并更改密码！','reload','succ','',5);
	}

	/**
	 * 邮箱绑定验证
	 */
	public function bind_emailOp() {
	   $model_member = Model('member');
	   $uid = @base64_decode($_GET['uid']);
	   $uid = decrypt($uid,'');
	   list($member_id,$member_email) = explode(' ', $uid);

	   if (!is_numeric($member_id)) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }

	   $member_info = $model_member->getMemberInfo(array('member_id'=>$member_id),'member_email');
	   if ($member_info['member_email'] != $member_email) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }

	   $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$member_id));
	   if (empty($member_common_info) || !is_array($member_common_info)) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }
	   if (md5($member_common_info['auth_code']) != $_GET['hash'] || TIMESTAMP - $member_common_info['send_acode_time'] > 24*3600) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }

	   $update = $model_member->editMember(array('member_id'=>$member_id),array('member_email_bind'=>1));
	   if (!$update) {
	       showMessage('系统发生错误，如有疑问请与管理员联系',SHOP_SITE_URL,'html','error');
	   }

	   $data = array();
	   $data['auth_code'] = '';
	   $data['send_acode_time'] = 0;
	   $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
	   if (!$update) {
	       showDialog('系统发生错误，如有疑问请与管理员联系');
	   }
	   showMessage('邮箱设置成功','index.php?act=member_security&op=index');
	}
	/**
	 * 邀请码检测
	 * @param 邀请码
	 * @return
	 * @author zhangchao
	 */
	public function check_inviteCode($code= '') {
		/**
		 * 解密 邀请码 
		 * 解密后获取上级会员的ID
		 */
		$pid = urlsafe_b64decode($code);
		if(!empty($code)){
			$model_member = Model('member');
			$mcount	= $model_member->getMemberCount(array('member_id'=>$pid));
			if($mcount<1) {
				showDialog('您的邀请码无效，请重新获取邀请码','','error');
			} else {
				return $pid;
			}
		}else{
			showDialog('邀请码不能为空，请获取您的邀请码','','error');
		}
		
	}
	
	/**
	 * 邀请码检测
	 * @param 邀请码
	 * @return
	 * @author zhangchao
	 */
	public function check_inviteCodeOp() {
		$pid = urlsafe_b64decode($_GET['invite_code']);
		if(!empty($pid)){
			$model_member = Model('member');
			$mcount	= $model_member->getMemberCount(array('member_id'=>$pid));
			if($mcount<1) {
				echo 'false';
			} else {
				echo 'true';
			}
		}else{
			echo 'false';
		}
	}
}