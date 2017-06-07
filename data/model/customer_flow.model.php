<?php
////////////////////////////////////////////////////////////////////
//                          _ooOoo_                               //
//                         o8888888o                              //
//                         88" . "88                              //
//                         (| ^_^ |)                              //
//                         O\  =  /O                              //
//                      ____/`---'\____                           //
//                    .'  \\|     |//  `.                         //
//                   /  \\|||  :  |||//  \                        //
//                  /  _||||| -:- |||||-  \                       //
//                  |   | \\\  -  /// |   |                       //
//                  | \_|  ''\---/''  |   |                       //
//                  \  .-\__  `-`  ___/-. /                       //
//                ___`. .'  /--.--\  `. . ___                     //
//              ."" '<  `.___\_<|>_/___.'  >'"".                  //
//            | | :  `- \`.;`\ _ /`;.`/ - ` : | |                 //
//            \  \ `-.   \_ __\ /__ _/   .-` /  /                 //
//      ========`-.____`-.___\_____/___.-`____.-'========         //
//                           `=---='                              //
//      ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^        //
//         佛祖保佑            永无BUG              永不修改         //
////////////////////////////////////////////////////////////////////
/**
 * 人脸识别 客流统计数据模块
 *
 *
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e.com Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
//客流统计接口API地址
define('CUSTOMER_FLOW_API_URL','http://112.124.57.247:8080/abdWebService/services/AxisService?wsdl');

class customer_flowModel extends Model {
	private $SoapClient;
	private $loginName = 'mgy';
	private $loginPwd  = 'mgy';
	
    public function __construct() {
        parent::__construct();
		
		$this->SoapClient = new SoapClient(CUSTOMER_FLOW_API_URL);
    }
	
	/*---------------------------------------ERP配置操作----------------------------------------*/
	/**
     * 取得店铺ERP配置信息
     * @param string $store_id
	 * @param string $fields
     */
    public function getERPConfigInfo($store_id = '',$fields = '*') {
		$erp_config = array();		
		if (!empty($store_id)){			
		    $condition = array();
		    $condition['store_id'] = $store_id;			
            $erp_config = $this->table('erp_config')->where($condition)->field($fields)->find();
		}		
		return $erp_config;
    }
	
	/**
     * 添加ERP配置信息
     * @param unknown $data
     */
    public function addERPConfig($data = array()) {
		$result = false;
		if (!empty($data)){
            $result = $this->table('erp_config')->insert($data);
		}		 
		return $result;
    }
	
    /**
     * 修改ERP配置信息
     * @param unknown $data
	 * @param string $store_id
     */
    public function editERPConfig($data = array(), $store_id = '') {
		$result = false;
		if (!empty($data) && !empty($store_id)){
			$condition = array();
		    $condition['store_id'] = $store_id;
            $result = $this->table('erp_config')->where($condition)->update($data);
		}
		return $result;
    }
	
	/**
     * 删除ERP配置信息
     * @param string $store_id
     */
	public function delERPConfig($store_id = '') {
		$result = false;
		if (!empty($store_id)){
			$condition = array();
		    $condition['store_id'] = $store_id;	
            $result = $this->table('erp_config')->where($condition)->delete();
		}		
		return $result;
    }    

	/*---------------------------------------客流API接口操作----------------------------------------*/	
    //判断当前登录用户是否为有效用户
	//正确时返回信息格式为JSON字符串，如{"status":1,"result":{"userId":65，"loginName":"wanda"}}字段说明：用户ID、登录名
    public function findAccout($loginName = '', $loginPwd = ''){
		$fun = 'findAccout';
		$param = array();
		$param['loginName'] = empty($loginName)?$this->loginName:$loginName;
		$param['loginPwd']  = empty($loginPwd)?$this->loginPwd:$loginPwd;
		
		return $this->soap_call_function($fun, $param);
	}
	
	//根据店铺名称或店铺编码查询店铺信息，当店铺名称和店铺编码均为空时，则表示查询所有店铺信息
	/*
	 * $shopName 店铺名称 可为空
	 * $shopCode 店铺编码 可为空
	 */
	//正确时返回信息格式为JSON字符串，如
	//{"status":1,"result":[{"shopId":85,"shopCode":"","shopName":"苏州万达","status":1,"shopArea":0,"longitude":0,"latitude":0,"header":"","headerTel":"","openDoorTime":"09:00:00","closeDoorTime":"21:00:00","address":"","memo":""}]}
    //字段说明：店铺ID、店铺编码、店铺名称、店铺状态( 1：营业中  2：停业中  3：已拆迁)、店铺面积、经度、纬度、负责人、负责人电话、开门时间、关门时间、地址、备注
	public function findShops($shopName = '', $shopCode = ''){
		$fun = 'findShops';
		$param = array();
		$param['loginName'] = $this->loginName;
		$param['loginPwd']  = $this->loginPwd;
		$param['shopName']  = $shopName;
		$param['shopCode']  = $shopCode;
		
		return $this->soap_call_function($fun, $param);
	}
	
	//根据店铺名称或者店铺编码查询对应的区域信息 当店铺编码和店铺名称均为空时，则表示查询所有店铺的区域信息
	/*
	 * $shopName 店铺名称 可为空
	 * $shopCode 店铺编码 可为空
	 * $areaOrChannel 0：全部   1：区域  2：通道
	 */
	//正确时返回信息格式为JSON字符串，如
	//{"status":1,"result":[{"shopId":85,"shopCode":"","shopName":"苏州万达","areaId":81,"areaName":"一层客流","areaCode":"","flowType":2,"isChannel":false,"pAreaId":19,"showOrder":10000,"showEnterRate":true},{"shopId":85,"shopCode":"","shopName":"苏州万达","areaId":82,"areaName":"二层客流","areaCode":"","flowType":2,"isChannel":false,"pAreaId":19,"showOrder":10000,"showEnterRate":true}]}
    //字段说明： 店铺Id、店铺名称、店铺编码、区域Id、区域名称、区域编码、客流类型（1：店铺客流  2：区域客流）、是否为通道、父级区域Id、显示顺序、是否显示进店率
	public function findShopAreas($shopName = '', $shopCode = '', $areaOrChannel = ''){
		$fun = 'findShopAreas';
		$param = array();
		$param['loginName']     = $this->loginName;
		$param['loginPwd']      = $this->loginPwd;
		$param['shopName']      = $shopName;
		$param['shopCode']      = $shopCode;
		$param['areaOrChannel'] = $areaOrChannel;
		
		return $this->soap_call_function($fun, $param);
	}
	
	//查询当前登录人员有权限查看的所有店铺指定时间范围内的客流信息
	/*
	 * $beginDate： 开始日期 格式：yyyy-MM-dd
	 * $endDate：   结束日期 格式:yyyy-MM-dd
	 * $queryType： 查询方式   1：年 2：季度 3：月 4：日  5：小时 6.半小时  7:15分钟
	 */
	//正确时返回信息格式为JSON字符串，如
	//{"status":1,"result":[{"shopId":85,"shopCode":" ","shopName":"苏州万达 ","recordTime":"2015-03-30","inNum":2335,"outNum":1924,"aisleNum":4259}]}
    //字段说明：店铺Id、店铺编码、店铺名称、记录时间、进入人数、离开人数、过道客流
	public function findPassengers($beginDate = '', $endDate = '', $queryType = 4){
		$fun = 'findPassengers';
		$param = array();
		$param['loginName'] = $this->loginName;
		$param['loginPwd']  = $this->loginPwd;
		$param['beginDate'] = empty($beginDate)?date("Y-m-d", TIMESTAMP):$beginDate;
		$param['endDate']   = empty($endDate)?date("Y-m-d", TIMESTAMP):$endDate;
		$param['queryType'] = $queryType;
		
		return $this->soap_call_function($fun, $param);
	}
	
	//根据店铺编码或者店铺名称查询指定时间范围内的客流信息   当店铺编码和店铺名称均为空时，表示查询所有店铺客流信息
	/*
	 * $shopName 店铺名称 可为空
	 * $shopCode 店铺编码 可为空
	 * $beginDate： 开始日期 格式：yyyy-MM-dd
	 * $endDate：   结束日期 格式:yyyy-MM-dd
	 * $queryType： 查询方式   1：年 2：季度 3：月 4：日  5：小时 6.半小时  7:15分钟
	 */
	//正确时返回信息格式为JSON字符串，如
	//{"status":1,"result":[{"shopId":85,"shopCode":" ","shopName":"苏州万达 ","recordTime":"2015-03-30","inNum":2335,"outNum":1924,"aisleNum":4259}]}
    //字段说明：店铺Id、店铺编码、店铺名称、记录时间、进入人数、离开人数、过道客流
	public function findPassengers_byShopNameOrCode($shopName = '', $shopCode = '', $beginDate = '', $endDate = '', $queryType = 4){
		$fun = 'findPassengers_byShopNameOrCode';
		$param = array();
		$param['loginName'] = $this->loginName;
		$param['loginPwd']  = $this->loginPwd;
		$param['shopName']  = $shopName;
		$param['shopCode']  = $shopCode;
		$param['beginDate'] = empty($beginDate)?date("Y-m-d", TIMESTAMP):$beginDate;
		$param['endDate']   = empty($endDate)?date("Y-m-d", TIMESTAMP):$endDate;
		$param['queryType'] = $queryType;
		
		return $this->soap_call_function($fun, $param);
	}
	
	//查询各店铺下各个区域客流信息
	/*
	 * $beginDate： 开始日期 格式：yyyy-MM-dd
	 * $endDate：   结束日期 格式:yyyy-MM-dd
	 * $queryType： 查询方式   1：年 2：季度 3：月 4：日  5：小时 6.半小时  7:15分钟
	 * $areaOrChannel 0：全部   1：区域客流  2：通道客流
	 */
	//正确时返回信息格式为JSON字符串，如
	//{"status":1,"result":[{"shopId":85,"shopCode":" ","shopName":"苏州万达 ","areaId":81,"areaName":"一层客流","areaCode":"","recordTime":"2015-03-30","inNum":1202,"outNum":868,"aisleNum":0},{"shopId":85,"shopCode":" ","shopName":"苏州万达 ","areaId":82,"areaName":"二层客流","areaCode":"","recordTime":"2015-03-30","inNum":6365,"outNum":1522,"aisleNum":0}]}
    //字段说明：店铺Id、店铺编码、店铺名称、区域Id、区域名称、区域编码、记录时间、进入人数、离开人数、过道客流
	public function findPassengers_areaOrChannel($beginDate = '', $endDate = '', $queryType = 4, $areaOrChannel = 0){
		$fun = 'findPassengers_areaOrChannel';
		$param = array();
		$param['loginName']     = $this->loginName;
		$param['loginPwd']      = $this->loginPwd;
		$param['beginDate']     = empty($beginDate)?date("Y-m-d", TIMESTAMP):$beginDate;
		$param['endDate']       = empty($endDate)?date("Y-m-d", TIMESTAMP):$endDate;
		$param['queryType']     = $queryType;
		$param['areaOrChannel'] = $areaOrChannel;
		
		return $this->soap_call_function($fun, $param);
	}
	
	//根据店铺编码或者店铺名称查询指定时间范围内指定店铺的客流信息   当店铺编码和店铺名称均为空时，表示查询所有店铺对应的区域客流信息
	/*
	 * $shopName 店铺名称 可为空
	 * $shopCode 店铺编码 可为空
	 * $beginDate： 开始日期 格式：yyyy-MM-dd
	 * $endDate：   结束日期 格式:yyyy-MM-dd
	 * $queryType： 查询方式   1：年 2：季度 3：月 4：日  5：小时 6.半小时  7:15分钟
	 * $areaOrChannel 0：全部   1：区域客流  2：通道客流
	 */
	//正确时返回信息格式为JSON字符串，如
	//{"status":1,"result":[{"shopId":85,"shopCode":" ","shopName":"苏州万达 ","areaId":81,"areaName":"一层客流","areaCode":"","recordTime":"2015-03-30","inNum":1202,"outNum":868,"aisleNum":0},{"shopId":85,"shopCode":" ","shopName":"苏州万达 ","areaId":82,"areaName":"二层客流","areaCode":"","recordTime":"2015-03-30","inNum":6365,"outNum":1522,"aisleNum":0}]}
    //字段说明：店铺Id、店铺编码、店铺名称、区域Id、区域名称、区域编码、记录时间、进入人数、离开人数、过道客流
	public function findPassengers_areaOrChannel_byShopNameOrCode($shopName = '', $shopCode = '', $beginDate = '', $endDate = '', $queryType = 4, $areaOrChannel = 0){
		$fun = 'findPassengers_areaOrChannel_byShopNameOrCode';
		$param = array();
		$param['loginName']     = $this->loginName;
		$param['loginPwd']      = $this->loginPwd;
		$param['shopName']      = $shopName;
		$param['shopCode']      = $shopCode;
		$param['beginDate']     = empty($beginDate)?date("Y-m-d", TIMESTAMP):$beginDate;
		$param['endDate']       = empty($endDate)?date("Y-m-d", TIMESTAMP):$endDate;
		$param['queryType']     = $queryType;
		$param['areaOrChannel'] = $areaOrChannel;
		
		return $this->soap_call_function($fun, $param);
	}
	
	//查询指定店铺下的计数设备信息
	/*
	 * $shopName 店铺名称 可为空
	 * $shopCode 店铺编码 可为空
	 */
	//正确时返回信息格式为JSON字符串，如
	//{"status":1,"result":[{"shopId":1,"shopName":"Welcome Center","shopCode":"99999999","area":[{"areaId":1,"areaName":"进入人数","areaCode":"","device":[{"deviceCode":"11166","channelNo":1,"channelName":"接待中心1"},{"deviceCode":"11166","channelNo":2,"channelName":"接待中心2"},{"deviceCode":"11166","channelNo":3,"channelName":"接待中心3"}]},{"areaId":2,"areaName":"离开人数","areaCode":"","device":[{"deviceCode":"11166","channelNo":1,"channelName":"接待中心1"},{"deviceCode":"11166","channelNo":2,"channelName":"接待中心2"},{"deviceCode":"11166","channelNo":3,"channelName":"接待中心3"}]},{"areaId":143,"areaName":"Welcome Center 1","areaCode":"","device":[{"deviceCode":"11166","channelNo":1,"channelName":"接待中心1"}]},{"areaId":144,"areaName":"Welcome Center 2","areaCode":"","device":[{"deviceCode":"11166","channelNo":2,"channelName":"接待中心2"}]},{"areaId":145,"areaName":"Welcome Center 3","areaCode":"","device":[{"deviceCode":"11166","channelNo":3,"channelName":"接待中心3"}]}]}]}
    //字段说明：店铺Id、店铺编码、店铺名称、区域Id、区域名称、区域编码、设备编号、通道号、通道名称
	public function findShopDevice($shopName = '', $shopCode = ''){
		$fun = 'findShopDevice';
		$param = array();
		$param['loginName']     = $this->loginName;
		$param['loginPwd']      = $this->loginPwd;
		$param['shopName']      = $shopName;
		$param['shopCode']      = $shopCode;
		
		return $this->soap_call_function($fun, $param);
	}
	
	//查询指定店铺下各个设备各个计数通道的每分钟客流信息
	/*
	 * $shopName 店铺名称 可为空
	 * $shopCode 店铺编码 可为空
	 * $queryDate 查询时间 格式 yyyy-MM-dd | yyyy-MM-dd HH | yyyy-MM-dd HH:mm | yyyy-MM-dd HH:mm:ss
	 */
	//正确时返回信息格式为JSON字符串，如
	//{"status":1,"result":[{"deviceCode":"11166","channel":[{"channel":1,"passenger":[{"recordTime":"2016-04-07 15:20:02","inNum":1,"outNum":0},{"recordTime":"2016-04-07 15:26:02","inNum":0,"outNum":1}]}]}]}
    //字段说明：设备号、通道号、记录时间、进入人数、离开人数
	public function findPassengers_minuteTraffic($shopName = '', $shopCode = '', $queryDate = ''){
		$fun = 'findPassengers_minuteTraffic';
		$param = array();
		$param['loginName']     = $this->loginName;
		$param['loginPwd']      = $this->loginPwd;
		$param['shopName']      = $shopName;
		$param['shopCode']      = $shopCode;
		$param['queryDate']     = empty($queryDate)?date("Y-m-d", TIMESTAMP):$queryDate;
		
		return $this->soap_call_function($fun, $param);
	}
	
	//根据店铺名称或店铺编码查询所有安装了人脸检测设备的店铺信息，当店铺名称和店铺编码均为空时，则表示查询所有店铺信息
	/*
	 * $shopName 店铺名称 可为空
	 * $shopCode 店铺编码 可为空
	 */
	//正确时返回信息格式为JSON字符串，如
	//{"status":1,"result":[{"shopId":417,"shopCode":"","shopName":"人脸测试","status":1,"shopArea":0,"longitude":0,"latitude":0,"header":"","headerTel":"","openDoorTime":"","closeDoorTime":"","address":"","memo":""},{"shopId":4,"shopCode":"1101","shopName":"梦燕横塘店","status":1,"shopArea":123.4,"longitude":0,"latitude":0,"header":"张三","headerTel":"13888888888","openDoorTime":"","closeDoorTime":"","address":"","memo":""}]}
    //字段说明：店铺ID、店铺编码、店铺名称、店铺状态( 1：营业中  2：停业中  3：已拆迁)、店铺面积、经度、纬度、负责人、负责人电话、开门时间、关门时间、地址、备注
	public function findShops_face($shopName = '', $shopCode = ''){
		$fun = 'findShops_face';
		$param = array();
		$param['loginName']     = $this->loginName;
		$param['loginPwd']      = $this->loginPwd;
		$param['shopName']      = $shopName;
		$param['shopCode']      = $shopCode;
		
		return $this->soap_call_function($fun, $param);
	}
	
	//查询当前登录人员有权限查看的安装了人脸检测设备的店铺指定时间范围内的顾客属性信息,当店铺名称和店铺编码均为空时，则表示查询所有店铺信息
	/*
	 * $shopName 店铺名称 可为空
	 * $shopCode 店铺编码 可为空
	 * $beginDate： 开始日期 格式：yyyy-MM-dd
	 * $endDate：   结束日期 格式:yyyy-MM-dd
	 * $queryType： 查询方式   1：年 2：季度 3：月 4：日  5：小时 6.半小时  7:15分钟
	 * $ages 年龄段  ages=10：按十岁进行年龄段划分   ages=5：按5岁进行年龄段划分，如传入非指定的数值，则默认按10岁进行年龄段划分
	 */
	//正确时返回信息格式为JSON字符串，如
	//{"status":1,"result":[{"shopId":417,"shopName":"人脸测试","shopCode":"","face_shop":[{"areaId":256,"areaName":"店铺客流","areaCode":"","face_area":[{"recordTime":"2016-07-10","age0_5_male":0,"age6_10_male":0,"age11_15_male":254,"age16_20_male":6,"age21_25_male":1709,"age26_30_male":69,"age31_35_male":519,"age36_40_male":288,"age41_45_male":19,"age46_50_male":20,"age51_55_male":0,"age56_60_male":0,"age61_65_male":0,"age66_70_male":0,"age_other_male":0},{"recordTime":"2016-07-10","age0_5_female":0,"age6_10_female":0,"age11_15_female":1,"age16_20_female":33,"age21_25_female":587,"age26_30_female":386,"age31_35_female":503,"age36_40_female":6,"age41_45_female":286,"age46_50_female":278,"age51_55_female":93,"age56_60_female":104,"age61_65_female":0,"age66_70_female":0,"age_other_female":0}]}]}]}
    //字段说明：shopId:店铺ID shopName:店铺名称 shopCode:店铺编码areaId:区域Id  areaName:区域名称 areaCode:区域编码recordTime:记录时间   age0_5_male：0~5岁男性顾客数  age6_10_male：6~10岁男性顾客数……age0_5_female：0~5岁女性顾客数  age6_10_female：6~10岁女性顾客数……
	public function findPassengers_face($shopName = '', $shopCode = '', $beginDate = '', $endDate = '', $queryType = 4, $ages = 10){
		$fun = 'findPassengers_face';
		$param = array();
		$param['loginName'] = $this->loginName;
		$param['loginPwd']  = $this->loginPwd;
		$param['shopName']  = $shopName;
		$param['shopCode']  = $shopCode;
		$param['beginDate'] = empty($beginDate)?date("Y-m-d", TIMESTAMP):$beginDate;
		$param['endDate']   = empty($endDate)?date("Y-m-d", TIMESTAMP):$endDate;
		$param['queryType'] = $queryType;
		$param['ages']      = $ages;
		
		return $this->soap_call_function($fun, $param);
	}

	/*---------------------------------------客流API接口公共处理----------------------------------------*/	
	public function soap_call_function($fun = '', $param = array()){
		if (empty($fun) || empty($param)){
			return false;
		}
		$result = array('status'=>0,'data'=>'');
		
		$server_result = $this->SoapClient->$fun($param);		
		$server_result = get_object_vars($server_result);
		$server_result = json_decode($server_result['return'],true);

		if (intval($server_result['status'])==1){
			$result['status'] = 1;
			$result['data']   = $server_result['result'];
		}else{
			$result['status'] = 0;
			$result['data']   = $server_result['result'];
		}
		return $result;	
	}	
}