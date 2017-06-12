<?php
/**
 * 商品调拔详细表
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

class branch_stubbsModel extends Model {
    public function __construct() {
        parent::__construct('branch_stubbs');
    }
	
	/**
     * 调拔/退货/代发商品详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getStubbsInfo($bs_id, $field = '*') {
		$condition = array();
		$condition['bs_id'] = $bs_id;
        $stubbs_info = $this->field($field)->where($condition)->find();
		return $stubbs_info;
    }
	
	/**
     * 调拔/退货/代发商品列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getStubbsList($condition = array(), $field = '*', $page = 0, $order = 'bs_id desc') {  
	    $stubbs_list = $this->where($condition)->page($page)->order($order)->select();
	    if (!empty($stubbs_list) && is_array($stubbs_list)){
			foreach ($stubbs_list as $k=>$value) {
				$stubbs_list[$k]['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
        	    $stubbs_list[$k]['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
        	    $stubbs_list[$k]['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
				
				$stubbs_list[$k]['b_image_60_url'] = cthumb($value['b_goods_image'], 60, $value['branch_id']);
        	    $stubbs_list[$k]['b_image_240_url'] = cthumb($value['b_goods_image'], 240, $value['branch_id']);
        	    $stubbs_list[$k]['b_goods_url'] = urlShop('goods','index',array('goods_id'=>$value['b_goods_id']));
			}
		}	   
  	    return $stubbs_list;
    }
	
	/**
     * 分店补货/代发商品列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getReplenishStubbsList($condition = array(), $field = '*', $page = 20, $order = 'bs_id desc') {
		$condition['stubbs_type'] = array('in',array(0,1));		
        return $this->getStubbsList($condition,$field,$page,$order);
    }
	
	/**
     * 分店退货商品列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getReturnedStubbsList($condition = array(), $field = '*', $page = 20, $order = 'bs_id desc') {
		$condition['stubbs_type'] = 2;		
        return $this->getStubbsList($condition,$field,$page,$order);
    }	
	
	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function addStubbsInfo($param){
        return $this->insert($param);	
    }
	
	/*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
	 */
    public function editStubbsInfo($update, $condition){
        return $this->where($condition)->update($update);
    }
	
	/*
	 * 删除
	 * @param array $condition
	 * @return bool
	 */
    public function delStubbsInfo($condition){
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