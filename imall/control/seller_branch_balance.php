<?php
/**
 * 分店结算管理
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class seller_branch_balanceControl extends BaseBranchControl {
	
	protected $appay_count = '';

    public function __construct() {
    	parent::__construct() ;
    	Language::read('member_layout');
		
		$this->appay_count = Model('apply_information')->getApplyCount(array('ai_target'=>$_SESSION['store_id'],'ai_type'=>4,'ai_dispose'=>0));
		if ($this->appay_count<=0){
			$this->appay_count = '';
		}else{
			$this->appay_count = '('.$this->appay_count.')';
		}
    }
	
	/**
	 * 分店结算列表
	 *
	 */
    public function indexOp() {
		$where = "store_id = '".$_SESSION['store_id']."' and order_state <100 and payment_state=0";		// 	
		if($_GET['branch_name'] != ''){			
			$where .= " and (branch_id like '%".$_GET['branch_name']."%' or branch_name like '%".$_GET['branch_name']."%')";
			$main_title = $_GET['branch_name'].$main_title;
		}
		
        $flow_list = $this->_calculatebBalance($where);

		//模版输出
		Tpl::output('flow_list',$flow_list);	
	
		self::profile_menu('index');
		Tpl::showpage('seller_branch_balance.index');
    }
	
	/**
	 * 计算分店补货和退货费用
	 */
    public function  _calculatebBalance($where = array(),$order = 'order_amount desc', $page = '20'){		
		$model = Model();
		$table = 'branch_order';
		$field = 'sum(goods_amount) as goods_totals, sum(shipping_fee) as shipping_totals, sum(order_amount) as order_totals, branch_id, branch_name';
		$group = 'branch_id';
		
		
		
		$flow_list = $model->table($table)->field($field)->where($where)->group($group)->page($page)->order($order)->select();
		Tpl::output('show_page',$model->showpage());
		
		return $flow_list;
	}
	
	/**
	 * 佣金结算
	 *
	 */
    public function balance_editOp(){
		$id = $_GET['id'];
		$promotion_id = '';
		if (!empty($id)){
			$promotion_id = ' and saleman_id in('.$id.')';
		}
		//提佣金额
		$where = "extension_commis_detail.store_id = '".$_SESSION['store_id']."' and extension_commis_detail.give_status = 0".$promotion_id;
        $flow_list = $this->calculateCommission($_SESSION['store_id'], $this->store_info['promotion_level'], $where);
		$pay_commis = 0;
		$promotion_name = '';
		if (!empty($flow_list) && is_array($flow_list)){
			foreach($flow_list as $k => $info) {
				$pay_commis = $pay_commis + $info['share_totals'];
				$promotion_name .= $info['member_name'].',';
			}			
		}
		if (empty($id)){
			$promotion_name = '全部待结算帐号';
		}
		$promotion_name = trim($promotion_name,',');
		Tpl::output('pay_commis',$pay_commis);
		Tpl::output('promotion_name',$promotion_name);
		Tpl::output('promotion_id',$id);
		//帐户余额
		$my_predeposit = Model('member')->getMemberPredepositByID($_SESSION['member_id']);
		Tpl::output('my_predeposit',$my_predeposit);

        Tpl::showpage('seller_commisputout_balance.edit','null_layout');		
	}
	
	/**
	 * 佣金结算保存
	 *
	 */
    public function balance_saveOp(){
		$id = $_POST['id'];
		$promotion_id = '';
		if (!empty($id)){
			$promotion_id = ' and saleman_id in('.$id.')';
		}
		//提佣金额
		$model_apply = Model('apply_information'); 
		$where = "extension_commis_detail.store_id = '".$_SESSION['store_id']."' and extension_commis_detail.give_status = 0".$promotion_id;        
        $flow_list = $this->calculateCommission($_SESSION['store_id'], $this->store_info['promotion_level'], $where);
		$pay_commis = 0;
		if (!empty($flow_list) && is_array($flow_list)){
			foreach($flow_list as $k => $info) {
				$pay_commis = $pay_commis + $info['share_totals'];
			}			
		}		    
		//帐户余额
		$my_predeposit = Model('member')->getMemberPredepositByID($_SESSION['member_id']);
	    if ($pay_commis>$my_predeposit){
			showDialog('您的余额不足，无法完成佣金结算!',urlShop('seller_commisputout', 'index'));
		}
		//提取佣金操作
		$this->extractCommission($flow_list,$pay_commis);						    

        showDialog('佣金结算操作成功!',urlShop('seller_commisputout', 'index'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
	}		
	
    /**
	 * 推广员信息
	 */
    public function promotion_infoOp(){		
		$promotion_id = intval($_GET["promotion_id"]);
		if (empty($promotion_id) || ($promotion_id<=0)){
			showDialog('非法参数');
		}

		$model_promotion = Model('member');
		
		$member_info = $model_promotion->getMemberInfo(array('member_id'=>$promotion_id),'mc_id');
		if (empty($member_info) || !is_array($member_info) || $member_info['mc_id'] < 1 || $member_info['mc_id'] > 2){
			showDialog('非法参数');
		}
		if ($member_info['mc_id'] == 2){
		  $promotion_info = $model_promotion->getPromotionDetailInfo($promotion_id);		
		
		  Tpl::output('promotion_info',$promotion_info);
		  Tpl::showpage('seller_promotion.info','null_layout');
		}else{
		  $saleman_info = $model_promotion->getSaleManInfo($promotion_id);		
		
		  Tpl::output('saleman_info',$saleman_info);
		  Tpl::showpage('seller_saleman.info','null_layout');
		}
	}
	
	/**
	 * 佣金明细
	 *
	 */
    public function commisputout_detailOP() {
		$model_detail = Model('extension_commis_detail');
		$promotion_id	 = $_REQUEST['promotion_id'];
		$promotion_info=Model('member')->getMemberInfo(array('member_id'=>$promotion_id),'member_name,member_truename,mc_id');
		$promotion_name = $promotion_info['member_name'];
		Tpl::output('mc_id',$promotion_info['mc_id']);
		
		$request_date_str = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to']);
		
		$condition = array();
		$condition['store_id'] = $_SESSION['store_id'];		
		if ($request_date_str != ''){			
			$args = explode(',',$request_date_str);
			$condition['add_date'] = array('in',$args);
		}
		//$condition['saleman_type'] = 2;

		if (!empty($_GET['give_status']) && $_GET['give_status']<2){			
			$condition['give_status'] = $_GET['give_status'];
		}
		
		if($promotion_id != ''){
			$condition['saleman_id'] = $promotion_id;
			Tpl::output('promotion_id',$promotion_id);
		}
		
		$detail_list = $model_detail->getCommisdetailList($condition,20);		
		Tpl::output('detail_list',$detail_list);
		Tpl::output('show_page',$model_detail->showpage());
		
		self::profile_menu('detail',$promotion_name);
        Tpl::showpage('seller_commisputout.detail');
    } 
	
	/**
	 * 佣金结算列表
	 *
	 */
    public function balance_listOp() {
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
		$where['pde_store_id'] = $_SESSION['store_id'];
		//时段
		if (!empty($request_date_arr) && is_array($request_date_arr)){
			$start = strtotime($request_date_arr['startdate']);
			$end = strtotime($request_date_arr['enddate'])+86400;
			
			$where['pde_add_time'] = array(array('egt',$start),array('lt',$end),'and');
		}		
		//名称
		if($_GET['saleman_name'] != ''){						
			$where['pde_member_name'] = array('like', '%' . $_GET['saleman_name'] . '%');
			$main_title = $_GET['saleman_name'].$main_title;
		}
		//类型：全部或导购员或推广员
		$saleman_type = intval($_GET['saleman_type']);
		if($saleman_type > 0){			
			$where['pde_mc_id'] = $saleman_type;
			if ($saleman_type==2){
				$main_title = '推广员'.$main_title;
			}else{
				$main_title = '导购员'.$main_title;
			}
		}
		
		$extcommis_list = $model_extcommis->getExtCommisList($where);		
		
		Tpl::output('extcommis_list',$extcommis_list);
		Tpl::output('page',$model_extcommis->showpage());
		
		Tpl::output('main_title',$main_title);
		Tpl::output('sub_title',$sub_title);
			
	    self::profile_menu('balance');
        Tpl::showpage('seller_commisputout_balance.list');		 
	}
		
	/**
	 * 发放佣金明细
	 *
	 */
    public function extcommis_infoOp() {
		$pde_sn = $_GET['sn'];
		$promotion_id = $_GET['id'];
		
		$condition = array();
		$condition['store_id'] = $_SESSION['store_id'];
		$condition['give_status'] = 1;
		$condition['saleman_id'] = $promotion_id;
		$condition['pde_sn'] = $pde_sn;
		
		$model_detail = Model('extension_commis_detail');
		$detail_list = $model_detail->getCommisdetailList($condition,10);		
		Tpl::output('detail_list',$detail_list);
		Tpl::output('show_page',$model_detail->showpage());
		
		self::profile_menu('balance');
        Tpl::showpage('seller_commisputout.extcommisinfo','null_layout');
    }
	
	/**
	 * 提佣申请列表
	 *
	 */
    public function apply_listOp() {
        $model_apply = Model('apply_information');
		
		$apply_list = $model_apply->getCommisputOutApplyList($_SESSION['store_id']);		
		
		Tpl::output('apply_list',$apply_list);
		Tpl::output('page',$model_apply->showpage());
			
	    self::profile_menu('apply');
        Tpl::showpage('seller_commisputout_apply.list');
    }
	
	/**
	 * 提佣申请信息
	 *
	 */
    public function apply_infoOp() {
		$ai_id = $_GET['id'];
		
        $model_apply = Model('apply_information');
		
		$apply_info = $model_apply->getApplyInfo($ai_id);		
		Tpl::output('apply_info',$apply_info);
			
        Tpl::showpage('seller_apply_infomation','null_layout');
    }
	
	/**
	 * 提佣申请信息编辑
	 *
	 */
    public function apply_editOp() {
        $ai_id = $_GET['id'];
		
        $model_apply = Model('apply_information');		
		$apply_info = $model_apply->getApplyInfo($ai_id);		
		Tpl::output('apply_info',$apply_info);
		//提佣金额
		$where = "extension_commis_detail.store_id = '".$_SESSION['store_id']."' and extension_commis_detail.give_status = 0 and saleman_id=".$apply_info['ai_from'];
        $flow_list = $this->calculateCommission($_SESSION['store_id'], $this->store_info['promotion_level'], $where);
		$pay_commis = 0;
		if (!empty($flow_list) && is_array($flow_list)){
			foreach($flow_list as $k => $info) {
				$pay_commis = $pay_commis + $info['share_totals'];
			}			
		}
		Tpl::output('pay_commis',$pay_commis);
		//帐户余额
		$my_predeposit = Model('member')->getMemberPredepositByID($_SESSION['member_id']);
		Tpl::output('my_predeposit',$my_predeposit);

        Tpl::showpage('seller_commisputout_apply.edit','null_layout');
    }
	
	/**
	 * 提佣申请信息保存
	 *
	 */
    public function apply_saveOp() {
        $ai_id = $_POST['id'];
		$verify = $_POST['verify'];
		if (empty($ai_id) || $ai_id<=0 || $verify<0 || $verify>2){
			showDialog('参数错误!',urlShop('seller_commisputout', 'apply_list'));
		}		
		
        $model_apply = Model('apply_information');
		$MemberMsg = 'apply_commisputout_failed';
		
		$apply_info = $model_apply->getApplyInfo($ai_id);
		$member_id = $apply_info['ai_from'];
		if ($verify==2){				  
			$MemberMsg = 'apply_commisputout_success';			
			//提佣金额
		    $where = "extension_commis_detail.store_id = '".$_SESSION['store_id']."' and extension_commis_detail.give_status = 0 and saleman_id=".$member_id;
            $flow_list = $this->calculateCommission($_SESSION['store_id'], $this->store_info['promotion_level'], $where);
		    $pay_commis = 0;
		    if (!empty($flow_list) && is_array($flow_list)){
			    foreach($flow_list as $k => $info) {
				    $pay_commis = $pay_commis + $info['share_totals'];
			    }			
		    }		    
		    //帐户余额
		    $my_predeposit = Model('member')->getMemberPredepositByID($_SESSION['member_id']);
			if ($pay_commis>$my_predeposit){
				showDialog('您的余额不足，无法完成佣金结算!',urlShop('seller_commisputout', 'apply_list'));
			}
			//提取佣金操作
			$this->extractCommission($flow_list,$pay_commis);						    
		}
		
		$apply_info = array();
		$apply_info['ai_dispose'] = $verify;
		$apply_info['ai_replyinfo'] = $_POST['ai_replyinfo'];
		$apply_info['ai_distime'] = time();
		
		$model_apply->editApplyInfo($apply_info,array('ai_id'=>$ai_id));

        showDialog('审核操作成功!',urlShop('seller_commisputout', 'apply_list'),'succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
    }
	
	/**
	 * 提取佣金操作
	 *
	 */
    public function extractCommission($flow_list = array(),$pay_commis = 0){		
		if (!empty($flow_list) && is_array($flow_list)){
			$model_commis_detail = Model('extension_commis_detail');
		    $model_pd = Model('predeposit');
			$model_extcommis = Model('pd_extcommis');
		    $pde_sn = $model_pd->makeSn();
			foreach($flow_list as $k => $info) {
				//更改佣金明细信息
				$where = array();
			    $where['store_id'] = $_SESSION['store_id'];
			    $where['give_status'] = 0;
			    $where['saleman_id'] = $info['saleman_id'];
			    $data = array();
			    $data['share_level'] = $info['share_level'];
			    $data['award_totals'] = $info['award_totals'];  
			    $data['share_totals'] = array('exp','mb_commis_totals*'.$info['award_totals']/100);							
			    $data['give_status'] = 1;    
			    $data['give_time'] = TIMESTAMP;						
			    $data['pde_sn'] = $pde_sn;							
			    $model_commis_detail->where($where)->update($data);
				//变更会员积分
				$predeposit = array();
		        $predeposit['member_id'] = $info['saleman_id'];
		        $predeposit['member_name'] = $info['member_name'];
		        $predeposit['amount'] = $info['share_totals'];
		        $predeposit['pde_sn'] = $pde_sn;
		        $predeposit['admin_name'] = $_SESSION['member_name'];
		        $model_pd->changePd('commis',$predeposit);
			    //添加会员提佣记录
				$param = array();
				$param['pde_sn'] = $pde_sn; //提佣交易号
				$param['pde_store_id'] = $_SESSION['store_id']; //店铺ID
				$param['pde_mc_id'] = $info['mc_id']; //店铺ID
				$param['pde_member_id'] = $info['saleman_id']; //会员编号
				$param['pde_member_name'] = $info['member_name']; //会员名称
				$param['pde_amount'] = $info['share_totals']; //提佣金额
				$param['pde_add_time'] = TIMESTAMP;	//添加时间
				$param['pde_admin'] = $_SESSION['member_name']; //结算操作
				$param['pde_payment_code'] = 0; //支付方式
				$param['pde_payment_name'] = '积分'; //支付方式名称
				$param['pde_trade_sn'] = $pde_sn; //支付交易号
				$param['pde_payment_state'] = 1; //支付状态 0未支付1支付
				$param['pde_payment_time'] = TIMESTAMP;	//支付时间
				$param['pde_payment_admin'] = $_SESSION['member_name']; //支付操作
				$model_extcommis->addExtCommis($param);					
			}
			//变更店铺积分
			$predeposit = array();
		    $predeposit['member_id'] = $_SESSION['member_id'];
		    $predeposit['member_name'] = $_SESSION['member_name'];
		    $predeposit['amount'] = $pay_commis;
		    $predeposit['pde_sn'] = $pde_sn;
		    $predeposit['admin_name'] = $_SESSION['member_name'];
		    $model_pd->changePd('commis_pay',$predeposit);		
		}		
	}	  
	
	/**
	 * 删除提佣申请信息
	 *
	 */
    public function apply_delOp() {
		$ai_id = $_GET['id'];
		if (empty($ai_id) || $ai_id<=0){
			showDialog('参数错误!',urlShop('seller_commisputout', 'apply_list'));
		}
		Model('apply_information')->delApplyInfo(array('ai_id'=>$ai_id));
		showDialog('申请信息删除成功!',urlShop('seller_commisputout', 'apply_list'));
	}	
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key='',$promotion_name='') {
        $menu_array	= array();
		$menu_array[1]=array('menu_key'=>'index','menu_name'=>'待结算佣金',	'menu_url'=>'index.php?act=seller_commisputout&op=index');
		$menu_array[2]=array('menu_key'=>'balance','menu_name'=>'已结算佣金',	'menu_url'=>'index.php?act=seller_commisputout&op=balance_list');
		$menu_array[3]=array('menu_key'=>'apply','menu_name'=>'提佣申请'.$this->appay_count,	'menu_url'=>'index.php?act=seller_commisputout&op=apply_list');
		if ($menu_key=='detail'){
		  $menu_array[4]=array('menu_key'=>'detail','menu_name'=>$promotion_name.'佣金明细',	'menu_url'=>'index.php?act=seller_commisputout&op=commisputout_detail&promotion_id='.$_REQUEST['promotion_id']);
		}
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}