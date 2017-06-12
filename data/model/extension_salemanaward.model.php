<?php
/**
 * 导购服务奖励标准表
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

class extension_salemanawardModel extends Model {
    public function __construct() {
        parent::__construct('extension_salemanaward');
    }
	
    /**
     * 导购服务奖励标准列表
     * @param unknown $condition
     * @param string $fields
     * @param string $pagesize
     * @param string $order
     * @param string $limit
     */
    public function getExtensionSalemanAwardList($condition = array(), $fields = '*', $pagesize = null, $order = 'award_rate desc', $limit = null) {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }
	
	/**
     * 根据店铺ID取得导购服务奖励标准列表
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionSalemanAwardListByStoreID($store_id = '', $fields = '*') {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$award_list = array();
		if (!empty($store_id)){
		    $condition = array();
		    $condition['store_id'] = $store_id;
			$award_list = $this->getExtensionSalemanAwardList($condition,$fields);
		}
        return $award_list;
    }
	
	/**
     * 根据店铺ID取得导购服务奖励标准列表并按奖励类型作为关键字
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionSalemanAwardListSortByTypeByStoreID($store_id = '', $fields = '*') {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$salemanaward_list = array();
		if (!empty($store_id)){
		    $condition = array();
		    $condition['store_id'] = $store_id;
			$award_list = $this->getExtensionSalemanAwardList($condition);
			if (!empty($award_list)){
				foreach ($award_list as $vk=>$award_info) {
					$salemanaward_list[$award_info['mc_id']] = $award_info;
				}
			}
		}
        return $salemanaward_list;
    }

    /**
     * 取得导购服务奖励标准单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionSalemanAwardInfo($condition = array(), $fields = '*') {
        return $this->where($condition)->field($fields)->find();
    }
	
	/**
     * 根据id取得导购服务奖励标准单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionSalemanAwardByID($sm_id = '', $fields = '*') {
        $award_info = array();
		if (!empty($sm_id)){
		    $condition = array();
		    $condition['sm_id'] = $sm_id;
			$award_info = $this->getExtensionSalemanAwardInfo($condition,$fields);
		}
        return $award_info;
    }	
    
    /**
     * 取得导购服务奖励标准数量
     * @param unknown $condition
     */
    public function getExtensionSalemanAwardCount($condition) {
        return $this->where($condition)->count();
    }
	
    /**
     * 添加导购服务奖励标准
     * @param unknown $condition
     */
    public function addExtensionSalemanAward($data) {
        return $this->insert($data);
    }
	
    /**
     * 修改导购服务奖励标准
     * @param unknown $condition
     */
    public function editExtensionSalemanAward($data, $condition = array()) {
        return $this->where($condition)->update($data);
    }
	
	/**
     * 删除导购服务奖励标准
     * @param unknown $condition
     */
	public function delExtensionSalemanAward($condition = array()) {
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
}