<?php
/**
 * 注销
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

class logoutControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 注销
     */
	public function indexOp(){
		$username = !empty($_GET['username'])?$_GET['username']:$_POST['username'];
		$seller_name = !empty($_GET['seller_name'])?$_GET['seller_name']:$_POST['seller_name'];
		$client = !empty($_GET['client'])?$_GET['client']:$_POST['client'];
		$key = !empty($_GET['key'])?$_GET['key']:$_POST['key'];
		
        if(empty($username) || !in_array($client, $this->client_type_array)) {
            output_error('参数错误');
        }

        $model_mb_user_token = Model('mb_user_token');

        if($this->member_info['member_name'] == $username) {
            $condition = array();
            $condition['member_id'] = $this->member_info['member_id'];
            $condition['client_type'] = $client;
			$condition['token'] = $key;
            $model_mb_user_token->delMbUserToken($condition);
            
            if(!empty($seller_name)){
            	//重新登录后以前的令牌失效
            	$model_mb_seller_token = Model('mb_seller_token');
            	$sction = array();
            	$sction['seller_name'] = $seller_name;
            	$sction['client_type'] = $client;
            	$sction['token'] = $key;
            	$model_mb_seller_token->delSellerToken($sction);
            }
            
            
			// 清理消息COOKIE
		    setIMCookie('msgnewnum'.$_SESSION['member_id'],'',-3600);
		    session_unset();
		    session_destroy();
		    setIMCookie('cart_goods_num','',-3600);
		
            output_data('1');
        } else {
            output_error('参数错误'.$this->member_info['member_name']);
        }
	}

}