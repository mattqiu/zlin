<?php
/**
 * 分店结算表
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

//以下是定义结算单状态
//默认
define('BILL_STATE_CREATE',1);
//店铺已确认
define('BILL_STATE_STORE_COFIRM',2);
//平台已审核
define('BILL_STATE_SYSTEM_CHECK',3);
//结算完成
define('BILL_STATE_SUCCESS',4);

class branch_billModel extends Model {
    public function __construct() {
        parent::__construct('branch_bill');
    }
	
    /**
     * 取得分店结算单列表
     * @param unknown $condition
     * @param string $fields
     * @param string $pagesize
     * @param string $order
     * @param string $limit
     */
    public function getBranchOrderBillList($condition = array(), $fields = '*', $pagesize = null, $order = '', $limit = null) {
        return $this->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
    }

    /**
     * 取得分店结算单单条
     * @param unknown $condition
     * @param string $fields
     */
    public function getBranchOrderBillInfo($condition = array(), $fields = '*') {
        return $this->where($condition)->field($fields)->find();
    }
    
    /**
     * 取得订单数量
     * @param unknown $condition
     */
    public function getBranchOrderBillCount($condition) {
        return $this->where($condition)->count();
    }
    
    public function addBranchOrderBill($data) {
        return $this->insert($data);
    }

    public function editBranchOrderBill($data, $condition = array()) {
        return $this->where($condition)->update($data);
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