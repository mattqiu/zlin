<?php
/**
 * 佣金结算管理
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class seller_commisputoutControl extends BaseExtensionControl {
	
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
	 * 佣金结算列表
	 *
	 */
    public function indexOp() {
		$where = "extension_commis_detail.store_id = '".$_SESSION['store_id']."' and extension_commis_detail.give_status = 0";			
		if($_GET['saleman_name'] != ''){			
			$where .= " and (extension_commis_detail.saleman_id like '%".$_GET['saleman_name']."%' or extension_commis_detail.saleman_name like '%".$_GET['saleman_name']."%')";
			$main_title = $_GET['saleman_name'].$main_title;
		}
		//类型：全部或导购员或推广员
		$saleman_type = intval($_GET['saleman_type']);
		if($saleman_type > 0){			
			$where .= " and extension_commis_detail.saleman_type =".$saleman_type;
			if ($saleman_type==2){
				$main_title = '推广员'.$main_title;
			}else{
				$main_title = '导购员'.$main_title;
			}
		}	
		
        $flow_list = $this->calculateCommission($_SESSION['store_id'], $this->store_info['promotion_level'], $where);

		//模版输出
		Tpl::output('flow_list',$flow_list);
	
		self::profile_menu('index');
		Tpl::showpage('seller_commisputout.index');
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
				$pay_commis = $pay_commis + $info['commis_totals'];
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
		$where = "extension_commis_detail.store_id = '".$_SESSION['store_id']."' and extension_commis_detail.give_status = 0".$promotion_id;        
        $flow_list = $this->calculateCommission($_SESSION['store_id'], $this->store_info['promotion_level'], $where);
		$pay_commis = 0;
		if (!empty($flow_list) && is_array($flow_list)){
			foreach($flow_list as $k => $info) {
				$pay_commis = $pay_commis + $info['commis_totals'];
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
	 * 计算发放佣金
	 */
    public function  calculateCommission($store_id = 0, $max_level = 3, $where = '',$order = 'extension.mc_id desc', $page = '20'){		
		$model = Model();
		$table = 'extension_commis_detail,extension';
		$field = 'sum(extension_commis_detail.goods_amount) as curr_sales, sum(extension_commis_detail.commis_amount) as total_commis, sum(extension_commis_detail.mb_commis_totals) as curr_commis, extension_commis_detail.saleman_id, extension.member_name, extension.mc_id, extension.mc_level, extension.mc_real, extension.parent_id, extension.parent_tree';
		$group = 'extension_commis_detail.saleman_id';
		$on    = 'extension.member_id=extension_commis_detail.saleman_id';
		
		$flow_list = $model->table($table)->field($field)->join('left')->on($on)->where($where)->group($group)->page($page)->order($order)->select();
		if (!empty($flow_list) && is_array($flow_list)) {
			//店铺绩优奖比率
			$rate_perfor  = Model('extension_commis_rate')->getRate_Perfor($store_id);
			//店铺绩优奖分配方案
			$perfor_award = Model('extension_perforaward')->getExtensionPerforAwardListByStoreID($store_id);
			
			//店铺管理奖比率
			$rate_manage  = Model('extension_commis_rate')->getRate_Manage($store_id);
			//店铺管理奖分配方案
			$manage_award = Model('extension_manageaward')->getExtensionManageAwardListByStoreID($store_id);
			
			//计算绩优奖励
			foreach($flow_list as $k => $info) {
				//身份类型
				switch($info['mc_id']) {
				  case 10:
				    $mc_name = '导购';
                    break;
			      case 5:
				    $mc_name = '股东';
                    break;
			      case 4:
				    $mc_name = '首席';
                    break;
			      case 3:
				    $mc_name = '协理';
                    break;
                  case 2:
				    $mc_name = '经理';
                    break;
				  case 1:
				    $mc_name = '推广员';
				    break;
			      default:
				    $mc_name = '导购';
                    break;			        
				}
				$flow_list[$k]['mc_name']=$mc_name;
				
			    //计算本期绩优奖励(门面补贴)
				$award_perfor_name = '';
				$award_perfor_rate = 0;
				$award_perfor_totals = 0;
				if ($info['mc_real']==10){					
				    if ($rate_perfor>0 && !empty($perfor_award)){						
					    $award_perfor_totals = $this->calculate_perforaward($perfor_award,$store_id,$info['saleman_id'],$info['mc_id'],$info['mc_level']);
				    }
				}
				$flow_list[$k]['award_perfor_name'] = $award_perfor_name;
				$flow_list[$k]['award_perfor_rate'] = $award_perfor_rate;
				$flow_list[$k]['award_perfor_totals'] = $award_perfor_totals;
				$flow_list[$k]['commis_totals'] = $info['curr_commis'] + $award_perfor_totals;
				
				//计算本期高管奖励
				$award_manage_name = '';
				$award_manage_rate = 0;
				$award_manage_totals = 0;
				$extension_upgrade_op = 0;
				$extension_upgrade_level = 0;
				if ($info['mc_id']==1 || $info['mc_id']==2 || $info['mc_id']==3 || $info['mc_id']==4 || $info['mc_id']==5){
				    if ($rate_manage>0 && !empty($manage_award)){
						$award_manage_value = $this->calculate_manageraward($manage_award,$store_id,$info['saleman_id'],$info['mc_id'],$info['mc_level']);
						
						$award_manage_totals     = $award_manage_value['manageraward'];
						$extension_upgrade_op    = $award_manage_value['extension_upgrade_op'];
						$extension_upgrade_level = $award_manage_value['extension_upgrade_level'];
				    }
				}
				$flow_list[$k]['award_manage_name'] = $award_manage_name;
				$flow_list[$k]['award_manage_rate'] = $award_manage_rate;
				$flow_list[$k]['award_manage_totals'] = $award_manage_totals;
				$flow_list[$k]['commis_totals'] = $flow_list[$k]['commis_totals'] + $award_manage_totals;
				//升级计划
				$flow_list[$k]['extension_upgrade_op'] = $extension_upgrade_op;
				$flow_list[$k]['extension_upgrade_level'] = $extension_upgrade_level;
				switch($extension_upgrade_level) {
			      case 5:
				    $extension_upgrade_name = '股东';
                    break;
			      case 4:
				    $extension_upgrade_name = '首席';
                    break;
			      case 3:
				    $extension_upgrade_name = '协理';
                    break;
                  case 2:
				    $extension_upgrade_name = '经理';
                    break;
			      default:
				    $extension_upgrade_name = '';
                    break;			        
				}
				$flow_list[$k]['extension_upgrade_name']=$extension_upgrade_name;
			}
		}
		Tpl::output('show_page',$model->showpage());
		
		return $flow_list;
	}
	
	//----------------------------------------------------------------------------门店租金处理开始------------------------------------------//
	/**
	 * 计算门店租金补贴
	 * $perfor_award 门店租金补贴标准
	 * $curr_sales 当前营业额
	 * $store_id 所属店铺
	 * $member_id 推广id
	 * $mc_id 推广身份类型
	 */
	public function calculate_perforaward($perfor_award=array(), $store_id=0, $member_id=0, $mc_id=0, $mc_level=0){
		//固定奖励
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['saleman_id']=$member_id;		  
		}
		$condition['saleman_type']=4;
		$condition['commis_type']=1;
		$condition['give_status']=0;
		
		$perforaward = 0;
		$model_detail = Model('extension_commis_detail');
		$perforaward = $model_detail->getPerforAward_total($condition);
		return $perforaward;
		//支持按销售额分等级奖励
		//$perforaward = 0;
		//switch($mc_id) {
		//	case 5:
		//		$perforaward = $this->calculate_perforaward_holder($perfor_award,$store_id,$member_id,$mc_level);
		//		$perforaward = $perforaward['perforaward'];
        //        break;
		//	case 4:
		//		$perforaward = $this->calculate_perforaward_ceo($perfor_award,$store_id,$member_id,$mc_level);
		//		$perforaward = $perforaward['perforaward'];
        //        break;
		//	case 3:
		//		$perforaward = $this->calculate_perforaward_coo($perfor_award,$store_id,$member_id,$mc_level);
		//		$perforaward = $perforaward['perforaward'];
        //        break;
        //    case 2:
		//		$perforaward = $this->calculate_perforaward_manager($perfor_award,$store_id,$member_id,$mc_level);
        //        break;      
		//}
		//return $perforaward;		
	}
	
	/**
	 * 计算经理级门店租金补贴
	 * $perfor_award 门店租金补贴标准
	 * $curr_sales 当前营业额
	 * $store_id 所属店铺
	 * $member_id 推广id
	 */
	public function calculate_perforaward_manager($perfor_award=array(), $store_id=0, $member_id=0, $mc_level=0){		
		$perforaward = 0;
		$award_perfor_rate = 0;
		$award_perfor_level = 0;		
		$model_detail = Model('extension_commis_detail');
		$curr_sales = $model_detail->getManagerSalesAmount_manager($store_id,$member_id);
		foreach($perfor_award as $key => $award_info) {
			if ($award_info['mc_id']==2 && $curr_sales >= $award_info['achieve_val'] && $award_info['award_rate']>$award_perfor_rate){
				$award_perfor_name  = $award_info['award_name'];
				$award_perfor_rate  = $award_info['award_rate'];
				$award_perfor_level = $award_info['award_level']+$mc_level;				
			}
		}
		if ($award_perfor_rate>0){
			$award_perfor_totals = $model_detail->getPerforAward_manager($store_id,$member_id,$award_perfor_level);	
			if ($award_perfor_totals>0){
				$perforaward = $award_perfor_totals*$award_perfor_rate/100;	
		    }		
		}
		if ($perforaward<0){
			$perforaward = 0;
		}
		return $perforaward;
	}
	
	/**
	 * 计算协理级门店租金补贴
	 * $perfor_award 门店租金补贴标准
	 * $curr_sales 当前营业额
	 * $store_id 所属店铺
	 * $member_id 推广id
	 */
	public function calculate_perforaward_coo($perfor_award=array(), $store_id=0, $member_id=0, $mc_level=0){		
		$perforaward = 0; //协理奖金
		$perforaward_child = 0; //经理奖金
		$award_perfor_rate = 0;
		$award_perfor_level = 0;
		$model_detail = Model('extension_commis_detail');
		$curr_sales = $model_detail->getManagerSalesAmount_coo($store_id,$member_id);
		foreach($perfor_award as $key => $award_info) {
			if ($award_info['mc_id']==3 && $curr_sales >= $award_info['achieve_val'] && $award_info['award_rate']>$award_perfor_rate){
				$award_perfor_name  = $award_info['award_name'];
				$award_perfor_rate  = $award_info['award_rate'];
				$award_perfor_level = $award_info['award_level']+$mc_level;				
			}
		}
		//协理奖金
		if ($award_perfor_rate>0){
			$award_perfor_totals = $model_detail->getPerforAward_coo($store_id,$member_id,$award_perfor_level);	
			if ($award_perfor_totals>0){
				$perforaward = $award_perfor_totals*$award_perfor_rate/100;	
			}
		}
		//下层经理奖金
		$manager_list = Model('extension')->GetPromotionManagerChildID($member_id,3,$store_id);
		if (!empty($manager_list) && is_array($manager_list)){
			foreach($manager_list as $key => $manager_id) {
				$manager_perforaward = $this->calculate_perforaward_manager($perfor_award,$store_id,$manager_id,$mc_level+1);
				if ($manager_perforaward>0){
					$perforaward_child = $perforaward_child+$manager_perforaward;
				}
			}					
		}
		$perforaward = $perforaward-$perforaward_child;
		if ($perforaward<0){
			$perforaward = 0;
		}
		return array('perforaward'=>$perforaward,'perforaward_child'=>$perforaward_child);
	}
	
	/**
	 * 计算首席级门店租金补贴
	 * $perfor_award 门店租金补贴标准
	 * $curr_sales 当前营业额
	 * $store_id 所属店铺
	 * $member_id 推广id
	 */
	public function calculate_perforaward_ceo($perfor_award=array(), $store_id=0, $member_id=0, $mc_level=0){		
		$perforaward = 0; //首席奖金
		$perforaward_child = 0; //协理及经理奖金
		$award_perfor_rate = 0;
		$award_perfor_level = 0;
		$model_detail = Model('extension_commis_detail');
		$curr_sales = $model_detail->getManagerSalesAmount_ceo($store_id,$member_id);
		foreach($perfor_award as $key => $award_info) {
			if ($award_info['mc_id']==4 && $curr_sales >= $award_info['achieve_val'] && $award_info['award_rate']>$award_perfor_rate){
				$award_perfor_name  = $award_info['award_name'];
				$award_perfor_rate  = $award_info['award_rate'];
				$award_perfor_level = $award_info['award_level']+$mc_level;				
			}
		}
		//首席奖金
		if ($award_perfor_rate>0){
			$award_perfor_totals = $model_detail->getPerforAward_ceo($store_id,$member_id,$award_perfor_level);	
			if ($award_perfor_totals>0){
				$perforaward = $award_perfor_totals*$award_perfor_rate/100;
			}
		}
		$coo_list = Model('extension')->GetPromotionManagerChildID($member_id,4,$store_id);
		if (!empty($coo_list) && is_array($coo_list)){
			foreach($coo_list as $key => $coo_id) {
				$coo_perforaward = $this->calculate_perforaward_coo($perfor_award,$store_id,$coo_id,$mc_level+1);
				if ($coo_perforaward['perforaward']>0){												
					$perforaward_child = $perforaward_child+$coo_perforaward['perforaward'];							
				}
				if ($coo_perforaward['perforaward_child']>0){												
					$perforaward_child = $perforaward_child+$coo_perforaward['perforaward_child'];							
				}
			}					
		}
		$perforaward = $perforaward-$perforaward_child;
		if ($perforaward<0){
			$perforaward = 0;
		}
		return array('perforaward'=>$perforaward,'perforaward_child'=>$perforaward_child);
	}
	
	/**
	 * 计算股东级门店租金补贴
	 * $perfor_award 门店租金补贴标准
	 * $curr_sales 当前营业额
	 * $store_id 所属店铺
	 * $member_id 推广id
	 */
	public function calculate_perforaward_holder($perfor_award=array(), $store_id=0, $member_id=0, $mc_level=0){		
		$perforaward = 0; //股东奖励
		$perforaward_child = 0; //首席及协理及经理奖金
		$award_perfor_rate = 0;
		$award_perfor_level = 0;
		$model_detail = Model('extension_commis_detail');
		$curr_sales = $model_detail->getManagerSalesAmount_holder($store_id,$member_id);
		foreach($perfor_award as $key => $award_info) {
			if ($award_info['mc_id']==5 && $curr_sales >= $award_info['achieve_val'] && $award_info['award_rate']>$award_perfor_rate){
				$award_perfor_name  = $award_info['award_name'];
				$award_perfor_rate  = $award_info['award_rate'];
				$award_perfor_level = $award_info['award_level']+$mc_level;				
			}
		}
		if ($award_perfor_rate>0){
			$award_perfor_totals = $model_detail->getPerforAward_holder($store_id,$member_id,$award_perfor_level);	
			if ($award_perfor_totals>0){
				$perforaward = $award_perfor_totals*$award_perfor_rate/100;
			}
		}
		$ceo_list = Model('extension')->GetPromotionManagerChildID($member_id,5,$store_id);
		if (!empty($ceo_list) && is_array($ceo_list)){
			foreach($ceo_list as $key => $ceo_id) {
				$ceo_perforaward = $this->calculate_perforaward_ceo($perfor_award,$store_id,$ceo_id,$mc_level+1);
				if ($ceo_perforaward['perforaward']>0){												
					$perforaward_child = $perforaward_child+$ceo_perforaward['perforaward'];							
				}
				if ($ceo_perforaward['perforaward_child']>0){												
					$perforaward_child = $perforaward_child+$ceo_perforaward['perforaward_child'];							
				}
			}
		}
        $perforaward = $perforaward-$perforaward_child;
		if ($perforaward<0){
			$perforaward = 0;
		}
		return array('perforaward'=>$perforaward,'perforaward_child'=>$perforaward_child);
	}
	
	//----------------------------------------------------------------------------高管奖励及升级处理开始------------------------------------------//
	/**
	 * 计算高管奖励
	 * $manager_award 门店租金补贴标准
	 * $curr_sales 当前营业额
	 * $store_id 所属店铺
	 * $member_id 推广id
	 * $mc_id 推广身份类型
	 */
	public function calculate_manageraward($manager_award=array(), $store_id=0, $member_id=0, $mc_id=0, $mc_level=0){
		$manageraward = array('manageraward'=>0,'manageraward_child'=>0,'extension_upgrade_op'=>0,'extension_upgrade_level'=>0);		
		//支持按销售额分等级奖励		
		switch($mc_id) {
			case 5:
				$manageraward = $this->calculate_manageraward_holder($manager_award,$store_id,$member_id,$mc_level);
                break;
			case 4:
				$manageraward = $this->calculate_manageraward_ceo($manager_award,$store_id,$member_id,$mc_level);
                break;
			case 3:
				$manageraward = $this->calculate_manageraward_coo($manager_award,$store_id,$member_id,$mc_level);
                break;
            case 2:
				$manageraward = $this->calculate_manageraward_manager($manager_award,$store_id,$member_id,$mc_level);
                break;
			case 1:
				$manageraward = $this->calculate_manageraward_fans($manager_award,$store_id,$member_id,$mc_level);
                break;      
		}
		return $manageraward;		
	}
	
	/**
	 * 计算代理奖励及升级计划
	 * $manager_award 高管奖励标准
	 * $store_id 所属店铺
	 * $member_id 推广id
	 * $mc_level 推广员层级
	 */
	public function calculate_manageraward_fans($manager_award=array(), $store_id=0, $member_id=0, $mc_level=0){		
		$extension_upgrade_op = 0; //是否升级
		$extension_upgrade_level = 0;
		//团队销售额及订单数	
		$model_detail = Model('extension_commis_detail');
		$curr_sales = $model_detail->getManagerSalesAndOrders_fans($store_id,$member_id);
		$curr_amount = $curr_sales['amount'];
		$curr_orders = $curr_sales['orders'];
		//直推下线人数及团队人数
		$model_extension = Model('extension');
		$curr_subs = $model_extension->countPromotionChild($store_id,$member_id);
		$curr_childs = $model_extension->countManagerAllChild($store_id,$member_id,1); 

		foreach($manager_award as $key => $award_info) {
			//升级
			if ($award_info['mc_id']>1 && $award_info['mc_id']>$extension_upgrade_level){
				if ($curr_subs>=$award_info['sub_nums'] && $curr_childs>=$award_info['child_nums'] && $curr_orders >= $award_info['order_nums'] && $curr_amount >= $award_info['achieve_val']){
				     $extension_upgrade_op = 1;
					 $extension_upgrade_level = $award_info['mc_id'];
				}
			}
		}
		return array('manageraward'=>0,'manageraward_child'=>0,'extension_upgrade_op'=>$extension_upgrade_op,'extension_upgrade_level'=>$extension_upgrade_level);
	}
	
	/**
	 * 计算经理级奖励及升级计划
	 * $manager_award 高管奖励标准
	 * $store_id 所属店铺
	 * $member_id 推广id
	 * $mc_level 推广员层级
	 */
	public function calculate_manageraward_manager($manager_award=array(), $store_id=0, $member_id=0, $mc_level=0){		
		$manageraward = 0; //经理奖励
		$extension_upgrade_op = 0; //是否升级
		$extension_upgrade_level = 0;
		$award_manager_rate = 0;
		$award_manager_level = 0;	
		//团队销售额及订单数	
		$model_detail = Model('extension_commis_detail');
		$curr_sales = $model_detail->getManagerSalesAndOrders_manager($store_id,$member_id);
		$curr_amount = $curr_sales['amount'];
		$curr_orders = $curr_sales['orders'];
		//直推下线人数及团队人数
		$model_extension = Model('extension');
		$curr_subs = $model_extension->countPromotionChild($store_id,$member_id);
		$curr_childs = $model_extension->countManagerAllChild($store_id,$member_id,2); 

		foreach($manager_award as $key => $award_info) {
			//高管奖
			if ($award_info['mc_id']==2 && $award_info['award_rate']>$award_manager_rate){
				$award_manager_name  = $award_info['award_name'];
				$award_manager_rate  = $award_info['award_rate'];
				$award_manager_level = $award_info['award_level']+$mc_level;
			}
			//升级
			if ($award_info['mc_id']>2 && $award_info['mc_id']>$extension_upgrade_level){
				if ($curr_subs>=$award_info['sub_nums'] && $curr_childs>=$award_info['child_nums'] && $curr_orders >= $award_info['order_nums'] && $curr_amount >= $award_info['achieve_val']){
				     $extension_upgrade_op = 1;
					 $extension_upgrade_level = $award_info['mc_id'];
				}
			}
		}
		//固定奖励
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['saleman_id']=$member_id;		  
		}
		$condition['saleman_type']=3;
		$condition['commis_type']=1;
		$condition['give_status']=0;
		
		$manageraward = $model_detail->getManagerAward_total($condition);
		//支持按销售额分等级奖励
		//if ($award_manager_rate>0){
		//	$award_manager_totals = $model_detail->getManagerAward_manager($store_id,$member_id,$award_manager_level);	
		//	if ($award_manager_totals>0){
		//		$manageraward = $award_manager_totals*$award_manager_rate/100;	
		//    }		
		//}
		if ($manageraward<0){
			$manageraward = 0;
		}
		return array('manageraward'=>$manageraward,'manageraward_child'=>0,'extension_upgrade_op'=>$extension_upgrade_op,'extension_upgrade_level'=>$extension_upgrade_level);
	}
	
	/**
	 * 计算协理级奖励及升级计划
	 * $manager_award 高管奖励标准
	 * $store_id 所属店铺
	 * $member_id 推广id
	 * $mc_level 推广员层级
	 */
	public function calculate_manageraward_coo($manager_award=array(), $store_id=0, $member_id=0, $mc_level=0){		
		$manageraward = 0; //协理奖金
		$manageraward_child = 0; //经理奖金
		$extension_upgrade_op = 0; //是否升级
		$extension_upgrade_level = 0;
		$award_manager_rate = 0;
		$award_manager_level = 0;
		//团队销售额及订单数	
		$model_detail = Model('extension_commis_detail');
		$curr_sales = $model_detail->getManagerSalesAndOrders_coo($store_id,$member_id);
		$curr_amount = $curr_sales['amount'];
		$curr_orders = $curr_sales['orders'];		
		//直推下线人数及团队人数
		$model_extension = Model('extension');
		$curr_subs = $model_extension->countPromotionChild($store_id,$member_id);
		$curr_childs = $model_extension->countManagerAllChild($store_id,$member_id,3); 

		foreach($manager_award as $key => $award_info) {
			//高管奖
			if ($award_info['mc_id']==3 && $award_info['award_rate']>$award_manager_rate){
				$award_manager_name  = $award_info['award_name'];
				$award_manager_rate  = $award_info['award_rate'];
				$award_manager_level = $award_info['award_level']+$mc_level;
			}
			//升级
			if ($award_info['mc_id']>3 && $award_info['mc_id']>$extension_upgrade_level){
				if ($curr_subs>=$award_info['sub_nums'] && $curr_childs>=$award_info['child_nums'] && $curr_orders >= $award_info['order_nums'] && $curr_amount >= $award_info['achieve_val']){
				     $extension_upgrade_op = 1;
					 $extension_upgrade_level = $award_info['mc_id'];
				}
			}
		}
		//固定奖励
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['saleman_id']=$member_id;		  
		}
		$condition['saleman_type']=3;
		$condition['commis_type']=1;
		$condition['give_status']=0;
		
		$manageraward = $model_detail->getManagerAward_total($condition);
		//支持按销售额分等级奖励
		//协理奖金
		//if ($award_manager_rate>0){			
		//	$award_manager_totals = $model_detail->getManagerAward_coo($store_id,$member_id,$award_manager_level);	
		//	if ($award_manager_totals>0){
		//		$manageraward = $award_manager_totals*$award_manager_rate/100;					
		//	}
		//}
		//下层经理奖金
		//$manager_list = Model('extension')->GetPromotionManagerChildID($member_id,3,$store_id);
		//if (!empty($manager_list) && is_array($manager_list)){
		//	foreach($manager_list as $key => $manager_id) {
		//		$manager_manageraward = $this->calculate_manageraward_manager($manager_award,$store_id,$manager_id,$mc_level+1);
		//		if ($manager_manageraward['manageraward']>0){
		//			$manageraward_child = $manageraward_child+$manager_manageraward['manageraward'];
		//		}
		//	}					
		//}
		//$manageraward = $manageraward-$manageraward_child;
		if ($manageraward<0){
			$manageraward = 0;
		}
		return array('manageraward'=>$manageraward,'manageraward_child'=>$manageraward_child,'extension_upgrade_op'=>$extension_upgrade_op,'extension_upgrade_level'=>$extension_upgrade_level);
	}
	
	/**
	 * 计算首席级奖励及升级计划
	 * $manager_award 高管奖励标准
	 * $store_id 所属店铺
	 * $member_id 推广id
	 * $mc_level 推广员层级
	 */
	public function calculate_manageraward_ceo($manager_award=array(), $store_id=0, $member_id=0, $mc_level=0){		
		$manageraward = 0; //首席奖金
		$manageraward_child = 0; //协理及经理奖金
		$extension_upgrade_op = 0; //是否升级
		$extension_upgrade_level = 0;
		$award_manager_rate = 0;
		$award_manager_level = 0;
		//团队销售额及订单数	
		$model_detail = Model('extension_commis_detail');
		$curr_sales = $model_detail->getManagerSalesAndOrders_ceo($store_id,$member_id);
		$curr_amount = $curr_sales['amount'];
		$curr_orders = $curr_sales['orders'];
		//直推下线人数及团队人数
		$model_extension = Model('extension');
		$curr_subs = $model_extension->countPromotionChild($store_id,$member_id);
		$curr_childs = $model_extension->countManagerAllChild($store_id,$member_id,4); 

		foreach($manager_award as $key => $award_info) {
			//高管奖
			if ($award_info['mc_id']==4 && $award_info['award_rate']>$award_manager_rate){
				$award_manager_name  = $award_info['award_name'];
				$award_manager_rate  = $award_info['award_rate'];
				$award_manager_level = $award_info['award_level']+$mc_level;
			}
			//升级
			if ($award_info['mc_id']>4 && $award_info['mc_id']>$extension_upgrade_level){
				if ($curr_subs>=$award_info['sub_nums'] && $curr_childs>=$award_info['child_nums'] && $curr_orders >= $award_info['order_nums'] && $curr_amount >= $award_info['achieve_val']){
				     $extension_upgrade_op = 1;
					 $extension_upgrade_level = $award_info['mc_id'];
				}
			}
		}
		//固定奖励
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['saleman_id']=$member_id;		  
		}
		$condition['saleman_type']=3;
		$condition['commis_type']=1;
		$condition['give_status']=0;
		
		$manageraward = $model_detail->getManagerAward_total($condition);
		//支持按销售额分等级奖励
		//首席奖金
		//if ($award_manager_rate>0){
		//	$award_manager_totals = $model_detail->getManagerAward_ceo($store_id,$member_id,$award_manager_level);	
		//	if ($award_manager_totals>0){
		//		$manageraward = $award_manager_totals*$award_manager_rate/100;
		//	}
		//}
		//下层协理奖金
		//$coo_list = Model('extension')->GetPromotionManagerChildID($member_id,4,$store_id);
		//if (!empty($coo_list) && is_array($coo_list)){
		//	foreach($coo_list as $key => $coo_id) {
		//		$coo_manageraward = $this->calculate_manageraward_coo($manager_award,$store_id,$coo_id,$mc_level+1);
		//		if ($coo_manageraward['manageraward']>0){												
		//			$manageraward_child = $manageraward_child+$coo_manageraward['manageraward'];							
		//		}
		//		if ($coo_manageraward['manageraward_child']>0){												
		//			$manageraward_child = $manageraward_child+$coo_manageraward['manageraward_child'];							
		//		}
		//	}					
		//}
		//$manageraward = $manageraward-$manageraward_child;
		if ($manageraward<0){
			$manageraward = 0;
		}
		return array('manageraward'=>$manageraward,'manageraward_child'=>$manageraward_child,'extension_upgrade_op'=>$extension_upgrade_op,'extension_upgrade_level'=>$extension_upgrade_level);
	}
	
	/**
	 * 计算股东级奖励及升级计划
	 * $manager_award 高管奖励标准
	 * $store_id 所属店铺
	 * $member_id 推广id
	 * $mc_level 推广员层级
	 */
	public function calculate_manageraward_holder($manager_award=array(), $store_id=0, $member_id=0, $mc_level=0){		
		$manageraward = 0; //股东奖励
		$manageraward_child = 0; //首席及协理及经理奖金
		$extension_upgrade_op = 0; //是否升级
		$extension_upgrade_level = 0;
		$award_manager_rate = 0;
		$award_manager_level = 0;
		//团队销售额及订单数	
		$model_detail = Model('extension_commis_detail');
		$curr_sales = $model_detail->getManagerSalesAndOrders_holder($store_id,$member_id);
		$curr_amount = $curr_sales['amount'];
		$curr_orders = $curr_sales['orders'];
		//直推下线人数及团队人数
		$model_extension = Model('extension');
		$curr_subs = $model_extension->countPromotionChild($store_id,$member_id);
		$curr_childs = $model_extension->countManagerAllChild($store_id,$member_id,4); 

		foreach($manager_award as $key => $award_info) {
			//高管奖
			if ($award_info['mc_id']==5 && $award_info['award_rate']>$award_manager_rate){
				$award_manager_name  = $award_info['award_name'];
				$award_manager_rate  = $award_info['award_rate'];
				$award_manager_level = $award_info['award_level']+$mc_level;
			}
		}
		//固定奖励
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['saleman_id']=$member_id;		  
		}
		$condition['saleman_type']=3;
		$condition['commis_type']=1;
		$condition['give_status']=0;
		
		$manageraward = $model_detail->getManagerAward_total($condition);
		//支持按销售额分等级奖励
		//股东奖
		//if ($award_manager_rate>0){
		//	$award_manager_totals = $model_detail->getManagerAward_holder($store_id,$member_id,$award_manager_level);	
		//	if ($award_manager_totals>0){
		//		$manageraward = $award_manager_totals*$award_manager_rate/100;
		//	}
		//}
		//下层首席奖金
		//$ceo_list = Model('extension')->GetPromotionManagerChildID($member_id,5,$store_id);
		//if (!empty($ceo_list) && is_array($ceo_list)){
		//	foreach($ceo_list as $key => $ceo_id) {
		//		$ceo_manageraward = $this->calculate_manageraward_ceo($manager_award,$store_id,$ceo_id,$mc_level+1);
		//		if ($ceo_manageraward['manageraward']>0){												
		//			$manageraward_child = $manageraward_child+$ceo_manageraward['manageraward'];							
		//		}
		//		if ($ceo_manageraward['manageraward_child']>0){												
		//			$manageraward_child = $manageraward_child+$ceo_manageraward['manageraward_child'];							
		//		}
		//	}
		//}
        //$manageraward = $manageraward-$manageraward_child;
		if ($manageraward<0){
			$manageraward = 0;
		}
		return array('manageraward'=>$manageraward,'manageraward_child'=>$manageraward_child,'extension_upgrade_op'=>$extension_upgrade_op,'extension_upgrade_level'=>$extension_upgrade_level);
	}	
	
	//---------------------------------------------------------------高管奖励及升级处理结束---------------------------------------------------------//
	
    /**
	 * 推广员信息
	 */
    public function promotion_infoOp(){		
		$promotion_id = intval($_GET["promotion_id"]);
		if (empty($promotion_id) || ($promotion_id<=0)){
			showDialog('非法参数');
		}

		$model_promotion = Model('extension');
		
		$member_info = Model('member')->getMemberInfo(array('member_id'=>$promotion_id),'mc_id');
		if (empty($member_info) || !is_array($member_info) || $member_info['mc_id'] < 1 || $member_info['mc_id'] > 2){
			showDialog('非法参数');
		}
		if ($member_info['mc_id'] == 2){
		  $promotion_info = $model_promotion->getPromotionDetailInfo($promotion_id);		
		
		  Tpl::output('promotion_info',$promotion_info);
		  Tpl::showpage('seller_promotion.info','null_layout');
		}else{
		  $saleman_info = $model_promotion->getSaleManDetailInfo($_SESSION['store_id'],$promotion_id);		
		
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
				$pay_commis = $pay_commis + $info['commis_totals'];
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
				    $pay_commis = $pay_commis + $info['commis_totals'];
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
			    $data['give_status'] = 1;    
			    $data['give_time'] = TIMESTAMP;						
			    $data['pde_sn'] = $pde_sn;							
			    $model_commis_detail->where($where)->update($data);
				//变更会员积分
				$predeposit = array();
		        $predeposit['member_id'] = $info['saleman_id'];
		        $predeposit['member_name'] = $info['member_name'];
		        $predeposit['amount'] = $info['commis_totals'];
		        $predeposit['pde_sn'] = $pde_sn;
		        $predeposit['admin_name'] = $_SESSION['member_name'];
		        $model_pd->changePd('commis',$predeposit);
			    //添加会员提佣记录
				$param = array();
				$param['pde_sn'] = $pde_sn; //提佣交易号
				$param['pde_store_id'] = $_SESSION['store_id']; //店铺ID
				$param['pde_mc_id'] = ($info['mc_id']==10)?1:2; //类型
				$param['pde_member_id'] = $info['saleman_id']; //会员编号
				$param['pde_member_name'] = $info['member_name']; //会员名称
				$param['pde_amount'] = $info['commis_totals']; //提佣金额
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
	 * 提取佣金操作
	 *
	 */
    public function extractCommission($flow_list = array(),$pay_commis = 0){	
		if (!empty($flow_list) && is_array($flow_list)){
			$model_extension = Model('extension');
			$model_commis_detail = Model('extension_commis_detail');
		    $model_pd = Model('predeposit');
			$model_extcommis = Model('pd_extcommis');
		    $pde_sn = $model_pd->makeSn();
			
			$upgrade_list = array();	
				
			foreach($flow_list as $k => $info) {
				//更改个人佣金明细信息
				$where = array();
			    $where['store_id']    = $_SESSION['store_id'];
			    $where['give_status'] = 0;
			    $where['saleman_id']  = $info['saleman_id'];
				
			    $data = array();				
			    $data['give_status']  = 1;    
			    $data['give_time']    = TIMESTAMP;						
			    $data['pde_sn']       = $pde_sn;	
										
			    $model_commis_detail->where($where)->update($data);	
				
				//更改个人奖励明细信息 支持按销售额分等级奖励
				//if ($info['mc_id']>=2 && $info['mc_id']<=5){
				//	switch($info['mc_id']) {
			    //    case 5:
				//        $mc_key = 'holder_id';
                //        break;
			    //    case 4:
				//        $mc_key = 'ceo_id';
                //        break;
			    //    case 3:
				//        $mc_key = 'coo_id';
                //        break;
                //    case 2:
				//        $mc_key = 'manager_id';
                //        break;	        
				//    }
				//    $where = array();
			    //    $where['store_id']    = GENERAL_PLATFORM_EXTENSION_ID;
			    //    $where['give_status'] = 0;
				//	  $where['saleman_type'] = array('in',array(3,4));
			    //    $where[$mc_key]  = $info['saleman_id'];
				
			    //    $data = array();				
			    //    $data[$mc_key]  = 0;    
			    //    $data['give_time']    = TIMESTAMP;						
			    //    $data['pde_sn']       = $pde_sn;	
										
			    //    $model_commis_detail->where($where)->update($data);
				//}				
				
			    //添加会员提佣记录
				$param = array();
				$param['pde_sn']            = $pde_sn; //提佣交易号
				$param['pde_store_id']      = $_SESSION['store_id']; //店铺ID
				$param['pde_mc_id']         = $info['mc_id'];//($info['mc_id']==10)?1:2; //类型
				$param['pde_member_id']     = $info['saleman_id']; //会员编号
				$param['pde_member_name']   = $info['member_name']; //会员名称				
				$param['pde_commis']        = $info['curr_commis']; //推广佣金
				$param['pde_manageaward']   = $info['award_manage_totals']; //高管补贴
				$param['pde_perforaward']   = $info['award_perfor_totals']; //门店补贴				
				$param['pde_amount']        = $info['commis_totals']; //提佣金额
				$param['pde_add_time']      = TIMESTAMP;	//添加时间
				$param['pde_admin']         = $_SESSION['member_name']; //结算操作
				$param['pde_payment_code']  = 0; //支付方式
				$param['pde_payment_name']  = '积分'; //支付方式名称
				$param['pde_trade_sn']      = $pde_sn; //支付交易号
				$param['pde_payment_state'] = 1; //支付状态 0未支付1支付
				$param['pde_payment_time']  = TIMESTAMP;	//支付时间
				$param['pde_payment_admin'] = $_SESSION['member_name']; //支付操作
				
				$model_extcommis->addExtCommis($param);
				
				//更新推广员总佣金收入
		        $where = array();
		        $where['member_id'] = $info['saleman_id'];
		
		        $new_info = array();
		        $new_info['commis_totals'] = array('exp','commis_totals+'.$info['commis_totals']);
				$new_info['commis_balance'] = array('exp','commis_balance+'.$info['curr_commis']); 
				$new_info['manageaward_balance'] = array('exp','manageaward_balance+'.$info['award_manage_totals']);
				$new_info['perforaward_balance'] = array('exp','perforaward_balance+'.$info['award_perfor_totals']);
		
		        $model_extension->editExtension($new_info,$where);
				
				//变更会员积分
				$predeposit = array();
		        $predeposit['member_id']  = $info['saleman_id'];
		        $predeposit['member_name']= $info['member_name'];
		        $predeposit['amount']     = $info['commis_totals'];
		        $predeposit['pde_sn']     = $pde_sn;
		        $predeposit['admin_name'] = $_SESSION['member_name'];
				
		        $model_pd->changePd('commis',$predeposit);
				//推广员升级列表
				if ($info['extension_upgrade_op']==1){
					$upgrade_info = array();
					$upgrade_info['member_id'] = $info['saleman_id'];
					$upgrade_info['mc_id'] = $info['mc_id'];
					$upgrade_info['upgrade_mc_id'] = $info['extension_upgrade_level'];
					
					$upgrade_list[] = $upgrade_info;
				}
			}
			//升级处理
			if (!empty($upgrade_list)){
				foreach($upgrade_list as $k => $info) {
					$this->extension_upgrade($info['member_id'],$info['mc_id'],$info['upgrade_mc_id']);
				}
			}
		}		
	}
	
	//---------------------------------------------------------------推广员升级处理开始---------------------------------------------------------//
	/**
	 * 推广员升级处理
	 * $member_id 推广id
	 * $mc_id 推广身份类型
	 * $upgrade_mc_id 推广升级身份类型
	 */
	public function extension_upgrade($member_id=0, $mc_id=0, $upgrade_mc_id=0){		
		if ($mc_id<$upgrade_mc_id && $upgrade_mc_id>1){
		    switch($upgrade_mc_id) {
			    case 5:
				    $manageraward = $this->extension_upgrade_holder($member_id);
                    break;
			    case 4:
				    $manageraward = $this->extension_upgrade_ceo($member_id);
                    break;
			    case 3:
				    $manageraward = $this->extension_upgrade_coo($member_id);
                    break;
                case 2:
				    $manageraward = $this->extension_upgrade_manager($member_id);
                    break;   
		    }
		}
	}
	
	/**
	 * 升级到股东处理
	 * $member_id 推广id
	 * $upgrade_mc_id 推广升级身份类型
	 */
	public function extension_upgrade_holder($member_id=0){
		$model_extension = Model('extension');
		$extension_info = $model_extension->getExtensionByMemberID($member_id);
		if ($member_id==0 || empty($extension_info) || $extension_info['mc_id']>=5){
			return;
		}
		//更新推广员升级信息
		$where = array();
		$where['member_id'] = $extension_info['member_id'];
		
		$new_info = array();
		$new_info['mc_id'] = 5;
		
		$model_extension->editExtension($new_info,$where);
		//更新推广员下级信息						
		$child_info = array();
		$child_info['holder_id'] = $extension_info['member_id'];		
		if ($extension_info['mc_id']>=2 && $extension_info['mc_id']<=4){
			//高管升级
		    $where = array();
		    switch($extension_info['mc_id']) {
			    case 4:
				    $where['ceo_id'] = $extension_info['member_id'];
					$child_info['ceo_id'] = 0;
                    break;
			    case 3:
			        $where['coo_id'] = $extension_info['member_id'];
					$child_info['coo_id'] = 0;
                    break;
                case 2:
			        $where['manager_id'] = $extension_info['member_id'];
					$child_info['manager_id'] = 0;
                    break;
			}
			$model_extension->editExtension($child_info,$where);			
		}else{
			//普通推广员升级
			$this->extension_upgrade_fans($child_info,$extension_info['member_id']);			
		}	
	}
	
	/**
	 * 升级到首席处理
	 * $member_id 推广id
	 * $upgrade_mc_id 推广升级身份类型
	 */
	public function extension_upgrade_ceo($member_id=0){
		$model_extension = Model('extension');
		$extension_info = $model_extension->getExtensionByMemberID($member_id);
		if ($member_id==0 || empty($extension_info) || $extension_info['mc_id']>=4){
			return;
		}
		//更新推广员升级信息
		$where = array();
		$where['member_id'] = $extension_info['member_id'];
		
		$new_info = array();
		$new_info['mc_id'] = 4;
		
		$model_extension->editExtension($new_info,$where);
		//更新推广员下级信息						
		$child_info = array();
		$child_info['ceo_id'] = $extension_info['member_id'];		
		if ($extension_info['mc_id']>=2 && $extension_info['mc_id']<=3){
			//高管升级
		    $where = array();
		    switch($extension_info['mc_id']) {
			    case 3:
			        $where['coo_id'] = $extension_info['member_id'];
					$child_info['coo_id'] = 0;
                    break;
                case 2:
			        $where['manager_id'] = $extension_info['member_id'];
					$child_info['manager_id'] = 0;
                    break;
			}
			$model_extension->editExtension($child_info,$where);			
		}else{
			//普通推广员升级
			$this->extension_upgrade_fans($child_info,$extension_info['member_id']);			
		}		
	}
	
	/**
	 * 升级到协理处理
	 * $member_id 推广id
	 * $upgrade_mc_id 推广升级身份类型
	 */
	public function extension_upgrade_coo($member_id=0){
		$model_extension = Model('extension');
		$extension_info = $model_extension->getExtensionByMemberID($member_id);
		if ($member_id==0 || empty($extension_info) || $extension_info['mc_id']>=3){
			return;
		}
		//更新推广员升级信息
		$where = array();
		$where['member_id'] = $extension_info['member_id'];
		
		$new_info = array();
		$new_info['mc_id'] = 3;
		
		$model_extension->editExtension($new_info,$where);
		//更新推广员下级信息						
		$child_info = array();
		$child_info['coo_id'] = $extension_info['member_id'];		
		if ($extension_info['mc_id']==2){
			$child_info['manager_id'] = 0;
			//高管升级
		    $where = array();
			$where['manager_id'] = $extension_info['member_id'];
			
			$model_extension->editExtension($child_info,$where);			
		}else{
			//普通推广员升级
			$this->extension_upgrade_fans($child_info,$extension_info['member_id']);			
		}		
	}
	
	/**
	 * 升级到经理处理
	 * $member_id 推广id
	 * $upgrade_mc_id 推广升级身份类型
	 */
	public function extension_upgrade_manager($member_id=0){
		$model_extension = Model('extension');
		$extension_info = $model_extension->getExtensionByMemberID($member_id);
		if ($member_id==0 || empty($extension_info) || $extension_info['mc_id']>=2){
			return;
		}
		//更新推广员升级信息
		$where = array();
		$where['member_id'] = $extension_info['member_id'];
		
		$new_info = array();
		$new_info['mc_id'] = 2;
		
		$model_extension->editExtension($new_info,$where);
		//更新推广员下级信息						
		$child_info = array();
		$child_info['manager_id'] = $extension_info['member_id'];		
		//普通推广员升级
		$this->extension_upgrade_fans($child_info,$extension_info['member_id']);
	}  
	
	/**
	 * 普通推广员升级处理
	 * $data 更新的内容
	 * $parent_id 上级推广员ID
	 */
	public function extension_upgrade_fans($data=array(),$parent_id=0){
		$model_extension = Model('extension');
		$where = array();
		$where['parent_id']=$parent_id;
		$model_extension->editExtension($data,$where);
		$child_list = $model_extension->getExtensionList($where,'member_id');
		if (!empty($child_list)){
			foreach($child_list as $k => $info) {
				$this->extension_upgrade_fans($data,$info['member_id']);
			}
		}
		
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