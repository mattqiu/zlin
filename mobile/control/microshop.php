<?php
/**
 * 个人微店
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class microshopControl extends mobileControl {
    protected $shop_owner = array();
	protected $shop_id = '';
	protected $view_type = 0;//普通查看
	
	public function __construct(){
		parent::__construct();
		$member_id = $_REQUEST['id'];

		if (empty($member_id)){	
		    $key = $_REQUEST['key'];
			$model_mb_user_token = Model('mb_user_token');
			$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
            if(empty($mb_user_token_info)) {
                output_error('非法操作');
            }			
			$member_id = $mb_user_token_info['member_id'];
			$this->view_type = 1; //自己查看		          					
		}else{
			if ($member_id == $_SESSION['member_id']){
				$this->view_type = 1; //自己查看
			}
		}
		
		//微店id
		$this->shop_id = $member_id;
		//店主信息
		$model_member = Model('member');
        $this->shop_owner = $model_member->getMemberInfoByID($member_id);        
        if(empty($this->shop_owner)) {
            output_error('非法操作');
		}		
	}

    /**
     * 我的商城
     */
	public function indexOp() {
        $model_distribute = Model('distribute_goods');		
        $member_info = array();
		//基本信息
		$member_info['shop_id'] = $this->shop_owner['member_id'];
        $member_info['user_name'] = $this->shop_owner['member_name'];
		$member_info['truename'] = empty($this->shop_owner['member_truename'])?$this->shop_owner['member_name']:$this->shop_owner['member_truename'];
        $member_info['avator'] = getMemberAvatarForID($this->shop_owner['member_id']);
		$member_info['shop_bg'] = getMicroShopAvatarForID($this->shop_owner['member_id']);
		$member_info['qrcode'] = GetExtensionQRcode($this->shop_owner['member_id']);
		//商品数		
		$member_info['distribute_goods'] = $model_distribute->getMicroshopGoodsCount($this->shop_owner['member_id']);
		
		//直推成员
		$member_info['fans_child'] = 0;
		//团队人数
		$member_info['fans_all'] = 0;
		//推广系统
		if (OPEN_STORE_EXTENSION_STATE > 0 && ($this->shop_owner['mc_id']==1 || $this->shop_owner['mc_id']==2)){
			$model_extension = Model('extension');
			//直推成员
		    $member_info['fans_child'] = $model_extension->countPromotionChild($this->shop_owner['store_id'], $this->shop_owner['member_id']);
		    //团队人数
		    $member_info['fans_all'] = $model_extension->countAllPromotionChild($this->shop_owner['store_id'], $this->shop_owner['member_id']);
		}

        output_data(array('member_info' => $member_info,'view_type'=>$this->view_type));		
	}
	
	/**
     * 微店商品列表
     */
    public function distribute_listOp() {
		$model_distribute = Model('distribute_goods');
		
		$distribute_list = $model_distribute->getDistributeGoodsList(array('member_id'=>$this->shop_owner['member_id']), '*', 40);
        $page_count = $model_distribute->gettotalpage();
        $distribute_id = '';
        foreach ($distribute_list as $value){
            $distribute_id .= $value['goods_id'] . ',';
        }
        $distribute_id = rtrim($distribute_id, ',');

        $model_goods = Model('goods');
        //所需字段
		$field ="goods_id,goods_commonid,store_id,goods_name,goods_price,goods_marketprice,goods_image,goods_salenum,evaluation_good_star,evaluation_count,goods_promotion_price";
        // 添加3个状态字段
		$field .= ',is_virtual,is_presell,is_fcode,have_gift,goods_jingle,store_name,promotion_amount,is_own_shop';
        $goods_list = $model_goods->getGoodsList(array(
            'goods_id' => array('in', $distribute_id),
            // 默认不显示预订商品
            'is_book' => 0,
        ), $field);
        foreach ($goods_list as $key=>$value) {
            $goods_list[$key]['goods_image_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
        }

        output_data(array('goods_list' => $goods_list,'view_type'=>$this->view_type), mobile_page($page_count));
    }
	
	/**
     * 微店粉丝列表
     */
    public function fans_listOp() {
		$fields = 'member_id,member_name';
        $model_promotion = Model('extension');
		//父ID
		$parent_id = $this->shop_owner['member_id'];
		
		$condition=array();			
		$condition['parent_id'] = $parent_id;
        $fans_list = $model_promotion->getPromotionList($this->shop_owner['store_id'],$condition,$fields,20,'mc_id desc,ext_id asc');
		$page_count = $model_promotion->gettotalpage();
		
        foreach ($fans_list as $key=>$value) {
            $fans_list[$key]['avator_url'] = getMemberAvatarForID($value['member_id']);
			$fans_list[$key]['store_name'] = $value['member_name'];
			
			$fans_list[$key]['store_fans'] = $model_promotion->countPromotionChild($this->shop_owner['store_id'], $value['member_id']);
			$fans_list[$key]['goods_count'] = Model('distribute_goods')->getMicroshopGoodsCount($value['member_id']);
        }

        output_data(array('fans_list' => $fans_list), mobile_page($page_count));
    }
}