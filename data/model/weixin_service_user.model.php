<?php
/**
 * 人工客服
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

class weixin_service_userModel extends Model {

    public function __construct(){
        parent::__construct('weixin_service_user');
    }
    /**
	 * 插入默认值数据
	 *
	 * @param array $data
	 * @param array $options
	 * @return mixed int/false
	 */
    protected function _auto_insert_data(&$data,$options) {
        $data['token']      = $_SESSION['token'];	
		$data['userPwd']       = $this->userPwd();
		$data['endJoinDate']    = time();	 
    }
	
	function userPwd(){	
		return md5($_POST['userPwd']);
	}
	
	function getServiceUser($id){
		$where['token']=$_SESSION['token'];
		$where['id']=$id;
		$data=Model('weixin_Service_user')->where($where)->find();
		return $data['name'];
	
	}
}
