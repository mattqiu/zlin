<?php
/**
 * 推广管理
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class member_extensionControl extends BaseMemberControl {
	protected $extension_info = array();

	public function __construct() {
		parent::__construct();
		/**
		 * 读取语言包
		 */
		//Language::read('member_member_points,member_pointorder');
		/**
		 * 判断系统是否是推广员或导购员
		 */
		if ($this->member_info['mc_id']<1 || $this->member_info['mc_id']>2){
			showMessage('你不是推广员或导购员！',urlShop('member', 'home'),'html','error');
		}
		if ($this->member_info['mc_id']==1){
			$this->extension_info = Model('extension')->getSaleManInfo($this->member_info['store_id'],$this->member_info['member_id']);
		}else if ($this->member_info['mc_id']==2){
			$this->extension_info = Model('extension')->getPromotionInfo($this->member_info['store_id'],$this->member_info['member_id']);
		}
		if (empty($this->extension_info)){
			showMessage('你不是推广员或导购员！',urlShop('member', 'home'),'html','error');
		}
	}
	public function indexOp(){
		$this->my_achievementOp();
		exit;
	}
	
	/**
	 * 我的业绩
	 */
	public function my_achievementOp(){
		$model_detail = Model('order');
		
		$request_date_arr = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to'],true);
		$child_list = Model('extension')->GetAllPromotionChildID($_SESSION['member_id'],$this->member_info['mc_id'],$this->member_info['store_id']);
		
		$condition = array();
		$condition['saleman_id'] = array('in',$child_list);
		
		if (!empty($request_date_arr) && is_array($request_date_arr)){			
			$start = strtotime($request_date_arr['startdate']);
			$end = strtotime($request_date_arr['enddate'])+86400;
			
			$condition['add_time'] = array(array('egt',$start),array('lt',$end),'and');
		}
		if (!empty($_GET['give_status'])){			
			$condition['order_state'] = $_GET['give_status'];
		}else{			
			$condition['order_state'] = array('in', array(ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS));
		}		
		if($_GET['saleman_name'] != ''){
			$condition['saleman_name'] = array('like', '%' . $_GET['saleman_name'] . '%');
		}
		
		$achievement_list = $model_detail->getNormalOrderList($condition,20,'order_id, add_time,saleman_name,order_sn,store_name,buyer_name,goods_amount,order_state','order_id desc','',array('desc'));		
		Tpl::output('achievement_list',$achievement_list);
		Tpl::output('show_page', $model_detail->showpage());
		
		//商品总业绩:amounts 、订单总业绩：commis		
		$statistics_all = $model_detail->getStatisticsCommis(array('buyer_id'=>array('in',$child_list),'order_state'=>array('in', array(ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS))));
		Tpl::output('statistics_all',$statistics_all);
		//当前商品业绩:amounts 、当前订单业绩：commis
		$statistics_curr = $model_detail->getStatisticsCommis($condition);
		Tpl::output('statistics_curr',$statistics_curr);

		//信息输出
		Tpl::output('menu_highlight', 'my_achievement');
		// 面包屑
        $nav_link = array();
        $nav_link[] = array('title' => L('homepage'), 'link'=>SHOP_SITE_URL);
        $nav_link[] = array('title' => '我的商城',  'link' => urlShop('member', 'home'));
        $nav_link[] = array('title' => '我的推广业绩');
        Tpl::output('nav_link_list',$nav_link);
		
		self::profile_menu('achievement');
		Tpl::showpage('member_extension.achievement');
	}
	
	/**
	 * 我的收益
	 */
	public function my_incomeOp(){
		$model_detail = Model('extension_commis_detail');
		
		$request_date_arr = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to'],true);
		
		$condition = array();
		$condition['saleman_id'] = $_SESSION['member_id'];
		
		if (!empty($request_date_arr) && is_array($request_date_arr)){
			$start = strtotime($request_date_arr['startdate']);
			$end = strtotime($request_date_arr['enddate'])+86400;
			
			$condition['add_time'] = array(array('egt',$start),array('lt',$end),'and');
		}
		if (!empty($_GET['give_status'])){
			if ($_GET['give_status']<2){			
			  $condition['give_status'] = $_GET['give_status'];
			}
		}else{
			$condition['give_status'] = 0;
		}
		
		if($_GET['saleman_name'] != ''){
			$condition['extension_name'] = array('like', '%' . $_GET['saleman_name'] . '%');
		}
		
		$income_list = $model_detail->getCommisdetailList($condition,20);		
		Tpl::output('income_list',$income_list);
		Tpl::output('show_page', $model_detail->showpage());
		
		//已结算收益 总业绩:amounts 、总佣金:commis、总分成:shares
		//$condition['give_status'] = 1;
		$statistics_all = $this->extension_info['commis_totals'];//$model_detail->getStatisticsCommis(NULL,$_SESSION['member_id'],array('give_status'=>1));
		Tpl::output('statistics_all',$statistics_all);
		//待结算收益 当前业绩:amounts 、当前佣金:commis、当前分成:shares
		//$condition['give_status'] = 0;
		$statistics_curr = $model_detail->getStatisticsCommis(NULL,$_SESSION['member_id'],array('give_status'=>0));
		Tpl::output('statistics_curr',$statistics_curr['commis']);

		//信息输出
		Tpl::output('menu_highlight', 'my_income');
		// 面包屑
        $nav_link = array();
        $nav_link[] = array('title' => L('homepage'), 'link'=>SHOP_SITE_URL);
        $nav_link[] = array('title' => '我的商城',  'link' => urlShop('member', 'home'));
        $nav_link[] = array('title' => '我的推广收益');
        Tpl::output('nav_link_list',$nav_link);
		
		self::profile_menu('income');
		Tpl::showpage('member_extension.income');
	}
	
	/**
	 * 我的提佣记录
	 */
	public function my_commissionOp(){
		if($_GET['type'] == 'today'){	
			$main_title = '今日佣金结算';
			$sub_title  = date('Y-m-d',time());
		}elseif ($_GET['type'] == 'month'){
			$year  = date('Y',time());
			$month = date('m',time());
			$day31 = array('01','03','05','07','08','10','12');
			if(in_array($month, $day31)){
				$daynum = 31;
			}else{
				if($month == '02'){
					//二月判断是否是闰月
					if ($year%4==0 && ($year%100!=0 || $year%400==0)){
						$daynum = 29;
					}else{
						$daynum = 28;
					}
				}else{
					$daynum = 30;
				}
			}
			$main_title = intval($month).'月份佣金结算';
			$sub_title  = $year.'.'.$month.'.01-'.$year.'.'.$month.'.'.$daynum;
		}elseif ($_GET['type'] == 'year'){
			$year = date('Y',time());
			$main_title = $year.'年度佣金结算';
			$sub_title  = $year.'.01-'.$year.'.12';
		}elseif($_GET['type'] == 'week'){
			//默认显示本周统计
			$day = date('l',time());
			switch ($day){
				case 'Monday':
					$sub_title = date('Y.m.d',time()).'-'.date('Y.m.d',time()+86400*6);
					break;
				case 'Tuesday':
					$sub_title = date('Y.m.d',time()-86400).'-'.date('Y.m.d',time()+86400*5);
					break;
				case 'Wednesday':
					$sub_title = date('Y.m.d',time()-86400*2).'-'.date('Y.m.d',time()+86400*4);
					break;
				case 'Thursday':
					$sub_title = date('Y.m.d',time()-86400*3).'-'.date('Y.m.d',time()+86400*3);
					break;
				case 'Friday':
					$sub_title = date('Y.m.d',time()-86400*4).'-'.date('Y.m.d',time()+86400*2);
					break;
				case 'Saturday':
					$sub_title = date('Y.m.d',time()-86400*5).'-'.date('Y.m.d',time()+86400);
					break;
				case 'Sunday':
					$sub_title = date('Y.m.d',time()-86400*6).'-'.date('Y.m.d',time());
					break;
			}
			$main_title = '本周佣金结算';
		}else{				
			if($_GET['add_time_from'] != '' && $_GET['add_time_to'] != ''){
				$from = $_GET['add_time_from'];
				$to = $_GET['add_time_to'];
				$main_title = '佣金结算';
				$sub_title  = substr($from,0,4).'.'.substr($from,4,2).'.'.substr($from,6,2).'-'.substr($to,0,4).'.'.substr($to,4,2).'.'.substr($to,6,2);
			}else{
				$main_title = '佣金结算';
				$sub_title  = '';				
			}
		}
		
		$request_date_arr = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to'],true);
		
		$model_extcommis = Model('pd_extcommis');
		
		$where = array();
		$where['pde_member_id'] = $_SESSION['member_id'];
		//时段
		if (!empty($request_date_arr) && is_array($request_date_arr)){
			$start = strtotime($request_date_arr['startdate']);
			$end = strtotime($request_date_arr['enddate'])+86400;
			
			$where['pde_add_time'] = array(array('egt',$start),array('lt',$end),'and');
		}		
		$balance_list = $model_extcommis->getExtCommisList($where);		
		
		Tpl::output('balance_list',$balance_list);
		Tpl::output('page',$model_extcommis->showpage());
		
		Tpl::output('main_title',$main_title);
		Tpl::output('sub_title',$sub_title);
		
		//已结算收益 总业绩:amounts 、总佣金:commis、总分成:shares
		//$condition['give_status'] = 1;
		$model_detail = Model('extension_commis_detail');
		$statistics_all = $model_detail->getStatisticsCommis(NULL,$_SESSION['member_id'],array('give_status'=>1));
		Tpl::output('statistics_all',$statistics_all);
		
		// 面包屑
        $nav_link = array();
        $nav_link[] = array('title' => L('homepage'), 'link'=>SHOP_SITE_URL);
        $nav_link[] = array('title' => '我的商城',  'link' => urlShop('member', 'home'));
        $nav_link[] = array('title' => '我的积分兑换记录');
        Tpl::output('nav_link_list',$nav_link);
			
	    self::profile_menu('commission');
        Tpl::showpage('member_extension.balance');
	}
	
	/**
	 * 佣金明细
	 *
	 */
    public function my_balance_detailOP() {
		$pde_sn = $_GET['sn'];
		
		$condition = array();
		$condition['give_status'] = 1;
		$condition['saleman_id'] = $_SESSION['member_id'];
		$condition['pde_sn'] = $pde_sn;
		
		$model_detail = Model('extension_commis_detail');
		$detail_list = $model_detail->getCommisdetailList($condition,10);		
		Tpl::output('detail_list',$detail_list);
		Tpl::output('show_page',$model_detail->showpage());
		
		self::profile_menu('commission');
        Tpl::showpage('member_extension.balancedetail','null_layout');
    } 
	
	/**
	 * 申请提佣
	 */
	public function my_apply_commissionOp(){		
		//继承父类的member_info
        $member_info = $this->member_info;
        if (!$member_info){
            $member_info = $model_member->getMemberInfo(array('member_id'=>$_SESSION['member_id']),'member_email,member_email_bind,member_mobile,member_mobile_bind');
        }
		
		//修改密码、设置支付密码时，必须绑定邮箱或手机
        if (intval($member_info['member_email_bind']) == 0 && intval($member_info['member_mobile_bind']) == 0) {
            showMessage('请先绑定邮箱或手机','index.php?act=member_security&op=index','html','error');
        }
		Tpl::output('member_info',$member_info);

        self::profile_menu('apply_commission');        
        Tpl::showpage('member_extension.apply');
	}
	
	/**
	 * 申请提佣保存
	 */
	public function apply_commission_saveOp(){
		$model_member = Model('member');

        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$_SESSION['member_id']));
        if (empty($member_common_info) || !is_array($member_common_info)) {
            showDialog('验证失败');
        }
        if ($member_common_info['auth_code'] != $_POST['auth_code'] || TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {
            showDialog('验证码已被使用或超时，请重新获取验证码');
        }
        $data = array();
        $data['auth_code'] = '';
        $data['send_acode_time'] = 0;
        $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
        if (!$update) {
            showDialog('系统发生错误，如有疑问请与管理员联系');
        }
		
		$ai_addinfo = array();
		$ai_addinfo['truename'] = $this->member_info['member_truename'];
		$ai_addinfo['email'] = $this->member_info['member_email'];	
		$ai_addinfo['mobile'] = $this->member_info['member_mobile'];	
		$ai_addinfo['qq'] = $this->member_info['member_qq'];
		$ai_addinfo['areainfo'] = $this->member_info['member_areainfo'];
		$ai_addinfo['describe'] = $_POST['describe'];
		
		$data=array();
		$data['ai_from'] = $_SESSION['member_id'];		
		$data['ai_type'] = 4;		
		$data['ai_addinfo'] = serialize($ai_addinfo); //unserialize
		$data['ai_dispose'] = 0;
		$data['ai_views'] = 0;
		//$data['ai_replyinfo']='';
		$data['ai_addtime'] = time();
		//$data['ai_distime']=NULL;
		
		$model_apply = Model('apply_information');		
		$model_detail = Model('extension_commis_detail');
		
		$where = array();
		$where['saleman_id'] = $_SESSION['member_id'];
		$where['give_status'] = 0;		
		$store_list = $model_detail->getCommisStoreIDList($where);
		$apply_ok = '';
		$apply_fail = '';
		if (!empty($store_list) && is_array($store_list)){
			foreach ($store_list as $store_info) {		
		        $data['ai_target'] = $store_info['store_id'];			
		        $state = $model_apply->addApplyInfo($data);		
		        if($state) {
			        // 发送店铺消息
                    $param = array();
		            $param['code'] = 'new_apply_commission';
			        $param['store_id'] = $store_info['store_id'];
			        $param['param'] = array();
			        QueueClient::push('sendStoreMsg', $param);
					
					$apply_ok .= $store_info['store_name'].'　';	
		        } else {
					$apply_fail .= $store_info['store_name'].'　';
		        };		
			} //end for foreach
			$msg = '';
			if ($apply_ok != ''){
				$msg = $apply_ok.'推广积分兑换申请提交成功！';
			}
			if ($apply_fail != ''){
				$msg = $apply_fail.'推广积分兑换申请提交失败！';
			}
			showDialog($msg,urlShop('member_extension', 'my_income'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}else{//end for if
		    showDialog('无推广积分可兑换！',urlShop('member_extension', 'my_income'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		}		
	}
	
	/**
	 * 我的团队
	 */
	public function my_subordinateOp(){
		$fields = 'member_id,member_name,parent_id,parent_tree,mc_id';
        $model_promotion = Model('extension');
		
		//父ID
		$parent_id = $_GET['parent_id']?intval($_GET['parent_id']):$_SESSION['member_id'];
		$curr_deep = $_GET['deep']?intval($_GET['deep']):0;
		
		$condition=array();			
		$condition['parent_id'] = $parent_id;
		$promotion_list = $model_promotion->getPromotionList($this->member_info['store_id'],$condition,$fields,20,'mc_id desc,ext_id asc',$curr_deep, $this->extension_info['mc_id']<2);		
		
		if ($_GET['ajax'] == '1'){
			//转码
			if (strtoupper(CHARSET) == 'GBK'){
				$promotion_list = Language::getUTF8($promotion_list);
			}
			$output = json_encode($promotion_list);
			print_r($output);
			exit;
		}else {		
		    Tpl::output('promotion_list',$promotion_list);
		    Tpl::output('page',$model_promotion->showpage());
			
			$my_subordinate = $model_promotion->countPromotionChild($this->member_info['store_id'], $_SESSION['member_id']);
		    Tpl::output('my_subordinate',$my_subordinate);
			
			$my_subordinate_all = $model_promotion->countAllPromotionChild($this->member_info['store_id'], $_SESSION['member_id']);
		    Tpl::output('my_subordinate_all',$my_subordinate_all);
			
			$apply_count = Model('apply_information')->getApplyCount(array('ai_target'=>$this->member_info['member_id'],'ai_dispose'=>0,'ai_type'=>3));
			Tpl::output('my_apply_count',$apply_count);
			
			//信息输出
		    Tpl::output('menu_highlight', 'my_subordinate');
		    // 面包屑
            $nav_link = array();
            $nav_link[] = array('title' => L('homepage'), 'link'=>SHOP_SITE_URL);
            $nav_link[] = array('title' => '我的商城',  'link' => urlShop('member', 'home'));
            $nav_link[] = array('title' => '我的团队');
            Tpl::output('nav_link_list',$nav_link);
			
			self::profile_menu('subordinate');
            Tpl::showpage('member_extension.subordinate');
		}		
	}
	
	/**
	 * 下线信息
	 */
    public function promotion_infoOp(){		
		$promotion_id = intval($_GET["promotion_id"]);
		if (empty($promotion_id) || ($promotion_id<=0)){
			showDialog('非法参数');
		}

		$model_promotion = Model('extension');
		$promotion_info = $model_promotion->getPromotionDetailInfo($promotion_id);		
		
		Tpl::output('promotion_info',$promotion_info);
		Tpl::showpage('member_extension.subordinate.info','null_layout');
	}
	
	/**
	 * 业绩明细
	 *
	 */
    public function promotion_detailOP() {
		$model_detail = Model('order');
		
		$promotion_id	 = $_REQUEST['promotion_id'];
		$promotion_info=Model('member')->getMemberInfo(array('member_id'=>$promotion_id),'member_name,member_truename,store_id');
		$promotion_name = $promotion_info['member_name'];
		
		$request_date_arr = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to'],true);

		$condition = array();
		if($promotion_id != ''){
			$condition['saleman_id'] = $promotion_id;
			Tpl::output('promotion_id',$promotion_id);
		}		
		if (!empty($request_date_arr) && is_array($request_date_arr)){			
			$start = strtotime($request_date_arr['startdate']);
			$end = strtotime($request_date_arr['enddate'])+86400;
			
			$condition['add_time'] = array(array('egt',$start),array('lt',$end),'and');
		}
		if (!empty($_GET['give_status'])){
			$condition['order_state'] = $_GET['give_status'];
		}else{
			$condition['order_state'] = array('in', array(ORDER_STATE_NEW, ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS));
		}
		if($_GET['saleman_name'] != ''){
			$condition['buyer_name'] = array('like', '%' . $_GET['saleman_name'] . '%');
		}		
		
		$detail_list = $model_detail->getNormalOrderList($condition,20,'order_id, add_time,saleman_name,order_sn,store_name,buyer_name,goods_amount,order_state','order_id desc','',array('desc'));
		Tpl::output('detail_list',$detail_list);
		Tpl::output('show_page',$model_detail->showpage());
		
		self::profile_menu('detail',$promotion_name);
        Tpl::showpage('member_extension.subordinate.detail');
    }
	
	/**
	 * 推广员申请列表
	 *
	 */
    public function promotion_applyOP() {
		$model_apply = Model('apply_information');
		
		$apply_list = $model_apply->getPromotionSubApplyList($this->member_info['member_id']);		
		
		Tpl::output('apply_list',$apply_list);
		Tpl::output('page',$model_apply->showpage());
		
		//信息输出
		Tpl::output('menu_highlight', 'my_subordinate');
		// 面包屑
        $nav_link = array();
        $nav_link[] = array('title' => L('homepage'), 'link'=>SHOP_SITE_URL);
        $nav_link[] = array('title' => '我的商城',  'link' => urlShop('member', 'home'));
        $nav_link[] = array('title' => '申请管理');
        Tpl::output('nav_link_list',$nav_link);
			
	    self::profile_menu('apply');
        Tpl::showpage('member_extension_apply.list');		
	}
	
	/**
	 * 推广员申请信息
	 *
	 */
    public function apply_infoOp() {
		$ai_id = $_GET['id'];
		
        $model_apply = Model('apply_information');
		
		$apply_info = $model_apply->getApplyInfo($ai_id);		
		Tpl::output('apply_info',$apply_info);
			
        Tpl::showpage('member_extension_apply.infomation','null_layout');
    }
	
	/**
	 * 推广员申请信息编辑
	 *
	 */
    public function apply_editOp() {
        $ai_id = $_GET['id'];
		
        $model_apply = Model('apply_information');
		
		$apply_info = $model_apply->getApplyInfo($ai_id);		
		Tpl::output('apply_info',$apply_info);

        Tpl::showpage('member_extension_apply.edit','null_layout');
    }
	
	/**
	 * 推广员申请信息保存
	 *
	 */
    public function apply_saveOp() {
        $ai_id = $_POST['id'];
		$verify = $_POST['verify'];
		if (empty($ai_id) || $ai_id<=0 || $verify<0 || $verify>2){
			showDialog('参数错误!',urlShop('member_extension', 'promotion_apply'));
		}		
		
        $model_apply = Model('apply_information');
		$MemberMsg = 'apply_extension_failed';
		
		$apply_info = $model_apply->getApplyInfo($ai_id);
		$member_id = $apply_info['ai_from'];		
		
		$MemberMsg = 'apply_extension_failed';
		if ($verify==2){						
			$model_member = Model('member');
			$model_promotion = Model('extension');
			
			if (OPEN_STORE_EXTENSION_STATE!=10){
				$model_store = Model('store');
			    $promotion_count = $model_promotion->getPromotionCount($this->member_info['store_id']); //店铺已有的推广员数量		
			    $promotion_limit = $model_store->getPromotionLimit($this->member_info['store_id']); //店铺推广员数量限制
			    $promotion_level = $model_store->getPromotionLevel($store_id);	//店铺推广员层级限制
			    if ($promotion_count >= $promotion_limit){
				    showDialog('店铺推广员已满，审核失败！',urlShop('member_extension', 'promotion_apply'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
		        }
			}else{
				$promotion_level = C('gl_promotion_level');
			}
		    $member_org = $model_member->getMemberInfoByID($member_id,'member_id,member_name,mc_id');
			if (empty($member_org) || empty($member_org['member_id']) || $member_org['mc_id']==2){
				showDialog('无效的推广申请信息！',urlShop('member_extension', 'promotion_apply'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
			}
			
			//添加推广员		
			$parent_info = $this->extension_info;
			$parent_id = $parent_info['member_id'];
			if (empty($parent_info['parent_tree']) || $parent_info['parent_tree']=='' || $parent_info['parent_tree']=='0'){				
			    $parent_tree = '|'.$parent_id.'|';
				$mc_level = 2;
			}else{
			    //生成层级数
			    $parent_str  = trim($parent_info['parent_tree'],'|');						
			    $parent_list = explode('|',$parent_str);						
			    if (count($parent_list) > ($promotion_level-2)){							
				    unset($parent_list[0]);							
				    $parent_tree = '|';
				    foreach($parent_list as $v){
					    $parent_tree .= $v . '|';
				    }
				    $parent_tree .= $parent_id.'|';
			    }else{
				    $parent_tree = '|'.$parent_str.'|'.$parent_id.'|';
			    }  
				$mc_level = $parent_info['mc_level']+1; 
			}
			//计算身份类型
			$holder_id = $parent_info['holder_id'];
			$ceo_id = $parent_info['ceo_id'];
			$coo_id = $parent_info['coo_id'];
			$manager_id = $parent_info['manager_id'];							
			$mc_id = 1;

		    $promotion = array();
			$promotion['member_id']  = $member_org['member_id'];
			$promotion['member_name']= $member_org['member_name'];			
			$promotion['mc_id']      = $mc_id;	//推广员类型:1 普通推广员 2经理 3协理 4首席 5股东 10 导购员
			$promotion['mc_level']   = $mc_level;
			$promotion['mc_real']    = 1; //推广员是否有实体店:1 无实体店 10 有实体店
		    $promotion['store_id']   = $parent_info['store_id'];
		    $promotion['parent_id']  = $parent_id;		
		    $promotion['parent_tree']= $parent_tree;				
			$promotion['holder_id']= $holder_id; //股东
			$promotion['ceo_id']= $ceo_id; //首席
			$promotion['coo_id']= $coo_id; //协理
			$promotion['manager_id']= $manager_id; //经理
			$promotion['commis_balance']= 0; //本期佣金
			$promotion['manageaward_balance']= 0; //本期高管奖
			$promotion['perforaward_balance']= 0; //本期绩优奖
			$promotion['commis_totals']= 0; //累积佣金
			$promotion['createdtime']= TIMESTAMP; //加入时间
			
			$promotion_info = $model_promotion->getExtensionByMemberID($member_org['member_id']);
			if (empty($promotion_info)){
			    $states = $model_promotion->addExtension($promotion);
			}else{
				$states = $model_promotion->editExtension($promotion,array('member_id'=>$member_org['member_id']));
			}
		    if ($states){
			    $MemberMsg = 'apply_extension_success';
		
		        $member_info = array();
		        $member_info['mc_id'] = 2;
		        $member_info['store_id'] = OPEN_STORE_EXTENSION_STATE==10?GENERAL_PLATFORM_EXTENSION_ID:$parent_info['store_id'];
		        $model_member->editMember(array('member_id'=>$member_id),$member_info);
			}else{
				showDialog('系统错误，请稍候再试!');
			}			
		}
		
		$apply_info = array();
		$apply_info['ai_dispose'] = $verify;
		$apply_info['ai_replyinfo'] = $_POST['ai_replyinfo'];
		$apply_info['ai_distime'] = time();
		
		$model_apply->editApplyInfo($apply_info,array('ai_id'=>$ai_id));
		
		// 发送买家消息
        $param = array();
        $param['code'] = $MemberMsg;  
        $param['member_id'] = $member_id;
        $param['param'] = array(
            'store_url' => urlShop('index', 'index'),
            'store_name' => C('site_name'),
		    'site_name' => C('site_name'),
			'replyinfo' => $_POST['ai_replyinfo'],
			'mail_send_time'=>date("Y-m-d",time())
        );
        QueueClient::push('sendMemberMsg', $param);

        showDialog('审核操作成功!',urlShop('member_extension', 'promotion_apply'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');		
    }
	
	/**
	 * 删除推广员申请信息
	 *
	 */
    public function apply_delOp() {
		$ai_id = $_GET['id'];
		if (empty($ai_id) || $ai_id<=0){
			showDialog('参数错误!',urlShop('member_extension', 'promotion_apply'));
		}
		Model('apply_information')->delApplyInfo(array('ai_id'=>$ai_id));
		showDialog('申请信息删除成功!',urlShop('member_extension', 'promotion_apply'));
	}	
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @param array 	$array		附加菜单
	 * @return
	 */
	private function profile_menu($menu_key='',$promotion_name='') {
		if ($menu_key == 'achievement'){
		    $menu_array = array(
			    1=>array('menu_key'=>'achievement',	'menu_name'=>'我的销售业绩',	'menu_url'=>'index.php?act=member_extension&op=my_achievement'),
		    );
		}elseif($menu_key == 'income' || $menu_key == 'commission' || $menu_key == 'apply_commission'){
		    $menu_array		= array();
		    $menu_array[1] = array('menu_key'=>'income', 'menu_name'=>'我的幸福指数',	'menu_url'=>'index.php?act=member_extension&op=my_income');
			$menu_array[2] = array('menu_key'=>'commission', 'menu_name'=>'兑换记录',	'menu_url'=>'index.php?act=member_extension&op=my_commission');
			if ($menu_key == 'apply_commission'){
				$menu_array[3] = array('menu_key'=>'apply_commission', 'menu_name'=>'申请兑换',	'menu_url'=>'index.php?act=member_extension&op=my_apply_commission');
			}			
		}elseif($menu_key == 'subordinate' || $menu_key == 'apply' || $menu_key == 'detail'){
		    $menu_array		= array();
            $menu_array[1] = array('menu_key'=>'subordinate', 'menu_name'=>'我的团队',	'menu_url'=>'index.php?act=member_extension&op=my_subordinate');
			$menu_array[2] = array('menu_key'=>'apply',	'menu_name'=>'申请管理',	'menu_url'=>'index.php?act=member_extension&op=promotion_apply');
			if ($menu_key == 'detail'){
				$menu_array[3] = array('menu_key'=>'detail', 'menu_name'=>$promotion_name.'销售明细', 'menu_url'=>'index.php?act=member_extension&op=promotion_detail');
			}
		}
		
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}