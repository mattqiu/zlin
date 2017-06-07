<?php
/**
 * 平台定价配置表
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class extension_price_classModel extends Model {
    public function __construct() {
        parent::__construct('extension_price_class');
    }
	
	/**
	 * 读取多行
	 *
	 * @param 
	 * @return array 数组格式的返回结果
	 */
	public function getPriceList($store_id,$condition = array()){
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		if (isset($store_id)){
		  $condition['store_id']=$store_id;
		}else{
		  $condition['store_id']=DEFAULT_PLATFORM_STORE_ID;
		}
        return $this->where($condition)->key('pid')->select();
	}
	/**
	 * 读取单行信息
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getPriceInfo($store_id,$condition = array()) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		if (isset($store_id)){
		  $condition['store_id']=$store_id;
		}else{
		  $condition['store_id']=DEFAULT_PLATFORM_STORE_ID;
		}
		return $this->where($condition)->find();
	}
	
	//输入ID返回返利比率
	public function getPriceRateByID($pid) {
		$profit_rate = array();
		$profit_rate['ptype'] = 0;
		$profit_rate['profit_rate'] = 0;
		
		if (!empty($pid) && $pid>0){
		    $condition = array();
		    $condition['pid'] = $pid;
            $price_class = $this->where($condition)->find();
            if(!empty($price_class)) {
				$profit_rate['ptype'] = $price_class['ptype'];
		        $profit_rate['profit_rate'] = $price_class['profit_rate'];
            }
		}
		return $profit_rate;
    }
	//输入ID,售价，成本价计算返回返利值
	public function getRebateValueByID($pid=0,$sellprice=0,$costprice=0) {
		$RebateValue = 0;
		if ($pid>0 && $sellprice>0){
		    $rebate_class = 0;
		    $rebate_rate  = 0;
		
		    $condition = array();
		    $condition['pid'] = $pid;
            $price_class = $this->where($condition)->find();
            if(!empty($ptype)) {
			    $rebate_class = $price_class['ptype'];
		        $rebate_rate  = $price_class['profit_rate'];
            }
			if ($rebate_rate>0){
			    switch ($rebate_class){
			      case 0:  //无提成
				    $RebateValue = 0;
				    break;
			      case 1:  //固定佣金
				    $RebateValue = $rebate_rate;
				    break;
			      case 2:  //售价佣金
				    $RebateValue = ($sellprice*$rebate_rate/100);
				    break;
			      case 3:  //利润佣金
				    $cost = $costprice>0?$costprice:$sellprice;		  
				    $RebateValue = (($sellprice-$cost)*$rebate_rate/100);
				    break;
			      default:  //无提成
				    $RebateValue = 0;
				    break;
			    }
			}
		}
		return $RebateValue;
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