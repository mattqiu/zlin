<?php
/**
 * 手机短信登录
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class connect_smsControl extends BaseHomeControl{
	public function __construct(){
		parent::__construct();		
		/**
		 * 判断微信互联功能是否开启
		 */
		if (C('sms_open')!=1){
			showDialog('系统未开启手机短信登录功能！','','error'); 
		}
		Language::read("home_login_register,home_login_index");
		
		Tpl::output('hidden_sidetoolbar', 1);		
		Tpl::setLayout('login_layout');
	}
	/**
	 * 获取短信验证码
	 */
	public function get_smscaptchaOp(){
		$imhash  = intval($_GET['imhash'])?intval($_GET['imhash']):0;
		$type    = intval($_GET['type'])?intval($_GET['type']):0;
		$captcha = $_GET['captcha'];
		$phone   = $_GET['phone'];
		
		if (empty($phone) || !CheckMobileValidator($phone)){  
		    exit(json_encode(array('state'=>'false','msg'=>'手机号码错误!')));
		}
		//检查手机号是否已绑定		
		$model_member = Model('member');
        $condition = array();
        $condition['member_mobile'] = $phone;
        $condition['member_mobile_bind'] = 1;
        $member_info = $model_member->getMemberInfo($condition,'member_id');
        if (empty($member_info) || empty($member_info['member_id'])) {
			if ($type==2 || $type==3){
                exit(json_encode(array('state'=>'false','msg'=>'该手机号未注册！')));
			}
        }else{
			if ($type==1){
                exit(json_encode(array('state'=>'false','msg'=>'该手机号已注册！')));
			}
		}
		
		if ($imhash==1){
			if ($type==1){ //注册
				$act = 'login';
				$op  = 'register';
			}else if ($type==2){//登录
				$act = 'login';
				$op  = 'index';
			}else if ($type==3){//找回密码
				$act = 'login';
				$op  = 'forget_password';
			}
			$imhash = substr(md5(SHOP_SITE_URL.$act.$op),0,8);
			
			if (!checkSeccode($imhash,$captcha)){
		        setIMCookie('seccode'.$imhash,'',-3600);
			    exit(json_encode(array('state'=>'false','msg'=>'验证码错误!')));
		    }
		    setIMCookie('seccode'.$imhash,'',-3600);
		}
		//验证码
		$verify_code = rand(100,999).rand(100,999);
		//短信模板
		$model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>'mobile_touser_verify_mobile'));
		//替换模板代码
		$param = array();
        $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
        $param['verify_code'] = $verify_code;
        $param['site_name']	= C('site_name');
        $message = imReplaceText($tpl_info['content'],$param);
		//发送短信
		$sms = new Sms();
        $result = $sms->send($phone,$message);
        if ($result) {
			setIMCookie('sms_captcha'.$phone, encrypt(strtoupper($verify_code)."\t".(time())."\t".$phone,MD5_KEY),1800);
            exit(json_encode(array('state'=>'true','msg'=>'验证码已发出，30分钟内有效,请注意查收......')));	
        } else {
            exit(json_encode(array('state'=>'false','msg'=>'动态码发送失败')));
        }			
	}
	
	/**
	 * 手机动态码登录
	 */
	public function loginOp(){
		$phone   = $_POST['phone'];
		$sms_captcha = $_POST['sms_captcha'];
		
		$result = chksubmit(false,false,'num');
		if ($result !== false){
			if ($result === -11){
				showDialog('非法提交！','','error'); 
			}elseif ($result === -12){
				showDialog('验证码错误！','','error'); 
			}
			if (process::islock('login')) {
				showDialog('您的操作过于频繁，请稍后再试',SHOP_SITE_URL,'','error');
			}
			if (empty($phone) || !CheckMobileValidator($phone)){  
		        showDialog('手机号码错误！','','error'); 
		    }
			
			//检查动态码是否正确
			list($checkvalue, $checktime, $checkidhash) = explode("\t", decrypt(cookie('sms_captcha'.$phone),MD5_KEY));
	        $return = $checkvalue == strtoupper($sms_captcha) && $checkidhash == $phone;
	        if (!$return){
				showDialog('动态码错误！','','error'); 
			}
			setIMCookie('seccode'.$phone,'',-1800);	        
	
			$model_member	= Model('member');
			$array	= array();
			$array['member_mobile']	= $phone;
			$array['member_mobile_bind'] = 1;
			$member_info = $model_member->getMemberInfo($array);
			if(is_array($member_info) and !empty($member_info)) {
				if(!$member_info['member_state']){
			        showDialog('帐号被停用!',''.'error');
				}
			}else{
			    process::addprocess('login');
			    showDialog('登录失败!','','error');
			}
    		$model_member->createSession($member_info);
			process::clear('login');

			// cookie中的cart存入数据库
			Model('cart')->mergecart($member_info,$_SESSION['store_id']);

			// cookie中的浏览记录存入数据库
			Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);

			if ($_GET['inajax'] == 1){
			    showDialog('',$_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'],'js');
			} else {
				if (OPEN_STORE_EXTENSION_STATE == 1 && ($member_info['mc_id']==1 || $member_info['mc_id']==2)){
					//单店推广模式
					redirect(urlShop('show_store','index',array('store_id'=>$member_info['store_id'])));
				}else{
			        redirect($_POST['ref_url']);
				}
			}
		}else{
			showDialog('非法提交！','','error'); 
		}
		
	}
	
	/**
	 * 手机动态码注册 检查校验码
	 */
	public function check_captchaOp(){
		$phone   = $_GET['phone'];
		$sms_captcha = $_GET['sms_captcha'];
		
	    //重复注册验证
		if (process::islock('reg')){
			exit(json_encode(array('state'=>'false','msg'=>'您的操作过于频繁，请稍后再试！')));
		}
		if (empty($phone) || !CheckMobileValidator($phone)){  
			exit(json_encode(array('state'=>'false','msg'=>'手机号码错误！')));
		}		
			
		//检查动态码是否正确
		list($checkvalue, $checktime, $checkidhash) = explode("\t", decrypt(cookie('sms_captcha'.$phone),MD5_KEY));
	    $return = $checkvalue == strtoupper($sms_captcha) && $checkidhash == $phone;
	    if (!$return){
			exit(json_encode(array('state'=>'false','msg'=>'动态码错误！')));
		}
		setIMCookie('seccode'.$phone,'',-1800);	        
	
	    $model_member = Model('member');
        $condition = array();
        $condition['member_mobile'] = $phone;
        $condition['member_mobile_bind'] = 1;
        $member_info = $model_member->getMemberInfo($condition,'member_id');
        if (!empty($member_info) || !empty($member_info['member_id'])) {
            exit(json_encode(array('state'=>'false','msg'=>'该手机号已经注册！')));
        }

		exit(json_encode(array('state'=>'true','msg'=>'手机号可以注册!')));	
	}
	
	/**
	 * 手机动态码注册
	 */
	public function registerOp(){
		$phone   = $_GET['phone'];
		$user_name = 'u'.substr($phone,-5);
		$user_pwd = rand(100,999).rand(100,999);
			
		echo "$('#member_name').val('".$user_name."');";
		echo "$('#sms_password').val('".$user_pwd."');";
		echo "$('#register_phone').val($('#phone').val());";
		echo "$('#register_sms_captcha').val($('#sms_captcha').val());";			
	}
	
	/**
	 * 手机注册会员添加操作
	 *
	 * @param
	 * @return
	 */
	public function usersaveOp() {	
		//重复注册验证
		if (process::islock('reg')){
			showDialog(Language::get('im_common_op_repeat'),'','error');
		}
		Language::read("home_login_register");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');		
		$model_member->checkloginMember();		
		$result = chksubmit(false,false,'num');
		if ($result){
			if ($result === -11){
				showDialog($lang['invalid_request'],'','error');
			}elseif ($result === -12){
				showDialog($lang['login_usersave_wrong_code'],'','error');
			}
		} else {
		    showDialog($lang['invalid_request'],'','error');
		}
        $register_info = array();
        $register_info['member_name']           = $_POST['member_name'];
        $register_info['member_passwd']           = $_POST['password'];
		$register_info['password_confirm']   = $_POST['password'];
        $register_info['email']              = $_POST['email'];
		$register_info['member_mobile']      = $_POST['register_phone'];
		$register_info['member_mobile_bind'] = 1;
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
	 * 找回密码的发邮件处理
	 */
	public function find_passwordOp(){
		Language::read('home_login_register');
		$lang	= Language::getLangContent();

		$result = chksubmit(false,false,'num');
		if ($result !== false){
		    if ($result === -11){
		        showDialog('非法提交','','error');
		    }elseif ($result === -12){
		        showDialog('验证码错误','','error');
		    }
		}
		if(empty($_POST['phone'])){
			showDialog('请输入手机号码','','error');
		}
		if(empty($_POST['password'])){
			showDialog('请输入新密码!','','error');
		}

		if (process::islock('forget')) {
		    showDialog($lang['im_common_op_repeat'],'reload');
		}
		
		$phone = $_POST['phone'];
		$sms_captcha = $_POST['sms_captcha'];
		//检查动态码是否正确
		list($checkvalue, $checktime, $checkidhash) = explode("\t", decrypt(cookie('sms_captcha'.$phone),MD5_KEY));
	    $return = $checkvalue == strtoupper($sms_captcha) && $checkidhash == $phone;
	    if (!$return){
			showDialog('校验码错误!','','error');
		}
		setIMCookie('seccode'.$phone,'',-1800);	
		        
		$model_member	= Model('member');
		$condition = array();
        $condition['member_mobile'] = $phone;
        $condition['member_mobile_bind'] = 1;
        $member_info = $model_member->getMemberInfo($condition,'member_id');
		
		if(empty($member_info) or !is_array($member_info)){
		    process::addprocess('forget');
			showDialog('该手机号未注册!','reload');
		}

		process::clear('forget');
		//产生密码
		$new_password	= $_POST['password'];
		
		if(!($model_member->editMember(array('member_id'=>$member_info['member_id']),array('member_passwd'=>md5($new_password))))){
			showDialog('密码重置失败!','','error');
		}
		showDialog('新密码设置成功！','index.php?act=login','succ','',5);
	}
}