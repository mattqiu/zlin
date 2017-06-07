<?php
/**
 * mobile父类
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

/********************************** 前台control父类 **********************************************/

class mobileControl{

    //客户端类型
    protected $client_type_array = array('android', 'wap', 'wechat', 'ios', 'windows');
    //列表默认分页数
    protected $page = 5;


	public function __construct() {
        Language::read('mobile');

        //分页数处理
        $page = intval($_GET['page']);
        if($page > 0) {
            $this->page = $page;
        }
    }
	
	/**
     * 获取购物车商品数量
     */
    protected function getCartCount($key) {
		if(empty($key)) {
			return 0;
		}
		$model_mb_user_token = Model('mb_user_token');
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            return 0;
        }
		$save_type = 'db';
		$cart_num = Model('cart')->getCartNum($save_type,array('buyer_id'=>$mb_user_token_info['member_id']));//查询购物车商品种类
		return $cart_num;        
    }
	
	protected function getMemberIdIfExists()
    {
        $key = $_POST['key'];
        if (empty($key)) {
            $key = $_GET['key'];
        }

        $model_mb_user_token = Model('mb_user_token');
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if (empty($mb_user_token_info)) {
            return 0;
        }

        return $mb_user_token_info['member_id'];
    }
}

class mobileHomeControl extends mobileControl{
	public function __construct() {
        parent::__construct();		
    }	
} 

class mobileMemberControl extends mobileControl{   
	
    protected $member_info = array();

