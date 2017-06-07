<?php
/**
 * 微活动
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

class weixin_lotteryModel extends Model {

    public function __construct(){
        parent::__construct('weixin_lottery');
    }
    /**
	 * 插入默认值数据
	 *
	 * @param array $data
	 * @param array $options
	 * @return mixed int/false
	 */
    protected function _auto_insert_data(&$data,$options) {
		$data['status']     = 0;
		$data['createtime'] = time();		 
    }
}
