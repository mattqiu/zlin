<?php
/**
 * wxapp父类
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

/********************************** 前台control父类 **********************************************/

class wxappControl{

    //客户端类型
    protected $client_type_array = array('android', 'wap', 'wechat', 'ios', 'windows');
    //列表默认分页数
    protected $page = 8;

	public function __construct() {
        Language::read('wxapp');
        //分页数处理
        $page = intval($_GET['page']);
        if($page > 0) {
            $this->page = $page;
        }
    }
	
	/**
     * 获取购物车商品数量
     */
    protected function getCartCount($token) {
		if(empty($token)) {
			return 0;
		}
		$model_mb_user_token = Model('mb_user_token');
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($token);
        if(empty($mb_user_token_info)) {
            return 0;
        }
		$save_type = 'db';
		$cart_num = Model('cart')->getCartNum($save_type,array('buyer_id'=>$mb_user_token_info['member_id']));//查询购物车商品种类
		return $cart_num;        
    }
	
	protected function getMemberIdIfExists()
    {
        $token = $_REQUEST['token'];
        if (empty($token)) {
            return 0;
        }

        $model_mb_user_token = Model('mb_user_token');
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($token);
        if (empty($mb_user_token_info)) {
            return 0;
        }

        return $mb_user_token_info['member_id'];
    }
}

class wxappHomeControl extends wxappControl{
	public function __construct() {
        parent::__construct();		
    }
} 

class wxappMemberControl extends wxappControl{   
	
    protected $member_info = array();
    protected $store_info = array();
    protected $seller_info = array();
    protected $store_list = array();//管辖的店铺列表
    protected $saleman_list = array();//管辖的导购ID
	public function __construct() {
        parent::__construct();
        //$agent = $_SERVER['HTTP_USER_AGENT'];

        $model_mb_user_token = Model('mb_user_token');
        $token = $_REQUEST['token'];
        //$token = trim($token, "\xEF\xBB\xBF");//PHP去除BOM头
        if(empty($token)) {
            output_error('没有获取到登录令牌的token，清除微信缓存后再试', array('login' => '0','error_code'=>CODE_InvalidSession));
        }
        if(!empty($_REQUEST['member_id'])){
        	$member_id = $_REQUEST['member_id'];
        }else{
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($token);
        if(empty($mb_user_token_info)) {
            output_error('没有找到对应的登录令牌，清除微信缓存后再登录'.strlen($token), array('login' => '0','error_code'=>CODE_InvalidSession));
        }		
        	$member_id = $mb_user_token_info['member_id'];
        }        
        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($member_id);
        if(empty($this->member_info)) {
            output_error('会员信息不存在，请先注册', array('login' => '0','error_code'=>CODE_InvalidUsernameOrPassword));
        } else {
	    $this->member_info['client_type'] = $mb_user_token_info['client_type'];
            $this->member_info['openid'] = $mb_user_token_info['openid'];
            $this->member_info['token'] = $mb_user_token_info['token'];
		    $level_name = $model_member->getOneMemberGrade($this->member_info['member_exppoints']);
			$this->member_info['level_name'] = $level_name['level_name'];
			$this->member_info['member_grade'] = $level_name['level'];
			
            //读取 会员是否是卖家员工
            /**
             * 这里考虑可能一个店长或导购管理多家店，所以查询的是列表
             */
			$model_seller = Model('seller');
			$model_store = Model('store');
			$condition_seller['member_id'] = $this->member_info['member_id'];
			if(!empty($_REQUEST['store_id'])){
				$condition_seller['store_id'] = $_REQUEST['store_id'];
			}
			$isSeller = $model_seller->isSellerExist($condition_seller);
			if($isSeller){
			//根据最后的登录店铺时间查询
				$seller_list = $model_seller->getSellerList(array('member_id'=>$this->member_info['member_id']),'','last_login_time desc');
            		
			if(!empty($seller_list)){
					$seller_num = count($seller_list);
					if($seller_num>1){
						//该导购或店长管理了多家店铺
						$store_id = $_REQUEST['store_id'];
						$store_list = array();
						foreach ($seller_list as $skey=> $seller_val){
							$val_storeId = $seller_val['store_id'];
							if($val_storeId == $store_id){
								$seller_info = $seller_val;
							}
							$store_list[$skey] = $store_info = $model_store->getStoreInfo(array('store_id'=>$val_storeId),'store_id,store_name,store_avatar');
							$store_list[$skey]['store_avatar'] = $store_info['store_avatar'] ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/LOGO/'.$store_info['store_avatar']: UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');
						}
						$this->store_list = $store_list;
					}else{						
				//不管如何获取到第一个商家信息
				$seller_info = $seller_list[0];
						$store_id = $seller_info['store_id'];
						//$seller_info = $model_seller->getSellerInfo($condition_seller);
			}
					//当前店铺下，管理导购列表
					$saleman_ids = $seller_info['saleman_id'];
					if(!empty($saleman_ids)){
						$saleman_list = $model_seller->getSellerList(array('seller_id'=>array('in',$saleman_ids)),'','','seller_id,seller_name,nick_name,member_id');
						foreach ($saleman_list as $skey=> $saleman){
							$saleman_list[$skey]['saleman_avatar'] = getMemberAvatarForID($saleman['member_id']);//导购员头像
						}
						$this->saleman_list = $saleman_list;
					}
					$this->member_info['myself_store_id'] = $store_id;//查出用户多重身份					
			/* 暂时不需要查询商家信息*/
			//是否店铺用户，是登录小程序
					$this->store_info = $model_store->getStoreInfoByID($store_id);
					$this->seller_info = $seller_info;
				}else{
					$this->member_info['myself_store_id'] = 0;//不是商家员工
				}				
							
			}
        }	
    }
	
	public function getOpenId()
    {
        return $this->member_info['openid'];
    }

    public function setOpenId($openId)
    {
        $this->member_info['openid'] = $openId;
        Model('mb_user_token')->updateMemberOpenId($this->member_info['token'], $openId);
    }
}

