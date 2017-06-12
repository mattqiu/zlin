<?php
/**
 * 推广员/导购员表
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class extensionModel extends Model {
    public function __construct() {
        parent::__construct('extension');
    }
	
    /**
     * 推广员/导购员列表
     * @param unknown $condition
     * @param string $fields
     * @param string $pagesize
     * @param string $order
     * @param string $limit
     */
    public function getExtensionList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null) {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }
	
	/**
     * 根据店铺ID取得推广员/导购员列表
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionListByStoreID($store_id = '', $fields = '*') {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$extension_list = array();
		if (!empty($store_id)){
		    $condition = array();
		    $condition['store_id'] = $store_id;
			$extension_list = $this->getExtensionList($condition,$fields);
		}
        return $extension_list;
    }

    /**
     * 取得推广员/导购员单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionInfo($condition = array(), $fields = '*') {
        return $this->where($condition)->field($fields)->find();
    }
	
	/**
     * 根据会员id取得推广员/导购员单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionByMemberID($member_id = '', $fields = '*') {
        $extension_info = array();
		if (!empty($member_id)){
		    $condition = array();
		    $condition['member_id'] = $member_id;
			$extension_info = $this->getExtensionInfo($condition,$fields);
		}
        return $extension_info;
    }	
    
    /**
     * 取得推广员/导购员数量
     * @param unknown $condition
     */
    public function getExtensionCount($condition) {
        return $this->where($condition)->count();
    }	
	
    /**
     * 添加推广员/导购员
     * @param unknown $condition
     */
    public function addExtension($data) {
        return $this->insert($data);
    }
	
    /**
     * 修改推广员/导购员
     * @param unknown $condition
     */
    public function editExtension($data, $condition = array()) {
        return $this->where($condition)->update($data);
    }
	
	/**
     * 删除推广员/导购员
     * @param unknown $condition
     */
	public function delExtension($condition = array()) {
        return $this->where($condition)->delete();
    }
    
    /**
	 * 插入默认值数据
	 *
	 * @param array $data
	 * @param array $options
	 * @return mixed int/false
	 */
	 protected function _auto_insert_data(&$data,$options) {
		 		 
	}
	
	/**
	 * 添加推广员
	 *
	 * @param array $data
	 * @param array $options
	 * @return mixed int/false
	 */
    public function addVirtualExtension($member_info) {
		$parent_id = 0;
		$extension_id=cookie('iMall_extension');
		if(!empty($member_info['parent_id'])){
			$parent_id = $member_info['parent_id'];
		}elseif (!empty($extension_id)){
		    $parent_id = urlsafe_b64decode($extension_id);	
		}else{
			$parent_id = 0;
		}
		
		$parent_info = array();
		if ($parent_id>0){
			$store_id = (OPEN_STORE_EXTENSION_STATE == 10)?GENERAL_PLATFORM_EXTENSION_ID:0;
			$parent_info = $this->getPromotionInfo($store_id,$parent_id);
			//判断上一级是否满足条件升级
			if($parent_info['mc_id']>0){
				//推广管理奖励表
				$manageaward_rate = Model('extension_manageaward')->getExtensionManageAwardInfo(array('mc_id'=>2));
				//升级条件
				$sub_nums = $manageaward_rate['sub_nums']; //直推人数
				$child_nums = $manageaward_rate['child_nums'];//下线人数
				$order_nums = $manageaward_rate['order_nums'];//订单总数
				$achieve_val = $manageaward_rate['achieve_val'];//业绩
				//个人条件
				$ger_nums = $this->countPromotionChild($store_id,$parent_id);
				$promotionChild = $this->countAllPromotionChild($store_id,$parent_id);
				$model_extension_commis_detail =Model('extension_commis_detail');
				$personalOrdes = $model_extension_commis_detail->getPersonalOrdes($store_id,$parent_id);
				$personalCommis = $model_extension_commis_detail->getPersonalCommis($store_id,$parent_id);
				
				if(($sub_nums<=$ger_nums) && ($child_nums<=$promotionChild) && ($order_nums<=$personalOrdes) && $achieve_val==0){//关于业绩需要结算时升级($achieve_val<=$personalCommis['amounts'])){
					//所有条件都满足升级
					/** 临时解决的仅仅只是经理的升级问题
					$mc_id = $parent_info['mc_id'];
					$upgrade_mc_id = $mc_id+1;
					$this->extension_upgrade($parent_id, $mc_id, $upgrade_mc_id);
					*/
					$mc_id = $parent_info['mc_id'] + 1;
					if($mc_id>5){
						$mc_id = 5;
					}
					$this->editExtension(array('mc_id'=>$mc_id),array('member_id'=>$parent_id,'store_id'=>$store_id));
					//修改所有被邀请人的店长ID
					if($mc_id==2){
						$this->editExtension(array('manager_id'=>$parent_id),array('parent_id'=>$parent_id,'store_id'=>$store_id));
						//并且刚刚被邀请的人的店长ID 也为
						//邀请人 是否是VIP
						$model_member = Model('member');
						$memberInfo = $model_member->getMemberInfoByID($parent_id,'member_exppoints');
						$member_grade = $model_member->getOneMemberGradeLevel($parent_id);
						
						if($member_grade>1){
							$parent_info['manager_id'] = $parent_id;
							Model('member')->editMember(array('member_id'=>$parent_id),array('mc_id'=>2,'mc_time'=>TIMESTAMP+365*24*3600));
						}else{
							$parent_info['manager_id'] = 0;
						}
						
					}elseif($mc_id==3){
						$this->editExtension(array('coo_id'=>$parent_id),array('parent_id'=>$parent_id,'store_id'=>$store_id));
					}elseif($mc_id==4){
						$this->editExtension(array('ceo_id'=>$parent_id),array('parent_id'=>$parent_id,'store_id'=>$store_id));
					}elseif($mc_id==5){
						$this->editExtension(array('holder_id'=>$parent_id),array('parent_id'=>$parent_id,'store_id'=>$store_id));
					}
				}
			}
			//系统条件
		}
		if (!empty($parent_info)){
			$store_id = $parent_info['store_id'];
			$parent_id = $parent_info['member_id'];
			if (empty($parent_info['parent_tree']) || $parent_info['parent_tree']=='' || $parent_info['parent_tree']=='0'){				
			    $parent_tree = '|'.$parent_id.'|';
				$mc_level = 2;
			}else{
				if (OPEN_STORE_EXTENSION_STATE == 10){
					$max_promotion_level = C('gl_promotion_level');
				}else{
					$max_promotion_level = Model('store')->getPromotionLevel($store_id);
				}
			    //生成层级数
			    $parent_str  = trim($parent_info['parent_tree'],'|');						
			    $parent_list = explode('|',$parent_str);						
			    if (count($parent_list) > ($max_promotion_level-2)){							
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
		}else{
			$store_id = ($member_info['store_id']>0)?$member_info['store_id']:0;
			
			$parent_id = 0;
			$parent_tree = '';
			$holder_id = 0;
		    $ceo_id = 0;
			$coo_id = 0;
			$manager_id = 0;
			$mc_level = 1;				
		}
		$mc_id = 1;
				
		$promotion = array();
		$promotion['member_id']  = $member_info['member_id'];
		$promotion['member_name']= $member_info['member_name'];				
		$promotion['mc_id']      = $mc_id;	//推广员类型:1 普通推广员 2经理 3协理 4首席 5股东 10 导购员
		$promotion['mc_level']   = $mc_level;
		$promotion['mc_real']    = 1; //推广员是否有实体店:1 无实体店 10 有实体店
		$promotion['store_id']   = $store_id;
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
				
		$this->addExtension($promotion);		 		 
	}	
	
	
	/**
	 * 查询会员消费总额（已付款，不含运费）
	 */
	public function GetMemberConsumeTotals($member_id, $store_id = 0){
		//已付款 已发货 已完成
		$condition = array();
		$condition ['buyer_id'] = $member_id;
		if ($store_id>0){
			$condition ['store_id'] = $store_id;
		}
		$condition['order_state'] = array('in', array(ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS));//ORDER_STATE_PAY, 
		$ConsumeTotals = $this->table('order')->where($condition)->sum('goods_amount');
		return $ConsumeTotals>0?$ConsumeTotals:0;
	}
	
	/**
     * 推广员数量
     * @param array $condition
     * @return int
     */
    public function getPromotionCount($store_id = 0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		$condition['store_id'] = $store_id;
		$condition['mc_id'] = array('lt',10);

		$count = $this->getExtensionCount($condition);
		return $count;
    }
	
	/**
     * 导购员数量
     * @param array $condition
     * @return int
     */
    public function getSalemanCount($store_id = 0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		//得到条件语句
		$condition_str	= "((parent_tree LIKE '%|".$store_id."|%') OR ( store_id = ".$store_id.")) AND (mc_id=10)";
		$count = Db::getCount('extension',$condition_str);
		return $count;
    }	
	
	/**
     * 推广员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getPromotionList($store_id = 0, $condition = array(), $field = '*', $page = 0, $order = 'mc_id desc,ext_id asc', $deep = 0, $check_deep = true) {	   
	   if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
	   
	   $condition['store_id'] = $store_id;
	   $condition['mc_id'] = array('lt',10);	
       $promotion_list = $this->getExtensionList($condition, $field, $page, $order); 
	   if (!empty($promotion_list) && is_array($promotion_list)) {
		  //获取店铺最大推广员层级数	  
		  $promotion_level = Model('store')->getPromotionLevel($store_id);		  
		   
		  $model_commis_detail = Model('extension_commis_detail');
		  
          foreach($promotion_list as $k => $info) {
			if ($check_deep && $deep>=($promotion_level-2)){
			    $promotion_list[$k]['have_child'] = 0;
			}else{
				$promotion_list[$k]['have_child'] = $this->countPromotionChild($store_id,$info['member_id']);				
			}
			$promotion_list[$k]['deep'] = $deep+1;
			//总业绩和提成
			$commis_totals = $model_commis_detail->getStatisticsCommis($store_id,$info['member_id'],array('saleman_type'=>array('lt',10)));
			$promotion_list[$k]['total_sales'] = $commis_totals['amounts']?$commis_totals['amounts']:0;
			$promotion_list[$k]['total_commis'] = $commis_totals['commis']?$commis_totals['commis']:0;
			//本期业绩和提成
			$curr_totals = $model_commis_detail->getStatisticsCommis($store_id,$info['member_id'],array('saleman_type'=>array('lt',10),'give_status'=>0));
			$promotion_list[$k]['curr_sales'] = $curr_totals['amounts']?$curr_totals['amounts']:0;
			$promotion_list[$k]['curr_commis'] = $curr_totals['commis']?$curr_totals['commis']:0;
		  }		  
		}
		return $promotion_list;
    }
	
	/**
     * 推广员简要信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getPromotionInfo($store_id, $promotion_id) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id'] = $store_id;
		}
		$condition['member_id'] = $promotion_id;
	    $condition['mc_id'] = array('lt',10);
        return $this->getExtensionInfo($condition, 'member_id,mc_id,mc_level,mc_real,store_id,parent_id, parent_tree,holder_id,ceo_id,coo_id,manager_id,commis_totals');
    }
	/**
     * 推广员详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getPromotionDetailInfo($member_id) {		
		$fields = 'extension.*,member.member_truename,member.member_sex,member.member_qq,member.member_mobile';
		$on = 'extension.member_id = member.member_id';		
		$condition = array();
		$condition['extension.member_id'] = $member_id;
		$condition['extension.mc_id'] = array('lt',10);
        $Promotion_Info = Model()->table('extension,member')->field($fields)->join('left')->on($on)->where($condition)->find();		
			
		if (!empty($Promotion_Info) && is_array($Promotion_Info)){
			switch($Promotion_Info['mc_id']) {
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
			  default:
			    $mc_name = '推广员';
				break;
            }	   
			$Promotion_Info['mc_name'] = $mc_name;
		    $Promotion_Info['sub_nums'] = $this->countPromotionChild($Promotion_Info['store_id'],$Promotion_Info['member_id']);
            $Promotion_Info['child_nums'] = $this->countAllPromotionChild($Promotion_Info['store_id'],$Promotion_Info['member_id']);
		    $Promotion_Info['member_add'] = date("Y-m-d",$Promotion_Info['createdtime']);
			
			$model_commis_detail = Model('extension_commis_detail');
		    //总业绩和提成
		    $commis_totals = $model_commis_detail->getStatisticsCommis($Promotion_Info['store_id'],$Promotion_Info['member_id'],array('saleman_type'=>2));
			$Promotion_Info['total_sales'] = $commis_totals['amounts'];
		    $Promotion_Info['total_commis'] = $commis_totals['commis'];
		    //本期业绩和提成
		    $curr_totals = $model_commis_detail->getStatisticsCommis($Promotion_Info['store_id'],$Promotion_Info['member_id'],array('saleman_type'=>2,'give_status'=>0));
		    $Promotion_Info['curr_sales'] = $curr_totals['amounts'];
		    $Promotion_Info['curr_commis'] = $curr_totals['commis'];
		}		 
		return $Promotion_Info;
    }
	/**
	 * 查询推广员下线人数(直推下线)
	 */
	public function countPromotionChild($store_id = 0, $member_id = 0){
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		$condition['store_id'] = $store_id;
		$condition['parent_id'] = $member_id;

		$count = $this->where($condition)->count();
		return $count;
	}
	
	/**
	 * 查询推广员所有下线人数
	 */
	public function countAllPromotionChild($store_id = 0, $member_id = 0){
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		$condition['store_id'] = $store_id;
		$condition['mc_id'] = array('lt',10);
		$condition['parent_tree'] = array('like', '%|' . $member_id . '|%');

		$count = $this->where($condition)->count();
		return $count;
	}
	
	/**
	 * 查询高管所有下线人数
	 */
	public function countManagerAllChild($store_id = 0, $member_id = 0, $mc_id = 0){
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		$condition['store_id'] = $store_id;
		$condition['mc_id'] = array('lt',10);
		switch ($mc_id){
		  case 2: //2经理
		    $condition['manager_id'] = $member_id;
			break;
		  case 3: //3协理
		    $condition['coo_id'] = $member_id;
			break;
		  case 4: //4首席
		    $condition['ceo_id'] = $member_id;
			break;
		  case 5: //5股东
		    $condition['holder_id'] = $member_id;
			break;
		  default:
		    $condition['parent_tree'] = array('like', '%|' . $member_id . '|%');
			break;
		}		

		$count = $this->where($condition)->count();
		return $count;
	}
	
	/**
	 * 查询推广员所有下线ID
	 */
	public function GetAllPromotionChildID($member_id = 0, $mc_id = 0, $store_id = 0){
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$id_list = array();
		$id_list[] = $member_id;
		if ($mc_id == 2){
		    $condition = array();
		    $condition['store_id'] = $store_id;
			$condition['mc_id'] = array('lt',10);
		    $condition['parent_tree'] = array('like', '%|' . $member_id . '|%');

		    $child_list = $this->where($condition)->field('member_id')->select();		
		    if (!empty($child_list) && is_array($child_list)){
			    foreach($child_list as $k => $info) {
				    $id_list[] = $info['member_id'];
			    }
		    }
		}		
		return $id_list;
	}
	
	/**
	 * 查询推广员所有直推高管下线ID
	 */
	public function GetPromotionManagerChildID($member_id = 0, $mc_id = 0, $store_id = 0){
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$id_list = array();
		$condition = array();
		$condition['store_id'] = $store_id;
		$condition['parent_id'] = $member_id;
		switch ($mc_id){
		  case 2: //2经理
		    $condition['mc_id'] = 1;
			$condition['manager_id'] = $member_id;
			break;
		  case 3: //3协理
		    $condition['mc_id'] = 2;
			$condition['coo_id'] = $member_id;
			break;
		  case 4: //4首席
		    $condition['mc_id'] = 3;
			$condition['ceo_id'] = $member_id;
			break;
		  case 5: //5股东
		    $condition['mc_id'] = 4;
			$condition['holder_id'] = $member_id;
			break;
		  default:
		    $condition['mc_id'] = 1;
			$condition['manager_id'] = $member_id;
			break;
		}	
		
		$child_list = $this->where($condition)->field('member_id')->select();		
		if (!empty($child_list) && is_array($child_list)){
			foreach($child_list as $k => $info) {
				$id_list[] = $info['member_id'];
			}
		}
		return $id_list;
	}
	
	/**
	 * 查询当前推广员级数
	 */
	public function GetPromotionLevel($member_id = 0){
		$Level = 1;
		$condition = array();
		$condition['member_id'] = $member_id;
        $Promotion_Info = $this->table('member')->field('parent_tree')->where($condition)->find();
		if (!empty($Promotion_Info) && is_array($Promotion_Info)){
			if (empty($Promotion_Info['parent_tree']) || $Promotion_Info['parent_tree']=='' || $Promotion_Info['parent_tree']=='0'){				
			  $Level = 1;
			}else{
			  $parent_tree = trim($Promotion_Info['parent_tree'],'|');
			  $parent_tree = explode('|',$parent_tree);
			  $Level = count($parent_tree)+1;
			}
		}else{
			$Level = 0;
		}

		return $Level;
	}
	
	/**
     * 导购员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getSaleManList($store_id = 0, $page = 0, $order = 'member_id asc') {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
			
		$fields = 'extension.member_id,extension.member_name,extension.mc_id,extension.parent_tree,member.member_truename,member.member_sex,member.member_qq,member.member_mobile';
		$on = 'extension.member_id = member.member_id';		
		$condition = array();
		$condition['extension.store_id'] = $store_id;
		$condition['extension.mc_id'] = 10;
		//$condition['extension.parent_tree'] = array('like', '%|' . $store_id . '|%');		
        $saleman_list = Model()->table('extension,member')->field($fields)->where($condition)->join('left')->on($on)->page($page)->order($order)->select();			
   
	   if (!empty($saleman_list) && is_array($saleman_list)) {
		  $model_commis_detail = Model('extension_commis_detail');	  
          foreach($saleman_list as $k => $info) {
			//总业绩和提成
			$commis_totals = $model_commis_detail->getStatisticsCommis($store_id,$info['member_id'],array('saleman_type'=>1));
			$saleman_list[$k]['total_sales'] = $commis_totals['amounts']?$commis_totals['amounts']:0;
			$saleman_list[$k]['total_commis'] = $commis_totals['commis']?$commis_totals['commis']:0;
			//本期业绩和提成
			$curr_totals = $model_commis_detail->getStatisticsCommis($store_id,$info['member_id'],array('saleman_type'=>1,'give_status'=>0));
			$saleman_list[$k]['curr_sales'] = $curr_totals['amounts']?$curr_totals['amounts']:0;
			$saleman_list[$k]['curr_commis'] = $curr_totals['commis']?$curr_totals['commis']:0;
		  }		  
		}
		return $saleman_list;
    }
	
	/**
     * 导购员简要信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getSaleManInfo($store_id, $saleman_id) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		$condition['store_id'] = $store_id;
		$condition['member_id'] = $saleman_id;
	    $condition['mc_id'] = 10;
        return $this->getExtensionInfo($condition, 'member_id,mc_id,store_id,parent_id, parent_tree,holder_id,ceo_id,coo_id,manager_id');
    }
	
	/**
     * 导购员详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getSaleManDetailInfo($store_id,$member_id) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$fields = 'extension.member_id,extension.member_name,extension.mc_id,extension.parent_tree,extension.createdtime,member.member_truename,member.member_sex,member.member_qq,member.member_mobile';
		$on = 'extension.member_id = member.member_id';		
		$condition = array();
		$condition['extension.store_id'] = $store_id;
		$condition['extension.mc_id'] = 10;
		$condition['extension.member_id'] = $member_id;
		//$condition['extension.parent_tree'] = array('like', '%|' . $store_id . '|%');
        $saleman_Info = Model()->table('extension,member')->field($fields)->join('left')->on($on)->where($condition)->find();

		if (!empty($saleman_Info) && is_array($saleman_Info)){
		    $saleman_Info['member_add'] = date("Y-m-d",$saleman_Info['createdtime']);
			
			$model_commis_detail = Model('extension_commis_detail');
		    //总业绩和提成
		    $commis_totals = $model_commis_detail->getStatisticsCommis($saleman_Info['store_id'],$saleman_Info['member_id'],array('saleman_type'=>1));
			$saleman_Info['total_sales'] = $commis_totals['amounts'];
		    $saleman_Info['total_commis'] = $commis_totals['commis'];
		    //本期业绩和提成
		    $curr_totals = $model_commis_detail->getStatisticsCommis($saleman_Info['store_id'],$saleman_Info['member_id'],array('saleman_type'=>1,'give_status'=>0));
		    $saleman_Info['curr_sales'] = $curr_totals['amounts'];
		    $saleman_Info['curr_commis'] = $curr_totals['commis'];
		}		 
		return $saleman_Info;
    }
	
	/**
     * 清除会员推广信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function ClearExtensionInfo($member_id) {
		if (!empty($member_id) && $member_id>0){
		    $where=array();
		    $where['member_id']=intval($member_id);
		
		    $data=array();
		    $data['mc_id'] = 0;
		    $data['store_id'] = 0;
			
			Model('member')->where($where)->update($data);

			return $this->delExtension($where);		
		}else{
			return false;
		}		
	}
	
	//---------------------------------------------分配返佣----------------------------------------------//
	/**
     * 分配返佣
     * @param $extension_id 推广员ID
     * @param $rebate 返佣金额
	 * @param $order_id 订单id
	 * @param $order_sn 订单号
	 * @param $order_amount 订单总金额
	 * @param $store_id 订单所属店铺ID
	 * @param $store_name 订单所属店铺名称
	 * @param $rebate_points 返佣云币
     */
    public function DistributeRebate($extension_id = '', $rebate = 0, $order_id = '', $order_sn = '', $order_amount = 0, $store_id = 0, $store_name = '', $rebate_points = 0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
				
		//本次发放的推广佣金金额，用于发送短信
		$order_commission_balance = 0;
		//推广员信息							
	    $saleman_info=$this->getExtensionByMemberID($extension_id,'member_id,member_name,mc_id,mc_level,mc_real,store_id,parent_id,parent_tree,holder_id,ceo_id,coo_id,manager_id');			
		if (!empty($saleman_info) && is_array($saleman_info)){				      
		    //佣金明细
			$commis_detail=array();
			$commis_detail['store_id'] = $store_id;
		    $commis_detail['store_name'] = $store_name;	
			//saleman_id
			//saleman_name
			//saleman_parent
			//saleman_type
			$commis_detail['extension_id'] = $saleman_info['member_id'];	
			$commis_detail['extension_name'] = $saleman_info['member_name'];			
			$commis_detail['mc_level'] = $saleman_info['mc_level'];
			$commis_detail['holder_id'] = $saleman_info['holder_id']; //股东ID
			$commis_detail['ceo_id'] = $saleman_info['ceo_id'];	//首席ID
			$commis_detail['coo_id'] = $saleman_info['coo_id']; //协理ID
			$commis_detail['manager_id'] = $saleman_info['manager_id']; //经理ID											    		
			$commis_detail['order_id'] = $order_id;	//订单ID
			$commis_detail['order_sn'] = $order_sn;	//订单号
			$commis_detail['goods_amount']= $order_amount; //order_amount:订单总价 goods_amount:实际付款 shipping_fee:优惠额
			$commis_detail['commis_amount'] = $rebate;	//返佣总金额
			//commis_rate
			//mb_commis_totals							
			$commis_detail['add_time']=time();	
			$commis_detail['add_date']=date('Ymd',time());	
			$commis_detail['give_status']=0;    //未结算
			$commis_detail['give_time']=0;      //暂不用填						
			//推广员、导购员抽佣明细表
			$model_detail=Model('extension_commis_detail');
			$mb_commis_totals=$rebate;	//返佣总金额	
			$mb_commis_points=$rebate_points;//返佣总云币
			if ($saleman_info['mc_id']<10){//推广员
				//查询上级是否存在，存在一般是一个数组形式
				if (empty($saleman_info['parent_id']) || $saleman_info['parent_id']=='' || $saleman_info['parent_id']==0){
					$parent_list = array();
				}else{										
				    $parent_str = trim($saleman_info['parent_tree'],'|');
					$parent_list = explode('|',$parent_str);
				}					    	
				//店铺抽佣分成比率	
				$fields = 'mcr_id,rate_manage,rate_perfor,rate_level1,rate_level2,rate_level3,rate_level4,rate_level5,rate_level6,rate_level7,rate_level8';			
				$commis_rate=Model('extension_commis_rate')->field($fields)->where(array('store_id'=>$store_id))->find();
				//推广管理奖励表
				$manageaward_rate = Model('extension_manageaward')->getExtensionManageAwardListSortByTypeByStoreID($store_id);
				//推广门店业绩奖励表
				$perforaward_rate = Model('extension_perforaward')->getExtensionPerforAwardListSortByTypeByStoreID($store_id);

				$mb_commis=$mb_commis_totals;//返佣总金额
				$commis_points=$mb_commis_points;//返佣总云币
				if (!empty($commis_rate) && is_array($commis_rate)){ //抽佣分成比例是否存在
					//门店推广员id
					$perforaward_id = 0;
					//上级抽佣
					$level_correct=0;
					if (!empty($parent_list) && count($parent_list)<(C('gl_promotion_level')-1)){
						$level_correct = C('gl_promotion_level')-1-count($parent_list);
					}
					
					//启用云币后就没有三级分销分利润
					//$points_trade = C('points_trade');
					//if($points_trade==0 || empty($points_trade)){					
						foreach ($parent_list as $vk=>$saleman_id) {
							//查询出 上级推广员信息
							$parent_info = $this->getExtensionByMemberID($saleman_id,'member_id,member_name,mc_id,mc_real,store_id,parent_id,parent_tree');
							if (empty($parent_info) || !is_array($parent_info)) continue;
							//判断是否是门店推广员
							if ($parent_info['mc_real'] == 10){
								$perforaward_id = $parent_info['member_id'];
							}
							$rate=intval($commis_rate['rate_level'.($vk+$level_correct+1)]);
							if ($rate<=0 || $rate>100) continue;
						
							$commis_detail['saleman_id'] = $parent_info['member_id'];
							$commis_detail['saleman_name'] = $parent_info['member_name'];
							$commis_detail['saleman_parent'] = $parent_info['parent_tree'];	//上级推广员
							$commis_detail['saleman_type'] = 2;//$parent_info['mc_id'];	//表示推广员
						
							$commis_detail['commis_type'] = 0;	//表示推广分佣
							$commis_detail['commis_rate'] = $rate;	//分成比率
							$commis_detail['mb_commis_totals'] = $mb_commis_totals*$rate/100; //实际佣金
							$commis_detail['mb_commis_points'] = $mb_commis_points*$rate/100; //实际佣金云币
													
							$insert=$model_detail->insert($commis_detail);
							// 推广佣金发放成功发送买家消息
							if ($insert && ($commis_detail['mb_commis_totals']>0 || $commis_detail['mb_commis_points']>0)){
								$param = array();
								$param['code'] = 'verify_commission_grant_success';
								$param['member_id'] = $manage_info['member_id'];
								$memberInfo = Model('member')->getMemberInfoByID($parent_info['member_id'],'member_mobile,member_email');
								$param['number']['mobile'] = $memberInfo['member_mobile'];
								$param['number']['email'] = $memberInfo['member_email'];
								$param['param'] = array(
										'order_sn' => $order_sn,
										'safe_balance' => empty($commis_detail['mb_commis_totals'])?$commis_detail['mb_commis_points']:$commis_detail['mb_commis_totals']
								);
								QueueClient::push('sendMemberMsg', $param);
							}
							$mb_commis = $mb_commis - $commis_detail['mb_commis_totals'];	//剩余返佣金额
							$commis_points = $commis_points - $commis_detail['mb_commis_points'];	//剩余返佣云币
							
							if ($mb_commis<0){
								$mb_commis=0;
								break;
							}
							if ($commis_points<0){
								$commis_points=0;
								break;
							}
						}
					//}else{
					//	$mb_commis=0;
					//	$commis_points=0;
					//}
											
				    //管理奖抽佣
					$rate=intval($commis_rate['rate_manage']);
				    if ($rate>=0 && $rate<=100){
						$manageaward_totals = $mb_commis_totals*$rate/100;
						$manageaward_commis = $manageaward_totals;
						$manageaward_childs_rate = 0;
						$manageaward_points = $mb_commis_points*$rate/100;
						$manageaward_commis_points = $manageaward_points;
						//分配经理级管理奖
						$award_level = intval($manageaward_rate[2]['award_level']); //层级
						$award_rate = intval($manageaward_rate[2]['award_rate']); //分利
						
						if ($award_rate>=0 && $award_rate<=100){
						    if (!empty($saleman_info['manager_id']) && $saleman_info['manager_id']>0 && $saleman_info['mc_id']==2){//同级别 没有分利
								//如果自己管理级别为经理时，上级经理的分配将留给自己
						    	if($saleman_info['mc_id']==2){
						    		$manage_info = $saleman_info;
								}else{
							    	$manage_id = $saleman_info['manager_id'];
									$manage_info = $this->getExtensionByMemberID($manage_id,'member_id,member_name,mc_id,mc_level,mc_real,store_id,parent_id,parent_tree');
								}
						        if (!empty($manage_info) && is_array($manage_info) ){
						            //判断是否是门店推广员
						            if ($manage_info['mc_real'] == 10){
							            $perforaward_id = $manage_info['member_id'];
						            }
									if ($award_level ==0 || ($manage_info['mc_level']+$award_level)>=$saleman_info['mc_level']){
										$commis_detail['saleman_id'] = $manage_info['member_id'];
				                        $commis_detail['saleman_name'] = $manage_info['member_name'];
					                    $commis_detail['saleman_parent'] = $manage_info['parent_tree'];					
					                    $commis_detail['saleman_type'] = 3;//$parent_info['mc_id'];
					                    $commis_detail['commis_rate'] = $rate*$award_rate/100;							
					                    $commis_detail['mb_commis_totals'] = $manageaward_totals*$award_rate/100;
					                    $commis_detail['mb_commis_points'] = $manageaward_points*$award_rate/100;
						                $commis_detail['commis_type'] = 1;
						                
						                $insert=$model_detail->insert($commis_detail);
						                // 推广佣金发放成功发送买家消息
						                if ($insert&& ($commis_detail['mb_commis_totals']>0||$commis_detail['mb_commis_points']>0)){
						                	$param = array();
						                	$param['code'] = 'verify_commission_grant_success';
						                	$param['member_id'] = $manage_info['member_id'];
						                	$memberInfo = Model('member')->getMemberInfoByID($manage_info['member_id'],'member_mobile,member_email');
						                	$param['number']['mobile'] = $memberInfo['member_mobile'];
						                	$param['number']['email'] = $memberInfo['member_email'];
						                	$param['param'] = array(
						                			'order_sn' => $order_sn,
						                			'safe_balance' => empty($commis_detail['mb_commis_totals'])?$commis_detail['mb_commis_points']:$commis_detail['mb_commis_totals']
						                	);
						                	QueueClient::push('sendMemberMsg', $param);
						                }						                
							            $manageaward_childs_rate = $award_rate;
					                    $manageaward_commis = $manageaward_commis - $commis_detail['mb_commis_totals'];	
					                    if ($manageaward_commis<=0){
						                  $manageaward_commis=0;
					                    }
					                    $manageaward_commis_points = $manageaward_commis_points - $commis_detail['mb_commis_points'];
					                    if ($manageaward_commis_points<=0){
					                    	$manageaward_commis_points=0;
					                    }
									}
								}
						    }
						}
						//分配协理级管理奖
						$award_level = intval($manageaward_rate[3]['award_level']);
						$award_rate = intval($manageaward_rate[3]['award_rate']);
						if ($award_rate>=0 && $award_rate<=100){
						    if (!empty($saleman_info['coo_id']) && $saleman_info['coo_id']>0 && $saleman_info['mc_id']<=3){//同级别 没有分利
								//如果自己管理级别为经理时，上级经理的分配将留给自己
						    	if($saleman_info['mc_id']==3){
						    		$manage_info = $saleman_info;
								}else{
							    	$manage_id = $saleman_info['coo_id'];
									$manage_info = $this->getExtensionByMemberID($manage_id,'member_id,member_name,mc_id,mc_level,mc_real,store_id,parent_id,parent_tree');
						        }
								if (!empty($manage_info) && is_array($manage_info)){
						            //判断是否是门店推广员
						            if ($manage_info['mc_real'] == 10){
							            $perforaward_id = $manage_info['member_id'];
						            }
									if ($award_level ==0 || ($manage_info['mc_level']+$award_level)>=$saleman_info['mc_level']){
										$commis_detail['saleman_id'] = $manage_info['member_id'];
				                        $commis_detail['saleman_name'] = $manage_info['member_name'];
					                    $commis_detail['saleman_parent'] = $manage_info['parent_tree'];					
					                    $commis_detail['saleman_type'] = 3;//$parent_info['mc_id'];						
								
						                $commis_detail['commis_rate'] = $rate*($award_rate-$manageaward_childs_rate)/100;							
					                    $commis_detail['mb_commis_totals'] = $manageaward_totals*($award_rate-$manageaward_childs_rate)/100;
					                    $commis_detail['mb_commis_points'] = $manageaward_points*($award_rate-$manageaward_childs_rate)/100;
					                    $commis_detail['commis_type'] = 1;				    					    
						
						                $insert=$model_detail->insert($commis_detail);
						                // 推广佣金发放成功发送买家消息
						                if ($insert&& ($commis_detail['mb_commis_totals']>0||$commis_detail['mb_commis_points']>0)){
						                	$param = array();
						                	$param['code'] = 'verify_commission_grant_success';
						                	$param['member_id'] = $manage_info['member_id'];
						                	$memberInfo = Model('member')->getMemberInfoByID($manage_info['member_id'],'member_mobile,member_email');
						                	$param['number']['mobile'] = $memberInfo['member_mobile'];
						                	$param['number']['email'] = $memberInfo['member_email'];
						                	$param['param'] = array(
						                			'order_sn' => $order_sn,
						                			'safe_balance' => empty($commis_detail['mb_commis_totals'])?$commis_detail['mb_commis_points']:$commis_detail['mb_commis_totals']
						                	);
						                	QueueClient::push('sendMemberMsg', $param);
						                }						                
							            $manageaward_childs_rate = $award_rate;
					                    $manageaward_commis = $manageaward_commis - $commis_detail['mb_commis_totals'];	
					                    if ($manageaward_commis<=0){
						                  $manageaward_commis=0;
					                    }
					                    $manageaward_commis_points = $manageaward_commis_points - $commis_detail['mb_commis_points'];
					                    if ($manageaward_commis_points<=0){
					                    	$manageaward_commis_points=0;
					                    }
									}
								}
						    }
						}
						//分配首席级管理奖
						$award_level = intval($manageaward_rate[4]['award_level']);
						$award_rate = intval($manageaward_rate[4]['award_rate']);
						if ($award_rate>=0 && $award_rate<=100){
						    if (!empty($saleman_info['ceo_id']) && $saleman_info['ceo_id']>0 && $saleman_info['mc_id']<=4){//同级别 没有分利
								//如果自己管理级别为经理时，上级经理的分配将留给自己
						    	if($saleman_info['mc_id']==4){
						    		$manage_info = $saleman_info;
								}else{
							    	$manage_id = $saleman_info['ceo_id'];
									$manage_info = $this->getExtensionByMemberID($manage_id,'member_id,member_name,mc_id,mc_level,mc_real,store_id,parent_id,parent_tree');
						        }
						        if (!empty($manage_info) && is_array($manage_info)){
						            //判断是否是门店推广员
						            if ($manage_info['mc_real'] == 10){
							            $perforaward_id = $manage_info['member_id'];
						            }
									if ($award_level ==0 || ($manage_info['mc_level']+$award_level)>=$saleman_info['mc_level']){
										$commis_detail['saleman_id'] = $manage_info['member_id'];
				                        $commis_detail['saleman_name'] = $manage_info['member_name'];
					                    $commis_detail['saleman_parent'] = $manage_info['parent_tree'];					
					                    $commis_detail['saleman_type'] = 3;//$parent_info['mc_id'];							
						                $commis_detail['commis_rate'] = $rate*($award_rate-$manageaward_childs_rate)/100;							
					                    $commis_detail['mb_commis_totals'] = $manageaward_totals*($award_rate-$manageaward_childs_rate)/100;
					                    $commis_detail['mb_commis_points'] = $manageaward_points*($award_rate-$manageaward_childs_rate)/100;
					                    $commis_detail['commis_type'] = 1;				    					    
						
						                $insert=$model_detail->insert($commis_detail);
						                // 推广佣金发放成功发送买家消息
						                if ($insert&& ($commis_detail['mb_commis_totals']>0||$commis_detail['mb_commis_totals']>0)){
						                	$param = array();
						                	$param['code'] = 'verify_commission_grant_success';
						                	$param['member_id'] = $manage_info['member_id'];
						                	$memberInfo = Model('member')->getMemberInfoByID($manage_info['member_id'],'member_mobile,member_email');
						                	$param['number']['mobile'] = $memberInfo['member_mobile'];
						                	$param['number']['email'] = $memberInfo['member_email'];
						                	$param['param'] = array(
						                			'order_sn' => $order_sn,
						                			'safe_balance' => empty($commis_detail['mb_commis_totals'])?$commis_detail['mb_commis_points']:$commis_detail['mb_commis_totals']
						                	);
						                	QueueClient::push('sendMemberMsg', $param);
						                }						                
							            $manageaward_childs_rate = $award_rate;
					                    $manageaward_commis = $manageaward_commis - $commis_detail['mb_commis_totals'];	
					                    if ($manageaward_commis<=0){
						                  $manageaward_commis=0;
					                    }
					                    $manageaward_commis_points = $manageaward_commis_points - $commis_detail['mb_commis_points'];
					                    if ($manageaward_commis_points<=0){
					                    	$manageaward_commis_points=0;
					                    }
									}
								}
						    }
						}
						//分配股东级管理奖
						$award_level = intval($manageaward_rate[5]['award_level']);
						$award_rate = intval($manageaward_rate[5]['award_rate']);
						if ($award_rate>=0 && $award_rate<=100){
						    if (!empty($saleman_info['holder_id']) && $saleman_info['holder_id']>0&& $saleman_info['mc_id']<=5){//同级别 没有分利
								//如果自己管理级别为经理时，上级经理的分配将留给自己
						    	if($saleman_info['mc_id']==5){
						    		$manage_info = $saleman_info;
								}else{
							    	$manage_id = $saleman_info['holder_id'];
									$manage_info = $this->getExtensionByMemberID($manage_id,'member_id,member_name,mc_id,mc_level,mc_real,store_id,parent_id,parent_tree');
						        }
								if (!empty($manage_info) && is_array($manage_info)){
						            //判断是否是门店推广员
						            if ($manage_info['mc_real'] == 10){
							            $perforaward_id = $manage_info['member_id'];
						            }
									if ($award_level ==0 || ($manage_info['mc_level']+$award_level)>=$saleman_info['mc_level']){
										$commis_detail['saleman_id'] = $manage_info['member_id'];
				                        $commis_detail['saleman_name'] = $manage_info['member_name'];
					                    $commis_detail['saleman_parent'] = $manage_info['parent_tree'];					
					                    $commis_detail['saleman_type'] = 3;//$parent_info['mc_id'];						
								
						                $commis_detail['commis_rate'] = $rate*($award_rate-$manageaward_childs_rate)/100;							
					                    $commis_detail['mb_commis_totals'] = $manageaward_totals*($award_rate-$manageaward_childs_rate)/100;
						
						                $commis_detail['commis_type'] = 1;				    					    
						
						                $insert=$model_detail->insert($commis_detail);
						                // 推广佣金发放成功发送买家消息
						                if ($insert &&$commis_detail['mb_commis_totals']>0){
						                	$param = array();
						                	$param['code'] = 'verify_commission_grant_success';
						                	$param['member_id'] = $manage_info['member_id'];
						                	$memberInfo = Model('member')->getMemberInfoByID($manage_info['member_id'],'member_mobile,member_email');
						                	$param['number']['mobile'] = $memberInfo['member_mobile'];
						                	$param['number']['email'] = $memberInfo['member_email'];
						                	$param['param'] = array(
						                			'order_sn' => $order_sn,
						                			'safe_balance' => $commis_detail['mb_commis_totals']
						                	);
						                	QueueClient::push('sendMemberMsg', $param);
						                }
					                    $manageaward_commis = $manageaward_commis - $commis_detail['mb_commis_totals'];	
					                    if ($manageaward_commis<=0){
						                  $manageaward_commis=0;
					                    }
									}
								}
						    }
						}
						//未分配完的管理奖充公
						if ($manageaward_commis>0||$manageaward_commis_points>0){
						    $award_rate = $manageaward_commis/$mb_commis_totals*100;						
						    $commis_detail['saleman_id'] = 0;
				            $commis_detail['saleman_name'] = '管理奖';
					        $commis_detail['saleman_parent'] = 0;					
					        $commis_detail['saleman_type'] = 3;	
							
						    $commis_detail['commis_rate'] = $award_rate;							
					        $commis_detail['mb_commis_totals'] = $manageaward_commis;
					        $commis_detail['mb_commis_points'] = $manageaward_commis_points;
						    $commis_detail['commis_type'] = 1;							    					    
						
						    $insert=$model_detail->insert($commis_detail);				        
						}
						$mb_commis = $mb_commis - $manageaward_totals;	
					    if ($mb_commis<=0){
						    $mb_commis=0;
						}
						$commis_points = $commis_points - $manageaward_commis_points;
						if ($commis_points<=0){
							$commis_points=0;
						}
				    }
				    //绩优奖抽佣
				    $rate=intval($commis_rate['rate_perfor']);
				    if ($rate>=0 && $rate<=100){
						$perforaward_totals = $mb_commis_totals*$rate/100;
						$perforaward_commis = $perforaward_totals;
						$perforaward_points = $mb_commis_points*$rate/100;
						$perforaward_commis_points = $perforaward_points;
						//如果存在门店推广员则分配门店补贴
						if ($perforaward_id>0){
							$perfor_info = $this->getExtensionByMemberID($perforaward_id,'member_id,member_name,mc_id,mc_level,mc_real,store_id,parent_id,parent_tree');
							if (!empty($perfor_info) && is_array($perfor_info)){
								$award_level = intval($perforaward_rate[$perfor_info['mc_id']]['award_level']);
						        $award_rate = intval($perforaward_rate[$perfor_info['mc_id']]['award_rate']);
								if ($award_rate>=0 && $award_rate<=100){
									if ($award_level ==0 || ($perfor_info['mc_level']+$award_level)>=$saleman_info['mc_level']){
										$commis_detail['saleman_id'] = $perfor_info['member_id'];
				                        $commis_detail['saleman_name'] = $perfor_info['member_name'];
					                    $commis_detail['saleman_parent'] = $perfor_info['parent_tree'];					
					                    $commis_detail['saleman_type'] = 4;//$parent_info['mc_id'];						
								
						                $commis_detail['commis_rate'] = $rate*$award_rate/100;							
					                    $commis_detail['mb_commis_totals'] = $perforaward_totals*$award_rate/100;
					                    $commis_detail['mb_commis_points'] = $perforaward_points*$award_rate/100;
					                    $commis_detail['commis_type'] = 1;				    					    
						
						                $insert=$model_detail->insert($commis_detail);
						                // 推广佣金发放成功发送买家消息
						                if ($insert&& ($commis_detail['mb_commis_totals']>0||$commis_detail['mb_commis_points']>0)){
						                	$param = array();
						                	$param['code'] = 'verify_commission_grant_success';
						                	$param['member_id'] = $perfor_info['member_id'];
						                	$memberInfo = Model('member')->getMemberInfoByID($perfor_info['member_id'],'member_mobile,member_email');
						                	$param['number']['mobile'] = $memberInfo['member_mobile'];
						                	$param['number']['email'] = $memberInfo['member_email'];
						                	$param['param'] = array(
						                			'order_sn' => $order_sn,
						                			'safe_balance' => empty($commis_detail['mb_commis_totals'])?$commis_detail['mb_commis_points']:$commis_detail['mb_commis_totals']
						                	);
						                	QueueClient::push('sendMemberMsg', $param);
						                }
					                    $perforaward_commis = $perforaward_commis - $commis_detail['mb_commis_totals'];	
					                    if ($perforaward_commis<=0){
						                  $perforaward_commis=0;
					                    }
					                    $perforaward_commis_points = $perforaward_commis_points - $commis_detail['mb_commis_points'];
					                    if ($perforaward_commis_points<=0){
					                    	$perforaward_commis_points=0;
					                    }
									}
								}
							}
						}
						//未分配完的门店补贴奖充公
						if ($perforaward_commis>0||$perforaward_commis_points>0){
							$award_rate = $perforaward_commis/$mb_commis_totals*100;	
						    $commis_detail['saleman_id'] = 0;
				            $commis_detail['saleman_name'] = '绩优奖';
					        $commis_detail['saleman_parent'] = 0;					
					        $commis_detail['saleman_type'] = 4;	
							
						    $commis_detail['commis_rate'] = $award_rate;							
					        $commis_detail['mb_commis_totals'] = $perforaward_commis;
					        $commis_detail['mb_commis_points'] = $perforaward_commis_points;
						    $commis_detail['commis_type'] = 1;						    					    
						
						    $insert=$model_detail->insert($commis_detail);					        
						}
						$mb_commis = $mb_commis - $perforaward_totals;	
					    if ($mb_commis<=0){
						    $mb_commis=0;
						}
						$commis_points = $commis_points - $perforaward_points;
						if ($commis_points<=0){
							$commis_points=0;
						}
					}
				}
			    //推广者本人抽佣
			    $rate = $mb_commis/$mb_commis_totals*100;
			    $commis_detail['saleman_id'] = $saleman_info['member_id'];
				$commis_detail['saleman_name'] = $saleman_info['member_name'];	
				$commis_detail['saleman_parent']= $saleman_info['parent_tree'];
				$commis_detail['saleman_type']= 2;//$saleman_info['mc_id'];
			    $commis_detail['commis_rate']=$rate;				
				$commis_detail['mb_commis_totals']=$mb_commis;
				$commis_detail['mb_commis_points']=$commis_points;
				$commis_detail['commis_type'] = 0;
					
			    $model_detail->insert($commis_detail);
				
				$order_commission_balance = $mb_commis;
			}else{  //导购员
			    $commis_detail['saleman_id'] = $saleman_info['member_id'];
				$commis_detail['saleman_name'] = $saleman_info['member_name'];
				$commis_detail['saleman_parent'] = $saleman_info['parent_tree'];
				$commis_detail['saleman_type'] = 1;//$saleman_info['mc_id'];
						
				$commis_detail['commis_rate'] = 100;
			    $commis_detail['mb_commis_totals'] = $mb_commis_totals;
			    $commis_detail['mb_commis_points'] = $mb_commis_points;
				$commis_detail['commis_type'] = 0;
					
			    $insert=$model_detail->insert($commis_detail);
				
				$order_commission_balance = $mb_commis_totals;
			}				
		}
		// 推广佣金发放成功发送买家消息
		if ($order_commission_balance>0){			
            $param = array();
            $param['code'] = 'verify_commission_grant_success';
            $param['member_id'] = $saleman_info['member_id'];
            $memberInfo = Model('member')->getMemberInfoByID($saleman_info['member_id'],'member_mobile,member_email');
            $param['number']['mobile'] = $memberInfo['member_mobile'];
            $param['number']['email'] = $memberInfo['member_email'];
            $param['param'] = array(
                'order_sn' => $order_sn,
                'safe_balance' => $order_commission_balance
            );
            QueueClient::push('sendMemberMsg', $param);	
		}		
	}
	
	/**
	 * 推广员 邀请人第一次购买时 所有的管理者返佣都归上一级所有
	 * @param $member_id 购买者ID
	 * @param $parent_id 上一级推广员ID
	 * @param $rebate 返佣金额
	 * @param $order_id 订单id
	 * @param $order_sn 订单号
	 * @param $order_amount 订单总金额
	 * @param $store_id 订单所属店铺ID
	 * @param $store_name 订单所属店铺名称
	 * @param $rebate_points 返佣云币
	 */
	public function firstDistributeRebate($member_id = '', $rebate = 0, $order_id = '', $order_sn = '', $order_amount = 0, $store_id = 0, $store_name = '',$rebate_points = 0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
	
		//本次发放的推广佣金金额，用于发送短信
		$order_commission_balance = 0;
		//购买者信息
		$saleman_info=$this->getExtensionByMemberID($member_id,'member_id,member_name,mc_id,mc_level,mc_real,store_id,parent_id,parent_tree,holder_id,ceo_id,coo_id,manager_id');
		if (!empty($saleman_info) && is_array($saleman_info)){
			//佣金明细
			$commis_detail=array();
			$commis_detail['store_id'] = $store_id;
			$commis_detail['store_name'] = $store_name;
			//saleman_id
			//saleman_name
			//saleman_parent
			//saleman_type
			$commis_detail['extension_id'] = $saleman_info['member_id'];
			$commis_detail['extension_name'] = $saleman_info['member_name'];
			$commis_detail['mc_level'] = $saleman_info['mc_level'];
			$commis_detail['holder_id'] = $saleman_info['holder_id']; //股东ID
			$commis_detail['ceo_id'] = $saleman_info['ceo_id'];	//首席ID
			$commis_detail['coo_id'] = $saleman_info['coo_id']; //协理ID
			$commis_detail['manager_id'] = $saleman_info['manager_id']; //经理ID
			$commis_detail['order_id'] = $order_id;	//订单ID
			$commis_detail['order_sn'] = $order_sn;	//订单号
			$commis_detail['goods_amount']= $order_amount; //order_amount:订单总价 goods_amount:实际付款 shipping_fee:优惠额
			$commis_detail['commis_amount'] = $rebate;	//返佣总金额
			//commis_rate
			//mb_commis_totals
			$commis_detail['add_time']=time();
			$commis_detail['add_date']=date('Ymd',time());
			$commis_detail['give_status']=0;    //未结算
			$commis_detail['give_time']=0;      //暂不用填
			//推广员、导购员抽佣明细表
			$model_detail=Model('extension_commis_detail');
			$mb_commis_totals=$rebate;	//返佣总金额
			$mb_commis_points = $rebate_points;
			if ($saleman_info['mc_id']<10){//推广员
				//查询上级是否存在，存在一般是一个数组形式
				if (empty($saleman_info['parent_id']) || $saleman_info['parent_id']=='' || $saleman_info['parent_id']==0){
					$parent_list = array();
				}else{
					$parent_str = trim($saleman_info['parent_tree'],'|');
					$parent_list = explode('|',$parent_str);
				}
				//店铺抽佣分成比率
				$fields = 'mcr_id,rate_manage,rate_perfor,rate_level1,rate_level2,rate_level3,rate_level4,rate_level5,rate_level6,rate_level7,rate_level8';
				$commis_rate=Model('extension_commis_rate')->field($fields)->where(array('store_id'=>$store_id))->find();
				//推广管理奖励表
				$manageaward_rate = Model('extension_manageaward')->getExtensionManageAwardListSortByTypeByStoreID($store_id);
				
				$mb_commis=$mb_commis_totals;//返佣总金额
				$commis_points = $mb_commis_points;//返佣云币
				if (!empty($commis_rate) && is_array($commis_rate)){ //抽佣分成比例是否存在
					//门店推广员id
					$perforaward_id = 0;
					//上级抽佣
					$level_correct=0;
					if (!empty($parent_list) && count($parent_list)<(C('gl_promotion_level')-1)){
						$level_correct = C('gl_promotion_level')-1-count($parent_list);
					}
					
					$rate_tol = 0;
					foreach ($parent_list as $vk=>$saleman_id) {
						//查询出 上级推广员信息
						$parent_info = $this->getExtensionByMemberID($saleman_id,'member_id,member_name,mc_id,mc_real,store_id,parent_id,parent_tree');
						if (empty($parent_info) || !is_array($parent_info)) continue;
						//判断是否是门店推广员
						if ($parent_info['mc_real'] == 10){
							$perforaward_id = $parent_info['member_id'];
						}
						$rate=intval($commis_rate['rate_level'.($vk+$level_correct+1)]);
						if ($rate<=0 || $rate>100) continue;
						$rate_tol = $rate_tol+$rate;
					}
					//所有推广员佣金分成补贴奖充公
					$commis_detail['saleman_id'] = 0;
					$commis_detail['saleman_name'] = '绩优奖';
					$commis_detail['saleman_parent'] = 0;
					$commis_detail['saleman_type'] = 4;
					
					$commis_detail['commis_type'] = 1;	//表示推广佣金
					$commis_detail['commis_rate'] = $rate_tol;	//分成比率
					$commis_detail['mb_commis_totals'] = $mb_commis_totals*$rate_tol/100;
					$commis_detail['mb_commis_points'] = $mb_commis_points*$rate_tol/100;
					$insert=$model_detail->insert($commis_detail);
					$mb_commis = $mb_commis - $commis_detail['mb_commis_totals'];//剩余返佣金额
					$commis_points = $commis_points - $commis_detail['mb_commis_points'];//剩余返佣云币
					
					//所有 管理奖的抽佣 均分给上一级即可
					$rate=intval($commis_rate['rate_manage']);
					if ($rate>=0 && $rate<=100){
						$manageaward_totals = $mb_commis_totals*$rate/100;
						$manageaward_commis = $manageaward_totals;
						$manageaward_points = $mb_commis_points*$rate/100;
						$manageaward_commis_points = $manageaward_points;
						$manageaward_childs_rate = 0;
						//分配经理级管理奖
						$award_level = 1;
						$award_rate = 100;
						
						if ($award_rate>=0 && $award_rate<=100){
							
							$parent_id = $saleman_info['parent_id'];
							if (!empty($parent_id) && $parent_id>0){
								$parent_info = $this->getExtensionByMemberID($parent_id,'member_id,member_name,mc_id,mc_level,mc_real,store_id,parent_id,parent_tree');
								if (!empty($parent_info) && is_array($parent_info)){
									
									if ($award_level ==0 || ($parent_info['mc_level']+$award_level)>=$parent_info['mc_level']){
										$commis_detail['saleman_id'] = $parent_info['member_id'];
										$commis_detail['saleman_name'] = $parent_info['member_name'];
										$commis_detail['saleman_parent'] = $parent_info['parent_tree'];
										$commis_detail['saleman_type'] = 3;//$parent_info['mc_id'];
						
										$commis_detail['commis_rate'] = $rate*$award_rate/100;
										$commis_detail['mb_commis_totals'] = $manageaward_totals*$award_rate/100;
										$commis_detail['mb_commis_points'] = $manageaward_points*$award_rate/100;
										$commis_detail['commis_type'] = 1;
										$insert=$model_detail->insert($commis_detail);
										// 推广佣金发放成功发送买家消息
										if ($insert&&($commis_detail['mb_commis_totals']>0||$commis_detail['mb_commis_points']>0)){
											$param = array();
											$param['code'] = 'verify_commission_grant_success';
											$param['member_id'] = $parent_info['member_id'];
											$memberInfo = Model('member')->getMemberInfoByID($parent_info['member_id'],'member_mobile,member_email');
											$param['number']['mobile'] = $memberInfo['member_mobile'];
											$param['number']['email'] = $memberInfo['member_email'];
											$param['param'] = array(
													'order_sn' => $order_sn,
													'safe_balance' => empty($commis_detail['mb_commis_totals'])?$commis_detail['mb_commis_points']:$commis_detail['mb_commis_totals']
											);
											QueueClient::push('sendMemberMsg', $param);
										}
						
										$manageaward_childs_rate = $award_rate;
										$manageaward_commis = $manageaward_commis - $commis_detail['mb_commis_totals'];
										if ($manageaward_commis<=0){
											$manageaward_commis=0;
										}
										$manageaward_commis_points = $manageaward_commis_points - $commis_detail['mb_commis_points'];
										if ($manageaward_commis_points<=0){
											$manageaward_commis_points=0;
										}
									}
								}
							}
						}
						//未分配完的管理奖充公
						if ($manageaward_commis>0||$manageaward_commis_points>0){
							$award_rate = $manageaward_commis/$mb_commis_totals*100;
							$commis_detail['saleman_id'] = 0;
							$commis_detail['saleman_name'] = '管理奖';
							$commis_detail['saleman_parent'] = 0;
							$commis_detail['saleman_type'] = 3;
								
							$commis_detail['commis_rate'] = $award_rate;
							$commis_detail['mb_commis_totals'] = $manageaward_commis;
							$commis_detail['mb_commis_points'] = $manageaward_commis_points;
							$commis_detail['commis_type'] = 1;
						
							$insert=$model_detail->insert($commis_detail);
						}
						$mb_commis = $mb_commis - $manageaward_totals;
						if ($mb_commis<=0){
							$mb_commis=0;
						}
						$commis_points = $commis_points - $manageaward_points;
						if ($commis_points<=0){
							$commis_points=0;
						}
					}
				}
			}
			
		}
		
	}
	
	/**
	 * 直接返佣给上级邀请人 忽略其他将来机制
	 * @param $member_id 购买者ID
	 * @param $parent_id 上一级推广员ID
	 * @param $rebate 返佣金额
	 * @param $order_id 订单id
	 * @param $order_sn 订单号
	 * @param $order_amount 订单总金额
	 * @param $store_id 订单所属店铺ID
	 * @param $store_name 订单所属店铺名称
	 * @param $rebate_points 返佣云币
	 */
	public function parentDistributeRebate($member_id = '', $parent_id = 0, $rebate = 0, $order_id = '', $order_sn = '', $order_amount = 0, $store_id = 0, $store_name = '',$rebate_points=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
	
		//本次发放的推广佣金金额，用于发送短信
		$order_commission_balance = 0;
		//购买者信息
		$saleman_info=$this->getExtensionByMemberID($member_id,'member_id,member_name,mc_id,mc_level,mc_real,store_id,parent_id,parent_tree,holder_id,ceo_id,coo_id,manager_id');
		if (!empty($saleman_info) && is_array($saleman_info)){
			//佣金明细
			$commis_detail=array();
			$commis_detail['store_id'] = $store_id;
			$commis_detail['store_name'] = $store_name;
			//saleman_id
			//saleman_name
			//saleman_parent
			//saleman_type
			$commis_detail['extension_id'] = $saleman_info['member_id'];
			$commis_detail['extension_name'] = $saleman_info['member_name'];
			$commis_detail['mc_level'] = $saleman_info['mc_level'];
			$commis_detail['holder_id'] = $saleman_info['holder_id']; //股东ID
			$commis_detail['ceo_id'] = $saleman_info['ceo_id'];	//首席ID
			$commis_detail['coo_id'] = $saleman_info['coo_id']; //协理ID
			$commis_detail['manager_id'] = $saleman_info['manager_id']; //经理ID
			$commis_detail['order_id'] = $order_id;	//订单ID
			$commis_detail['order_sn'] = $order_sn;	//订单号
			$commis_detail['goods_amount']= $order_amount; //order_amount:订单总价 goods_amount:实际付款 shipping_fee:优惠额
			$commis_detail['commis_amount'] = $rebate;	//返佣总金额
			//commis_rate
			//mb_commis_totals
			$commis_detail['add_time']=time();
			$commis_detail['add_date']=date('Ymd',time());
			$commis_detail['give_status']=0;    //未结算
			$commis_detail['give_time']=0;      //暂不用填
			//推广员、导购员抽佣明细表
			$model_detail=Model('extension_commis_detail');
			$mb_commis_totals=$rebate;	//返佣总金额
			$mb_commis_points = $rebate_points;//返佣积分
			if(empty($parent_id)){
				//推广员佣金
				$commis_detail['saleman_id'] = 0;
				$commis_detail['saleman_name'] = '邀请返佣';
				$commis_detail['saleman_parent'] = 0;
				$commis_detail['saleman_type'] = 2;		
				$commis_detail['commis_type'] = 1;	//表示推广佣金
				$commis_detail['commis_rate'] = 100;	//分成比率
				$commis_detail['mb_commis_totals'] = $mb_commis_totals;
				$commis_detail['mb_commis_points'] = $mb_commis_points;
				$insert=$model_detail->insert($commis_detail);
			}else{
				//查询出 上级推广员信息
				$parent_info = $this->getExtensionByMemberID($parent_id,'member_id,member_name,mc_id,mc_real,store_id,parent_id,parent_tree');
				$commis_detail['saleman_id'] = $parent_info['member_id'];
				$commis_detail['saleman_name'] = $parent_info['member_name'];
				$commis_detail['saleman_parent'] = $parent_info['parent_tree'];
				$commis_detail['saleman_type'] = 2;
				
				$commis_detail['commis_rate'] = 100;
				$commis_detail['mb_commis_totals'] = $mb_commis_totals;
				$commis_detail['mb_commis_points'] = $mb_commis_points;
				$commis_detail['commis_type'] = 1;
				$insert=$model_detail->insert($commis_detail);
				// 推广佣金发放成功发送买家消息
				if ($insert && ($mb_commis_totals > 0||$mb_commis_points>0)){
					$param = array();
					$param['code'] = 'verify_commission_grant_success';
					$param['member_id'] = $parent_info['member_id'];
					$memberInfo = Model('member')->getMemberInfoByID($parent_info['member_id'],'member_mobile,member_email');
					$param['number']['mobile'] = $memberInfo['member_mobile'];
					$param['number']['email'] = $memberInfo['member_email'];
					$param['param'] = array(
							'order_sn' => $order_sn,
							'safe_balance' => empty($mb_commis_totals)?$mb_commis_points:$mb_commis_totals
					);
					QueueClient::push('sendMemberMsg', $param);
				}
			}
				
		}
	
	}
	
	
	/**
     * 退货退款取消返佣
	 * @param $order_id 订单id
	 * @param $order_sn 订单号
     */
    public function RemoveRebate($order_id = '',$order_sn = '') {
		$model_detail=Model('extension_commis_detail');

		$where = array();
		$where['order_id'] = $order_id;
		$where['order_sn'] = $order_sn;
		$model_detail->delCommisdetail($where);
		
		// 养老金取消成功发送买家消息
		/*
		if ($order_safe_balance>0){			
            $param = array();
            $param['code'] = 'verify_commission_cancel_success';
            $param['member_id'] = $buyer_id;
            $param['param'] = array(
                'order_sn' => $order_sn,
                'safe_balance' => $order_safe_balance
            );
            QueueClient::push('sendMemberMsg', $param);	
		}
		*/
	}
	
	/**
	 * 会员列表
	 * @param array $condition
	 * @param string $field
	 * @param number $page
	 * @param string $order
	 */
	public function getMemberList($store_id = 0, $page = 0, $order = 'member_id asc') {
		$fields = 'extension.member_id,extension.member_name,extension.mc_id,extension.parent_tree,member.member_truename,member.member_sex,member.member_qq,member.member_mobile';
		$on = 'extension.member_id = member.member_id';
		$condition = array();
		$condition['extension.store_id'] = $store_id;
		//$condition['extension.parent_tree'] = array('like', '%|' . $store_id . '|%');
		$saleman_list = Model()->table('extension,member')->field($fields)->where($condition)->join('left')->on($on)->page($page)->order($order)->select();
		 
		if (!empty($saleman_list) && is_array($saleman_list)) {
			$model_commis_detail = Model('extension_commis_detail');
			foreach($saleman_list as $k => $info) {
				//总业绩和提成
				$commis_totals = $model_commis_detail->getStatisticsCommis($store_id,$info['member_id'],array('saleman_type'=>1));
				$saleman_list[$k]['total_sales'] = $commis_totals['amounts']?$commis_totals['amounts']:0;
				$saleman_list[$k]['total_commis'] = $commis_totals['commis']?$commis_totals['commis']:0;
				//本期业绩和提成
				$curr_totals = $model_commis_detail->getStatisticsCommis($store_id,$info['member_id'],array('saleman_type'=>1,'give_status'=>0));
				$saleman_list[$k]['curr_sales'] = $curr_totals['amounts']?$curr_totals['amounts']:0;
				$saleman_list[$k]['curr_commis'] = $curr_totals['commis']?$curr_totals['commis']:0;
			}
		}
		return $saleman_list;
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
		$extension_info = $this->getExtensionByMemberID($member_id);
		if ($member_id==0 || empty($extension_info) || $extension_info['mc_id']>=5){
			return;
		}
		//更新推广员升级信息
		$where = array();
		$where['member_id'] = $extension_info['member_id'];
	
		$new_info = array();
		$new_info['mc_id'] = 5;
	
		$this->editExtension($new_info,$where);
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
			$this->editExtension($child_info,$where);
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
		$extension_info = $this->getExtensionByMemberID($member_id);
		if ($member_id==0 || empty($extension_info) || $extension_info['mc_id']>=4){
			return;
		}
		//更新推广员升级信息
		$where = array();
		$where['member_id'] = $extension_info['member_id'];
	
		$new_info = array();
		$new_info['mc_id'] = 4;
	
		$this->editExtension($new_info,$where);
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
			$this->editExtension($child_info,$where);
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
		$extension_info = $this->getExtensionByMemberID($member_id);
		if ($member_id==0 || empty($extension_info) || $extension_info['mc_id']>=3){
			return;
		}
		//更新推广员升级信息
		$where = array();
		$where['member_id'] = $extension_info['member_id'];
	
		$new_info = array();
		$new_info['mc_id'] = 3;
	
		$this->editExtension($new_info,$where);
		//更新推广员下级信息
		$child_info = array();
		$child_info['coo_id'] = $extension_info['member_id'];
		if ($extension_info['mc_id']==2){
			$child_info['manager_id'] = 0;
			//高管升级
			$where = array();
			$where['manager_id'] = $extension_info['member_id'];
				
			$this->editExtension($child_info,$where);
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
		$extension_info = $this->getExtensionByMemberID($member_id);
		if ($member_id==0 || empty($extension_info) || $extension_info['mc_id']>=2){
			return;
		}
		//更新推广员升级信息
		$where = array();
		$where['member_id'] = $extension_info['member_id'];
	
		$new_info = array();
		$new_info['mc_id'] = 2;
	
		$this->editExtension($new_info,$where);
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
		$where = array();
		$where['parent_id']=$parent_id;
		$this->editExtension($data,$where);
		$child_list = $this->getExtensionList($where,'member_id');
		if (!empty($child_list)){
			foreach($child_list as $k => $info) {
				$this->extension_upgrade_fans($data,$info['member_id']);
			}
		}
	
	}
	
}
