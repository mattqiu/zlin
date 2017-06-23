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
		$phone   = $_REQUEST['phone'];				$captcha = $_REQUEST['captcha'];
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
	}	/**		* 手机动态码注册 检查校验码		*/		public function check_sms_captchaOp(){			$phone   = $_REQUEST['phone'];			$sms_captcha = $_REQUEST['captcha'];			$type    = intval($_REQUEST['type'])?intval($_REQUEST['type']):0;					//重复注册验证			if (process::islock('reg')){				output_error('您的操作过于频繁，请稍后再试！');			}			if (empty($phone) || !CheckMobileValidator($phone)){				output_error('手机号码错误！');			}							//检查动态码是否正确			list($checkvalue, $checktime, $checkidhash) = explode("\t", decrypt(cookie('sms_captcha'.$phone),MD5_KEY));			$return = $checkvalue == strtoupper($sms_captcha) && $checkidhash == $phone;			if (!$return){				exit(json_encode(array('state'=>'false','msg'=>'动态码错误！')));			}			setIMCookie('seccode'.$phone,'',-1800);					if ($type==3){				output_data(1);			}		}
	
	/**
	 * 小程序注册会员添加操作
	 *
	 * @param
	 * @return
	 */
	public function sms_registerOp() {	
		//重复注册验证		
		$phone   = $_REQUEST['phone'];				$sms_captcha = $_REQUEST['captcha'];				$type    = intval($_REQUEST['type'])?intval($_REQUEST['type']):0;								//重复注册验证				if (process::islock('reg')){					output_error('您的操作过于频繁，请稍后再试！');				}		
		$model_member	= Model('member');						$register_info = array();		
		$model_member->checkloginMember();		
		$password = $_REQUEST['password'];	
		$member_mobile = $_REQUEST['phone'];				$member_wxinfo = $_REQUEST['userinfo'];				if(preg_match('/^\xEF\xBB\xBF/',$member_wxinfo))    //去除可能存在的BOM		{			$member_wxinfo=substr($member_wxinfo,15);		}				$member_info = json_decode(htmlspecialchars_decode($member_wxinfo),true);				$member_name = $member_info['nickName'];		 		$member_wxopenid = $member_info['openid'];				$extension = $_REQUEST['extension'];				$phonecaptcha = $_REQUEST['phonecaptcha'];				$member_name = str_replace("\\","\\\\",$member_name);					if($phonecaptcha != $sms_captcha){						exit(json_encode(array('state'=>'false','msg'=>'动态码错误！')));		}			print_r($extension).'</br>';print_r($password).'</br>';print_r($member_mobile).'</br>';		exit;
		if ( empty($extension) || empty($password) || empty($member_mobile)){
			output_error('信息填写不全!');	
		}				$extension_id = urlsafe_b64decode($extension);				$register_info['parent_id'] = $extension_id;				$member_info = $model_member->register($register_info);				if(!isset($member_info['error'])) {					$model_member->createSession($member_info,true);									$token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);					if($token) {						output_data(array('username' => $member_info['member_name'], 'key' => $token));						setIMCookie('iMall_extension',$member_info['member_id'],3600*24,'/');					} else {						output_error('注册失败');					}				} else {					output_error($member_info['error']);				}        
        $register_info['member_passwd']    = $password;
		$register_info['password_confirm']   = $password;
		$register_info['member_mobile']      = $member_mobile;				$register_info['member_name']      = $member_name;				$register_info['member_wxinfo']      = $member_wxinfo;				$register_info['member_wxopenid']      = $member_wxopenid;				$register_info['parent_id'] = $extension_id;
		$register_info['member_mobile_bind'] = 1;		
        $member_info = $model_member->wxappregister($register_info);
	if(!isset($member_info['error'])) {			$model_member->createSession($member_info,true);			            $token = $model_member->get_mobile_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);            if($token) {                output_data(array('username' => $member_info['member_name'], 'key' => $token));                setIMCookie('iMall_extension',$member_info['member_id'],3600*24,'/');            } else {                output_error('注册失败');            }        } else {			output_error($member_info['error']);        }
	}
}