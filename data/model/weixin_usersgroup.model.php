<?php
/**
 * 微站用户等级模型
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

class weixin_usersgroupModel extends Model {
	
	public function __construct(){
        parent::__construct('weixin_usersgroup');
    }
	/**
	 * 列表
	 *
	 * @param array $condition 检索条件
	 * @return array 数组结构的返回结果
	 */
	public function getUsersGroupList($condition = array()){
		$condition_str = $this->_condition($condition);
		$param = array();
		$param['table'] = 'weixin_usersgroup';
		$param['where'] = $condition_str;
		$param['order'] = $condition['order']?$condition['order']:'id';
		$result = Db::select($param);
		return $result;
	}
	/**
	 * 构造检索条件
	 *
	 * @param int $id 记录ID
	 * @return string 字符串类型的返回结果
	 */
	private function _condition($condition){
		$condition_str = '';
		
		if ($condition['like_name'] != ''){
			$condition_str .= " and name like '%". $condition['like_name'] ."%'";
		}
		if ($condition['no_id'] != ''){
			$condition_str .= " and id != '". intval($condition['no_id']) ."'";
		}
		if ($condition['name'] != ''){
			$condition_str .= " and name = '". $condition['name'] ."'";
		}
		if ($condition['id'] != ''){
			$condition_str .= " and weixin_usersgroup.id = '". $condition['id'] ."'";
		}
		if(isset($condition['users_id'])) {
			$condition_str .= " and weixin_users.id = '{$condition['users_id']}' ";
		}
		return $condition_str;
	}
	
	/**
	 * 取单个内容
	 *
	 * @param int $id 分类ID
	 * @return array 数组类型的返回结果
	 */
	public function getOneUsersGroup($id){
		if (intval($id) > 0){
			$param = array();
			$param['table'] = 'weixin_usersgroup';
			$param['field'] = 'id';
			$param['value'] = intval($id);
			$result = Db::getRow($param);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 新增
	 *
	 * @param array $param 参数内容
	 * @return bool 布尔类型的返回结果
	 */
	public function add($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$result = Db::insert('weixin_usersgroup',$tmp);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 更新信息
	 *
	 * @param array $param 更新数据
	 * @return bool 布尔类型的返回结果
	 */
	public function update($param){
		if (empty($param)){
			return false;
		}
		if (is_array($param)){
			$tmp = array();
			foreach ($param as $k => $v){
				$tmp[$k] = $v;
			}
			$where = " id = '{$param['id']}'";
			$result = Db::update('weixin_usersgroup',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 删除分类
	 *
	 * @param int $id 记录ID
	 * @return bool 布尔类型的返回结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('weixin_usersgroup',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	
	/**
	 * 等级对应用户列表
	 *
	 * @param array $condition 检索条件
	 * @param obj $page 分页
	 * @return array 数组结构的返回结果
	 */
	public function getGroupUsersList($condition,$page=''){
		$condition_str = $this->_condition($condition);
		$param = array(
					'table'=>'weixin_usersgroup,weixin_users',
					'field'=>'weixin_usersgroup.*,weixin_users.*',
					'where'=>$condition_str,
					'join_type'=>'left join',
					'join_on'=>array(
						'weixin_usersgroup.id = weixin_users.gid',
					)
				);		
		$result = Db::select($param,$page);
		return $result;
	}
}