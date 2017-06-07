<?php
/**
 * 卖家帐号模型
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
class sellerModel extends Model{

    public function __construct(){
        parent::__construct('seller');
    }

	/**
	 * 读取列表 
	 * @param array $condition
	 *
	 */
	public function getSellerList($condition, $page='', $order='', $field='*') {
        $result = $this->field($field)->where($condition)->page($page)->order($order)->select();
        return $result;
	}

    /**
	 * 读取单条记录
	 * @param array $condition
	 *
	 */
    public function getSellerInfo($condition, $field='*') {
        $result = $this->field($field)->where($condition)->find();
        return $result;
    }

	/*
	 *  判断是否存在 
	 *  @param array $condition
     *
	 */
	public function isSellerExist($condition) {
        $result = $this->getSellerInfo($condition);
        if(empty($result)) {
            return FALSE;
        } else {
            return TRUE;
        }
	}

	/**
	 * 会员对应管理店铺数
	 * @param array $condition
	 *
	 */
	public function getStoreCountByMemberID($member_id, $field='store_id') {
		$condition = array();
		$condition['member_id'] = $member_id;
		$result = $this->field($field)->where($condition)->count(); //->group('store_id')
		return $result;
	}
	/*
	 * 增加 
	 * @param array $param
	 * @return bool
	 */
    public function addSeller($param){
        return $this->insert($param);	
    }
	
	/*
	 * 更新
	 * @param array $update
	 * @param array $condition
	 * @return bool
	 */
    public function editSeller($update, $condition){
        return $this->where($condition)->update($update);
    }
	
	/*
	 * 删除
	 * @param array $condition
	 * @return bool
	 */
    public function delSeller($condition){
        return $this->where($condition)->delete();
    }
	
}
