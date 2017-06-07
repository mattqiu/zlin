<?php
/**
 * 我的商城
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

class member_indexControl extends mobileMemberControl {
    public function __construct(){
        parent::__construct();
    }

    /**
     * 我的商城
     */
    public function indexOp() {
        $member_info = array();
		if($_SESSION['member_id']) {
            $model_member = Model('member');
            $member_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
            if ($member_info){
                $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
                $member_info = array_merge($member_info,$member_gradeinfo);
                $member_info['security_level'] = $model_member->getMemberSecurityLevel($member_info);
            }
        }
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avatar'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['point'] = $this->member_info['member_points'];
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
        $member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];
		$model_order = Model('order');
		$member_info['order_nopay_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'NewCount');
        $member_info['order_noreceipt_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'SendCount');
        $member_info['order_noeval_count'] = $model_order->getOrderCountByID('buyer',$_SESSION['member_id'],'EvalCount');
		
		$member_info['favorites_goods'] = Model('favorites')->getGoodsFavoritesCountByGoodsId(array(),$_SESSION['member_id']);
		$member_info['favorites_store'] = Model('favorites')->getStoreFavoritesCountByStoreId(array(),$_SESSION['member_id']);

        output_data(array('member_info' => $member_info));
    }

	public function my_assetOp() {
        $member_info['point'] = $this->member_info['member_points'];
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
		$member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];
		$member_info['voucher'] = Model('voucher')->getCurrentAvailableVoucherCount($_SESSION['member_id']);
		$member_info['redpacket'] = Model('redpacket')->getCurrentAvailableRedpacketCount($_SESSION['member_id']);
        output_data($member_info);
    }
    /**
     * 店铺商品分类
     */
    public function store_goods_classOp()
    {
    	$store_id = (int)$_REQUEST['store_id'];
    	if ($store_id <= 0 || empty($store_id)) {
    		$store_id = $this->store_info['store_id'];
    		if (empty($store_id)) {
    			output_error('参数错误');
    		}
    	}
    	$store_online_info = Model('store')->getStoreOnlineInfoByID($store_id);
    	if (empty($store_online_info)) {
    		output_error('店铺不存在或未开启');
    	}
    	$store_info = array();
    	$store_info['store_id'] = $store_online_info['store_id'];
    	$store_info['store_name'] = $store_online_info['store_name'];
    	$store_goods_class = Model('store_goods_class')->getStoreGoodsClassPlainList2($store_id);
    	output_data(array(
	    	'store_info' => $store_info,
	    	'store_goods_class' => $store_goods_class
    	));
    }
}