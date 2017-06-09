<?php
/**
 * 小程序首页
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class ordering_indexControl extends wxappSellerControl{

	public function __construct() {
        parent::__construct();
    }
	
    /**
     * 微信小程序-导购首页
     */
    public function indexOp() {
    	$indexInfo['member_id'] = $this->member_id; //当前会员ID
    	$indexInfo['store_id'] = $this->store_info['store_id']; //当前店铺ID    	
    	$indexInfo['store_avatar'] = $this->store_info['store_avatar']; //当前店铺LOGO
    	$indexInfo['seller_id'] = $this->seller_info['seller_id']; //当前导购ID
    	$indexInfo['seller_name'] = $this->seller_info['seller_name']; //当前导购用户名
    	$indexInfo['store_num'] 	= Model('seller')->getStoreCountByMemberID($this->member_id);//可管理店铺数
    	
    	$indexInfo['saleman'] = $this->saleman;//导购列表
    	$indexInfo['saleman_len'] = count($this->saleman['saleman_name']);//导购数
    	//切换导购
    	if(!empty($_REQUEST['saleman_id'])){
    		$saleman_id = $_REQUEST['saleman_id'];
    	}else{
    		$saleman_id = $this->seller_info['seller_id'];
    	}
    	$indexInfo['saleman_id'] = $saleman_id;
    	output_data($indexInfo,'首页加载成功');
    }
    
    
}