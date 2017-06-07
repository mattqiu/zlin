<?php
/**
 * 咨询管理
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
class consult_typeModel extends Model{
    public function __construct() {
        parent::__construct('consult_type');
    }
    
    /**
     * 咨询类型列表
     * 
     * @param array $condition
     * @param string $field
     * @param string $order
     * @return array
     */
    public function getConsultTypeList($condition, $field = '*', $order = 'ct_sort asc,ct_id desc') {
        return $this->where($condition)->field($field)->order($order)->select();
    }
    
    /**
     * 单条咨询类型
     * 
     * @param unknown $condition
     * @param string $field
     */
    public function getConsultTypeInfo($condition, $field = '*') {
        return $this->where($condition)->field($field)->find();
    }
    
    /**
     * 添加咨询类型
     * @param array $insert
     * @return int
     */
    public function addConsultType($insert) {
        return $this->insert($insert);
    }
    
    /**
     * 编辑咨询类型
     * @param array $condition
     * @param array $update
     * @return boolean
     */
    public function editConsultType($condition, $update) {
        return $this->where($condition)->update($update);
    }
    
    /**
     * 删除咨询类型
     * 
     * @param array $condition
     * @return boolean
     */
    public function delConsultType($condition) {
        return $this->where($condition)->delete();
    }
}