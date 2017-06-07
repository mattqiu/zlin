<?php
/**
 * 公众号管理
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
class weixin_wxuserModel extends Model {

    public function __construct(){
        parent::__construct('weixin_wxuser');
    }
    
    /**
     * 公众号详细信息
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getWXUserInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }

    /**
     * 公众号列表
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getWXUserList($condition = array(), $field = '*', $page = 0, $order = 'id desc') {
       return $this->where($condition)->page($page)->order($order)->select();
    }

    /**
     * 公众号数量
     * @param array $condition
     * @return int
     */
    public function getWXUserCount($condition) {
        return $this->where($condition)->count();
    }
	/**
	 * 添加公众号信息
	 *
	 * @param	array $param 更改信息
	 * @param	int $member_id 公众号条件 id
	 * @return	array 数组格式的返回结果
	 */
	public function addWXUser($param) {
		if(empty($param)) {
			return false;
		}
        $wxuser_info	= array();
		$wxuser_info['routerid'] = '';
		
		$wxuser_info['uid'] = $param['uid'];		
        $wxuser_info['wxname'] = $param['wxname'];
		$wxuser_info['winxintype'] = $param['winxintype'];
		$wxuser_info['appid'] = $param['appid'];
		$wxuser_info['appsecret'] = $param['appsecret'];
		$wxuser_info['wxid'] = $param['wxid'];
		$wxuser_info['weixin'] = $param['weixin'];		
		$wxuser_info['headerpic'] = $param['headerpic'];
		$wxuser_info['token'] = $param['token'];
		$wxuser_info['province'] = $param['province'];
		$wxuser_info['city'] = $param['city'];
		$wxuser_info['qq'] = $param['qq'];
		$wxuser_info['wxfans'] = $param['wxfans'];
		$wxuser_info['typeid'] = $param['typeid'];
		$wxuser_info['typename'] = $param['typename'];	
			
		$wxuser_info['tongji'] ='';
		$wxuser_info['allcardnum'] = 0;
		$wxuser_info['cardisok'] = 0;
		$wxuser_info['yetcardnum'] = 0;
		$wxuser_info['totalcardnum'] = 0;
		$wxuser_info['createtime'] =time();
		$wxuser_info['tpltypeid'] ='1';
		$wxuser_info['updatetime'] =time();
		$wxuser_info['tpltypename'] ='ty_index';
		$wxuser_info['tpllistid'] ='1';
		$wxuser_info['tpllistname'] ='yl_list';
		$wxuser_info['tplcontentid'] ='1';
		$wxuser_info['tplcontentname'] ='ktv_content';
		$wxuser_info['transfer_customer_service'] =0;
		$wxuser_info['color_id'] =0;		
		
		$result	= Db::insert('weixin_wxuser',$wxuser_info);
		if($result) {
			return Db::getLastId();
		} else {
			return false;
		}
	}
	/**
	 * 更新公众号信息
	 *
	 * @param	array $param 更改信息
	 * @param	int $member_id 公众号条件 id
	 * @return	array 数组格式的返回结果
	 */
	public function updateWXUser($param,$id) {
		if(empty($param)) {
			return false;
		}
		$update		= false;
		//得到条件语句
		$condition_str	= " id='{$id}' ";
		$update		= Db::update('weixin_wxuser',$param,$condition_str);
		return $update;
	}
	/**
	 * 删除公众号
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function delWXUser($id){
		if (intval($id) > 0){
			$where = " id = '". intval($id) ."'";
			$result = Db::delete('weixin_wxuser',$where);
			return $result;
		}else {
			return false;
		}
	}
}
