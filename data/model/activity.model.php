<?php
/**
 * 活动
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
class activityModel extends Model{
	/**
	 * 活动列表
	 *
	 * @param array $condition 查询条件
	 * @param obj $page 分页对象
	 * @return array 二维数组
	 */
	public function getList($condition,$page=''){
		$param	= array();
		$param['table']	= 'activity';
		$param['where']	= $this->getCondition($condition);
		$param['order']	= $condition['order'] ? $condition['order'] : 'activity_id';
		return Db::select($param,$page);
	}
	/**
	 * 添加活动
	 *
	 * @param array $input
	 * @return bool
	 */
	public function add($input){
		return Db::insert('activity',$input);
	}
	/**
	 * 更新活动
	 *
	 * @param array $input
	 * @param int $id
	 * @return bool
	 */
	public function update($input,$id){
		return Db::update('activity',$input," activity_id='$id' ");
	}
	/**
	 * 活动浏览数+1
	 *
	 * @param array $input
	 * @param int $id
	 * @return bool
	 */
	public function add_views($id){
		$sql = "UPDATE `".DBPRE."activity` AS `activity` SET activity_views = activity_views+1 WHERE activity_id='".$id."'";
		return Db::execute($sql);
	}
	/**
	 * 点赞数+1
	 *
	 * @param array $input
	 * @param int $id
	 * @return bool
	 */
	public function add_follows($id){
		$sql = "UPDATE `".DBPRE."activity` AS `activity` SET activity_follows = activity_follows+1 WHERE activity_id='".$id."'";
		return Db::execute($sql);
	}
	/**
	 * 删除活动
	 *
	 * @param string $id
	 * @return bool
	 */
	public function del($id){
		return Db::delete('activity','activity_id in('.$id.')');
	}
	/**
	 * 根据id查询一条活动
	 *
	 * @param int $id 活动id
	 * @return array 一维数组
	 */
	public function getOneById($id){
		return Db::getRow(array('table'=>'activity','field'=>'activity_id','value'=>$id));
	}
	/**
	 * 根据条件
	 *
	 * @param array $condition 查询条件
	 * @param obj $page 分页对象
	 * @return array 二维数组
	 */
	public function getJoinList($condition,$page=''){
		$param	= array();
		$param['table']	= 'activity,activity_detail';
		$param['join_type']	= empty($condition['join_type'])?'right join':$condition['join_type'];
		$param['join_on']	= array('activity.activity_id=activity_detail.activity_id');
		$param['where']	= $this->getCondition($condition);
		$param['order']	= $condition['order'];
		return Db::select($param,$page);
	}
	/**
	 * 构造查询条件
	 *
	 * @param array $condition 条件数组
	 * @return string
	 */
	private function getCondition($condition){
		$conditionStr	= '';
		if($condition['activity_id'] != ''){
			$conditionStr	.= " and activity.activity_id='{$condition['activity_id']}' ";
		}
		if($condition['activity_type'] != ''){
			$conditionStr	.= " and activity.activity_type='{$condition['activity_type']}' ";
		}
		if($condition['activity_state'] != ''){
			$conditionStr	.= " and activity.activity_state = '{$condition['activity_state']}' ";
		}
		//活动删除in
		if(isset($condition['activity_id_in'])){
			if ($condition['activity_id_in'] == ''){
				$conditionStr	.= " and activity_id in('')";
			}else{
				$conditionStr	.= " and activity_id in({$condition['activity_id_in']}) ";
			}
		}
		if($condition['activity_title'] != ''){
			$conditionStr	.= " and activity.activity_title like '%{$condition['activity_title']}%' ";
		}
		//当前时间大于结束时间（过期）
		if ($condition['activity_enddate_greater'] != ''){
			$conditionStr	.= " and activity.activity_end_date < '{$condition['activity_enddate_greater']}'";
		}
		//可删除的活动记录
		if ($condition['activity_enddate_greater_or'] != ''){
			$conditionStr	.= " or activity.activity_end_date < '{$condition['activity_enddate_greater_or']}'";
		}
		//某时间段内正在进行的活动
		if($condition['activity_daterange'] != ''){
			$conditionStr .= " and (activity.activity_end_date >= '{$condition['activity_daterange']['startdate']}' and activity.activity_start_date <= '{$condition['activity_daterange']['enddate']}')";
		}
		if($condition['opening']){//在有效期内、活动状态为开启
			$conditionStr	.= " and (activity.activity_start_date <=".time()." and activity.activity_end_date >= ".time()." and activity.activity_state =1)";
		}
		//即将开始的活动
		if($condition['be_to_on']){
			$conditionStr	.= " and (activity.activity_start_date > ".time()." and activity.activity_state =1)";
		}
		// 时间段检索
        if ($condition['pdates']) {
            $conditionStr .= " and ({$condition['pdates']})";
        }

        return $conditionStr;
	}
}