<?php
/**
 * 佣金配置表
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

class extension_commis_classModel extends Model {
    public function __construct() {
        parent::__construct('extension_commis_class');
    }
	
	/**
	 * 读取多行
	 *
	 * @param 
	 * @return array 数组格式的返回结果
	 */
	public function getCommisList($store_id,$condition = array()){
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		if (isset($store_id)){
		  $condition['store_id']=$store_id;
		}else{
		  $condition['store_id']=DEFAULT_PLATFORM_STORE_ID;
		}
        return $this->where($condition)->key('commis_id')->select();
	}
	/**
	 * 读取单行信息
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getCommisInfo($store_id,$condition = array()) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		if (isset($store_id)){
		  $condition['store_id']=$store_id;
		}else{
		  $condition['store_id']=DEFAULT_PLATFORM_STORE_ID;
		}
		return $this->where($condition)->find();
	}
	
	//输入ID返回返利比率
	public function getCommisRateByID($commis_id) {
		$commis_rate = array();
		$commis_rate['commis_class'] = 0;
		$commis_rate['commis_rate'] = 0;
		
		if (!empty($commis_id) && $commis_id>0){
		    $condition = array();
		    $condition['commis_id'] = $commis_id;
            $commis_class = $this->where($condition)->find();
            if(!empty($commis_class)) {
				$commis_rate['commis_class'] = $commis_class['commis_class'];
		        $commis_rate['commis_rate'] = $commis_class['commis_rate'];
            }
		}
		return $commis_rate;
    }
    
	//输入ID,售价，成本价计算返回返利值
	public function getRebateValueByID($commis_id=0,$sellprice=0,$costprice=0) {
		$commis_points = 0;
		$commis_price = 0;
		if ($commis_id>0 && $sellprice>0){
		    $commis_class = 0;
		    $commis_rate  = 0;
		
		    $condition = array();
		    $condition['commis_id'] = $commis_id;
            $commis_info = $this->where($condition)->find();
	    
            if(!empty($commis_info)) {
			    $commis_class = $commis_info['commis_class'];
		        $commis_rate  = $commis_info['commis_rate'];
		        $commis_mode = $commis_info['commis_mode']; //返积分还是返云币
            }
	   
			if ($commis_rate>0){
				if($commis_mode == 1){
					switch ($commis_class){
						case 0:  //无提成
							$commis_points = 0;
							break;
						case 1:  //固定云币
							$commis_points = $commis_rate;
							break;
						case 2:  //售价云币
							$commis_points = ($sellprice*$commis_rate/100);
							break;
						case 3:  //利润云币
							$cost = $costprice>0?$costprice:$sellprice;
							$commis_points = (($sellprice-$cost)*$commis_rate/100);
							break;
						default:  //无提成
							$commis_points = 0;
							break;
					}
				}else{
					switch ($commis_class){
						case 0:  //无提成
							$commis_price = 0;
							break;
						case 1:  //固定佣金
							$commis_price = $commis_rate;
							break;
						case 2:  //售价佣金
							$commis_price = ($sellprice*$commis_rate/100);
							break;
						case 3:  //利润佣金
							$cost = $costprice>0?$costprice:$sellprice;
							$commis_price = (($sellprice-$cost)*$commis_rate/100);
							break;
						default:  //无提成
							$commis_price = 0;
							break;
					}
				}			    
			}
		}
		return array('commis_points'=>$commis_points,'commis_price'=>$commis_price);
    }
    
    /**
     * 输入模板ID,售价，成本价
     * 获取返佣金额即积分
     */
    public function getCommisPriceByID($commis_id=0,$sellprice=0,$costprice=0) {
    	$commis_points = 0;
    	$commis_price = 0;
    	if ($commis_id>0 && $sellprice>0){
    		$commis_class = 0;
    		$commis_rate  = 0;
    
    		$condition = array();
    		$condition['commis_id'] = $commis_id;
    		$condition['commis_mode'] = 0; //积分
    		$commis_class = $this->where($condition)->find();
    		if(!empty($commis_class)) {
    			$commis_class = $commis_class['commis_class'];
    			$commis_rate  = $commis_class['commis_rate'];
    		}
    		if ($commis_rate>0){
    			switch ($commis_class){
    				case 0:  //无提成
    					$commis_price = 0;
    					break;
    				case 1:  //固定佣金
    					$commis_price = $commis_rate;
    					break;
    				case 2:  //售价佣金
    					$commis_price = ($sellprice*$commis_rate/100);
    					break;
    				case 3:  //利润佣金
    					$cost = $costprice>0?$costprice:$sellprice;
    					$commis_price = (($sellprice-$cost)*$commis_rate/100);
    					break;
    				default:  //无提成
    					$commis_price = 0;
    					break;
    			}
    			
    		}
    	}
    	return $commis_price;
    }
    
    /**
     * 输入模板ID,售价，成本价
     * 获取返佣云币
     */
    public function getCommisPointsByID($commis_id=0,$sellprice=0,$costprice=0) {
    	$commis_points = 0;
    	$commis_price = 0;
    	if ($commis_id>0 && $sellprice>0){
    		$commis_class = 0;
    		$commis_rate  = 0;
    
    		$condition = array();
    		$condition['commis_id'] = $commis_id;
    		$condition['commis_mode'] = 1; //云币
    		$commis_class = $this->where($condition)->find();
    		if(!empty($commis_class)) {
    			$commis_class = $commis_class['commis_class'];
    			$commis_rate  = $commis_class['commis_rate'];
    		}
    		if ($commis_rate>0){
    			switch ($commis_class){
    				case 0:  //无提成
    					$commis_points = 0;
    					break;
    				case 1:  //固定云币
    					$commis_points = $commis_rate;
    					break;
    				case 2:  //售价云币
    					$commis_points = ($sellprice*$commis_rate/100);
    					break;
    				case 3:  //利润云币
    					$cost = $costprice>0?$costprice:$sellprice;
    					$commis_points = (($sellprice-$cost)*$commis_rate/100);
    					break;
    				default:  //无提成
    					$commis_points = 0;
    					break;
    			}
    			
    		}
    	}
    	return $commis_points;
    }
    
    /**
     * 根据系统默认的店铺ID 获取默认的返佣的积分
     * 
     * @param 店铺ID
     * @param 会员价
     * @param 批发价
     * @return array 数组格式的返回结果
     */
    public function getCommisPriceByDefault($store_id,$sellprice=0,$costprice=0){
    	if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}    
	    $condition = array();
	    $condition['is_default'] = 1; //mo默认模板
	    $condition['commis_mode'] = 0; //积分
    	if (isset($store_id)){
    		$condition['store_id']=array('in', array($store_id,DEFAULT_PLATFORM_STORE_ID));
    		$commis_list = $this->where($condition)->select();
    		if(!empty($commis_list)) {
    			foreach ($commis_list as $cinfo){
    				if($cinfo['store_id'] == $store_id){//查询到本店是否已经存在默认的模板
    					$commis_class = $cinfo['commis_class'];
    					$commis_rate  = $cinfo['commis_rate'];
    				}else{
    					$commis_class = $cinfo['commis_class'];
    					$commis_rate  = $cinfo['commis_rate'];
    				}
    			}    			
    		}else{
    			$commis_class = 0;
    		}
    	}else{
    		$condition['store_id']=DEFAULT_PLATFORM_STORE_ID;
    	$commis_class = $this->where($condition)->find();
    	if(!empty($commis_class)) {
    		$commis_class = $commis_class['commis_class'];
    		$commis_rate  = $commis_class['commis_rate'];
    		}else{
    			$commis_class = 0;
    	}
    	}    	
    	if ($commis_rate>0){
    		switch ($commis_class){
    			case 0:  //无提成
    				$commis_price = 0;
    				break;
    			case 1:  //固定佣金
    				$commis_price = $commis_rate;
    				break;
    			case 2:  //售价佣金
    				$commis_price = ($sellprice*$commis_rate/100);
    				break;
    			case 3:  //利润佣金
    				$cost = $costprice>0?$costprice:$sellprice;
    				$commis_price = (($sellprice-$cost)*$commis_rate/100);
    				break;
    			default:  //无提成
    				$commis_price = 0;
    				break;
    		}    			
    	}
    	return $commis_price;
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
}