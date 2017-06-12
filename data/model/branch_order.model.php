<?php
/**
 * 分销订单表
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

class branch_orderModel extends Model {
    public function __construct() {
        parent::__construct('branch_order');
    }
	
	/**
     * 分销订单详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getOrderInfo($order_id, $field = '*') {
		$condition = array();
		$condition['order_id'] = $order_id;
        $order_info = $this->field($field)->where($condition)->find();
		return $order_info;
    }
	
	/**
     * 分销订单列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getOrderList($condition = array(), $field = '*', $page = 0, $order = 'order_id desc') {  
	   $order_list = $this->where($condition)->page($page)->order($order)->select();
  	   return $order_list;
    }
	
	/**
     * 分店补货订单列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getReplenishOrderList($condition = array(), $field = '*', $page = 10, $order = 'order_id desc') {
		$condition['order_type'] = array('in',array(0,1));		
        return $this->getOrderList($condition,$field,$page,$order);
    }
	
	/**
     * 分店退货订单列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getReturnedOrderList($condition = array(), $field = '*', $page = 10, $order = 'order_id desc') {
		$condition['order_type'] = 2;		
        return $this->getOrderList($condition,$field,$page,$order);
    }	
	
	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function addOrderInfo($param){
        return $this->insert($param);	
    }
	
	/*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
	 */
    public function editOrderInfo($update, $condition){
        return $this->where($condition)->update($update);
    }
	
	/*
	 * 删除
	 * @param array $condition
	 * @return bool
	 */
    public function delOrderInfo($condition){
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