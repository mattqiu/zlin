<?php
/**
 * 微汽车
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

class weixin_CarmodelModel extends Model {

    public function __construct(){
        parent::__construct('weixin_Carmodel');
    }
    /**
	 * 插入默认值数据
	 *
	 * @param array $data
	 * @param array $options
	 * @return mixed int/false
	 */
	 protected function _auto_insert_data(&$data,$options) {
		 		 
	}
	//自动验证
	protected $_validate = array(

			array('name','require','车型名不能为空',1),
			array('brand_serise','require','品牌/车系必须选择',3),
			array('guide_price','require','指导价不能为空',1),
			array('dealer_price','require','经销商报价不能为空',1),
			array('pic_url','require','图片不能为空',1),
	 );
}