class wxappStoreControl extends wxappControl{
	protected $member_id;
	protected $store_id;
	protected $store_info;
    protected $store_decoration_only = false;
	protected $show_own_copyright = false;
	
	public function __construct() {
        parent::__construct();
		
		$token = $_REQUEST['token'];
		if(empty($token)) {
			output_error('您没有获取到登录令牌的token，清除微信缓存后再试', array('login' => '0','error_code'=>CODE_InvalidSession));
		}
		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($token);
		if(empty($mb_user_token_info)) {
			output_error('您获取到登录令牌可能失效，请清除缓存后再试'.$token, array('login' => '0','error_code'=>CODE_InvalidSession));
		}else{
			$this->member_id = $salaman_member_id = $mb_user_token_info['member_id']; //导购会员ID
		}
		/*
		 * 导购暂时不需要获取对应的会员信息
		$model_member = Model('member');
		$this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);
		if(empty($this->member_info)) {
			output_error('您没有对应的前台会员信息，请重新登录', array('login' => '0','error_code'=>CODE_InvalidUsernameOrPassword));
		} else {
			$this->member_info['client_type'] = $mb_user_token_info['client_type'];
			$this->member_info['openid'] = $mb_user_token_info['openid'];
			$this->member_info['token'] = $mb_user_token_info['token'];
			$level_name = $model_member->getOneMemberGrade($this->member_info['member_exppoints']);
			$this->member_info['level_name'] = $level_name['level_name'];
			$this->member_info['member_grade'] = $level_name['level'];
		}
		*/
		$this->store_id = intval($_REQUEST['store_id']);
		if(empty($this->store_id)){
			$model_mb_seller_token = Model('mb_seller_token');
			//获取该员工绑定的多家店铺列表
			$mb_seller_token_info = $model_mb_seller_token->getSellerTokenInfoByToken($token);
			if(empty($mb_seller_token_info)) {
				output_error('该账户没有可管理的店铺，请更换账户后再登录', array('login' => '0','error_code'=>CODE_InvalidSession));
			}
			$model_seller = Model('seller');
			$seller_list = $model_seller->getSellerList(array('seller_id' => $mb_seller_token_info['seller_id']));
			foreach ($seller_list as $mbkey=>$mb_seller_info){
				if($mb_seller_info['is_default']==1){//默认管理的店铺
					$seller_info = $mb_seller_info;
				}
			}
			//防止该员工未设置默认管理的店铺
			if(empty($seller_info) && count($seller_list)>0)
			{
				$seller_info = $seller_list[0];
			}
			$this->seller_info = $seller_info;
			$this->store_id = $this->seller_info['store_id'];
		}
        //店铺详细信息
        $model_store = Model('store');
        $this->store_info = $model_store->getStoreOnlineInfoByID($this->store_id);
        if (empty($this->store_info)) {
            output_error('店铺不存在');
        }	
		//分店
		if ($this->store_info['branch_op']==1){
		    $this->store_info['branch_count'] = $model_store->getBranchCount($this->store_info['store_id']);
			$this->store_info['branch_apply'] = ($this->store_info['branch_count'] < $this->store_info['branch_limit'])?1:0;
		}else{
			$this->store_info['branch_count'] = 0;
			$this->store_info['branch_apply'] = 0;
		}
		//店铺推广 推广处理
		if (OPEN_STORE_EXTENSION_STATE == 1 && $this->store_info['extension_op']==1){
			$model_extension	= Model('extension');
			//推广员
			$this->store_info['promotion_count'] = $model_extension->getPromotionCount($this->store_info['store_id']);				
		    if ($this->store_info['promotion_open']==1){
				if ($this->store_info['promotion_limit']<=0){
					$this->store_info['promotion_apply'] = 1;
				}else{
			        $this->store_info['promotion_apply'] = ($this->store_info['promotion_count'] < $this->store_info['promotion_limit'])?1:0;
				}
		    }else{
			    $this->store_info['promotion_apply'] = 0;
		    }
			//导购员
		    $this->store_info['saleman_count'] = $model_extension->getSalemanCount($this->store_info['store_id']);
		    if ($this->store_info['saleman_open']==1){
				if ($this->store_info['saleman_limit']<=0){
					$this->store_info['saleman_apply'] = 1;
				}else{
			        $this->store_info['saleman_apply'] = ($this->store_info['saleman_count'] < $this->store_info['saleman_limit'])?1:0;
				}
		    }else{
			    $this->store_info['saleman_apply'] = 0;
		    }			
		}else{
			$this->store_info['promotion_count'] = 0;
			$this->store_info['promotion_apply'] = 0;
			$this->store_info['saleman_count'] = 0;
			$this->store_info['saleman_apply'] = 0;
		}
		
		// 店铺头像
		$this->store_info['store_avatar'] = $this->store_info['store_avatar'] ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/LOGO/'.$this->store_info['store_avatar']: UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');
		//实体店导航地址
		$this->store_info['map_url'] = Model('store_map')->getTXMapUrl($this->store_id);
		$invitation_code = urlsafe_b64encode($this->store_info['member_id']);//店铺邀请码
		$extension_url = WAP_SITE_URL . '/tmpl/member/extension_join.html?extension='.$invitation_code.'&salaman_id='.$salaman_member_id;  //wap端
		//推广链接
		$this->store_info['apply_extension_url'] = $extension_url;
    }
}

