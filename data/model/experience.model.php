<?php
/**
 * 经验值及经验值日志管理
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

class experienceModel {
	/**
	 * 操作经验值
	 * @param  string $stage 操作阶段 regist(注册),login(登录),comments(评论),order(下单),system(系统),other(其他),pointorder(经验值礼品兑换),app(同步经验值兑换)
	 * @param  array $insertarr 该数组可能包含信息 array('pl_memberid'=>'会员编号','pl_membername'=>'会员名称','pl_adminid'=>'管理员编号','pl_adminname'=>'管理员名称','pl_experience'=>'经验值','pl_desc'=>'描述','orderprice'=>'订单金额','order_sn'=>'订单编号','order_id'=>'订单序号','point_ordersn'=>'经验值兑换订单编号');
	 * @param  bool $if_repeat 是否可以重复记录的信息,true可以重复记录，false不可以重复记录，默认为true
	 * @return bool
	 */
	function saveExperienceLog($stage,$insertarr,$if_repeat = true){
		if (!$insertarr['pl_memberid']){
			return false;
		}
		//记录原因文字
		switch ($stage){
			case 'regist':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('pointsregistdesc');
				}
				$insertarr['pl_experience'] = intval($GLOBALS['setting_config']['experience_reg']);
				break;
			case 'login':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('pointslogindesc');
				}
				$insertarr['pl_experience'] = intval($GLOBALS['setting_config']['experience_login']);
				break;
			case 'comments':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('pointscommentsdesc');
				}
				$insertarr['pl_experience'] = intval($GLOBALS['setting_config']['experience_comments']);
				break;
			case 'order':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('pointsorderdesc_1').$insertarr['order_sn'].Language::get('pointsorderdesc');
				}
				$insertarr['pl_experience'] = 0;
				if ($insertarr['orderprice']){
					$insertarr['pl_experience'] = @intval($insertarr['orderprice']/$GLOBALS['setting_config']['experience_orderrate']);
					if ($insertarr['pl_experience'] > intval($GLOBALS['setting_config']['experience_ordermax'])){
						$insertarr['pl_experience'] = intval($GLOBALS['setting_config']['experience_ordermax']);
					}
				}
				break;
			case 'system':
				break;
			case 'pointorder':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('points_pointorderdesc_1').$insertarr['point_ordersn'].Language::get('points_pointorderdesc');
				}
				break;
            case 'app':
				if (!$insertarr['pl_desc']){
					$insertarr['pl_desc'] = Language::get('points_pointorderdesc_app');
				}
				break;
			case 'other':
				break;
		}
		$save_sign = true;
		if ($if_repeat == false){
			//检测是否有相关信息存在，如果没有，入库
			$condition['pl_memberid'] = $insertarr['pl_memberid'];
			$condition['pl_stage'] = $stage;
			$log_array = self::getexperienceInfo($condition,$page);
			if (!empty($log_array)){
				$save_sign = false;
			}
		}
		if ($save_sign == false){
			return true;
		}
		//新增日志
		$value_array = array();
		$value_array['pl_memberid'] = $insertarr['pl_memberid'];
		$value_array['pl_membername'] = $insertarr['pl_membername'];
		if ($insertarr['pl_adminid']){
			$value_array['pl_adminid'] = $insertarr['pl_adminid'];
		}
		if ($insertarr['pl_adminname']){
			$value_array['pl_adminname'] = $insertarr['pl_adminname'];
		}
		$value_array['pl_experience'] = $insertarr['pl_experience'];
		$value_array['pl_addtime'] = time();
		$value_array['pl_desc'] = $insertarr['pl_desc'];
		$value_array['pl_stage'] = $stage;
		$result = false;
		if($value_array['pl_experience'] != '0'){
			$result = self::addExperienceLog($value_array);
		}
		if ($result){
			//更新member内容
			$obj_member = Model('member');
			$upmember_array = array();
			$upmember_array['member_exppoints'] = array('exp','member_exppoints + '.$insertarr['pl_experience']);
			$obj_member->editMember(array('member_id'=>$insertarr['pl_memberid']),$upmember_array);
			return true;
		}else {
			return false;
		}

	}
	/**
	 * 添加经验值日志信息
	 *
	 * @param array $param 添加信息数组
	 */
	public function addExperienceLog($param) {
		if(empty($param)) {
			return false;
		}
		$result	= Db::insert('experience_log',$param);
		return $result;
	}
	/**
	 * 经验值日志列表
	 *
	 * @param array $condition 条件数组
	 * @param array $page   分页
	 * @param array $field   查询字段
	 * @param array $page   分页
	 */
	public function getExperienceLogList($condition,$page='',$field='*'){
		$condition_str	= $this->getCondition($condition);
		$param	= array();
		$param['table']	= 'experience_log';
		$param['where']	= $condition_str;
		$param['field'] = $field;
		$param['order'] = $condition['order'] ? $condition['order'] : 'experience_log.pl_id desc';
		$param['limit'] = $condition['limit'];
		$param['group'] = $condition['group'];
		return Db::select($param,$page);
	}
	/**
	 * 经验值日志详细信息
	 *
	 * @param array $condition 条件数组
	 * @param array $field   查询字段
	 */
	public function getExperienceInfo($condition,$field='*'){
		//得到条件语句
		$condition_str	= $this->getCondition($condition);
		$array			= array();
		$array['table']	= 'experience_log';
		$array['where']	= $condition_str;
		$array['field']	= $field;
		$list		= Db::select($array);
		return $list[0];
	}
	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $condition_array
	 * @return	string
	 */
	private function getCondition($condition_array){
		$condition_sql = '';
		//经验值日志会员编号
		if ($condition_array['pl_memberid']) {
			$condition_sql	.= " and `experience_log`.pl_memberid = '{$condition_array['pl_memberid']}'";
		}
		//操作阶段
		if ($condition_array['pl_stage']) {
			$condition_sql	.= " and `experience_log`.pl_stage = '{$condition_array['pl_stage']}'";
		}
		//会员名称
		if ($condition_array['pl_membername_like']) {
			$condition_sql	.= " and `experience_log`.pl_membername like '%{$condition_array['pl_membername_like']}%'";
		}
		//管理员名称
		if ($condition_array['pl_adminname_like']) {
			$condition_sql	.= " and `experience_log`.pl_adminname like '%{$condition_array['pl_adminname_like']}%'";
		}
		//添加时间
		if ($condition_array['saddtime']){
			$condition_sql	.= " and `experience_log`.pl_addtime >= '{$condition_array['saddtime']}'";
		}
		if ($condition_array['eaddtime']){
			$condition_sql	.= " and `experience_log`.pl_addtime <= '{$condition_array['eaddtime']}'";
		}
		//描述
		if ($condition_array['pl_desc_like']){
			$condition_sql	.= " and `experience_log`.pl_desc like '%{$condition_array['pl_desc_like']}%'";
		}
		return $condition_sql;
	}
}