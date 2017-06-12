<?php
/**
 * 买家品牌关注表
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

class brand_followModel extends Model{
    public function __construct() {
        parent::__construct('brand_follow');
    }
    
    /**
     * 关注列表
     * 
     * @param array $condition
     * @param treing $field
     * @param int $page
     * @param string $order
     * @return array
     */
    public function getFollowsList($condition, $field = '*', $page = 0 , $order = 'follow_time desc') {
        return $this->where($condition)->order($order)->page($page)->select();
    }
	/**
	 * 取单个关注的内容
	 *
	 * @param array $condition 查询条件
	 * @param string $field 查询字段
	 * @return array 数组类型的返回结果
	 */
	public function getOneFollows($condition,$field='*'){
		$param = array();
		$param['table'] = 'brand_follow';
		$param['field'] = array_keys($condition);
		$param['value'] = array_values($condition);
		return Db::getRow($param,$field);
	}
	
	/**
	 * 新增关注
	 *
	 * @param array $param 参数内容
	 * @return bool 布尔类型的返回结果
	 */
	public function addFollows($param){
		if (empty($param)){
			return false;
		}
		return Db::insert('brand_follow',$param);
	}
	
	/**
	 * 更新关注数量
	 * 
	 * 
	 * @param string $table 表名
	 * @param array  $update 更新内容
	 * @param array  $param  相应参数
	 * @return bool 布尔类型的返回结果
	 */
	public function updateFollowsNum($table, $update, $param){
		$where = $this->_condition($param);
		return Db::update($table,$update,$where);
	}
	
	/**
	 * 验证是否为当前用户关注
	 *
	 * @param array $param 条件数据
	 * @return bool 布尔类型的返回结果
	 */
	public function checkFollows($brand_id,$member_id){
		if (intval($brand_id) == 0 || intval($member_id) == 0){
			return true;
		}
		$result = self::getOneFavorites($brand_id,$member_id);
		if ($result['member_id'] == $member_id){
			return true;
		}else {
			return false;
		}
	}
	
	/**
	 * 删除
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function delFollows($condition){
		if (empty($condition)){
			return false;
		}
		$condition_str = '';	
		if ($condition['brand_id'] != ''){
			$condition_str .= " and brand_id='{$condition['brand_id']}' ";
		}
		if ($condition['member_id'] != ''){
			$condition_str .= " and member_id='{$condition['member_id']}' ";
		}
		if ($condition['brand_id_in'] !=''){
			$condition_str .= " and brand_id in({$condition['brand_id_in']}) ";
		}
		return Db::delete('brand_follow',$condition_str);
	}
	/**
	 * 构造检索条件
	 *
	 * @param array $condition 检索条件
	 * @return string 字符串类型的返回结果
	 */
	public function _condition($condition){
		$condition_str = '';
		
		if ($condition['member_id'] != ''){
			$condition_str .= " and member_id = '".$condition['member_id']."'";
		}
		if ($condition['brand_id'] != ''){
			$condition_str .= " and brand_id = '".$condition['brand_id']."'";
		}
		if ($condition['brand_id_in'] !=''){
			$condition_str .= " and brand_follow.brand_id in({$condition['brand_id_in']}) ";
		}
		return $condition_str;
	}
}
