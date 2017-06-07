<?php
/**
 * 微站文本管理
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
class weixin_textModel extends Model {

    public function __construct(){
        parent::__construct('weixin_text');
    }
    /**
	 * 插入默认值数据
	 *
	 * @param array $data
	 * @param array $options
	 * @return mixed int/false
	 */
	 protected function _auto_insert_data(&$data,$options) {
		 $data['uid']        = $_SESSION['uid'];
		 $data['uname']      = $_SESSION['member_name'];
		 $data['createtime'] = time();		 
		 $data['updatetime'] = time();		 
		 $data['token']      = $_SESSION['token'];		 
		 $data['click']      = 0;
	}
    /**
     * 文本详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getTextInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 文本列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getTextList($condition = array(), $field = '*', $page = 0, $order = 'id desc') {
       return $this->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 文本数量
     * @param array $condition
     * @return int
     */
    public function getTextCount($condition) {
        return $this->where($condition)->count();
    }
	/**
	 * 删除文本
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('weixin_text',$where);
			return $result;
		}else {
			return false;
		}
	}
}