/**
 * 店员操作端
 * @author Administrator
 *
 */
class wxappSellerControl extends wxappControl{

    protected $store_id; //默认店铺ID
	protected $member_id; //导购对应会员ID
    protected $seller_id; //当前店铺对应的店铺员工ID
    protected $seller_info = array();
    protected $seller_group_info = array();
    protected $member_info = array();
    protected $saleman = array();//导购一维数组
    protected $saleman_list = array();
    protected $store_list = array();//管辖店铺ID
    protected $store_info = array();
    protected $store_grade = array();

    public function __construct() {
        parent::__construct();
    	$token = $_REQUEST['token'];
    	//$token = trim($token, "\xEF\xBB\xBF");//PHP去除BOM头
        if(empty($token)) {
            output_error('没有获取到登录令牌的token，清除微信缓存后再试', array('login' => '0','error_code'=>CODE_InvalidSession));
        }
    	
    	$condition_seller =array();
    	//切换店铺的时候 	seller_id 是需要发生变化，前提是该会员可以管理多家店铺
    	if(empty($_REQUEST['store_id'])||empty($_REQUEST['member_id'])){
	    	/*
	    	 * 这里应该查看的是管理员或导购是否已经登录了，暂时和会员登录没有关系
	    	 * @seller_id 表示登录商铺的管理员ID
	    	 * seller_id 存在则无需再去登录店铺，不存在则需要登录
	    	 */
	    	if(!empty($_REQUEST['seller_id'])){
	    		$seller_id = $_REQUEST['seller_id'];
        }else{
        	$model_mb_seller_token = Model('mb_seller_token');
        	//获取该员工登录店铺令牌
        	$mb_seller_token_info = $model_mb_seller_token->getSellerTokenInfoByToken($token);
        	if(empty($mb_seller_token_info)) {
	    			output_error('该账户没有可管理的店铺，请更换账户后再登录'.strlen($token), array('login' => '0','error_code'=>CODE_InvalidSession));
        	}
	    		$seller_id = $mb_seller_token_info['seller_id'];    		
        }
	    	$condition_seller['seller_id'] = $seller_id;
    	}else{
    		//店铺和会员ID同时存在的时候才可以确定新的seller_id
    		$condition_seller['store_id'] = $_REQUEST['store_id'];
    		$condition_seller['member_id'] = $_REQUEST['member_id'];
    	}    	
        $model_seller = Model('seller');
        $seller_info = $model_seller->getSellerInfo($condition_seller);
    	if(empty($seller_info)){
		//防止当前登录店铺账号被删除
    		$model_mb_user_token = Model('mb_user_token');
    		$mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($token);
    		$seller_info = $model_seller->getSellerInfo(array('member_id'=>$mb_user_token_info['member_id']));
    	}
        $store_list =array();
        $saleman_arr = array();
        $saleman_list = array();
        if(!empty($seller_info)){
        	$seller_info['seller_name'] = empty($seller_info['nick_name'])?$seller_info['seller_name']:$seller_info['nick_name'];
        	$saleman_arr['saleman_id'][0] = $seller_info['seller_id'];
        	$saleman_arr['saleman_name'][0] = $seller_info['seller_name'];
        	$saleman_arr['saleman_member_id'][0] = $seller_info['member_id'];
        	//当前店铺下，管理导购列表
        	$saleman_ids = $seller_info['saleman_id'];
        	if(!empty($saleman_ids)){
        		$seller_list = $model_seller->getSellerList(array('seller_id'=>array('in',$saleman_ids)),'','','seller_id,seller_name,nick_name,member_id');
        		foreach ($seller_list as $skey=> $saleman){
        			$saleman_arr['saleman_id'][] = $saleman_list[$skey]['saleman_id'] = $saleman['seller_id'];
        			$saleman_arr['saleman_name'][] = $saleman_list[$skey]['saleman_name'] = empty($saleman['nick_name'])?$saleman['seller_name']:$saleman['nick_name'];
        			$saleman_arr['saleman_member_id'][] = $saleman_list[$skey]['saleman_member_id'] = $saleman['member_id'];
        			$saleman_list[$skey]['saleman_avatar'] = getMemberAvatarForID($saleman['member_id']);//导购员头像
        		}
        	}
        	$this->seller_info = $seller_info;
        	$this->saleman = $saleman_arr; //管理导购一维
        	$this->saleman_list = $saleman_list; //管理导购列表
        	$model_seller_group = Model('seller_group');
        	$this->seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $this->seller_info['seller_group_id']));
        	if(empty($this->seller_group_info)){
        		output_error('该账户还未开通管理店铺的相关权限，请联系店长');
        	}
        }else{
        	output_error('您登录的账号，目前没有可管理的店铺，请联系店长设置权限后再登录'.$seller_id, array('error_code'=>CODE_InvalidSession));
        }
        $this->seller_id = $this->seller_info['seller_id'];
        $this->member_id = $this->seller_info['member_id'];
        $this->store_id = $store_id = $this->seller_info['store_id'];
        $model_member = Model('member');
        $model_store = Model('store');        
        //是否店铺用户，是登录小程序
        $this->store_info = $model_store->getStoreInfoByID($store_id);
        if(empty($this->store_info)) {
        	output_error('当前店铺已经不存在，请联系管理员', array('error_code'=>CODE_InvalidSession));
        }else{
        	// 店铺头像
        	$this->store_info['store_avatar'] = $this->store_info['store_avatar'] ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/LOGO/'.$this->store_info['store_avatar']: UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');
        	//实体店导航地址
        	//$this->store_info['map_url'] = Model('store_map')->getTXMapUrl($this->store_id);
        }
               
    }

    /**
     * 微信小程序或APP 目录列表
     * @return multitype:multitype:string multitype:multitype:string
     */
    private function _getAppMenuList() {
    	$menu_list = array();
    	$menu_list['wxapp']  = array('name' => '小程序权限', 'child' => array(
    			array('name' => '快捷收银', 'power'=>'cashier', 'url'=>'cashier_quick'),
    			array('name' => '邀请会员', 'power'=>'invitation', 'url'=>'index'),
    			array('name' => '商品管理', 'power'=>'goods', 'url'=>'list'),
    			array('name' => '入库管理', 'power'=>'warehousing', 'url'=>'index'),
    			array('name' => '订单管理', 'power'=>'order', 'url'=>'list'),
    			array('name' => '库存管理', 'power'=>'stock', 'url'=>'index'),
    	));
    	/*
    	$menu_list['goods']  = array('name' => '商品', 'child' => array(
    			array('name' => '商品发布', 'power'=>'store_goods_add', 'url'=>'index'),
    			array('name' => '出售中的商品', 'power'=>'store_goods_online', 'url'=>'index'),
    			array('name' => '市场选货', 'power'=>'store_goods_offline', 'url'=>'index'),
    	));
    	$menu_list['order']  = array('name' => '订单', 'child' => array(
    			array('name' => '快捷收银', 'power'=>'cashier_quick', 'url'=>'index'),
    			array('name' => '交易订单', 'power'=>'store_order', 'url'=>'index'),
    			array('name' => '虚拟订单', 'power'=>'store_vr_order', 'url'=>'index'),
    			array('name' => '发货', 'power'=>'store_deliver', 'url'=>'index'),
    			array('name' => '评价管理', 'power'=>'store_evaluate', 'url'=>'list'),
    			array('name' => '发货设置', 'power'=>'store_deliver_set', 'url'=>'daddress_list'),
    	));
    	*/
    	return $menu_list;
    }
    /*
     * app商家目录列表
     */
    public function getAppSellerMenuList($limits) {
    	$seller_menu = array();
    	$menu_list = $this->_getAppMenuList();
    	foreach ($menu_list as $key => $value) {
    		foreach ($value['child'] as $child_key => $child_value) {
    			if (!in_array($child_value['power'], $limits)) {
    				unset($menu_list[$key]['child'][$child_key]);
    			}
    		}
    		if(count($menu_list[$key]['child']) > 0) {
    			$seller_menu[$key] = $menu_list[$key];
    		}
    	}
    	$seller_function_list = $this->_getSellerFunctionList($seller_menu);
    	return array('app_seller_menu' => $seller_menu, 'app_seller_function_list' => $seller_function_list);
    }
    private function _getSellerFunctionList($menu_list) {
    	$format_menu = array();
    	foreach ($menu_list as $key => $menu_value) {
    		foreach ($menu_value['child'] as $submenu_value) {
    			$format_menu[$submenu_value['power']] = array(
    					'model' => $key,
    					'model_name' => $menu_value['name'],
    					'name' => $submenu_value['name'],
    					'url' => $submenu_value['power'].'/'.$submenu_value['url'],
    			);
    		}
    	}
    	return $format_menu;
    }
    /**
     * 商家消息数量
     * @param unknown $store_id 店铺ID
     * @param unknown $seller_id 导购ID
     * @param unknown $seller_smt_limits //消息权限
     */
    private function checkSellerMsg($store_id,$seller_id,$seller_smt_limits) {
    	$where = array();
    	$where['store_id'] = $store_id;
    	$where['sm_readids'] = array('exp', 'sm_readids NOT LIKE \'%,'.$seller_id.',%\' OR sm_readids IS NULL');
    	if ($seller_smt_limits !== false) {
    		$where['smt_code'] = array('in', $seller_smt_limits);
    	}
    	$countnum = Model('store_msg')->getStoreMsgCount($where);
    }
}


