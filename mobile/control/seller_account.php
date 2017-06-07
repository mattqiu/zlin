<?php
/**
 * 商家帐户
 * 
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class seller_accountControl extends mobileSellerControl {

    public function __construct(){
        parent::__construct();
    }
	
    //获取商家基本信息
    public function get_seller_infoOp(){
    	
    	$data = array();
    	$data['state'] = true;
    	$store_info = $this->store_info;
    	//获取异业店铺设置
    	if(empty($store_info['store_trade'])){
    		$data['jf_limit'] = 0;
    		$data['rc_balance'] = 0;
    		$data['predeposit'] = 0;
    	}else{
    		$un_trade = unserialize($store_info['store_trade']);
    		$data['jf_limit'] = $un_trade['jf_limit'];
    		$data['rc_balance'] = $un_trade['rc_balance'];
    		$data['predeposit'] = $un_trade['predeposit'];
    	}
    	$data['state'] = true;
    	output_data($data);
    }
    
    /**
     * 编辑店铺优惠设置
     */
    public function store_discount_editOp() {
    	
    	/**
    	 * 更新商家优惠设置--主要针对手机端的异业
    	 */
    	$data = array();
    	$data['jf_limit'] = !empty($_POST['jf_limit'])?$_POST['jf_limit']:0;
    	$data['rc_balance'] = !empty($_POST['rc_balance'])?$_POST['rc_balance']:0;
    	$data['predeposit'] = !empty($_POST['predeposit'])?$_POST['predeposit']:0;
    	$update_array['store_trade'] = serialize($data);
    	$result = Model('store')->editStore($update_array, array('store_id' => $this->store_info['store_id']));
    	if(!$result) {
    		output_error('编辑失败');
    	}
    	output_data('编辑成功');
    }
}
