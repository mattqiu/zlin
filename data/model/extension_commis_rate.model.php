<?php
/**
 * 推广员抽佣比率表
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

class extension_commis_rateModel extends Model {
    public function __construct() {
        parent::__construct('extension_commis_rate');
    }
	
	/**
	 * 读取单行信息
	 *
	 * @param
	 * @return array 数组格式的返回结果
	 */
	public function getCommisRateInfo($store_id,$condition = array()) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		if (isset($store_id)){
		  $condition['store_id']=$store_id;
		}else{
		  $condition['store_id']=DEFAULT_PLATFORM_STORE_ID;
		}
		return $this->where($condition)->find();
	}
	
	/**
     * 管理奖比率
     * @param array $condition
     * @return int
     */
    public function getRate_Manage($store_id = 0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		$condition['store_id'] = $store_id;

		$rate_manage = $this->where($condition)->get_field('rate_manage');
		return $rate_manage;
    }
	
	/**
     * 绩优奖比率
     * @param array $condition
     * @return int
     */
    public function getRate_Perfor($store_id = 0) {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$condition = array();
		$condition['store_id'] = $store_id;

		$rate_perfor = $this->where($condition)->get_field('rate_perfor');
		return $rate_perfor;
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