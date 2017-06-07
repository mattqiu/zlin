<?php
/**
 * 微站用户案例管理
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
class weixin_caseModel extends Model {

    public function __construct(){
        parent::__construct('weixin_case');
    }
  
    /**
     * 用户案例详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getcaseInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 用户案例列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getcaseList($page = 10, $order = 'id desc') {
	   return $this->page($page)->order($order)->select();
    }

    /**
     * 用户案例数量
     * @param array $condition
     * @return int
     */
    public function getcaseCount($condition) {
        return $this->where($condition)->count();
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
			$result = Db::insert('weixin_case',$tmp);
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
			$result = Db::update('weixin_case',$tmp,$where);
			return $result;
		}else {
			return false;
		}
	}
	/**
	 * 删除用户案例
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('weixin_case',$where);
			return $result;
		}else {
			return false;
		}
	}
}
