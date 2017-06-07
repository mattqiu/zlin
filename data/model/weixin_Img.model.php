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
class weixin_ImgModel extends Model {

    public function __construct(){
        parent::__construct('weixin_img');
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
		 $data['usort']      = 1;
	}
}