	public function __construct() {
        parent::__construct();
        $agent = $_SERVER['HTTP_USER_AGENT']; 

        $model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }		
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录', array('login' => '0','error_code'=>CODE_InvalidSession));
        }		
        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);        
        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0','error_code'=>CODE_InvalidUsernameOrPassword));
        } else {
			$this->member_info['client_type'] = $mb_user_token_info['client_type'];
            $this->member_info['openid'] = $mb_user_token_info['openid'];
            $this->member_info['token'] = $mb_user_token_info['token'];
		    $level_name = $model_member->getOneMemberGrade($this->member_info['member_exppoints']);
			$this->member_info['level_name'] = $level_name['level_name'];
			$this->member_info['member_grade'] = $level_name['level'];
			
            //读取卖家信息
            $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
            $this->member_info['myself_store_id'] = $seller_info['store_id'];
			if (intval($_SESSION['is_login']) !=1){
				$model_member->createSession($this->member_info,true);
			}
			$this->store_info = Model('store')->getStoreInfoByID($seller_info['store_id']);
			if($this->store_info){
				// 店铺等级
				if ($this->store_info['is_own_shop'] == 1) {
					$this->store_grade = array(
							'sg_id' => '0',
							'sg_name' => '自营店铺专属等级',
							'sg_goods_limit' => '0',
							'sg_album_limit' => '0',
							'sg_space_limit' => '999999999',
							'sg_template_number' => '6',
							// see also store_settingControl.themeOp()
							// 'sg_template' => 'default|style1|style2|style3|style4|style5',
							'sg_price' => '0.00',
							'sg_description' => '',
							'sg_function' => 'editor_multimedia',
							'sg_sort' => '0',
					);
				} else {
					$store_grade = rkcache('store_grade', true);
					$this->store_grade = $store_grade[$this->store_info['grade_id']];
				}
				//是否店铺用户，是登录wadmin
				$seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
				$store_info = Model('store')->getStoreInfoByID($seller_info['store_id']);
				if($store_info) {
					session_start();
					$_SESSION['wadmin_store_id'] = $store_info['store_id'];
					$_SESSION['wadmin_seller_id'] = $this->member_info['member_id'];
					$_SESSION['wadmin_seller_name'] = $this->member_info['member_name'];
					$_SESSION['is_own_shop'] = $store_info['is_own_shop'];
					// 更新卖家登陆时间
					Model('seller')->editSeller(array('last_login_time' => TIMESTAMP), array('seller_id' => $seller_info['seller_id']));
					$model_seller_group = Model('seller_group');
					$seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $seller_info['seller_group_id']));
					$model_store = Model('store');
					$store_info = $model_store->getStoreInfoByID($seller_info['store_id']);
					$_SESSION['is_login'] = '1';
					$_SESSION['member_id'] = $this->member_info['member_id'];
					$_SESSION['member_name'] = $this->member_info['member_name'];
					$_SESSION['member_email'] = $this->member_info['member_email'];
					$_SESSION['is_buy'] = $this->member_info['is_buy'];
					$_SESSION['avatar'] = $this->member_info['member_avatar'];
					$_SESSION['grade_id'] = $store_info['grade_id'];
					$_SESSION['seller_id'] = $seller_info['seller_id'];
					$_SESSION['seller_name'] = $seller_info['seller_name'];
					$_SESSION['seller_is_admin'] = intval($seller_info['is_admin']);
					$_SESSION['store_id'] = intval($seller_info['store_id']);
					$_SESSION['store_name'] = $store_info['store_name'];
					$_SESSION['store_avatar'] = $store_info['store_avatar'];
					$_SESSION['is_own_shop'] = (bool)$store_info['is_own_shop'];
					$_SESSION['bind_all_gc'] = (bool)$store_info['bind_all_gc'];
					$_SESSION['seller_limits'] = explode(',', $seller_group_info['limits']);
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

class mobileStoreControl extends mobileControl{
	protected $store_id;
	protected $store_info;
    protected $store_decoration_only = false;
	protected $show_own_copyright = false;
	
	public function __construct() {
        parent::__construct();
		
		$this->store_id = intval($_REQUEST['store_id']);
        //店铺详细信息
        $model_store = Model('store');
        $this->store_info = $model_store->getStoreOnlineInfoByID($this->store_id);
        if (empty($this->store_info)) {
            output_error('店铺不存在');
        }		
		// 店铺头像
		$this->store_info['store_avatar'] = $this->store_info['store_avatar'] ? UPLOAD_SITE_URL.'/'.ATTACH_STORE.'/LOGO/'.$this->store_info['store_avatar']: UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_store_avatar');
		//是否只显示店铺装修部分
        if($this->store_info['store_decoration_switch'] > 0 & $this->store_info['store_decoration_only'] == 1) {
            $this->store_decoration_only = true;
        }
		//是否显示店铺版权信息
		if($this->store_info['show_own_copyright'] == 1) {
            $this->show_own_copyright = true;
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
		//判断是否是导购员，如果是则在商品详情页不显示推广链接
		$extension_type = 0;
		if (OPEN_STORE_EXTENSION_STATE > 0){
		    $extension_id=cookie('iMall_extension');			
		    if (!empty($extension_id)){
		        $extension_id=urlsafe_b64decode($extension_id);
		        $extension_type = Model('member')->getMemberTypeByID($extension_id);	
			}else{
				$extension_id=cookie('extension');
				$extension_id=urlsafe_b64decode($extension_id);
				$extension_type = Model('member')->getMemberTypeByID($extension_id);
			}
		}
		$this->store_info['extension_type'] = $extension_type;
		//实体店导航地址
		$this->store_info['map_url'] = Model('store_map')->getTXMapUrl($this->store_id);
		//推广链接
		$this->store_info['apply_extension_url'] = intval($_SESSION['is_login']) == 1?'extension_store_apply.html':'extension_store_register.html';
    }
}

class mobileSellerControl extends mobileControl{

    protected $seller_info = array();
    protected $seller_group_info = array();
    protected $member_info = array();
    protected $store_info = array();
    protected $store_grade = array();

    public function __construct() {
        parent::__construct();

        $model_mb_seller_token = Model('mb_seller_token');
        $_GET['key'] = $_GET['key']?$_GET['key']:$_COOKIE['key'];
        $key = $_POST['key']?$_POST['key']:$_GET['key'];
        if(empty($key)) {
            output_error('请登录', array('login' => '0'));
        }

        $mb_seller_token_info = $model_mb_seller_token->getSellerTokenInfoByToken($key);
        if(empty($mb_seller_token_info)) {
            output_error('请登录', array('login' => '0'));
        }

        $model_seller = Model('seller');
        $model_member = Model('member');
        $model_store = Model('store');
        $model_seller_group = Model('seller_group');

        $this->seller_info = $model_seller->getSellerInfo(array('seller_id' => $mb_seller_token_info['seller_id']));
        $this->member_info = $model_member->getMemberInfoByID($this->seller_info['member_id']);
        $this->store_info = $model_store->getStoreInfoByID($this->seller_info['store_id']);
        $this->seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $this->seller_info['seller_group_id']));

        // 店铺等级
        if (intval($this->store_info['is_own_shop']) === 1) {
            $this->store_grade = array(
                'sg_id' => '0',
                'sg_name' => '自营店铺',
                'sg_goods_limit' => '0',
                'sg_album_limit' => '0',
                'sg_space_limit' => '999999999',
                'sg_template_number' => '6',
                'sg_price' => '0.00',
                'sg_description' => '',
                'sg_function' => 'editor_multimedia',
                'sg_sort' => '0',
            );
        } else {
            $store_grade = rkcache('store_grade', true);
            $this->store_grade = $store_grade[$this->store_info['grade_id']];
        }

        if(empty($this->member_info)) {
            output_error('请登录', array('login' => '0'));
        } else {
            $this->seller_info['client_type'] = $mb_seller_token_info['client_type'];
        }
        Tpl::setLayout('seller_layout');
    }
}
class BaseSellerControl extends mobileControl{
	const MAX_RECORDNUM = 20;   // 允许插入新记录的最大次数，sns页面该常量是一样的。
	/**
	 * 构造函数
	 */
	public function __construct(){
		parent::__construct();
		$this->store_id = $_SESSION['store_id'];
		if(empty($this->store_id)){
			header('location:../wap/tmpl/member/login.html');
			exit;
		}
		$this->store_info = Model('store')->getStoreInfoByID($this->store_id);
		if($this->store_info){
			// 店铺等级
			if ($this->store_info['is_own_shop'] == 1) {
				$this->store_grade = array(
						'sg_id' => '0',
						'sg_name' => '自营店铺专属等级',
						'sg_goods_limit' => '0',
						'sg_album_limit' => '0',
						'sg_space_limit' => '999999999',
						'sg_template_number' => '6',
						// see also store_settingControl.themeOp()
						// 'sg_template' => 'default|style1|style2|style3|style4|style5',
						'sg_price' => '0.00',
						'sg_description' => '',
						'sg_function' => 'editor_multimedia',
						'sg_sort' => '0',
				);
			} else {
				$store_grade = rkcache('store_grade', true);
				$this->store_grade = $store_grade[$this->store_info['grade_id']];
			}
		}
		/**
		 * 设置布局文件内容
		*/
		Tpl::setLayout('seller_layout');
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