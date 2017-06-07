<?php
/**
 * 推广员、导购员抽佣明细表
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

class extension_commis_detailModel extends Model {
    public function __construct() {
        parent::__construct('extension_commis_detail');
    }
	
	/**
	 * 读取多行
	 *
	 * @param 
	 * @return array 数组格式的返回结果
	 */
	public function getCommisdetailList($condition = array(),$page = 0, $field = '*', $order = 'mcd_id desc'){
        return $this->field($field)->where($condition)->page($page)->order($order)->select();
	}
	
	/**
	 * 读取佣金店铺列表(主要针对导购员，用于提佣申请)
	 *
	 * @param 
	 * @return array 数组格式的返回结果
	 */
	public function getCommisStoreIDList($condition = array(),$page = 0, $field = 'distinct store_id, store_name', $order = 'mcd_id desc'){
        return $this->field($field)->where($condition)->page($page)->order($order)->select();
	}
	/**
	 * 读取单行信息
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getCommisdetailInfo($store_id,$condition = array()) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		if (isset($store_id)){
		  $condition['store_id']=$store_id;
		}else{
		  $condition['store_id']=DEFAULT_PLATFORM_STORE_ID;
		}
		return $this->where($condition)->find();
	}
	
	/**
     * 取得收益数量
     * @param unknown $condition
     */
    public function getOrdersCount($condition) {
        return $this->where($condition)->count();
    }
	
	/** 
	 * 统计导购/推广员渠道推广订单数
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getGroupsOrdes($store_id,$member_id,$condition = array()) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		if (isset($store_id)){
		  $condition['store_id']=$store_id;
		};
		if (isset($member_id)){
		  $condition['saleman_id']=$member_id;
		};		
		$condition['saleman_type']=2;
		$condition['commis_type']=0;
		
		return $this->getOrdersCount($condition);
	}
	
	/** 
	 * 统计导购/推广员个人推广订单数
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPersonalOrdes($store_id,$member_id,$condition = array()) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		if (isset($store_id)){
		  $condition['store_id']=$store_id;
		};
		if (isset($member_id)){
		  $condition['extension_id']=$member_id;
		  $condition['saleman_id']=$member_id;
		};		
		return $this->getOrdersCount($condition);
	}		
	
	/** 推广处理
	 * 统计导购/推广员渠道总营业额和总提成
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getStatisticsCommis($store_id,$member_id,$condition = array()) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		if (isset($store_id)){
		  $condition['store_id']=$store_id;
		};
		if (isset($member_id)){
		  $condition['saleman_id']=$member_id;
		};
		$condition['saleman_type']=2;
		$condition['commis_type']=0;
		$statistics_info = $this->field('sum(goods_amount) as amounts, sum(mb_commis_totals) as commis')->where($condition)->find();
		$re_statistics = array();
		$re_statistics['amounts'] = ($statistics_info['amounts']>0)?$statistics_info['amounts']:0;
		$re_statistics['commis'] = ($statistics_info['commis']>0)?$statistics_info['commis']:0;
		return $re_statistics;
	}
	
	/** 推广处理
	 * 统计导购/推广员直推总营业额和总提成
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPersonalCommis($store_id,$member_id,$condition = array()) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		if (isset($store_id)){
		  $condition['store_id']=$store_id;
		};
		if (isset($member_id)){
		  $condition['extension_id']=$member_id;
		  $condition['saleman_id']=$member_id;
		};
		$condition['saleman_type']=2;
		$condition['commis_type']=0;
		$statistics_info = $this->field('sum(goods_amount) as amounts, sum(mb_commis_totals) as commis')->where($condition)->find();
		$re_statistics = array();
		$re_statistics['amounts'] = ($statistics_info['amounts']>0)?$statistics_info['amounts']:0;
		$re_statistics['commis'] = ($statistics_info['commis']>0)?$statistics_info['commis']:0;
		return $re_statistics;
	}
	
	/**
     * 删除抽佣返利明细
     * @param unknown $condition
     */
	public function delCommisdetail($condition = array()) {
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
	
	//-----------------------------------------------------------门店租金 高管奖励及升级处理 ----------------------------------------------------------------------//
	/** 
	 * 统计管理层销售额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAmount($condition = array()) {
	    return $this->where($condition)->sum('goods_amount');
	}
	
	/** 
	 * 统计经理层销售额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAmount_manager($store_id=0,$member_id=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
			$condition['manager_id']=$member_id;  	  
		}
		$condition['saleman_type']=4;
		$condition['give_status']=0;
			
		return $this->getManagerSalesAmount($condition);
	}
	
	/** 
	 * 统计协理层销售额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAmount_coo($store_id=0,$member_id=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
			$condition['coo_id']=$member_id;	  	  
		}
		$condition['saleman_type']=4;
		$condition['give_status']=0;
			
		return $this->getManagerSalesAmount($condition);
	}
	
	/** 
	 * 统计首席层销售额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAmount_ceo($store_id=0,$member_id=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
			$condition['ceo_id']=$member_id; 	  
		}
		$condition['saleman_type']=4;
		$condition['give_status']=0;
			
		return $this->getManagerSalesAmount($condition);
	}
	
	/** 
	 * 统计股东层销售额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAmount_holder($store_id=0,$member_id=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
			$condition['holder_id']=$member_id;	  	  
		}
		$condition['saleman_type']=4;
		$condition['give_status']=0;
			
		return $this->getManagerSalesAmount($condition);
	}
	
	
	/** 
	 * 统计门店租金补贴总额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPerforAward_total($condition = array()) {
	    return $this->where($condition)->sum('mb_commis_totals');
	}
	
	/** 
	 * 统计经理级门店租金补贴总额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPerforAward_manager($store_id=0,$member_id=0,$max_level=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['manager_id']=$member_id;		  
		}
		if ($max_level>0){
		    $condition['mc_level']=array('elt',$max_level);		  
		}
		$condition['saleman_type']=4;
		$condition['give_status']=0;
			
		return $this->getPerforAward_total($condition);
	}
	
	/** 
	 * 统计协理级门店租金补贴总额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPerforAward_coo($store_id=0,$member_id=0,$max_level=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['coo_id']=$member_id;		  
		}
		if ($max_level>0){
		    $condition['mc_level']=array('elt',$max_level);		  
		}
		$condition['saleman_type']=4;
		$condition['give_status']=0;
			
		return $this->getPerforAward_total($condition);
	}
	
	/** 
	 * 统计首席级门店租金补贴总额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPerforAward_ceo($store_id=0,$member_id=0,$max_level=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['ceo_id']=$member_id;		  
		}
		if ($max_level>0){
		    $condition['mc_level']=array('elt',$max_level);		  
		}
		$condition['saleman_type']=4;
		$condition['give_status']=0;
			
		return $this->getPerforAward_total($condition);
	}
	
	/** 
	 * 统计股东级门店租金补贴总额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPerforAward_holder($store_id=0,$member_id=0,$max_level=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['holder_id']=$member_id;		  
		}
		if ($max_level>0){
		    $condition['mc_level']=array('elt',$max_level);		  
		}
		$condition['saleman_type']=4;
		$condition['give_status']=0;
			
		return $this->getPerforAward_total($condition);
	}
	
	//-----------------------------------------------------------高管奖励及升级处理 ----------------------------------------------------------------------//
	
	/** 
	 * 统计管理层销售额及订单数
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAndOrders($condition = array()) {
	    return $this->field('sum(goods_amount) as amount, count(order_id) as orders')->where($condition)->find();
	}
	
	/** 
	 * 统计经理层销售额及订单数
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAndOrders_fans($store_id=0,$member_id=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
			$condition['saleman_id']=$member_id;  	  
		}
		$condition['saleman_type']=2;
		$condition['give_status']=0;
			
		return $this->getManagerSalesAndOrders($condition);
	}
	
	/** 
	 * 统计经理层销售额及订单数
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAndOrders_manager($store_id=0,$member_id=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
			$condition['manager_id']=$member_id;  	  
		}
		$condition['saleman_type']=3;
		$condition['give_status']=0;
			
		return $this->getManagerSalesAndOrders($condition);
	}
	
	/** 
	 * 统计协理层销售额及订单数
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAndOrders_coo($store_id=0,$member_id=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
			$condition['coo_id']=$member_id;	  	  
		}
		$condition['saleman_type']=3;
		$condition['give_status']=0;
			
		return $this->getManagerSalesAndOrders($condition);
	}
	
	/** 
	 * 统计首席层销售额及订单数
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAndOrders_ceo($store_id=0,$member_id=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
			$condition['ceo_id']=$member_id; 	  
		}
		$condition['saleman_type']=3;
		$condition['give_status']=0;
			
		return $this->getManagerSalesAndOrders($condition);
	}
	
	/** 
	 * 统计股东层销售额及订单数
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerSalesAndOrders_holder($store_id=0,$member_id=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
			$condition['holder_id']=$member_id;	  	  
		}
		$condition['saleman_type']=3;
		$condition['give_status']=0;
			
		return $this->getManagerSalesAndOrders($condition);
	}
	
	/** 
	 * 统计高管奖励总额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerAward_total($condition = array()) {
	    return $this->where($condition)->sum('mb_commis_totals');
	}
	
	/** 
	 * 统计经理级高管奖励总额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerAward_manager($store_id=0,$member_id=0,$max_level=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['manager_id']=$member_id;		  
		}
		if ($max_level>0){
		    $condition['mc_level']=array('elt',$max_level);		  
		}
		$condition['saleman_type']=3;
		$condition['give_status']=0;
			
		return $this->getManagerAward_total($condition);
	}
	
	/** 
	 * 统计协理级高管奖励总额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerAward_coo($store_id=0,$member_id=0,$max_level=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['coo_id']=$member_id;		  
		}
		if ($max_level>0){
		    $condition['mc_level']=array('elt',$max_level);		  
		}
		$condition['saleman_type']=3;
		$condition['give_status']=0;
			
		return $this->getManagerAward_total($condition);
	}
	
	/** 
	 * 统计首席级高管奖励总额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerAward_ceo($store_id=0,$member_id=0,$max_level=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['ceo_id']=$member_id;		  
		}
		if ($max_level>0){
		    $condition['mc_level']=array('elt',$max_level);		  
		}
		$condition['saleman_type']=3;
		$condition['give_status']=0;
			
		return $this->getManagerAward_total($condition);
	}
	
	/** 
	 * 统计股东级高管奖励总额
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getManagerAward_holder($store_id=0,$member_id=0,$max_level=0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		if ($store_id>0){
		    $condition['store_id']=$store_id;
		}
		if ($member_id>0){
		    $condition['holder_id']=$member_id;		  
		}
		if ($max_level>0){
		    $condition['mc_level']=array('elt',$max_level);		  
		}
		$condition['saleman_type']=3;
		$condition['give_status']=0;
			
		return $this->getManagerAward_total($condition);
	}
	
}