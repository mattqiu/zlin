<?php
/**
 * 微站充值管理
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
class weixin_chargesModel extends Model {

    public function __construct(){
        parent::__construct('weixin_charges');
    }
    
    /**
     * 充值记录详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getchargesInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 充值记录列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getchargesList($condition = array(), $field = '*', $page = 0, $order = 'id desc') {
       return $this->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 充值记录数量
     * @param array $condition
     * @return int
     */
    public function getchargesCount($condition) {
        return $this->where($condition)->count();
    }
	/**
	 * 删除充值记录
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('weixin_charges',$where);
			return $result;
		}else {
			return false;
		}
	}
}
