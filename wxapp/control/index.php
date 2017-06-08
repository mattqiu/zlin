<?php
/**
 * 小程序首页
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class indexControl extends wxappHomeControl{

	public function __construct() {
        parent::__construct();
    }
	
    /**
     * 微信小程序-导购首页
     */
    public function indexOp() {
    	file_put_contents('test.log',"获取：".json_encode('').PHP_EOL,FILE_APPEND);
    	$indexInfo['member_id'] = $this->member_id; //当前会员ID
    	$indexInfo['store_id'] = $this->store_info['store_id']; //当前店铺ID    	
    	$indexInfo['store_avatar'] = $this->store_info['store_avatar']; //当前店铺LOGO
    	$indexInfo['seller_id'] = $this->seller_info['seller_id']; //当前导购ID
    	$indexInfo['seller_name'] = $this->seller_info['seller_name']; //当前导购用户名
    	$indexInfo['store_num'] 	= Model('seller')->getStoreCountByMemberID($this->member_id);//可管理店铺数
    	/*
    	$seller_arr[0] = $indexInfo['seller_name'];
    	if(!empty($this->seller_info['saleman_id'])){
	    	$model_seller = Model('seller');	
    	$salemanList = explode(',',$this->seller_info['saleman_id']);//导购列表
    	foreach ($salemanList as $skey=>$smInfo){
    		$selInfo = $model_seller->getSellerInfo(array('seller_id' => $smInfo['seller_id']),'seller_name,nick_name');
    		$seller_list[$skey]['seller_id'] = $smInfo['seller_id'];
    			$seller_arr[] = empty($selInfo['nick_name'])?$selInfo['seller_name']:$selInfo['nick_name'];
    	}
    	}    	
    	*/
    	$indexInfo['saleman'] = $this->saleman;//导购列表
    	$indexInfo['saleman_len'] = count($this->saleman['saleman_name']);//导购数
    	//切换导购
    	if(!empty($_REQUEST['saleman_id'])){
    		$saleman_id = $_REQUEST['saleman_id'];
    	}else{
    		$saleman_id = $this->seller_info['seller_id'];
    	}
    	$indexInfo['saleman_id'] = $saleman_id;
    	//导购权限
    	$app_limits = explode(',',$this->seller_group_info['app_limits']);
    	$app_seller_limits = $this->getAppSellerMenuList($app_limits);
    	$app_seller_menu = $app_seller_limits['app_seller_menu']['wxapp']['child'];
    	if(!empty($app_seller_menu)){
	    	$indexInfo['app_seller_menu'] 	= $app_seller_menu;//导购可操作的菜单
    	}else{
    		output_error('您没有任何操作权限无法登录，请联系店长开通相关权限', array('error_code'=>CODE_NoPermission));
    	}
    	//待办事宜
    	$msg_where['store_id'] = $this->store_info['store_id'];
    	$msg_where['smt_code'] = array('in', $this->seller_group_info['smt_limits']);
    	$model_storemsg = Model('store_msg');
    	$msg_list = $model_storemsg->getStoreMsgList($msg_where, '*', 10);//读取10条记录
    	// 整理数据
    	if (!empty($msg_list)) {
    		foreach ($msg_list as $key => $val) {
    			$msg_list[$key]['sm_readids'] = explode(',', $val['sm_readids']);
    		}
    	}
    	$indexInfo['msg_list'] 	= $msg_list;//待办事宜
    	//今日业绩
    	$model_member = Model('member');
    	$model_order = Model('order');
    	$todayStart = mktime(0,0,0,date('m'),date('d'),date('Y'));
    	$todayEnd = mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
    	$sales_condition['store_id'] = $this->store_info['store_id'];
    	$sales_condition['add_time'] = array('between',$todayStart.','.$todayEnd);//between是区间s<=x<=e
    	$salesInfo = $model_order->getStatisticsCommis($sales_condition);
    	$indexInfo['today_sales'] = $salesInfo['commis'];
    	//客流
    	$beginDate= date("Y-m-d");//今天
    	$endDate = date("Y-m-d",strtotime("+1 day"));//明天
    	$total = $this->customerflow($store_name,$beginDate,$endDate);
    	$indexInfo['customerflow'] = $total;
    	output_data($indexInfo);
    }
    
    /**
     * 实体店客流统计
     */
    public function customerflow($store_name,$beginDate,$endDate){
    	$model_flow = Model('customer_flow');
    	/*	$shopName 店铺名称 可为空
		 * $shopCode 店铺编码 可为空
		 * $beginDate： 开始日期 格式：yyyy-MM-dd
		 * $endDate：   结束日期 格式:yyyy-MM-dd
		 * $queryType： 查询方式   1：年 2：季度 3：月 4：日  5：小时 6.半小时  7:15分钟
		 */
    	$data = $model_flow->findPassengers_byShopNameOrCode($store_name,"",$beginDate, $endDate, 4);
    	if ($data['status']==1){
    		$total = 0;
    		foreach ($data['data'] as $k=>$v){
    			$total += $v['inNum'];
    		}
    		//$stat_arr['title'] = '客流统计(入店：'.$total.'人次)';
    	}else{
    		//$stat_arr['title'] = '暂无客流统计';
    		$total = 0;
    	}
    	
    	return $total;
    }
    
}