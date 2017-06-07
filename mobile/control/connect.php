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

class connectControl extends mobileHomeControl {

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
	
	/**
     * 新浪微博登陆
     */
    public function get_sina_oauth2Op() {
		$code_url = SHOP_SITE_URL.'/api.php?act=tosina&state=api&display=mobile';
		@header("location:$code_url");
	}

	/**
     * QQ登陆
     */
    public function get_qq_oauth2Op() {
		$code_url = SHOP_SITE_URL.'/api.php?act=toqq&display=mobile';
		@header("location:$code_url");
	}
	
	/**
     * 微信登陆
     */
    public function get_weixin_oauth2Op() {
		$code_url = SHOP_SITE_URL.'/api.php?act=towx';
		@header("location:$code_url");
	}
}