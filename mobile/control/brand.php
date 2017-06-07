<?php
/**
 * 商品品牌
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class brandControl extends mobileHomeControl{

	public function __construct() {
        parent::__construct();
    }
	
	public function indexOp() {
        $this->recommend_listOp();
	}

	public function recommend_listOp() {
		$class_id = intval($_GET['class_id']);
		
		$model_brand = Model('brand');
		$condition = array();
		$condition['brand_recommend'] = 1;
		$condition['brand_apply'] = 1;
		if ($class_id>0){
		    $condition['class_id'] = $class_id;
		}
		$brand_arr = $model_brand->getBrandList($condition,'brand_id,brand_name,brand_pic');
		$brand_list = array();
		if (!empty($brand_arr) && is_array($brand_arr)){
			foreach ($brand_arr as $key => $brand) {
				$brand_info = array();
				$brand_info['brand_id'] = $brand['brand_id'];
				$brand_info['brand_name'] = $brand['brand_name'];
				$brand_info['brand_pic'] = brandImage($brand['brand_pic']);
				
				$brand_list[] = $brand_info;
			}
		}
		
        output_data(array('brand_list' => $brand_list));
	}    
}