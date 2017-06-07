<?php
/**
 * 会员管理
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
class memberModel extends Model {

    public function __construct(){
        parent::__construct('member');
    }
    
    /**
     * 会员详细信息（查库）
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getMemberInfo($condition, $field = '*', $master = false) {
        return $this->table('member')->field($field)->where($condition)->master($master)->find();
    }
	
	/**
     * 取得会员详细信息（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getMemberInfoByID($member_id, $fields = '*') {
        $member_info = rcache($member_id, 'member', $fields);
        if (empty($member_info)) {
            $member_info = $this->getMemberInfo(array('member_id'=>$member_id),$fields,true);
            wcache($member_id, $member_info, 'member');
        }
        return $member_info;
    }		

	/**
     * 取得会员类型（优先查询缓存）
     * 如果未找到，则缓存所有字段
     * @param int $member_id
     * @param string $field 需要取得的缓存键值, 例如：'*','member_name,member_sex'
     * @return array
     */
    public function getMemberTypeByID($member_id) {
        $member_info = rcache($member_id, 'member', 'mc_id');
        if (empty($member_info)) {
            $member_info = $this->getMemberInfo(array('member_id'=>$member_id),'*',true);
            wcache($member_id, $member_info, 'member');
        }
        return $member_info['mc_id'];
    }
	
	/**
     * 取得会员帐户可用余额
     */
    public function getMemberPredepositByID($member_id) {
        $member_info = $this->getMemberInfo(array('member_id'=>$member_id),'available_predeposit',true);
        return $member_info['available_predeposit'];
    }

    /**
     * 会员列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getMemberList($condition = array(), $field = '*', $page = null, $order = 'member_id desc', $limit = '') {
       return $this->table('member')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }

    /**
     * 会员数量
     * @param array $condition
     * @return int
     */
    public function getMemberCount($condition) {
        return $this->table('member')->where($condition)->count();
    }
	
	/**
     * 编辑会员
     * @param array $condition
     * @param array $data
     */
    public function editMember($condition, $data) {
        $update = $this->table('member')->where($condition)->update($data);
        if ($update && $condition['member_id']) {
            dcache($condition['member_id'], 'member');
        }
        return $update;
    }

    /**
     * 登录时创建会话SESSION
     *
     * @param array $member_info 会员信息
     */
    public function createSession($member_info = array(),$reg = false) {
        if (empty($member_info) || !is_array($member_info)) return ;
		$_SESSION['is_login']	= '1';
		$_SESSION['member_id']	= $member_info['member_id'];
		$_SESSION['member_name']= $member_info['member_name'];
		$_SESSION['member_email']= $member_info['member_email'];
		$_SESSION['is_buy']		= isset($member_info['is_buy']) ? $member_info['is_buy'] : 1;
		$_SESSION['avatar'] 	= $member_info['member_avatar'];		
		
		//微信处理
		if (OPEN_MODULE_WEIXIN_STATE == 1){
		    $_SESSION['weixin_active'] 	= $member_info['weixin_active'];
		}
		
		$_SESSION['M_grade_level'] 	= $this->getOneMemberGradeLevel($member_info['member_exppoints']);//会员等级
		//推广员、导购员处理
		if (OPEN_STORE_EXTENSION_STATE > 0){
		    $_SESSION['M_mc_id'] 	    = $member_info['mc_id'];//会员类型
		    $_SESSION['M_store_id'] 	= $member_info['store_id'];//会员所在的推广店铺ID
			//缓存推广员、导购员ID
			if ($member_info['mc_id'] ==1 || $member_info['mc_id']==2){
                $extension=urlsafe_b64encode($member_info['member_id']);
                setIMCookie('iMall_extension',$extension,30*24*60*60);
			}
		}	
		
		//会员快捷菜单
		if(!empty($member_info['member_quicklink'])&&$member_info['member_quicklink']!=='') {
          $quicklink_array = explode(',', $member_info['member_quicklink']);
          foreach ($quicklink_array as $value) {
            $_SESSION['member_quicklink'][$value] = $value ;
          }
        }	
		//会员 卖家ID			
		$seller_info = Model('seller')->getSellerInfo(array('member_id'=>$_SESSION['member_id']));
		$_SESSION['store_id'] = $seller_info['store_id'];//会员自己的店铺ID
		//会员的QQ互联ID
		if (trim($member_info['member_qqopenid'])){
			$_SESSION['openid']		= $member_info['member_qqopenid'];
		}
		//会员的新浪互联ID
		if (trim($member_info['member_sinaopenid'])){
			$_SESSION['slast_key']['uid'] = $member_info['member_sinaopenid'];
		}
		//如果不是注册操作则增加云币和经验值
		if (!$reg) {
		    //添加会员云币
		    $this->addPoint($member_info);
		    //添加会员经验值
		    $this->addExppoint($member_info);		    
		}
		//如果不是注册操作则修改会员登录时间及地点
		if(!empty($member_info['member_login_time'])) {
            $update_info	= array(
                'member_login_num'=> ($member_info['member_login_num']+1),
                'member_login_time'=> TIMESTAMP,
                'member_old_login_time'=> $member_info['member_login_time'],
                'member_login_ip'=> getIp(),
                'member_old_login_ip'=> $member_info['member_login_ip'],
				'weixin_login_month'=> date('m',time())
            );
            $this->editMember(array('member_id'=>$member_info['member_id']),$update_info);
		}
		//清空会员购物车数量
		setIMCookie('cart_goods_num','',-3600);		
    }
	
	/**
	 * 获取会员信息
	 *
	 * @param	array $param 会员条件
	 * @param	string $field 显示字段
	 * @return	array 数组格式的返回结果
	 */
	public function infoMember($param, $field='*') {
		if (empty($param)) return false;

		//得到条件语句
		$condition_str	= $this->getCondition($param);
		$param	= array();
		$param['table']	= 'member';
		$param['where']	= $condition_str;
		$param['field']	= $field;
		$param['limit'] = 1;
		$member_list	= Db::select($param);
		$member_info	= $member_list[0];
		if (intval($member_info['store_id']) > 0){
	      $param	= array();
	      $param['table']	= 'store';
	      $param['field']	= 'store_id';
	      $param['value']	= $member_info['store_id'];
	      $field	= 'store_id,store_name,grade_id';
	      $store_info	= Db::getRow($param,$field);
	      if (!empty($store_info) && is_array($store_info)){
		      $member_info['store_name']	= $store_info['store_name'];
		      $member_info['grade_id']	= $store_info['grade_id'];
	      }
		}
		return $member_info;
	}

    /**
     * 注册
     */
    public function register($register_info, $addVRccard = 1) {
		// 注册验证
		$obj_validate = new Validate();
		$obj_validate->validateparam = array(
		    array("input"=>$register_info["member_name"],		"require"=>"true",		"message"=>'用户名不能为空'),
		    array("input"=>$register_info["member_passwd"],		"require"=>"true",		"message"=>'密码不能为空'),
		    array("input"=>$register_info["password_confirm"],"require"=>"true",	"validator"=>"Compare","operator"=>"==","to"=>$register_info["member_passwd"],"message"=>'密码与确认密码不相同'),
		    //array("input"=>$register_info["member_email"],			"require"=>"true",		"validator"=>"email", "message"=>'电子邮件格式不正确'),
		);
		$error = $obj_validate->validate();
		if ($error != ''){
            return array('error' => $error);
		}
		// 验证用户名是否重复
		$check_member_name	= $this->getMemberInfo(array('member_name'=>$register_info['member_name']));
		if(is_array($check_member_name) and count($check_member_name) > 0) {
            return array('error' => '用户名已存在');
		}
		// 验证手机是否重复
        if (isset($register_info['member_mobile'])){
			if (!CheckMobileValidator(trim($register_info['member_mobile']))){
				return array('error' => '手机号填写不正确');
			}
			$check_member_mobile	= $this->getMemberInfo(array('member_mobile'=>$register_info['member_mobile']));
		    if(is_array($check_member_mobile) and count($check_member_mobile)>0) {
                return array('error' => '手机已注册');
		    }
			$register_info['member_mobile_bind'] = 1;
		}
        // 验证邮箱是否重复
		if (!empty($register_info['member_email'])){
		    $check_member_email	= $this->getMemberInfo(array('member_email'=>$register_info['member_email']));
		    if(is_array($check_member_email) and count($check_member_email)>0) {
                return array('error' => '邮箱已存在');
		    }
		}

		// 会员添加
		$member_info	= $register_info;

		$insert_id	= $this->addMember($member_info,$addVRccard);
		if($insert_id) {
            $member_info['member_id'] = $insert_id;
            $member_info['is_buy'] = 1;

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
	public function addMember(&$param, $addVRccard = 1) {
		if(empty($param)) {
			return false;
		}
		try {		
		    $this->beginTransaction();
			
		    $member_info	= array();
		    $member_info['member_id']			= $param['member_id'];
		    $member_info['member_name']			= $param['member_name'];
		    $member_info['member_passwd']		= md5(trim($param['member_passwd']));
		    $member_info['member_email']		= $param['member_email'];
			$member_info['member_mobile']	    = isset($param['member_mobile'])?$param['member_mobile']:'';
			$member_info['member_mobile_bind']	= isset($param['member_mobile_bind'])?$param['member_mobile_bind']:0;

		    $member_info['member_time']			= TIMESTAMP;
		    $member_info['member_login_time'] 	= TIMESTAMP;
		    $member_info['member_old_login_time'] = TIMESTAMP;
		    $member_info['member_login_ip']		= getIp();
		    $member_info['member_old_login_ip']	= $member_info['member_login_ip'];

		    $member_info['member_truename']		= $param['member_truename'];
		    $member_info['member_qq']			= $param['member_qq'];
		    $member_info['member_sex']			= $param['member_sex'];
		    $member_info['member_avatar']		= $param['member_avatar'];
		    $member_info['member_qqopenid']		= $param['member_qqopenid'];
		    $member_info['member_qqinfo']		= $param['member_qqinfo'];
			$member_info['member_wxopenid']		= $param['member_wxopenid'];
		    $member_info['member_wxinfo']		= $param['member_wxinfo'];			
			$member_info['member_tbopenid']		= $param['member_tbopenid'];
			$member_info['member_tbaccount']	= $param['member_tbaccount'];
		    $member_info['member_tbinfo']		= $param['member_tbinfo'];			
		    $member_info['member_sinaopenid']	= $param['member_sinaopenid'];
		    $member_info['member_sinainfo']	    = $param['member_sinainfo'];			
			
			//判断是否是推广链接
			$extension_id=cookie('iMall_extension');
			if(!empty($param['parent_id'])){
				$member_info['parent_id']			= $param['parent_id'];
			}elseif (!empty($extension_id)){
				$member_info['weixin_invitecode']	= $extension_id;
				$extension_id=urlsafe_b64decode($extension_id);
				$member_info['parent_id']	   	 = $extension_id;
			}
			//会员信息-微信处理
			if (OPEN_MODULE_WEIXIN_STATE == 1){
		        $member_info['weixin_active']	    = isset($param['weixin_active'])?$param['weixin_active']:0;		        
		        $member_info['weixin_reg_ip']	    = getIp();
		        $member_info['weixin_login_month']  = isset($param['weixin_login_month'])?$param['weixin_login_month']:0;
		        $member_info['weixin_money']	    = isset($param['weixin_money'])?$param['weixin_money']:0;
		        $member_info['weixin_invitecode']   = isset($param['weixin_invitecode'])?$param['weixin_invitecode']:''; 
		        $member_info['weixin_inviters']     = isset($param['weixin_inviters'])?$param['weixin_inviters']:0;	
			}	

			//会员信息-推广员、导购员处理
			if (OPEN_STORE_EXTENSION_STATE > 0){
		        $member_info['mc_id']	            = isset($param['mc_id'])?$param['mc_id']:0; //会员类型:0表示普通会员,1表示导购员,2表示推广员
		        $member_info['store_id']	        = isset($param['store_id'])?$param['store_id']:0;
				if (OPEN_STORE_EXTENSION_STATE == 10 && $addVRccard == 1){
					$member_info['store_id'] = GENERAL_PLATFORM_EXTENSION_ID;
				}
			}		    
						
		    $insert_id	= $this->table('member')->insert($member_info);			
		    if (!$insert_id) {
		        throw new Exception();
		    }
		    $insert = $this->addMemberCommon(array('member_id'=>$insert_id));			
		    if (!$insert) {
		        throw new Exception();
		    }	
			
			//会员信息-添加推广员
			if (OPEN_STORE_EXTENSION_STATE > 0 && $addVRccard == 1){
				Model('extension')->addVirtualExtension(array('member_id'=>$insert_id,'member_name'=>$member_info['member_name'],'store_id'=>$member_info['store_id'],'parent_id'=>$member_info['parent_id']));
			}

            // 添加默认相册
			$insert = array();
            $insert['ac_name']      = '买家秀';
            $insert['member_id']    = $insert_id;
            $insert['ac_des']       = '买家秀默认相册';
            $insert['ac_sort']      = 1;
            $insert['is_default']   = 1;
            $insert['upload_time']  = TIMESTAMP;
            $this->table('sns_albumclass')->insert($insert);
			
			//添加会员云币
			if (C('points_isuse')){
				Model('points')->savePointsLog('regist',array('pl_memberid'=>$insert_id,'pl_membername'=>$register_info['username']),false);
			}
			//添加会员经验值
			if ($GLOBALS['setting_config']['experience_isuse'] == 1){
				$experience_model = Model('experience');
				$experience_model->saveExperienceLog('regist',array('pl_memberid'=>$insert_id,'pl_membername'=>$register_info['username']),false);
			}
				
		    $this->commit();
		    return $insert_id;
		} catch (Exception $e) {
		    $this->rollback();
		    return false;
		}
	}
	
	/**
	 * 会员登录检查
	 *
	 */
	public function checkloginMember() {
		if($_SESSION['is_login'] == '1') {
			@header("Location: index.php");
			exit();
		}
	}

    /**
	 * 检查会员是否允许举报商品
	 *
	 */
	public function isMemberAllowInform($member_id) {        
        $condition = array();
        $condition['member_id'] = $member_id;
        $member_info = $this->getMemberInfo($condition,'inform_allow');
        if(intval($member_info['inform_allow']) === 1) {
            return true;
        }
        else {
            return false;
        }
	}
	/**
	 * 审核开通会员
	 *
	 */
	public function ActiveMember($member_id) {
		
        $condition_str	= " member_id='{$member_id}' ";
        return Db::update('member',array('member_state'=>1), $condition_str);
	}
	
	/**
	 * 取单条信息
	 * @param unknown $condition
	 * @param string $fields
	 */
	public function getMemberCommonInfo($condition = array(), $fields = '*') {
	    return $this->table('member_common')->where($condition)->field($fields)->find();
	}

	/**
	 * 插入扩展表信息
	 * @param unknown $data
	 * @return Ambigous <mixed, boolean, number, unknown, resource>
	 */
	public function addMemberCommon($data) {
	    return $this->table('member_common')->insert($data);
	}

	/**
	 * 编辑会员扩展表
	 * @param unknown $data
	 * @param unknown $condition
	 * @return Ambigous <mixed, boolean, number, unknown, resource>
	 */
	public function editMemberCommon($data,$condition) {
		if (!empty($condition['member_id'])){
			$info = $this->getMemberCommonInfo(array('member_id'=>$condition['member_id']));
			if (empty($info)){
				$this->addMemberCommon(array('member_id'=>$condition['member_id']));	
			}
		}
        return $this->table('member_common')->where($condition)->update($data);
	}

	/**
	 * 添加会员云币
	 * @param unknown $member_info
	 */
	public function addPoint($member_info) {
	    if (!C('points_isuse') || empty($member_info)) return;
	
	    //一天内只有第一次登录赠送云币
	    if(trim(@date('Y-m-d',$member_info['member_login_time'])) == trim(date('Y-m-d'))) return;
		
	    //加入队列
	    $queue_content = array();
	    $queue_content['member_id'] = $member_info['member_id'];
	    $queue_content['member_name'] = $member_info['member_name'];
	    QueueClient::push('addPoint',$queue_content);
	}

	/**
	 * 添加会员经验值
	 * @param unknown $member_info
	 */
	public function addExppoint($member_info) {
	    if (empty($member_info)) return;

	    //一天内只有第一次登录赠送经验值
	    if(trim(@date('Y-m-d',$member_info['member_login_time'])) == trim(date('Y-m-d'))) return;
	
	    //加入队列
	    $queue_content = array();
	    $queue_content['member_id'] = $member_info['member_id'];
	    $queue_content['member_name'] = $member_info['member_name'];
	    QueueClient::push('addExppoint',$queue_content);
	}

	/**
	 * 取得会员安全级别
	 * @param unknown $member_info
	 */
	public function getMemberSecurityLevel($member_info = array()) {
	    $tmp_level = 0;
	    if ($member_info['member_email_bind'] == '1') {
	        $tmp_level += 1;
	    }
	    if ($member_info['member_mobile_bind'] == '1') {
	        $tmp_level += 1;
	    }
	    if ($member_info['member_paypwd'] != '') {
	        $tmp_level += 1;
	    }
	    return $tmp_level;
	}

	/**
	 * 获得会员等级
	 * @param bool $show_progress 是否计算其当前等级进度
	 * @param int $exppoints  会员经验值
	 * @param array $cur_level 会员当前等级
	 */
	public function getMemberGradeArr($show_progress = false,$exppoints = 0,$cur_level = ''){
	    $member_grade = C('member_grade')?unserialize(C('member_grade')):array();
	    //处理会员等级进度
	    if ($member_grade && $show_progress){
	        $is_max = false;
	        if ($cur_level === ''){
	            $cur_gradearr = $this->getOneMemberGrade($exppoints, false, $member_grade);
	            $cur_level = $cur_gradearr['level'];
	        }
	        foreach ($member_grade as $k=>$v){
	            if ($cur_level == $v['level']){
	                $v['is_cur'] = true;
	            }
	            $member_grade[$k] = $v;
	        }
	    }
	    return $member_grade;
	}
	
	/**
	 * 获得某一会员等级
	 * @param int $exppoints
	 * @param bool $show_progress 是否计算其当前等级进度
	 * @param array $member_grade 会员等级
	 */
	public function getOneMemberGrade($exppoints,$show_progress = false,$member_grade = array()){
	    if (!$member_grade){
	        $member_grade = C('member_grade')?unserialize(C('member_grade')):array();
	    }
	    if (empty($member_grade)){//如果会员等级设置为空
	        $grade_arr['level'] = -1;
	        $grade_arr['level_name'] = '暂无等级';
	        return $grade_arr;
	    }
	    
	    $exppoints = intval($exppoints);
	    
	    $grade_arr = array();
	    if ($member_grade){
		    foreach ($member_grade as $k=>$v){
		        if($exppoints >= $v['exppoints']){
		            $grade_arr = $v;
		        }
			}
		}
		//计算提升进度
		if ($show_progress == true){
		    if (intval($grade_arr['level']) >= (count($member_grade) - 1)){//如果已达到顶级会员
		        $grade_arr['downgrade'] = $grade_arr['level'] - 1;//下一级会员等级
		        $grade_arr['downgrade_name'] = $member_grade[$grade_arr['downgrade']]['level_name'];
		        $grade_arr['downgrade_exppoints'] = $member_grade[$grade_arr['downgrade']]['exppoints'];
		        $grade_arr['upgrade'] = $grade_arr['level'];//上一级会员等级
		        $grade_arr['upgrade_name'] = $member_grade[$grade_arr['upgrade']]['level_name'];
		        $grade_arr['upgrade_exppoints'] = $member_grade[$grade_arr['upgrade']]['exppoints'];
		        $grade_arr['less_exppoints'] = 0;
		        $grade_arr['exppoints_rate'] = 100;
		    } else {
		        $grade_arr['downgrade'] = $grade_arr['level'];//下一级会员等级
		        $grade_arr['downgrade_name'] = $member_grade[$grade_arr['downgrade']]['level_name'];
		        $grade_arr['downgrade_exppoints'] = $member_grade[$grade_arr['downgrade']]['exppoints'];
		        $grade_arr['upgrade'] = $member_grade[$grade_arr['level']+1]['level'];//上一级会员等级
		        $grade_arr['upgrade_name'] = $member_grade[$grade_arr['upgrade']]['level_name'];
		        $grade_arr['upgrade_exppoints'] = $member_grade[$grade_arr['upgrade']]['exppoints'];
		        $grade_arr['less_exppoints'] = $grade_arr['upgrade_exppoints'] - $exppoints;
		        $grade_arr['exppoints_rate'] = round(($exppoints - $member_grade[$grade_arr['level']]['exppoints'])/($grade_arr['upgrade_exppoints'] - $member_grade[$grade_arr['level']]['exppoints'])*100,2);
		    }
		}
		return $grade_arr;
	}
	
	/**
	 * 获得某一会员等级level
	 * @param int $exppoints
	 * @param array $member_grade 会员等级
	 */
	public function getOneMemberGradeLevel($exppoints,$member_grade = array()){
	    if (!$member_grade){
	        $member_grade = C('member_grade')?unserialize(C('member_grade')):array();
	    }
	    if (empty($member_grade)){//如果会员等级设置为空
	        return -1;
	    }
	    
	    $exppoints = intval($exppoints);
	    
	    $grade_level = array();
	    if ($member_grade){
		    foreach ($member_grade as $k=>$v){
		        if($exppoints >= $v['exppoints']){
		            $grade_level = $v['level'];
		        }
			}
		}
		return $grade_level;
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
			$result = Db::delete('member',$where);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
     * 清除会员信息
     * 
     * @param array $condition
     * @return boolean
     */
    public function del_memberinfo($member_id) {
		if (intval($member_id) > 0){
		    $condition = array();
		    $condition['member_id'] = $member_id;
			
			$this->table('member_common')->where($condition)->delete();
			$this->table('extension')->where($condition)->delete();
			$this->table('sns_albumclass')->where($condition)->delete();				
            return $this->table('member')->where($condition)->delete();			
		}else {
			return false;
		}
    }
	
	/**
	 * 查询会员总数
	 */
	public function countMember($condition){
		//得到条件语句
		$condition_str	= $this->getCondition($condition);
		$count = Db::getCount('member',$condition_str);
		return $count;
	}
	
	/**
     * 登录生成token
     */
    public function get_mobile_token($member_id, $member_name, $client) {
        $model_mb_user_token = Model('mb_user_token');

        //重新登录后以前的令牌失效
        //暂时停用 可以控制一个端口登陆
        //$condition = array();
       	//$condition['member_id'] = $member_id;
        //$condition['client_type'] = $_POST['client'];
        //$model_mb_user_token->delMbUserToken($condition);

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] = $client;
		
        //会员登陆的同时也登陆商家
        $model_seller = Model('seller');
        $seller_info = $model_seller->getSellerInfo(array('member_id' => $member_id));
        
        if(!empty($seller_info)){ //判断该会员是否是商家
        	$model_mb_seller_token = Model('mb_seller_token');
        	//重新登录后以前的令牌失效
        	//$sli_condition = array();
        	//$sli_condition['seller_id'] = $seller_info['seller_id'];
        	//$model_mb_seller_token->delSellerToken($sli_condition);
        	
        	$mb_seller_token_info = array();
        	$mb_seller_token_info['seller_id'] = $seller_info['seller_id'];
        	$mb_seller_token_info['seller_name'] = $seller_info['seller_name'];
        	$mb_seller_token_info['token'] = $token;
        	$mb_seller_token_info['login_time'] = TIMESTAMP;
        	$mb_seller_token_info['client_type'] = $client;
        	$res = $model_mb_seller_token->addSellerToken($mb_seller_token_info);
        }
        
        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return $token;
        }
    }
	
	/**
	 * 将条件数组组合为SQL语句的条件部分
	 *
	 * @param	array $conditon_array
	 * @return	string
	 */
	private function getCondition($conditon_array){
		$condition_sql = '';
		if($conditon_array['member_id'] != '') {
			$condition_sql	.= " and member_id= '" .intval($conditon_array['member_id']). "'";
		}
		if($conditon_array['member_name'] != '') {
			$condition_sql	.= " and member_name='".$conditon_array['member_name']."'";
		}
		if($conditon_array['member_passwd'] != '') {
			$condition_sql	.= " and member_passwd='".$conditon_array['member_passwd']."'";
		}
		//是否允许举报
		if($conditon_array['inform_allow'] != '') {
			$condition_sql	.= " and inform_allow='{$conditon_array['inform_allow']}'";
		}
		//是否允许购买
		if($conditon_array['is_buy'] != '') {
			$condition_sql	.= " and is_buy='{$conditon_array['is_buy']}'";
		}
		//是否允许发言
		if($conditon_array['is_allowtalk'] != '') {
			$condition_sql	.= " and is_allowtalk='{$conditon_array['is_allowtalk']}'";
		}
		//是否允许登录
		if($conditon_array['member_state'] != '') {
			$condition_sql	.= " and member_state='{$conditon_array['member_state']}'";
		}
		if($conditon_array['friend_list'] != '') {
			$condition_sql	.= " and member_name IN (".$conditon_array['friend_list'].")";
		}
		if($conditon_array['member_email'] != '') {
			$condition_sql	.= " and member_email='".$conditon_array['member_email']."'";
		}
		if($conditon_array['no_member_id'] != '') {
			$condition_sql	.= " and member_id != '".$conditon_array['no_member_id']."'";
		}
		if($conditon_array['like_member_name'] != '') {
			$condition_sql	.= " and member_name like '%".$conditon_array['like_member_name']."%'";
		}
		if($conditon_array['like_member_email'] != '') {
			$condition_sql	.= " and member_email like '%".$conditon_array['like_member_email']."%'";
		}
		if($conditon_array['like_member_truename'] != '') {
			$condition_sql	.= " and member_truename like '%".$conditon_array['like_member_truename']."%'";
		}
		if($conditon_array['in_member_id'] != '') {
			$condition_sql	.= " and member_id IN (".$conditon_array['in_member_id'].")";
		}
		if($conditon_array['in_member_name'] != '') {
			$condition_sql	.= " and member_name IN (".$conditon_array['in_member_name'].")";
		}
		if($conditon_array['member_qqopenid'] != '') {
			$condition_sql	.= " and member_qqopenid = '{$conditon_array['member_qqopenid']}'";
		}
		if($conditon_array['member_sinaopenid'] != '') {
			$condition_sql	.= " and member_sinaopenid = '{$conditon_array['member_sinaopenid']}'";
		}
		
		return $condition_sql;
	}	
	/*=============================== 会员等级制度设置方案分割线  zhangc ============================================*/
	/**
	 * 会员等级制度设置标准列表
	 * @param unknown $condition
	 * @param string $fields
	 * @param string $pagesize
	 * @param string $order
	 * @param string $limit
	 */
	public function getMemberGradeList($condition = array(), $fields = '*', $pagesize = null, $order = 'mg_id desc', $limit = null) {
		return $this->table('member_upgrade')->where($condition)->field($fields)->order($order)->limit($limit)->page($pagesize)->select();
	}
	/**
	 * 根据店铺ID取得会员等级制度设置标准列表
	 * @param unknown $condition
	 * @param string $fields
	 */
	public function getMemberGradeListByStoreID($store_id = '', $fields = '*') {
	
		$grade_list = array();
		if (empty($store_id) && OPEN_STORE_EXTENSION_STATE == 10){
			$store_id = GENERAL_PLATFORM_EXTENSION_ID;
		}
			$condition = array();
			$condition['store_id'] = $store_id;
			$grade_list = $this->getMemberGradeList($condition,$fields);
		return $grade_list;
	}
	/**
	 * 取得会员等级制度设置标准单条信息
	 * @param unknown $condition
	 * @param string $fields
	 */
	public function getMemberGradeInfo($condition = array(), $fields = '*') {
		$memberGradeInfo = $this->table('member_upgrade')->where($condition)->field($fields)->find();
		if(empty($memberGradeInfo)){
			$condition['store_id'] = GENERAL_PLATFORM_EXTENSION_ID;
			$memberGradeInfo = $this->table('member_upgrade')->where($condition)->field($fields)->find();
		}
		return $memberGradeInfo;
	}
	/**
	 * 根据id取得会员等级制度设置标准单条信息
	 * @param unknown $condition
	 * @param string $fields
	 */
	public function getMemberGradeByID($mg_id = '', $fields = '*') {
		$grade_info = array();
		if (!empty($mg_id)){
			$condition = array();
			$condition['mg_id'] = $mg_id;
			$grade_info = $this->getMemberGradeInfo($condition,$fields);
		}
		return $grade_info;
	}
	/**
	 * 取得会员等级制度设置标准数量
	 * @param unknown $condition
	 */
	public function getMemberGradeCount($condition) {
		return $this->table('member_upgrade')->where($condition)->count();
	}
	/**
	 * 添加会员等级制度设置标准
	 * @param unknown $condition
	 */
	public function addMemberGrade($data) {
		return $this->table('member_upgrade')->insert($data);
	}
	/**
	 * 修改会员等级制度设置标准
	 * @param unknown $condition
	 */
	public function editMemberGrade($data, $condition = array()) {
		return $this->table('member_upgrade')->where($condition)->update($data);
	}
	/**
	 * 删除会员等级制度设置标准
	 * @param unknown $condition
	 */
	public function delMemberGrade($condition = array()) {
		return $this->table('member_upgrade')->where($condition)->delete();
	}
}