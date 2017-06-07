<?php
/**
 * 总店分店补货退货申请表
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

class branch_applyModel extends Model {
    public function __construct() {
        parent::__construct('branch_apply');
    }
	
	/**
     * 申请记录详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getApplyInfo($id, $field = '*') {
		$condition = array();
		$condition['bp_id'] = $id;
        $apply_info = $this->field($field)->where($condition)->find();
		if (!empty($apply_info)){			
			$addinfo = unserialize($apply_info['bp_addinfo']);
			$apply_info['goods_list'] = $addinfo;
			$apply_info['goods_nums'] = count($addinfo);
			$goods_total = 0;
			if (!empty($addinfo)){
				foreach($addinfo as $k=>$v){
					$goods_total += $v['nums'];
				}
			}
			$apply_info['goods_total'] = $goods_total;
		}
		return $apply_info;
    }
	
	/**
     * 申请记录列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getApplyList($condition = array(), $field = '*', $page = 0, $order = 'bp_id desc') {
  
	   $apply_list = $this->where($condition)->page($page)->order($order)->select();
		
		if (!empty($apply_list) && is_array($apply_list)){
			foreach($apply_list as $k=>$v){
				$addinfo = unserialize($v['bp_addinfo']);
				$apply_list[$k]['goods_list'] = $addinfo;
				$apply_list[$k]['goods_nums'] = count($addinfo);
			    $goods_total = 0;
			    if (!empty($addinfo)){
				    foreach($addinfo as $key=>$value){
					    $goods_total += $value['nums'];
				    }
			    }
			    $apply_list[$k]['goods_total'] = $goods_total;
			}
		}
		return $apply_list;
    }
	
	/**
     * 分店补货申请记录列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getReplenishApplyList($condition = array(), $field = '*', $page = 20, $order = 'bp_id desc') {
		$condition['bp_type'] = 1;
		
        return $this->getApplyList($condition,$field,$page,$order);
    }
	
	/**
     * 分店退货申请记录列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getReturnedApplyList($condition = array(), $field = '*', $page = 20, $order = 'bp_id desc') {
		$condition['bp_type'] = 2;
		
        return $this->getApplyList($condition,$field,$page,$order);
    }
	
	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function addApplyInfo($param){
        return $this->insert($param);	
    }
	
	/*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
	 */
    public function editApplyInfo($update, $condition){
        return $this->where($condition)->update($update);
    }
	
	/*
	 * 删除
	 * @param array $condition
	 * @return bool
	 */
    public function delApplyInfo($condition){
        return $this->where($condition)->delete();
    }	
	
	/**
     * 申请记录数量
     * @param array $condition
     * @return int
     */
    public function getApplyCount($condition) {
        return $this->where($condition)->count();
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