<?php
/**
 * 自定义表单
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

class weixin_selfformModel extends Model {

    public function __construct(){
        parent::__construct('weixin_selfform');
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
		$data['time']       = time();
		$data['endtime']    = $this ->getTime();		 
    }
	protected function getTime(){
		$date=$_POST['enddate'];
		if ($date){
		    $dates=explode('-',$date);
		    $time=mktime(23,59,59,$dates[1],$dates[2],$dates[0]);
		}else {
			$time=0;
		}
		return $time;
	}
}
