<?php
/**
 * 推广业绩奖励标准表
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

class extension_perforawardModel extends Model {
    public function __construct() {
        parent::__construct('extension_perforaward');
    }
	
    /**
     * 推广业绩奖励标准列表
     * @param unknown $condition
     * @param string $fields
     * @param string $pagesize
     * @param string $order
     * @param string $limit
     */
    public function getExtensionPerforAwardList($condition = array(), $fields = '*', $pagesize = null, $order = 'award_rate desc', $limit = null) {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }
	
	/**
     * 根据店铺ID取得推广业绩奖励标准列表
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionPerforAwardListByStoreID($store_id = '', $fields = '*') {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$award_list = array();
		if (!empty($store_id)){
		    $condition = array();
		    $condition['store_id'] = $store_id;
			$award_list = $this->getExtensionPerforAwardList($condition,$fields);
		}
        return $award_list;
    }
	
	/**
     * 根据店铺ID取得推广业绩奖励标准列表并按奖励类型作为关键字
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionPerforAwardListSortByTypeByStoreID($store_id = '', $fields = '*') {
		if (OPEN_STORE_EXTENSION_STATE == 10){$store_id = GENERAL_PLATFORM_EXTENSION_ID;}
		
		$perforaward_list = array();
		if (!empty($store_id)){
		    $condition = array();
		    $condition['store_id'] = $store_id;
			$award_list = $this->getExtensionPerforAwardList($condition);
			if (!empty($award_list)){
				foreach ($award_list as $vk=>$award_info) {
					$perforaward_list[$award_info['mc_id']] = $award_info;
				}
			}
		}
        return $perforaward_list;
    }

    /**
     * 取得推广业绩奖励标准单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionPerforAwardInfo($condition = array(), $fields = '*') {
        return $this->where($condition)->field($fields)->find();
    }
	
	/**
     * 根据id取得推广业绩奖励标准单条信息
     * @param unknown $condition
     * @param string $fields
     */
    public function getExtensionPerforAwardByID($ep_id = '', $fields = '*') {
        $award_info = array();
		if (!empty($ep_id)){
		    $condition = array();
		    $condition['ep_id'] = $ep_id;
			$award_info = $this->getExtensionPerforAwardInfo($condition,$fields);
		}
        return $award_info;
    }	
    
    /**
     * 取得推广业绩奖励标准数量
     * @param unknown $condition
     */
    public function getExtensionPerforAwardCount($condition) {
        return $this->where($condition)->count();
    }
	
    /**
     * 添加推广业绩奖励标准
     * @param unknown $condition
     */
    public function addExtensionPerforAward($data) {
        return $this->insert($data);
    }
	
    /**
     * 修改推广业绩奖励标准
     * @param unknown $condition
     */
    public function editExtensionPerforAward($data, $condition = array()) {
        return $this->where($condition)->update($data);
    }
	
	/**
     * 删除推广业绩奖励标准
     * @param unknown $condition
     */
	public function delExtensionPerforAward($condition = array()) {
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