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

class connect_wxappControl extends wxappHomeControl {

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
		$type = $_REQUEST['type'];		
		$phone   = $_REQUEST['phone'];
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
                output_data('该手机号已注册！');
			}
		}
		output_data(true);	
	}
	
	/**
	 * 获取手机动态验证码
	 */
	public function get_sms_captchaOp(){
		$imhash  = intval($_REQUEST['sec_key'])?intval($_REQUEST['sec_key']):0;
		$type    = intval($_REQUEST['type'])?intval($$_REQUEST['type']):0;
		$captcha = $_REQUEST['sec_val'];
		$phone   = $_REQUEST['phone'];
		
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
            output_data(array('state'=>'true','msg'=>$verify_code,'sms_time'=>60));	
        } else {
            output_error(array('state'=>'false','msg'=>'动态码发送失败'));
        }			
	}
	
	/**
	 * 小程序注册会员添加操作
	 *
	 * @param
	 * @return
	 */
	public function sms_registerOp() {	
		//重复注册验证
		$phone   = $_REQUEST['phone'];
		$model_member	= Model('member');		
		$model_member->checkloginMember();
		$password = $_REQUEST['password'];	
		$member_mobile = $_REQUEST['phone'];
		if ( empty($extension) || empty($password) || empty($member_mobile)){
			output_error('信息填写不全!');	
		}
        $register_info['member_passwd']    = $password;
		$register_info['password_confirm']   = $password;
		$register_info['member_mobile']      = $member_mobile;
		$register_info['member_mobile_bind'] = 1;
        $member_info = $model_member->wxappregister($register_info);
	if(!isset($member_info['error'])) {
	}
}