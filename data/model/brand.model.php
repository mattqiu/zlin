<?php
/**
 * 商品品牌模型
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

class brandModel extends Model {
    public function __construct() {
        parent::__construct('brand');
    }
    
    /**
     * 添加品牌
     * @param array $insert
     * @return boolean
     */
    public function addBrand($insert) {
        return $this->insert($insert);
    }
    
    /**
     * 编辑品牌
     * @param array $condition
     * @param array $update
     * @return boolean
     */
    public function editBrand($condition, $update) {
        return $this->where($condition)->update($update);
    }
    
    /**
     * 删除品牌
     * @param unknown $condition
     * @return boolean
     */
    public function delBrand($condition) {
        $brand_array = $this->getBrandList($condition, 'brand_id,brand_pic');
        $brandid_array = array();
        foreach ($brand_array as $value) {
            $brandid_array[] = $value['brand_id'];
            @unlink(BASE_UPLOAD_PATH.DS.ATTACH_BRAND.DS.$value['brand_pic']);
        }
        return $this->where(array('brand_id' => array('in', $brandid_array)))->delete();
    }
    
    /**
     * 查询品牌数量
     * @param array $condition
     * @return array
     */
    public function getBrandCount($condition) {
        return $this->where($condition)->count();
    }
    
    /**
     * 品牌列表
     * @param array $condition
     * @param string $field
     * @param string $order
     * @param number $page
     * @param string $limit
     * @return array
     */
    public function getBrandList($condition, $field = '*', $page = 0, $order = 'brand_sort asc, brand_id desc', $limit = '') {
        return $this->where($condition)->field($field)->order($order)->page($page)->limit($limit)->select();
    }
    
    /**
     * 通过的品牌列表
     * 
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getBrandPassedList($condition, $field = '*', $page = 0, $order = 'brand_sort asc, brand_id desc', $limit = '') {
        $condition['brand_apply'] = 1;
        return $this->getBrandList($condition, $field, $page, $order, $limit);
    }
    
    /**
     * 未通过的品牌列表
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getBrandNoPassedList($condition, $field = '*', $page = 0) {
        $condition['brand_apply'] = 0;
        return $this->getBrandList($condition, $field, $page);
    }
    
    /**
     * 取单个品牌内容
     * @param array $condition
     * @param string $field
     * @return array
     */
    public function getBrandInfo($condition, $field = '*') {
        return $this->field($field)->where($condition)->find();
    }
	
	/**
	 * 取单个品牌的内容
	 *
	 * @param int $brand_id 品牌ID
	 * @return array 数组类型的返回结果
	 */
	public function getOneBrand($brand_id){
		if (intval($brand_id) > 0){
			$param = array();
			$param['table'] = 'brand';
			$param['field'] = 'brand_id';
			$param['value'] = intval($brand_id);
			$result = Db::getRow($param);
			return $result;
		}else {
			return false;
		}
	}
	
	/**
	 * 前台头部的品牌分类
	 *
     * @param   number  $update_all   更新
     * @return  array   数组
	 */
	public function get_all_brandcategory($update_all = 0) {
	    $file_name = BASE_DATA_PATH.'/cache/index/brandcategory.php';
		if (!file_exists($file_name) || $update_all == 1) {//文件不存在时更新或者强制更新时执行		
		    $brands = array();
			
            $brand_c_list = $this->where(array('brand_apply'=>'1'))->order('brand_sort asc')->select();             
			if (is_array($brand_c_list) && !empty($brand_c_list)) {				
				$brands = $this->_tidyBrand($brand_c_list);
    			F('brandcategory', $brands, 'cache/index');
			}
		} else {
		    $brands = include $file_name;
		}
	    return $brands;
    }
	
	/**
	 * 整理品牌
	 * 所有品牌全部显示在一级类目下，不显示二三级类目
	 * @param array $brand_c_list
	 * @return array
	 */
	private function _tidyBrand($brand_c_list) {
	    $brand_listnew = array();
		$brand_list = array();
	    $brand_class = array();
	    $brand_r_list = array();
	    if (!empty($brand_c_list) && is_array($brand_c_list)){
	        $goods_class = rkcache('goods_class') ? rkcache('goods_class') : rkcache('goods_class',true);
	        foreach ($brand_c_list as $key=>$brand_c){
                $gc_array = $this->_getTopClass($goods_class, $brand_c['class_id']);
                if (empty($gc_array)) {		
                    $other_list['brand'][] = $brand_c;
                    $other_list['gc_name'] = '其他';
					$other_list['gc_id'] = '-1';
					//推荐品牌
	                if ($brand_c['brand_recommend'] == 1){
	                    $other_list['recommend'][] = $brand_c;
	                } 
                } else {					
					if ($gc_array['depth']==1){
						$gc_id = $gc_array['gc_id'];
						$pic_name = BASE_UPLOAD_PATH.'/'.ATTACH_COMMON.'/category-pic-'.$gc_id.'.jpg';
						$image_name = BASE_UPLOAD_PATH.'/'.ATTACH_COMMON.'/category-image-'.$gc_id.'.png';
						
						$brand_listnew[$gc_id][] = $brand_c;
						$brand_class[$gc_id]['brand_class'] = $gc_array['gc_name'];						
						
						$brand_list[$gc_id]['brand'][] = $brand_c;
						$brand_list[$gc_id]['gc_name'] = $gc_array['gc_name'];
						$brand_list[$gc_id]['gc_id'] = $gc_id; 
						$brand_list[$gc_id]['gc_parent_id'] = $gc_array['gc_parent_id']; 
						if (file_exists($pic_name)) {
    			          $brand_list[$gc_id]['pic'] = UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/category-pic-'.$gc_id.'.jpg';
    			        }
						if (file_exists($image_name)) {
    			          $brand_list[$gc_id]['image'] = UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/category-image-'.$gc_id.'.png';
    			        }
						//推荐品牌
	                    if ($brand_c['brand_recommend'] == 1){
	                        $brand_list[$gc_id]['recommend'][] = $brand_c;
	                    }                     
					}elseif ($gc_array['depth']==2){
						$gc_id = $gc_array['gc_parent_id'];
						$pic_name = BASE_UPLOAD_PATH.'/'.ATTACH_COMMON.'/category-pic-'.$gc_id.'.jpg';
						
						$brand_listnew[$gc_id][] = $brand_c;
						$brand_class[$gc_id]['brand_class'] = $goods_class[$gc_id]['gc_name'];						
						
						$brand_list[$gc_id]['gc_name'] = $goods_class[$gc_id]['gc_name'];
						$brand_list[$gc_id]['gc_id'] = $gc_id;
						$brand_list[$gc_id]['gc_parent_id'] = $goods_class[$gc_id]['gc_parent_id'];
						if (file_exists($pic_name)) {
						  $brand_list[$gc_id]['pic'] = UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/category-pic-'.$gc_id.'.jpg';
						}
						$brand_list[$gc_id]['child'][$gc_array['gc_id']]['gc_name'] = $gc_array['gc_name'];
						$brand_list[$gc_id]['child'][$gc_array['gc_id']]['gc_id'] = $gc_array['gc_id'];
						$brand_list[$gc_id]['child'][$gc_array['gc_id']]['gc_parent_id'] = $gc_array['gc_parent_id'];
						$brand_list[$gc_id]['child'][$gc_array['gc_id']]['child'][] = $brand_c; 
						//推荐品牌
	                    if ($brand_c['brand_recommend'] == 1){
	                        $brand_list[$gc_id]['recommend'][] = $brand_c;
	                    }                         
					}					
                }
	            //推荐品牌
	            if ($brand_c['brand_recommend'] == 1){
	                $brand_r_list[] = $brand_c;
	            }
	        }
	    }
	    ksort($brand_class);
		ksort($brand_list);	
	    ksort($brand_listnew);
		if (!empty($other_list)){
			$brand_class[]=array('brand_class'=>'其它');
			$brand_listnew[]=$other_list['brand'];
			$brand_list[]=$other_list;
		}
	    return array('brand_listnew' => $brand_listnew, 'brand_list' => $brand_list, 'brand_class' => $brand_class, 'brand_r_list' => $brand_r_list);
	}
	
	/**
	 * 获取顶级商品分类
	 * 递归调用
	 * @param array $goods_class
	 * @param int $gc_id
	 * @return array
	 */
	private function _getTopClass($goods_class, $gc_id) {
	    if (!isset($goods_class[$gc_id])) {
	        return null;
	    }
		if ($goods_class[$gc_id]['gc_parent_id'] == 0){
			return $goods_class[$gc_id];
		}elseif ($goods_class[$gc_id]['depth'] == 2){
			return $goods_class[$gc_id];
		}else{
	        return $this->_getTopClass($goods_class, $goods_class[$gc_id]['gc_parent_id']);
		}
	}
}