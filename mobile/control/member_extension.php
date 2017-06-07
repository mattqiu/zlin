<?php
/**
 * 我的推广
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

class member_extensionControl extends mobileMemberControl {
	protected $extension_info = array();

	public function __construct() {
		parent::__construct();
		/**
		 * 判断系统是否是推广员或导购员
		 */
		if ($this->member_info['member_grade']<1&&$this->member_info['mc_id']<1){
			//output_error('你不是VIP会员！');
		}
		if ($this->member_info['mc_id']==1){
			$this->extension_info = Model('extension')->getSaleManInfo($this->member_info['store_id'],$this->member_info['member_id']);
		}else{
			$this->extension_info = Model('extension')->getPromotionInfo($this->member_info['store_id'],$this->member_info['member_id']);
		}
		if (empty($this->extension_info)){
			//output_error('你不是推广员或导购员！');
		}
	}
	
	/**
	 * 推广员身份
	 */
	public function getExtensionTypeName($mc_id = 0){
		switch($mc_id) {
		    case 5:
				$mc_name = '总代';
                break;
			case 4:
				$mc_name = '区代';
                break;
			case 3:
				$mc_name = '省代';
                break;
            case 2:
				$mc_name = '店长';
                break;
			default:
			    $mc_name = '会员';
				break;
        }
		return $mc_name;		
	}
	
	
	/**
	 * 推广员信息
	 */
	public function my_extensioninfoOp(){
		$member_info = array();
        $member_info['user_name'] = get_greeting().$this->member_info['member_name'];
        $member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['point'] = $this->member_info['member_points'];
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
		
		//可用代金券数量
		$model_voucher = Model('voucher');
		$member_info['vouchers'] = $model_voucher->getCurrentAvailableVoucherCount($this->member_info['member_id']);
		
		//订单信息
		$order_info = array();
		$model_order = Model('order');
		$condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
		//待付款订单数量
		$order_info['order_new'] = $model_order->getOrderStateNewCount($condition);
		//待发货订单
		$order_info['order_pay'] = $model_order->getOrderStatePayCount($condition);
		//待收货订单
		$order_info['order_send'] = $model_order->getOrderStateSendCount($condition);
			
		$member_info['order_info'] = $order_info;
		//推广处理
		$extension_info = array();
		if (OPEN_STORE_EXTENSION_STATE > 0 && ($this->member_info['mc_id']==1 || $this->member_info['mc_id']==2)){						
			$model_extension = Model('extension');
			$extension_info = $model_extension->getExtensionByMemberID($this->member_info['member_id'],'member_id,store_id,mc_id,mc_real,commis_totals,commis_balance');
			if (!empty($extension_info)){
				$model_detail = Model('extension_commis_detail');
				//个人_团队人数
				$extension_info['personal_childs'] = $model_extension->countManagerAllChild($extension_info['store_id'],$extension_info['member_id'],$extension_info['mc_id']);
				//个人_直推人数
				$extension_info['personal_subs'] = $model_extension->countPromotionChild($extension_info['store_id'],$extension_info['member_id']);
				//管理奖
				$condition = array();
		        $condition['store_id']=$extension_info['store_id'];
				$condition['saleman_id']=$extension_info['member_id'];
		        $condition['saleman_type']=3;
		        $condition['commis_type']=1;
		        $condition['give_status']=0;
				$extension_info['personal_manageaward'] = $model_detail->getManagerAward_total($condition);
				//门店补贴
				$condition = array();
		        $condition['store_id']=$extension_info['store_id'];
				$condition['saleman_id']=$extension_info['member_id'];
		        $condition['saleman_type']=4;
		        $condition['commis_type']=1;
		        $condition['give_status']=0;
				$extension_info['personal_perforaward'] = $model_detail->getPerforAward_total($condition);
			    
				//团队_销售额
				$group_amounts = $model_detail->getStatisticsCommis($extension_info['store_id'],$extension_info['member_id'],array('give_status'=>0));
				$extension_info['group_amounts'] = $group_amounts['amounts'];
				//团队_未结算订单数
				$extension_info['group_orders'] = $model_detail->getGroupsOrdes($extension_info['store_id'],$extension_info['member_id'],array('give_status'=>0));
				//团队_未结算佣金收益
				$group_commis = $model_detail->getStatisticsCommis($extension_info['store_id'],$extension_info['member_id'],array('give_status'=>0));
				$extension_info['group_commis'] = $group_commis['commis'];
				
				//推广信息
				$extension_info['mc_id']    = $extension_info['mc_id']?$extension_info['mc_id']:0;
				$extension_info['mc_name']  = $this->getExtensionTypeName($extension_info['mc_id']);
				if (OPEN_STORE_EXTENSION_STATE == 10){
					$extension_info['store_id'] = 0;
				}else{
		            $extension_info['store_id'] = $extension_info['store_id']>0?$extension_info['store_id']:0;
				}
		        $extension_info['extension'] = '&extension='.urlsafe_b64encode($extension_info['member_id']);
			}else{
				$extension_info['personal_childs'] = 0;
				$extension_info['personal_subs'] = 0;
			    $extension_info['personal_manageaward'] = 0;
			    $extension_info['personal_perforaward'] = 0;
			
			    $extension_info['group_amounts'] = 0;
			    $extension_info['group_orders'] = 0;
			    $extension_info['group_commis'] = 0;
				
				$extension_info['mc_id'] = 0;
				$extension_info['mc_real'] = 1;
				$extension_info['commis_totals'] = 0;
				$extension_info['mc_name'] = '';	
		        $extension_info['store_id'] = 0;
		        $extension_info['extension'] = '';
			}
					
		}else{
			$extension_info['mc_id'] = 0;
			$extension_info['mc_real'] = 1;
			$extension_info['commis_totals'] = 0;
			$extension_info['mc_name'] = '';		
		    $extension_info['store_id'] = 0;
		    $extension_info['extension'] = '';
			
			$extension_info['personal_childs'] = 0;
			$extension_info['personal_subs'] = 0;
			$extension_info['personal_manageaward'] = 0;
			$extension_info['personal_perforaward'] = 0;
			
			$extension_info['group_amounts'] = 0;
			$extension_info['group_orders'] = 0;
			$extension_info['group_commis'] = 0;
		}
		$member_info['extension_info'] = $extension_info;
		
		output_data(array('member_info' => $member_info));
	}	
	
	/**
	 * 推广员二维码
	 */
	public function my_extensionqrcodeOp(){
		$member_info = array();
		
        $member_info['user_name'] = get_greeting().$this->member_info['member_name'];
        $member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['mc_id'] = $this->member_info['mc_id'];		
		$extension_info = Model('extension')->getExtensionByMemberID($this->member_info['member_id'],'member_id,store_id,mc_id,commis_balance,commis_totals');
		$member_info['mc_name']  = $this->getExtensionTypeName($extension_info['mc_id']);
		$member_info['member_grade'] = $this->member_info['member_grade'];
		if($member_info['member_grade']<1 && $member_info['mc_id']==0){
			//output_error('只有VIP会员或成为推广员才会生成邀请二维码');
		}
		
		//订单信息
		$order_info = array();
		$model_order = Model('order');
		$condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
		//待付款订单数量
		$order_info['order_new'] = $model_order->getOrderStateNewCount($condition);
		//待发货订单
		$order_info['order_pay'] = $model_order->getOrderStatePayCount($condition);
		//待收货订单
		$order_info['order_send'] = $model_order->getOrderStateSendCount($condition);
			
		$member_info['order_info'] = $order_info;
		$member_info['extension_code'] = urlsafe_b64encode($this->member_info['member_id']);
		$member_info['extension_qrcode'] = GetExtensionQRcode($this->member_info['member_id']);
		
		output_data(array('member_info' => $member_info));
	}	
	
	/**
	 * 我的业绩
	 */
	public function my_achievementOp(){		
		$model_detail = Model('order');
		
		//$request_date_arr = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to'],true);
		$child_list = Model('extension')->GetAllPromotionChildID($this->member_info['member_id'],$this->member_info['mc_id'],$this->member_info['store_id']);
		
		$condition = array();
		$condition['saleman_id'] = array('in',$child_list);
		
		//if (!empty($request_date_arr) && is_array($request_date_arr)){
		//	$start = strtotime($request_date_arr['startdate']);
		//	$end = strtotime($request_date_arr['enddate'])+86400;
			
		//	$condition['add_time'] = array(array('egt',$start),array('lt',$end),'and');
		//}
		//if (!empty($_GET['give_status'])){
		//	$condition['order_state'] = $_GET['give_status'];
		//}else{
			$condition['order_state'] = array('in', array(ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS));
		//}		
		//if($_GET['saleman_name'] != ''){
		//	$condition['saleman_name'] = array('like', '%' . $_GET['saleman_name'] . '%');
		//}		
		$out_data = array();
		
		$achievement_list = $model_detail->getNormalOrderList($condition,20,'order_id, add_time,saleman_name,order_sn,store_name,buyer_name,goods_amount,order_state');
		$out_data['achievement_list'] = $achievement_list;
		$page_count = $model_detail->gettotalpage();
		
		//商品总业绩:amounts 、订单总业绩：commis		
		$statistics_all = $model_detail->getStatisticsCommis(array('buyer_id'=>array('in',$child_list),'order_state'=>array('in', array(ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS))));
		$out_data['statistics_all'] = $statistics_all['amounts'];
		$out_data['statistics_all_show'] = 1;
		$out_data['statistics_all_name'] = '累积业绩';
		//当前商品业绩:amounts 、当前订单业绩：commis
		$statistics_curr = $model_detail->getStatisticsCommis($condition);
		$out_data['statistics_curr'] = $statistics_curr['amounts'];
		$out_data['statistics_curr_show'] = 1;
		$out_data['statistics_curr_name'] = '本季业绩';
		
        $out_data['user_name'] = get_greeting().$this->member_info['member_name'];
        $out_data['avator'] = getMemberAvatarForID($this->member_info['member_id']);		
		$out_data['mc_id'] = $this->member_info['mc_id'];		
		$extension_info = Model('extension')->getExtensionByMemberID($this->member_info['member_id'],'member_id,store_id,mc_id,commis_balance,commis_totals');
		$out_data['mc_name']  = $this->getExtensionTypeName($extension_info['mc_id']);
		
		output_data(array('extension_list' => $out_data), mobile_page($page_count));
	}
	
	/**
	 * 待收益
	 */
	public function my_incomeOp(){
		$model_detail = Model('extension_commis_detail');
		
		$request_date_arr = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to'],true);
		
		$condition = array();
		$condition['saleman_id'] = $this->member_info['member_id'];
		
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
		$out_data['income_list'] = $income_list;
		$page_count = $model_detail->gettotalpage();	
		//已结算收益 总业绩:amounts 、总佣金:commis、总分成:shares
		//$condition['give_status'] = 1;
		//$statistics_all = $model_detail->getStatisticsCommis(NULL,$this->member_info['member_id'],array('give_status'=>1));
		$out_data['statistics_all'] = $this->extension_info['commis_totals'];//$statistics_all['commis'];
		$out_data['statistics_all_show'] = 1;
		$out_data['statistics_all_name'] = '已收益';
		//待结算收益 当前业绩:amounts 、当前佣金:commis、当前分成:shares
		//$condition['give_status'] = 0;
		$statistics_curr = $model_detail->getStatisticsCommis(NULL,$this->member_info['member_id'],array('give_status'=>0));
		$out_data['statistics_curr'] = $statistics_curr['commis'];
		$out_data['statistics_curr_show'] = 1;
		$out_data['statistics_curr_name'] = '待收益';
		
		$out_data['user_name'] = get_greeting().$this->member_info['member_name'];
        $out_data['avator'] = getMemberAvatarForID($this->member_info['member_id']);		
		$out_data['mc_id'] = $this->member_info['mc_id'];		
		$extension_info = Model('extension')->getExtensionByMemberID($this->member_info['member_id'],'member_id,store_id,mc_id,commis_balance,commis_totals');
		$out_data['mc_name']  = $this->getExtensionTypeName($extension_info['mc_id']);		
	
		output_data(array('extension_list' => $out_data), mobile_page($page_count));
	}
	
	/**
	 * 已结算
	 */
	public function my_balanceOp(){
		$model_extcommis = Model('pd_extcommis');
		
		$request_date_arr = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to'],true);
		
		$condition = array();
		$condition['pde_member_id'] = $this->member_info['member_id'];
		
		if (!empty($request_date_arr) && is_array($request_date_arr)){
			$start = strtotime($request_date_arr['startdate']);
			$end = strtotime($request_date_arr['enddate'])+86400;
			
			$condition['pde_add_time'] = array(array('egt',$start),array('lt',$end),'and');
		}
		
		$balance_list = $model_extcommis->getExtCommisList($condition);
		$out_data['balance_list'] = $balance_list;
		$page_count = $model_extcommis->gettotalpage();	
		
		$model_detail = Model('extension_commis_detail');
		//已结算收益 总业绩:amounts 、总佣金:commis、总分成:shares
		//$condition['give_status'] = 1;
		//$statistics_all = $model_detail->getStatisticsCommis(NULL,$this->member_info['member_id'],array('give_status'=>1));
		$out_data['statistics_all'] = $this->extension_info['commis_totals'];//$statistics_all['commis'];
		$out_data['statistics_all_show'] = 1;
		$out_data['statistics_all_name'] = '已结算';
		//待结算收益 当前业绩:amounts 、当前佣金:commis、当前分成:shares
		//$condition['give_status'] = 0;
		$statistics_curr = $model_detail->getStatisticsCommis(NULL,$this->member_info['member_id'],array('give_status'=>0));
		$out_data['statistics_curr'] = $statistics_curr['commis'];
		$out_data['statistics_curr_show'] = 1;
		$out_data['statistics_curr_name'] = '待结算';
		
		$out_data['user_name'] = get_greeting().$this->member_info['member_name'];
        $out_data['avator'] = getMemberAvatarForID($this->member_info['member_id']);		
		$out_data['mc_id'] = $this->member_info['mc_id'];		
		$extension_info = Model('extension')->getExtensionByMemberID($this->member_info['member_id'],'member_id,store_id,mc_id,commis_balance,commis_totals');
		$out_data['mc_name']  = $this->getExtensionTypeName($extension_info['mc_id']);		
		
		output_data(array('extension_list' => $out_data), mobile_page($page_count));
	}
	
	/**
	 * 我的团队
	 */
	public function my_subordinateOp(){
		$fields = 'member_id,member_name,parent_id,parent_tree,mc_id';
        $model_promotion = Model('extension');
		
		//父ID
		$parent_id = $_GET['parent_id']?intval($_GET['parent_id']):$this->member_info['member_id'];
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
		    $out_data['promotion_list'] = $promotion_list;
		    $page_count = $model_promotion->gettotalpage();			
		
			$my_subordinate = $model_promotion->countPromotionChild($this->member_info['store_id'], $this->member_info['member_id']);
			$out_data['statistics_all'] = $my_subordinate;
			$out_data['statistics_all_show'] = 1;
		    $out_data['statistics_all_name'] = '直推';
			
			$my_subordinate_all = $model_promotion->countAllPromotionChild($this->member_info['store_id'], $this->member_info['member_id']);
			$out_data['statistics_curr'] = $my_subordinate_all;			
			$out_data['statistics_curr_show'] = 1;
		    $out_data['statistics_curr_name'] = '代理';
		
		    $out_data['user_name'] = get_greeting().$this->member_info['member_name'];
            $out_data['avator'] = getMemberAvatarForID($this->member_info['member_id']);		
		    $out_data['mc_id'] = $this->member_info['mc_id'];		
		    $extension_info = Model('extension')->getExtensionByMemberID($this->member_info['member_id'],'member_id,store_id,mc_id,commis_balance,commis_totals');
		    $out_data['mc_name']  = $this->getExtensionTypeName($extension_info['mc_id']);	
			
			output_data(array('extension_list' => $out_data), mobile_page($page_count));
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
	 * 抽佣明细
	 *
	 */
    public function promotion_detailOP() {
		$model_detail = Model('extension_commis_detail');
		$promotion_id	 = $_REQUEST['promotion_id'];
		$promotion_info=Model('member')->getMemberInfo(array('member_id'=>$promotion_id),'member_name,member_truename,store_id');
		$promotion_name = $promotion_info['member_name'];
		
		$request_date_str = BuildDateQueryStr($_GET['type'],$_GET['add_time_from'],$_GET['add_time_to'],true);
		
		$condition = array();
		$condition['store_id'] = $promotion_info['store_id'];
			
		if (!empty($request_date_arr) && is_array($request_date_arr)){
			$start = strtotime($request_date_arr['startdate']);
			$end = strtotime($request_date_arr['enddate'])+86400;
			
			$condition['add_time'] = array(array('egt',$start),array('lt',$end),'and');
		}
		$condition['saleman_type'] = 2;

		if (!empty($_GET['give_status'])){
			if ($_GET['give_status']<2){			
			  $condition['give_status'] = $_GET['give_status'];
			}
		}else{
			$condition['give_status'] = 0;
		}
		
		if($promotion_id != ''){
			$condition['saleman_id'] = $promotion_id;
			Tpl::output('promotion_id',$promotion_id);
		}		
		
		if($_GET['saleman_name'] != ''){
			$condition['extension_name'] = array('like', '%' . $_GET['saleman_name'] . '%');
		}		
		
		$detail_list = $model_detail->getCommisdetailList($condition,20);		
		Tpl::output('detail_list',$detail_list);
		Tpl::output('show_page',$model_detail->showpage());
		
		self::profile_menu('detail',$promotion_name);
        Tpl::showpage('member_extension.subordinate.detail');
    }
	
	/**
	 * 下线申请列表
	 */
	public function my_applyOp(){
		$model_apply = Model('apply_information');
		
		$apply_list = $model_apply->getPromotionSubApplyList($this->member_info['member_id']);		
		
		$out_data['apply_list'] = $apply_list;
		$page_count = $model_apply->gettotalpage();			
		//总数
		$apply_all = $model_apply->getApplyCount(array('ai_target'=>$this->member_info['member_id'],'ai_type'=>3));
		$out_data['statistics_all'] = $apply_all;
		$out_data['statistics_all_show'] = 1;
		$out_data['statistics_all_name'] = '申请数';
		//待审核
		$apply_curr = $model_apply->getApplyCount(array('ai_target'=>$this->member_info['member_id'],'ai_dispose'=>0,'ai_type'=>3));
		$out_data['statistics_curr'] = $apply_curr;
		$out_data['statistics_curr_show'] = 1;
		$out_data['statistics_curr_name'] = '待审核';
		
		$out_data['user_name'] = get_greeting().$this->member_info['member_name'];
        $out_data['avator'] = getMemberAvatarForID($this->member_info['member_id']);		
		$out_data['mc_id'] = $this->member_info['mc_id'];		
		$extension_info = Model('extension')->getExtensionByMemberID($this->member_info['member_id'],'member_id,store_id,mc_id,commis_balance,commis_totals');
		$out_data['mc_name']  = $this->getExtensionTypeName($extension_info['mc_id']);	
		
		output_data(array('extension_list' => $out_data), mobile_page($page_count));
	}
	
	/**
	 * 推广员申请信息保存
	 *
	 */
    public function verify_saveOp() {
        $ai_id = $_POST['id'];
		$verify = $_POST['verify'];
		if ($verify==2){
		    $ai_replyinfo = '通过'; //$_POST['ai_replyinfo']
		}else{
			$ai_replyinfo = '拒绝';
		}
		if (empty($ai_id) || $ai_id<=0 || $verify<0 || $verify>2){
			output_error('参数错误!');
		}		
		
        $model_apply = Model('apply_information');
		$MemberMsg = 'apply_extension_failed';
		
		$apply_info = $model_apply->getApplyInfo($ai_id);
		$member_id = $apply_info['ai_from'];
		if ($verify==2){						
			$model_member = Model('member');
			$model_promotion = Model('extension');
			
			if (OPEN_STORE_EXTENSION_STATE!=10){
				$model_store = Model('store');			
			    $promotion_count = $model_promotion->getPromotionCount($this->member_info['store_id']); //店铺已有的推广员数量		
			    $promotion_limit = $model_store->getPromotionLimit($this->member_info['store_id']); //店铺推广员数量限制
			    $promotion_level = $model_store->getPromotionLevel($store_id);	//店铺推广员层级限制
			    if ($promotion_count >= $promotion_limit){
				    output_error('店铺推广员已满，审核失败！');
		        }			
			}else{
				$promotion_level = C('gl_promotion_level');
			}
			$member_org = $model_member->getMemberInfoByID($member_id,'member_id,member_name,mc_id');
			if (empty($member_org) || empty($member_org['member_id']) || $member_org['mc_id']==2){
				output_error('无效的推广申请信息！');
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
		    $promotion['store_id']   = $parent_info['store_id'];
		    $promotion['parent_id']  = $parent_id;		
		    $promotion['parent_tree']= $parent_tree;				
			$promotion['holder_id']= $holder_id; //股东
			$promotion['ceo_id']= $ceo_id; //首席
			$promotion['coo_id']= $coo_id; //协理
			$promotion['manager_id']= $manager_id; //经理
			$promotion['commis_balance']= 0; //佣金余额
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
				output_error('系统错误，请稍候再试!');
			}			
		}
		
		$apply_info = array();
		$apply_info['ai_dispose'] = $verify;
		$apply_info['ai_replyinfo'] = $ai_replyinfo;
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
			'replyinfo' => $ai_replyinfo,
			'mail_send_time'=>date("Y-m-d",time())
        );
        QueueClient::push('sendMemberMsg', $param);
		
		$datas = array();
		$datas['verify_info'] = '审核操作成功!';		
		output_data($datas);
    }
	
	/**
	 * 删除推广员申请信息
	 *
	 */
    public function apply_delOp() {
		$ai_id = $_GET['id'];
		if (empty($ai_id) || $ai_id<=0){
			output_error('参数错误!');
		}
		Model('apply_information')->delApplyInfo(array('ai_id'=>$ai_id));
		
		$datas = array();
		$datas['del_info'] = '申请记录删除成功!';		
		output_data($datas);
	}	
	
	/**
	 * 申请提佣保存
	 */
	public function apply_commissionOp(){		
		$ai_addinfo = array();
		$ai_addinfo['truename'] = $this->member_info['member_truename'];
		$ai_addinfo['email'] = $this->member_info['member_email'];	
		$ai_addinfo['mobile'] = $this->member_info['member_mobile'];	
		$ai_addinfo['qq'] = $this->member_info['member_qq'];
		$ai_addinfo['areainfo'] = $this->member_info['member_areainfo'];
		$ai_addinfo['describe'] = '老板，想钱想疯了，给点吧！';
		
		$data=array();
		$data['ai_from'] = $this->member_info['member_id'];		
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
		$where['saleman_id'] = $this->member_info['member_id'];	
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
				$msg = $apply_ok.'店铺提佣申请提交成功！';
			}
			if ($apply_fail != ''){
				$msg = $apply_fail.'店铺提佣申请提交失败！';
			}
			$datas = array();
		    $datas['info'] = $msg;		
		    output_data($datas);
		}else{//end for if
		    output_error('无店铺佣金收益可提取！');
		}		
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
		if ($menu_key == 'detail'){
			$menu_array		= array();
		    $menu_array = array(
			    1=>array('menu_key'=>'subordinate',	'menu_name'=>'我的团队',	'menu_url'=>'index.php?act=member_extension&op=my_subordinate'),
				2=>array('menu_key'=>'detail','menu_name'=>$promotion_name.'业绩明细', 'menu_url'=>'index.php?act=member_extension&op=promotion_detail'),
		    );
		}else{
            $keyarr = array('achievement'=>'我的业绩','income'=>'我的收益','subordinate'=>'我的团队');
		    $menu_array		= array();
		    $menu_array = array(
			    1=>array('menu_key'=>$menu_key,	'menu_name'=>$keyarr[$menu_key],	'menu_url'=>'index.php?act=member_extension&op=my_'.$menu_key),
		    );
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
