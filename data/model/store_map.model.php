<?php
/**
 * 店铺地址百度地图
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

class store_mapModel extends Model{

    public function __construct() {
        parent::__construct();
    }

    /**
     * 增加店铺地址
     *
     * @param
     * @return int
     */
    public function addStoreMap($map_array) {
        $map_id = $this->table('store_map')->insert($map_array);
        return $map_id;
    }

    /**
     * 删除店铺地址记录
     *
     * @param
     * @return bool
     */
    public function delStoreMap($condition) {
        if (empty($condition)) {
            return false;
        } else {
            $result = $this->table('store_map')->where($condition)->delete();
            return $result;
        }
    }

    /**
     * 修改店铺地址记录
     *
     * @param
     * @return bool
     */
    public function editStoreMap($condition, $data) {
        if (empty($condition)) {
            return false;
        }
        if (is_array($data)) {
            $result = $this->table('store_map')->where($condition)->update($data);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 店铺地址记录
     *
     * @param
     * @return array
     */
    public function getStoreMapList($condition = array(), $page = '', $limit = '', $order = 'map_id desc') {
        $result = $this->table('store_map')->where($condition)->page($page)->limit($limit)->order($order)->select();
        return $result;
    }
    
    /**
     * 生成腾讯地图导航店铺地址
     *	根据腾讯地图的接口文档，可查询地址是http://lbs.qq.com/tool/component-marker.html
     *
     * @param $store_id 为店铺的ID
     * 
     * @author
     * @return array
     */
    public function getTXMapUrl($store_id) {
    	
    	$condition = array('store_id'=>$store_id);
    	$storeMapList = $this->getStoreMapList($condition);
    	if(!empty($storeMapList)){
	    	$tx_url = "http://apis.map.qq.com/tools/poimarker?type=0"; //调用地址
	    	//封装其他所需参数
	    	$tx_url .="&marker=";
	    	foreach ($storeMapList as $mkey=>$map){
	    		if($mkey!=0){
	    			$tx_url .="|";
	    		}
	    		$tx_url .="coord:";
	    		$tx_url .=$map['baidu_lat'].",";
	    		$tx_url .=$map['baidu_lng'].";";
	    		$tx_url .="coordtype:3;";//说明： 1. GPS坐标 2. sogou经纬度 3. baidu经纬度 4. mapbar经纬度 5. [默认]腾讯、google、高德坐标 6. sogou墨卡托
	    		$tx_url .="title:".$map['name_info'].";";
	    		$tx_url .="addr:".$map['baidu_province']." ".$map['baidu_city']." ".$map['baidu_district']." ".$map['baidu_street'].";";
	    	}
	    	$tx_url .="&key=WWDBZ-2ANHU-JEDVX-4CNJB-HUQFZ-5TF7E";//开发密钥(key)
	    	$tx_url .="&referer=zhilin"; //调用来源，一般为您的应用名称，为了保障对您的服务，请务必填写！在此处申请http://lbs.qq.com/mykey.html
	    	   
    	}else{
    		$tx_url = "";
    	}
    	return $tx_url;
    }
}
