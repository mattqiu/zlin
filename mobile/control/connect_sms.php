<?php
/**
 * 第三方互联操作
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

class connect_smsControl extends mobileHomeControl {

	public function __construct(){
		parent::__construct();
	}

    /**
	 * 获取互联开启状态
	 */
	public function get_stateOp(){
		$type = $_GET['t'];
		$connect_status = 0;
		if ($type == 'connect_sms_reg'){
			$connect_status = C('sms_open');
		}
		output_data($connect_status);
	}

	//检查手机号是否可以注册
	public function check_sms_mobileOp(){
		$type = $_GET['type'];		
		$phone   = $_GET['phone'];
		$captcha = $_GET['captcha'];
		$codekey = $_GET['codekey'];

		if (!checkSeccode($codekey,$captcha )){
            output_error('验证码错误!');
        }
		//检查手机号是否已绑定		
		$model_member = Model('member');
        $condition = array();
        $condition['member_mobile'] = $phone;
        $condition['member_mobile_bind'] = 1;
        $member_info = $model_member->getMemberInfo($condition,'member_id');
        if (empty($member_info) || empty($member_info['member_id'])) {
			if ($type==2 || $type==3){
                output_error('该手机号未注册！');
			}
        }else{
			if ($type==1){
                output_error('该手机号已注册！');
			}
		}
		output_data(true);	
	}
	
	/**
	 * 获取手机动态验证码
	 */
	public function get_sms_captchaOp(){
		$imhash  = intval($_GET['sec_key'])?intval($_GET['sec_key']):0;
		$type    = intval($_GET['type'])?intval($_GET['type']):0;
		$captcha = $_GET['sec_val'];
		$phone   = $_GET['phone'];
		
		if (empty($phone) || !CheckMobileValidator($phone)){  
		    output_error('手机号码错误!');
		}
		//检查手机号是否已绑定		
		$model_member = Model('member');
        $condition = array();
        $condition['member_mobile'] = $phone;
        $condition['member_mobile_bind'] = 1;
        $member_info = $model_member->getMemberInfo($condition,'member_id');
        if (empty($member_info) || empty($member_info['member_id'])) {
			if ($type==2 || $type==3){
                output_error('该手机号未注册！');
			}
        }else{
			if ($type==1){
                output_error('该手机号已注册！');
			}
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
            output_data(array('state'=>'true','msg'=>'验证码已发出，30分钟内有效,请注意查收......','sms_time'=>60));	
        } else {
            output_error(array('state'=>'false','msg'=>'动态码发送失败'));
        }			
	}
	
	/**
	 * 手机动态码注册 检查校验码
	 */
	public function check_sms_captchaOp(){
		$phone   = $_GET['phone'];
		$sms_captcha = $_GET['captcha'];
		$type    = intval($_GET['type'])?intval($_GET['type']):0;
		
	    //重复注册验证
		if (process::islock('reg')){
			output_error('您的操作过于频繁，请稍后再试！');
		}
		if (empty($phone) || !CheckMobileValidator($phone)){  
			output_error('手机号码错误！');
		}		
			
		//检查动态码是否正确
		list($checkvalue, $checktime, $checkidhash) = explode("\t", decrypt(cookie('sms_captcha'.$phone),MD5_KEY));
	    $return = $checkvalue == strtoupper($sms_captcha) && $checkidhash == $phone;
	    if (!$return){
			exit(json_encode(array('state'=>'false','msg'=>'动态码错误！')));
		}
		setIMCookie('seccode'.$phone,'',-1800);	
		
		if ($type==3){
			output_data(1);
		}
	
	    $model_member = Model('member');
        $condition = array();
        $condition['member_mobile'] = $phone;
        $condition['member_mobile_bind'] = 1;
        $member_info = $model_member->getMemberInfo($condition,'member_id');
        if (!empty($member_info) || !empty($member_info['member_id'])) {
            output_error('该手机号已经注册！');
        }

		exit(json_encode(array('state'=>'true','msg'=>'手机号可以注册!')));	
	}	
	
	/**
	 * 手机注册会员添加操作
	 *
	 * @param
	 * @return
	 */
	public function sms_registerOp() {	
		//重复注册验证
		if (process::islock('reg')){
			output_error('您的操作过于频繁，请稍后再试');
		}

		$model_member	= Model('member');		
		$model_member->checkloginMember();
		
		$member_name = $_POST['member_name'];
		$password = $_POST['password'];	
		$member_mobile = $_POST['phone'];
		if (empty($member_name) || empty($password) || empty($member_mobile)){
			output_error('信息填写不全!');	
		}
		
		$check_member_name  = $model_member->getMemberInfo(array('member_name'=>$member_name)); 
		if (!empty($check_member_name)){
			output_error('用户名已注册!');	
		}

        $register_info = array();
        $register_info['member_name']        = $member_name;
        $register_info['member_passwd']    = $password;
		$register_info['password_confirm']   = $password;
		$register_info['member_mobile']      = $member_mobile;
		$register_info['member_mobile_bind'] = 1;
		
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
			$client = $_GET['client']?$_GET['client']:$_POST['client'];
			$token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $client);
            if($token) {				
				$model_member->createSession($member_info);	
				process::addprocess('reg');
                output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));				
            } else {
                output_error('登录失败');
            }
        } else {
			output_error($member_info['error']);
        }
	}
	
	//修改登录密码-保存新密码
	public function find_passwordOp() {
        $obj_validate = new Validate();
        $obj_validate->validateparam = array(
                array("input"=>$_POST['phone'],     "require"=>"true",  "message"=>'请正确输入手机号'),
                array("input"=>$_POST['password'],  "require"=>"true",  "message"=>'请正确输入密码'),
        );
        $error = $obj_validate->validate();
        if ($error != ''){
            output_error($error);
        }
		$member_mobile = $_POST['phone'];
		$password = $_POST['password'];
		$captcha = $_POST['captcha'];
		$client = $_POST['client'];
		
		$model_member = Model('member');
		
		$condition = array();
        $condition['member_mobile'] = $member_mobile;
        $condition['member_mobile_bind'] = 1;
        $member_info = $model_member->getMemberInfo($condition);
		if (!empty($member_info)){
			$update = $model_member->editMember(array('member_id'=>$member_info['member_id']),array('member_passwd'=>md5($password)));
			if ($update){
			    $token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $client);
				if($token) {				
				    $model_member->createSession($member_info);	
                    output_data(array('userid' => $member_info['member_id'], 'username' => $member_info['member_name'], 'key' => $token,'mc_id'=>$member_info['mc_id'],'store_id'=>$member_info['store_id']));				
                } else {
                    $message = '登录失败';
                }
		    }else{
				$message = '密码修改失败';
		    }
		}else{
			$message = '无效的手机号';
		}
        output_error($message);
	}	
}