class BaseSellerControl extends wxappControl{
	
	protected $store_id; //默认店铺ID
	protected $store_info; //默认店铺资料
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->store_id = $_REQUEST['store_id'];
		if(empty($this->store_id)){
			output_error('您登录的店铺已经不存在，请更换账户后再登录', array('error_code'=>CODE_InvalidSession));
		}
		$this->store_info = Model('store')->getStoreInfoByID($this->store_id);
	}
	/**
	 * 记录卖家日志
	 *
	 * @param $content 日志内容
	 * @param $state 1成功 0失败
	 */
	protected function recordSellerLog($content = '', $state = 1){
		$seller_info = array();
		$seller_info['log_content'] = $content;
		$seller_info['log_time'] = TIMESTAMP;
		$seller_info['log_seller_id'] = $_SESSION['seller_id'];
		$seller_info['log_seller_name'] = $_SESSION['seller_name'];
		$seller_info['log_store_id'] = $_SESSION['store_id'];
		$seller_info['log_seller_ip'] = getIp();
		$seller_info['log_url'] = $_GET['act'].'&'.$_GET['op'];
		$seller_info['log_state'] = $state;
		$model_seller_log = Model('seller_log');
		$model_seller_log->addSellerLog($seller_info);
	}

	/**
	 * 记录店铺费用
	 *
	 * @param $cost_price 费用金额
	 * @param $cost_remark 费用备注
	 */
	protected function recordStoreCost($cost_price, $cost_remark) {
		// 平台店铺不记录店铺费用
		if (checkPlatformStore()) {
			return false;
		}
		$model_store_cost = Model('store_cost');
		$param = array();
		$param['cost_store_id'] = $_SESSION['store_id'];
		$param['cost_seller_id'] = $_SESSION['seller_id'];
		$param['cost_price'] = $cost_price;
		$param['cost_remark'] = $cost_remark;
		$param['cost_state'] = 0;
		$param['cost_time'] = TIMESTAMP;
		$model_store_cost->addStoreCost($param);

		// 发送店铺消息
		$param = array();
		$param['code'] = 'store_cost';
		$param['store_id'] = $_SESSION['store_id'];
		$param['param'] = array(
				'price' => $cost_price,
				'seller_name' => $_SESSION['seller_name'],
				'remark' => $cost_remark
		);

		QueueClient::push('sendStoreMsg', $param);

	}

	/**
	 * 自动发布店铺动态
	 *
	 * @param array $data 相关数据
	 * @param string $type 类型 'new','coupon','xianshi','mansong','bundling','groupbuy'
	 *            所需字段
	 *            new       goods表'             goods_id,store_id,goods_name,goods_image,goods_price,goods_freight
	 *            xianshi   p_xianshi_goods表'   goods_id,store_id,goods_name,goods_image,goods_price,goods_freight,xianshi_price
	 *            mansong   p_mansong表'         mansong_name,start_time,end_time,store_id
	 *            bundling  p_bundling表'        bl_id,bl_name,bl_img,bl_discount_price,bl_freight_choose,bl_freight,store_id
	 *            groupbuy  goods_group表'       group_id,group_name,goods_id,goods_price,groupbuy_price,group_pic,rebate,start_time,end_time
	 *            coupon在后台发布
	 */
	public function storeAutoShare($data, $type) {
		$param = array(
				3 => 'new',
				4 => 'coupon',
				5 => 'xianshi',
				6 => 'mansong',
				7 => 'bundling',
				8 => 'groupbuy'
		);
		$param_flip = array_flip($param);
		if (!in_array($type, $param) || empty($data)) {
			return false;
		}

		$auto_setting = Model('store_sns_setting')->getStoreSnsSettingInfo(array('sauto_storeid' => $_SESSION ['store_id']));
		$auto_sign = false; // 自动发布开启标志

		if ($auto_setting['sauto_' . $type] == 1) {
			$auto_sign = true;
			if (CHARSET == 'GBK') {
				foreach ((array)$data as $k => $v) {
					$data[$k] = Language::getUTF8($v);
				}
			}
			$goodsdata = addslashes(json_encode($data));
			if ($auto_setting['sauto_' . $type . 'title'] != '') {
				$title = $auto_setting['sauto_' . $type . 'title'];
			} else {
				$auto_title = 'nc_store_auto_share_' . $type . rand(1, 5);
				$title = Language::get($auto_title);
			}
		}
		if ($auto_sign) {
			// 插入数据
			$stracelog_array = array();
			$stracelog_array['strace_storeid'] = $this->store_info['store_id'];
			$stracelog_array['strace_storename'] = $this->store_info['store_name'];
			$stracelog_array['strace_storelogo'] = empty($this->store_info['store_avatar']) ? '' : $this->store_info['store_avatar'];
			$stracelog_array['strace_title'] = $title;
			$stracelog_array['strace_content'] = '';
			$stracelog_array['strace_time'] = TIMESTAMP;
			$stracelog_array['strace_type'] = $param_flip[$type];
			$stracelog_array['strace_goodsdata'] = $goodsdata;
			Model('store_sns_tracelog')->saveStoreSnsTracelog($stracelog_array);
			return true;
		} else {
			return false;
		}
	}

	/**
	 * 商家消息数量
	 */
	private function checkStoreMsg() {//判断cookie是否存在
		$cookie_name = 'storemsgnewnum'.$_SESSION['seller_id'];
		if (cookie($cookie_name) != null && intval(cookie($cookie_name)) >=0){
			$countnum = intval(cookie($cookie_name));
		}else {
			$where = array();
			$where['store_id'] = $_SESSION['store_id'];
			$where['sm_readids'] = array('exp', 'sm_readids NOT LIKE \'%,'.$_SESSION['seller_id'].',%\' OR sm_readids IS NULL');
			if ($_SESSION['seller_smt_limits'] !== false) {
				$where['smt_code'] = array('in', $_SESSION['seller_smt_limits']);
			}
			$countnum = Model('store_msg')->getStoreMsgCount($where);
			setNcCookie($cookie_name,intval($countnum),2*3600);//保存2小时
		}
		Tpl::output('store_msg_num',$countnum);
	}
}