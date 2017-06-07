<?php
/**
 * 微信用户管理
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
class weixin_usersModel extends Model {

    public function __construct(){
        parent::__construct('weixin_users');	
    }
    
    /**
     * 会员详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getUsersInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }
	/**
     * 会员详细信息（包括组名)
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getUsersNameAndGName($uid) {	
	    $condition = array();			
	    $condition['member.member_id'] = array('eq', intval($uid));		
		$memberinfo = Model()->field('member.member_name, weixin_usersgroup.name as groupname')->table('member,weixin_users,weixin_usersgroup')->join('left join')->on('member.member_id=weixin_users.member_id,weixin_users.gid=weixin_usersgroup.id')->where($condition)->select();
		if (!empty($memberinfo) && is_array($memberinfo)){
          return $memberinfo[0];
		}else{
		  return array();	
		}
    }
	/**
     * 会员详细信息（包括组名)
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberAndUsersInfo($uid) {
		
	    $condition = array();			
	    $condition['member.member_id'] = array('eq', intval($uid));		
		$memberinfo = Model()->field('member.*,weixin_users.gid,weixin_users.status,weixin_users.viptime,weixin_users.wechat_card_num,weixin_users.connectnum,weixin_users.smscount,weixin_users.diynum,weixin_users.activitynum,weixin_users.card_num,weixin_users.serviceUserNum,weixin_users.attachmentsize,weixin_usersgroup.name as groupname')->table('member,weixin_users,weixin_usersgroup')->join('left join')->on('member.member_id=weixin_users.member_id,weixin_users.gid=weixin_usersgroup.id')->where($condition)->select();
		if (!empty($memberinfo) && is_array($memberinfo)){
          return $memberinfo[0];
		}else{
		  return array();	
		}
    }
    /**
     * 会员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getUsersList($condition = array(), $field = '*', $page = 0, $order = 'id desc') {
       return $this->where($condition)->page($page)->order($order)->select();
    }
	/**
     * 会员信息列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getUsersInfoList($condition = array(), $field = '*', $page = 0, $order = 'member_id desc') {
	   return Model()->field('member.*,weixin_users.gid,weixin_users.status,weixin_users.viptime,weixin_users.wechat_card_num,weixin_users.connectnum,weixin_users.smscount,weixin_users.diynum,weixin_users.activitynum,weixin_users.card_num,weixin_users.serviceUserNum,weixin_users.attachmentsize,weixin_usersgroup.name as groupname')->table('member,weixin_users,weixin_usersgroup')->join('left join')->on('member.member_id=weixin_users.member_id,weixin_users.gid=weixin_usersgroup.id')->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 会员数量
     * @param array $condition
     * @return int
     */
    public function getUsersCount($condition) {
        return $this->where($condition)->count();
    }

    /**
     * 登录时创建会话SESSION
     *
     * @param array $member_info 会员信息
     */
    public function createSession($users_info = array()) {
        if (empty($users_info) || !is_array($users_info)) return ;		

		$_SESSION['uid'] = $users_info['member_id'];		
		$_SESSION['gid'] = $users_info['gid'];
		$_SESSION['diynum'] = $users_info['diynum'];
		$_SESSION['connectnum'] = $users_info['connectnum'];
		$_SESSION['activitynum'] = $users_info['activitynum'];
		$_SESSION['viptime'] = $users_info['viptime'];
		$_SESSION['gname'] = $users_info['group_name'];		
		
		//每个月第一次登陆数据清零
		$now=time();
		$month=date('m',$now);
		if($month!=$users_info['lastloginmonth']&&$users_info['lastloginmonth']!=0){
			$update_info	= array(
    		'diynum'=> 0,
    		'connectnum'=> 0,
    		'activitynum'=> 0);
    		$this->updateUsers($update_info,$users_info['member_id']);
			//
			$_SESSION['diynum'] =0;
			$_SESSION['connectnum'] =0;
			$_SESSION['activitynum'] =0;
		}
    }

    /**
     * 注册
     */
    public function register($register_info) {
        // 验证用户名是否重复
		$check_member_name	= $this->infoUsers(array('member_id'=>trim($register_info['member_id'])));
		if(is_array($check_member_name) and count($check_member_name) > 0) {
            return array('error' => '用户名已存在');
		}
		// 会员添加
		$users_info	= array();
		$users_info['member_id']    = $register_info['member_id'];
		$users_info['gid']	        = $register_info['gid'];
		$users_info['status']		= $register_info['status'];
		$users_info['viptime']		= $register_info['viptime'];
		$insert_id	= $this->addUsers($users_info);
		if($insert_id) {
            $users_info['id'] = $insert_id;

            return $member_info;
		} else {
            return array('error' => '注册失败');
		}

    }

	/**
	 * 注册商城会员
	 *
	 * @param	array $param 会员信息
	 * @return	array 数组格式的返回结果
	 */
	public function addUsers($param) {
		if(empty($param)) {
			return false;
		}
		$users_info	= array();
		$users_info['member_id']			= $param['member_id'];
		$users_info['gid']			        = $param['gid'];
		$users_info['status']		        = $param['status'];
		$users_info['viptime']		        = $param['viptime'];
		
		$users_info['wechat_card_num']		= 0;
		$users_info['connectnum']		    = 0;
		$users_info['smscount']		    = 0;
		$users_info['diynum']		        = 0;
		$users_info['activitynum']		    = 0;
		$users_info['card_num']		    = 0;
		$users_info['serviceUserNum']		= 0;
		$users_info['attachmentsize']		= 0;
		$users_info['card_create_status']	= 0;
		$users_info['spend']		        = 0;
	
		$result	= Db::insert('weixin_users',$users_info);
		if($result) {
			return Db::getLastId();
		} else {
			return false;
		}
	}
	/**
	 * 获取会员信息
	 *
	 * @param	array $param 会员条件
	 * @param	string $field 显示字段
	 * @return	array 数组格式的返回结果
	 */
	public function infoUsers($param, $field='*') {
		if (empty($param)) return false;

		//得到条件语句
		$condition_str	= $this->getCondition($param);
		$param	= array();
		$param['table']	= 'weixin_users';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['limit'] = 1;
		$users_list	= Db::select($param);
		$users_info	= $users_list[0];
		if (intval($users_info['gid']) > 0){
	      $param	= array();
	      $param['table']	= 'weixin_usersgroup';
	      $param['field']	= 'id';
	      $param['value']	= $users_info['gid'];
	      $field	= 'id,name';
	      $usersgroup_info	= Db::getRow($param,$field);
	      if (!empty($usersgroup_info) && is_array($usersgroup_info)){
		      $users_info['group_name']	= $usersgroup_info['name'];
	      }
		}
		return $users_info;
	}

	/**
	 * 更新会员信息
	 *
	 * @param	array $param 更改信息
	 * @param	int $member_id 会员条件 id
	 * @return	array 数组格式的返回结果
	 */
	public function updateUsers($param,$member_id) {
		if(empty($param)) {
			return false;
		}
		$update		= false;
		//得到条件语句
		$condition_str	= " member_id='{$member_id}' ";
		$update		= Db::update('weixin_users',$param,$condition_str);
		return $update;
	}
	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $conditon_array
	 * @return	string
	 */
	private function getCondition($conditon_array){
		$condition_sql = '';
		if($conditon_array['id'] != '') {
			$condition_sql	.= " and id= '" .intval($conditon_array['id']). "'";
		}
		if($conditon_array['member_id'] != '') {
			$condition_sql	.= " and member_id= '" .intval($conditon_array['member_id']). "'";
		}
		if($conditon_array['gid'] != '') {
			$condition_sql	.= " and gid= '" .intval($conditon_array['gid']). "'";
		}
        if($conditon_array['no_id'] != '') {
			$condition_sql	.= " and id != '".$conditon_array['no_id']."'";
		}
		if($conditon_array['no_member_id'] != '') {
			$condition_sql	.= " and member_id != '".$conditon_array['no_member_id']."'";
		}
        if($conditon_array['no_gid'] != '') {
			$condition_sql	.= " and gid != '".$conditon_array['no_gid']."'";
		}		
		return $condition_sql;
	}
	
	/**
	 * 删除会员
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function del($id){
		if (intval($id) > 0){
			$where = " member_id = '". intval($id) ."'";
			$result = Db::delete('weixin_users',$where);
			return $result;
		}else {
			return false;
		}
	}
	/**
	 * 审核开通会员
	 *
	 */
	public function ActiveUsers($member_id) {
        
		$condition_str	= " member_id='{$member_id}' ";
        return Db::update('weixin_users',array('status'=>1), $condition_str);
	}
	/**
	 * 查询会员总数
	 */
	public function countUsers($condition){
		//得到条件语句
		$condition_str	= $this->getCondition($condition);
		$count = Db::getCount('weixin_users',$condition_str);
		return $count;
	}
}