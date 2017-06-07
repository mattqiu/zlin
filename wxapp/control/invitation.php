<?php
/**
 * 邀请会员页
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class invitationControl extends wxappSellerControl{
	
    protected $member_info = array();
    protected $store_list = array();
	
	public function __construct() {
	    parent::__construct(); 		
	    //根据最后的登录店铺时间查询
	    $model_seller = Model('seller');
	    $seller_list = $model_seller->getSellerList(array('member_id'=>$this->member_id),'','last_login_time desc');
	    if(!empty($seller_list)){
	    	$seller_num = count($seller_list);
	    	if($seller_num>1){
	    		//该导购或店长管理了多家店铺
	    		//$store_id = $_REQUEST['store_id'];
	    		$store_list = array();
	    		foreach ($seller_list as $skey=> $seller_val){
	    			$val_storeId = $seller_val['store_id'];
	    			$model_store = Model('store');
	    			$store_list[$skey] = $store_info = $model_store->getStoreInfo(array('store_id'=>$val_storeId),'store_id,store_name,store_avatar');
	    			$store_list[$skey]['store_avatar'] = $store_info['store_avatar'] ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/LOGO/'.$store_info['store_avatar']: UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');
	    		}
	    		$this->store_list = $store_list;
	    	}
	    }
	}
	
	/**
	 * 邀请会员加入
	 */
	public function indexOp(){
		
		$seller_id = $this->seller_info['seller_id'];
		$seller_name = empty($this->seller_info['nick_name'])?$this->seller_info['seller_name']:$this->seller_info['nick_name'];
		$saleman_id = $_REQUEST['saleman_id'];
		if(!empty($saleman_id) && $saleman_id !='undefined' && $saleman_id != $seller_id){
			//更换的导购信息
			$model_seller = Model('seller');
			$saleman_info = $model_seller->getSellerInfo(array('seller_id'=>$saleman_id));
			$salaman_member_id = $saleman_info['member_id']; //导购员对应会员ID
			$saleman_name = empty($saleman_info['nick_name'])?$saleman_info['seller_name']:$saleman_info['nick_name'];
		}else{
			//登录者自己的信息
			$saleman_name = $seller_name;
			$saleman_id = $seller_id;
			$salaman_member_id = $this->member_id; //导购员对应会员ID
		}
		$salaman_info = array();
		
		$store_member_id = $this->store_info['member_id']; //商铺对应会员ID
		$salaman_info['seller_id'] = $seller_id;//登陆者的seller_id
		$salaman_info['seller_name'] = $seller_name;//登陆者的昵称
		$salaman_info['seller_avatar'] = getMemberAvatarForID($this->seller_info['member_id']);//登陆者的头像
		$salaman_info['store_name'] = $this->store_info['store_name'];//商铺名称
		$salaman_info['saleman_id'] = $saleman_id;//导购对应的seller_id
		$salaman_info['saleman_name'] = $saleman_name;//导购名称
		$salaman_info['saleman_avatar'] = $member_avatar = getMemberAvatarForID($salaman_member_id);//导购员头像
		$salaman_info['invitation_code'] = $invitation_code = urlsafe_b64encode($store_member_id);//店铺邀请码
		$salaman_info['saleman_list'] = $this->saleman_list;//管辖导购列表
		$salaman_info['store_list'] = $this->store_list;//管辖店铺列表
		$code_url = "http://qr.topscan.com/api.php?";//对接了联图网API
		$filename = 'extension_join.html'; //推广系统
		$extension_url = WAP_SITE_URL . '/tmpl/member/'.$filename.'?extension='.$invitation_code.'&salaman_id='.$salaman_member_id;  //wap端		
		$salaman_info['saleman_qrcode'] = $code_url."&text=".$extension_url;//."&logo=".$member_avatar;
		
		output_data($salaman_info);	
	}	
	
	/**
	 * 店铺列表
	 */
	public function storeListOp(){
		$salaman_info['store_list'] = $this->store_list;//管辖店铺列表
		output_data($this->store_list);
	}
	/**
	 * 导购列表
	 */
	public function salemanListOp(){
		output_data($this->saleman_list);
	}
}