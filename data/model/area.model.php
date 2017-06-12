<?php
/**
 * 地区模型
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class areaModel extends Model {

    public function __construct() {
        parent::__construct('area');
    }

    /**
     * 获取地址列表
     *
     * @return mixed
     */
    public function getAreaList($condition = array(), $fields = '*', $group = '',$order = '') {
        return $this->where($condition)->field($fields)->limit(false)->group($group)->order($order)->select();
    }

    /**
     * 获取地址详情
     *
     * @return mixed
     */
    public function getAreaInfo($condition = array(), $fileds = '*') {
        return $this->where($condition)->field($fileds)->find();
    }

    /**
     * 获取一级地址（省级）名称数组
     *
     * @return array 键为id 值为名称字符串
     */
    public function getTopLevelAreas() {
        $data = $this->getCache();

        $arr = array();
        foreach ($data['children'][0] as $i) {
            $arr[$i] = $data['name'][$i];
        }

        return $arr;
    }

    /**
     * 获取获取市级id对应省级id的数组
     *
     * @return array 键为市级id 值为省级id
     */
    public function getCityProvince() {
        $data = $this->getCache();

        $arr = array();
        foreach ($data['parent'] as $k => $v) {
            if ($v && $data['parent'][$v] == 0) {
                $arr[$k] = $v;
            }
        }

        return $arr;
    }

    /**
     * 获取地区缓存
     *
     * @return array
     */
    public function getAreas() {
        return $this->getCache();
    }
    
    /**
     * 根据地区ID 获取所有上级省市区的名称数组
     *
     * @return array
     */
    public function getTreeAreas($areaId) {
    	$data = $this->getCache();
    	
    	$is_deep = $data['deep'][$areaId];
    	$area[$is_deep]['name'] = $data['name'][$areaId];
    	$area[$is_deep]['parent'] = $parentId =  $data['parent'][$areaId];
    	$area[$is_deep]['children'] = $areaId;
    	if(!empty($parentId) && $parentId>0){
    		$area[$is_deep]['tree'] = $this->getTreeAreas($parentId);
    	}
    	return $area;
    }
    
    /**
     * 根据地区ID 获取所有上级省市区的名称数组
     *
     * @return array
     */
    public function getParentAreas($areaId) {
    	$treeAreas = $this->getTreeAreas($areaId);
    	$area_arr = array();
    	foreach ($treeAreas as $tkey => $area){
    		$area_arr[$tkey] = $area;
    		if($tkey>1){
    			$area_arr[$tkey-1] = $area['tree'][$tkey-1];
    		}
    		foreach ($area_arr[$tkey-1]['tree'] as $ckey => $carea){
    			$area_arr[$ckey] = $carea;
    			if($ckey>1){
    				$area_arr[$ckey-1] = $carea['tree'][$ckey-1];
    			}
    		}
    		unset($area_arr[$tkey]['tree']);
    		unset($area_arr[$tkey-1]['tree']);
    	}
    	return $area_arr;
    }
    
    /**
     * 获取全部地区名称数组
     *
     * @return array 键为id 值为名称字符串
     */
    public function getAreaNames() {
        $data = $this->getCache();

        return $data['name'];
    }

    /**
     * 获取用于前端js使用的全部地址数组
     *
     * @return array
     */
    public function getAreaArrayForJson() {
        $data = $this->getCache();

        $arr = array();
        foreach ($data['children'] as $k => $v) {
            foreach ($v as $vv) {
                $arr[$k][] = array($vv, $data['name'][$vv]);
            }
        }

        return $arr;
    }

    /**
     * 获取地区数组 格式如下
     * array(
     *   'name' => array(
     *     '地区id' => '地区名称',
     *     // ..
     *   ),
     *   'parent' => array(
     *     '子地区id' => '父地区id',
     *     // ..
     *   ),
     *   'children' => array(
     *     '父地区id' => array(
     *       '子地区id 1',
     *       '子地区id 2',
     *       // ..
     *     ),
     *     // ..
     *   ),
     *   'region' => array(array(
     *     '华北区' => array(
     *       '省级id 1',
     *       '省级id 2',
     *       // ..
     *     ),
     *     // ..
     *   ),
     * )
     *
     * @return array
     */
    protected function getCache() {
        // 对象属性中有数据则返回
        if ($this->cachedData !== null)
            return $this->cachedData;

        // 缓存中有数据则返回
        if ($data = rkcache('area')) {
            $this->cachedData = $data;
            return $data;
        }

        // 查库
        $data = array();
        $area_all_array = $this->limit(false)->select();
        foreach ((array) $area_all_array as $a) {
            $data['name'][$a['area_id']] = $a['area_name'];
            $data['parent'][$a['area_id']] = $a['area_parent_id'];
            $data['deep'][$a['area_id']] = $a['area_deep'];
            $data['children'][$a['area_parent_id']][] = $a['area_id'];

            if ($a['area_deep'] == 1 && $a['area_region'])
                $data['region'][$a['area_region']][] = $a['area_id'];
        }

        wkcache('area', $data);
        $this->cachedData = $data;

        return $data;
    }

    protected $cachedData;
}