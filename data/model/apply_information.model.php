<?php
/**
 * 申请信息表
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
class apply_informationModel extends Model {

    public function __construct(){
        parent::__construct('apply_information');
    }
    
    /**
     * 申请记录详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getApplyInfo($id, $field = '*') {
		$condition = array();
		$condition['ai_id'] = $id;
        $apply_info = $this->field($field)->where($condition)->find();
		if (!empty($apply_info)){			
			$addinfo = unserialize($apply_info['ai_addinfo']);
			$apply_info['truename'] = $addinfo['truename'];
			$apply_info['email'] = $addinfo['email'];
			$apply_info['mobile'] = $addinfo['mobile'];
			$apply_info['qq'] = $addinfo['qq'];
			$apply_info['areainfo'] = $addinfo['areainfo'];
			$apply_info['describe'] = $addinfo['describe'];
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
    public function getApplyList($condition = array(), $field = '*', $page = 0, $order = 'ai_id desc') {
  
	   $apply_list = $this->where($condition)->page($page)->order($order)->select();
		
		if (!empty($apply_list) && is_array($apply_list)){
			foreach($apply_list as $k=>$v){
				$addinfo = unserialize($v['ai_addinfo']);
				$apply_list[$k]['truename'] = $addinfo['truename'];
				$apply_list[$k]['email'] = $addinfo['email'];
				$apply_list[$k]['mobile'] = $addinfo['mobile'];
				$apply_list[$k]['qq'] = $addinfo['qq'];
				$apply_list[$k]['areainfo'] = $addinfo['areainfo'];
				$apply_list[$k]['describe'] = $addinfo['describe'];
			}
		}
		return $apply_list;
    }	
	
	/**
     * 推广员申请记录列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getPromotionApplyList($store_id, $field = '*', $page = 20, $order = 'ai_id desc') {
		$condition = array();
		$condition['ai_target'] = $store_id;
		$condition['ai_type'] = 2;
		
        return $this->getApplyList($condition,$field,$page,$order);
    }
	
	/**
     * 导购员申请记录列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getSalemanApplyList($store_id, $field = '*', $page = 20, $order = 'ai_id desc') {
		$condition = array();
		$condition['ai_target'] = $store_id;
		$condition['ai_type'] = 1;
		
        return $this->getApplyList($condition,$field,$page,$order);
    }
	
	/**
     * 推广员下线申请记录列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getPromotionSubApplyList($member_id, $field = '*', $page = 20, $order = 'ai_id desc') {
		$condition = array();
		$condition['ai_target'] = $member_id;
		$condition['ai_type'] = 3;
		
        return $this->getApplyList($condition,$field,$page,$order);
    }
	
	/**
     * 提佣申请记录列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getCommisputOutApplyList($store_id, $field = '*', $page = 20, $order = 'ai_id desc') {
		$condition = array();
		$condition['ai_target'] = $store_id;
		$condition['ai_type'] = 4;
		
        return $this->getApplyList($condition,$field,$page,$order);
    }
	
	/**
     * 加盟申请记录列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getBranchApplyList($store_id, $field = '*', $page = 20, $order = 'ai_id desc') {
		$condition = array();
		$condition['ai_target'] = $store_id;
		$condition['ai_type'] = 5;
		
        return $this->getApplyList($condition,$field,$page,$order);
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
		$data['ai_addtime']    = time();	 
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
}
