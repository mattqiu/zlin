<?php
/**
 * 微站图文管理
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
class weixin_articleModel extends Model {

    public function __construct(){
        parent::__construct('weixin_img');
    }
    
    /**
     * 图文详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getArticleInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 图文列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getArticleList($condition = array(), $field = '*', $page = 0, $order = 'id desc') {
       return $this->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 图文数量
     * @param array $condition
     * @return int
     */
    public function getArticleCount($condition) {
        return $this->where($condition)->count();
    }
	/**
	 * 删除图文
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('weixin_img',$where);
			return $result;
		}else {
			return false;
		}
	}
}
