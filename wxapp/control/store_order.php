<?php

/**
 * 商家收银
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

defined('InIMall') or exit('Access Invalid!');

class store_orderControl extends mobileHomeControl{

    public function __construct(){
        parent::__construct();
    }

    /**
     * 收银
     */
    public function indexOp(){
        
    }
	
    /**
     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式
     *
     */
    public function createOrderOp() {
    	$param = $_POST;
    	
    	$param['member_id']   = $member_id = $_POST['member_id'];
    	$param['store_id']   = $store_id = $_POST['store_id'];
    	$param['goods_list']   = $goods_list = str_replace('&quot;','"',$_POST['g_list']);//商品列表
    	$param['goods_amount']   = $goods_amount = $_POST['goods_amount'];//商品总金额
    	$param['order_amount']   = $order_amount = $_POST['order_amount'];//订单优惠后的金额
    	$param['vip_points']  = $vip_points = $_POST['vip_points']; //订单支付云币
    	$param['rebate_price']   = $_POST['rebate_price']; //订单返利的云币
    	$param['pd_amount']   = $_POST['pd_amount']; //余额支付金额
    	$param['rcb_amount']  = $_POST['rcb_amount']; //充值卡支付金额
    	$param['payment_code']    = $_POST['payment_code']; //支付方式
    	//手机端暂时不做支付留言，页面内容太多了
    	//$param['pay_message'] = json_decode($_POST['pay_message']);
    	//默认值
    	$param['address_id'] = 0; //收货地址ID，店铺自提
    	$param['order_from'] = 3; //来自pos
    	
    	$logic_buy = logic('buy');    
    	//得到会员等级
    	$model_member = Model('member');
    	$member_info = $model_member->getMemberInfoByID($member_id);
    	if ($member_info){
    		$member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
    		$member_discount = $member_gradeinfo['orderdiscount'];
    		$member_level = $member_gradeinfo['level'];
    	} else {
    		$member_discount = $member_level = 0;
    	}
    	$result = $this->buyStep2($param, $member_info);
    	if(!$result['state']) {
    		output_error($result['msg']);
    	}else{
    		$log_file = BASE_UPLOAD_PATH.'/mobile/cashier/'.date('Y-m-d').'_'.$member_id.'.log';
    		$pay_info = '['.json_encode($result['data']['order_list']).']';    		
    		file_put_contents($log_file,$pay_info,FILE_APPEND);
    	}
    	output_data(array('pay_sn' => $result['data']['pay_sn']));
    }
    /**
     * 购买第二步
     * @param array $post
     * @param int $member_id
     * @param string $member_name
     * @param string $member_email
     * @return array
     */
    public function buyStep2($post, $member_info) {
    
    	$this->_member_info = $member_info;
    	$this->_member_info['member_id'] = $member_info['member_id'];
    	$this->_member_info['member_name'] = $member_info['member_name'];
    	$this->_member_info['member_email'] = $member_info['member_email'];
    	$this->_member_info['member_mobile'] = $member_info['member_mobile'];
    	$post['goods_list'] = json_decode($post['goods_list'],true); //转译处理
    	$this->_post_data = $post;
    	
    	try {
    		$model = Model('order');
    		$model->beginTransaction();    	
    		//第1步 生成订单
    		$this->_createOrderStep1();
    		//第2步 冻结用户的账户以及返利管理奖云币
    		$this->_createOrderStep2();
    		
    		$model->commit();   	
    	
    		return callback(true,'',$this->_order_data);
    	
    	}catch (Exception $e){
    		$model->rollback();
    		return callback(false, $e->getMessage());
    	}
    
    }
    
    /**
     * 生成订单
     * @param array $input
     * @throws Exception
     * @return array array(支付单sn,订单列表)
     */
    private function _createOrderStep1() {    
    	
    	$member_id = $this->_member_info['member_id'];
    	$member_name = $this->_member_info['member_name'];
    	$member_email = $this->_member_info['member_email'];
    	$member_mobile = $this->_member_info['member_mobile'];
    	$store_id = $this->_post_data['store_id'];
    	$goods_list = $this->_post_data['goods_list'];
    	$store_goods_total = $this->_post_data['goods_amount'];
    	$store_order_total = $this->_post_data['order_amount'];
    	$vip_points_total = $this->_post_data['vip_points']; //支付的云币
    	$saleman_id = $this->_post_data['saleman_id']; //获取当前导购ID
    	$model_order = Model('order');
    	$logic_buy_1 = Logic('buy_1');
    	//存储生成的订单数据
    	$order_list = array();
    	//存储通知信息
    	$notice_list = array();
    	//检查各个店铺的支付方式,付款到哪里
    	$model_store = Model('store');
    	$pay_sn_total = 0;
    	$store_pm =$model_store->getStorePayment_MethodByID($store_id);
    	//收银台当日同一客户只生成一个支付单 暂时没处理
    	$pay_sn = $logic_buy_1->makePaySn($member_id);
    	$order_pay = array();
    	$order_pay['pay_sn'] = $pay_sn;
    	$order_pay['buyer_id'] = $member_id;
    	$order_pay_id = $model_order->addOrderPay($order_pay);
    	if (!$order_pay_id) {
    		throw new Exception('订单保存失败[未生成支付单]');
    	}
    	//print_r($goods_list);
    	//echo '<br>goods_barcode='.$goods_list[0]['goods_barcode'].'<br>';
    	//echo "订单保存成功[已生成支付单]".$order_pay_id.'<br>';
    	$pay_sn_total += 1;    
    	
    	//取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
    	list($store_premiums_list,$store_mansong_rule_list) = $logic_buy_1->getMansongRuleCartListByTotal($store_goods_total[$store_id]);
    	//重新计算店铺扣除满即送后商品实际支付金额
    	$store_final_goods_total = $logic_buy_1->reCalcGoodsTotal($store_goods_total[$store_id],$store_mansong_rule_list,'mansong');
    	//计算每个店铺(所有店铺级优惠活动)总共优惠多少
    	$store_promotion_total = $logic_buy_1->getStorePromotionTotal($store_goods_total[$store_id], $store_final_goods_total);
    	//取得本店优惠额度(后面用来计算每件商品实际支付金额，结算需要)
    	$promotion_total = !empty($store_promotion_total[$store_id]) ? $store_promotion_total[$store_id] : 0;
    	
    	$storeInfo = $model_store->getStoreInfo(array('store_id'=>$store_id),'store_name');
    	//每种商品的优惠金额累加保存入 $promotion_sum
    	$promotion_sum = 0;
    	$order = array();
    	$order_common = array();
    	$order_goods = array();
    	$order['order_sn']     = $logic_buy_1->makeOrderSn($order_pay_id);
    	$order['pay_sn']       = $pay_sn;
    	$order['store_id']     = $store_id;
    	$order['store_name']   = $storeInfo['store_name'];
    	//<!-- 分销选入店铺 start zhang -->
    	$order['up_id']   = '';//$goods_list[0]['up_id'];
    	$order['up_name'] = '';//$goods_list[0]['up_name'];
    	$order['buyer_id']     = $member_id;
    	$order['buyer_name']   = $member_name;
    	$order['buyer_email']  = $member_email;
    	$order['buyer_phone'] = $member_mobile;
    	$order['add_time']     = TIMESTAMP;
    	$order['payment_code'] = $this->_post_data['payment_code'];//orderPaymentName();
    	if(($this->_post_data['payment_code'] == 'predeposit' && $store_order_total == 0)||$this->_post_data['payment_code'] == 'xjpay'||$this->_post_data['payment_code'] == 'skpay'){
    		$order_state = ORDER_STATE_SEND;//直接发货
    		$order['payment_time']     = TIMESTAMP;//支付时间
    		$order['api_pay_time']     = TIMESTAMP;//组合付款时间    		
    	}else{
    		$order_state = ORDER_STATE_NEW;
    	}
    	$order['order_state']  =  $order_state;
    	$order['order_amount'] = $store_order_total;
    	$order['shipping_fee'] = 0;
    	$order['goods_amount'] = $store_goods_total;
    	$order['order_from']   = $this->_post_data['order_from'];
    	$order['order_type'] = 3;//订单类型1普通订单(默认),2预定订单,3自提订单
    	$order['chain_id'] = $store_id; //自提门店ID
    	$order['pd_amount'] = $this->_post_data['pd_amount'];
    	$order['rcb_amount'] = $this->_post_data['rcb_amount'];
    	$order['rpt_amount'] = 0;//红包支付金额
    	$order['pay_method']   = $store_pm['pay_method'];
    	$order['pay_store_id'] = $store_pm['pay_store_id'];
    	
    	//此处注意 该金额只是暂存数据库。真正使用云币在后面，确定付款的时候 在给予考虑 没有选择使用云币或账户的云币够不够 再给予扣除 。可使用云币抵扣的现金
    	$order['points_amount'] = $vip_points_total;
    	$order['usable_points'] = $vip_points_total;
    	//计算推广员、导购员提成 - 推广处理
    	if (OPEN_STORE_EXTENSION_STATE > 0){
    		if(!empty($saleman_id)){
    			$extension_id = $saleman_id;
    		}else{
    			$extension_id = $_SESSION['member_id'];//当前导购
    		}
    		$store_commis_amount = $store_other_total[$store_id]['store_commis_amount'];
    		$store_commis_points = $store_other_total[$store_id]['store_commis_points'];
    		if($store_commis_amount>0||$store_commis_points>0){
    			$order['mb_commis_totals'] = $store_commis_amount;
    			$order['mb_commis_points'] = $store_commis_points;
    			$order['saleman_id'] = $_SESSION['member_id'];
    			$order['saleman_name'] = $_SESSION['member_name'];
    		}else{
    			$Commisinfo=$this->_getOrderGoodsCommisTotals($goods_list,$extension_id,$store_id);
    			if (!empty($Commisinfo)){
    				$order['saleman_id'] =empty($Commisinfo['saleman_id'])?$_SESSION['member_id']:$Commisinfo['saleman_id'];
    				$order['saleman_name'] =empty($Commisinfo['saleman_name'])?$_SESSION['member_name']:$Commisinfo['saleman_name'];
    				$order['mb_commis_totals'] =$Commisinfo['CommisTotals'];
    			}
    		}    		
    	}
    	
    	$order_id = $model_order->addOrder($order);
    	if (!$order_id) {
    		throw new Exception('订单保存失败[未生成订单数据]');
    	}
    	//echo "订单保存成功[已生成订单数据]".$order_id.'<br>';
    	$order['order_id'] = $order_id;
    	$order_list[$order_id] = $order;
    	$order_common['order_id'] = $order_id;
		$order_common['store_id'] = $store_id;
    	$order_common['order_message'] = $input_pay_message;
	    //返利云币数
  		$order_common['order_pointscount'] = $this->_post_data['rebate_price'];
    	$order_common['reciver_info']= '';
    	$order_common['reciver_name'] = $member_name;
    	$order_common['reciver_city_id'] = 0;
    	//发票信息
    	$order_common['invoice_info'] = '';
    	//保存促销信息
    	if(is_array($store_mansong_rule_list[$store_id])) {
    		$order_common['promotion_info'] = addslashes($store_mansong_rule_list[$store_id]['desc']);
    	}
    	$order_id = $model_order->addOrderCommon($order_common);
    	if (!$order_id) {
    		throw new Exception('订单保存失败[未生成订单扩展数据]');
    	}
    	//echo "订单保存成功[已生成订单扩展数据]".$order_id.'<br>';
    	$promotion_rate = 0;
    	//生成order_goods订单商品数据
    	$i = 0;
    	if(!empty($goods_list)){
    		foreach ($goods_list as $goods_info) {
    			//如果不是优惠套装
    			$order_goods[$i]['order_id'] = $order_id;
    			$order_goods[$i]['goods_id'] = $goods_info['goods_id'];
    			$order_goods[$i]['store_id'] = $store_id;
    			if(empty($goods_info['goods_name'])){
    				$goods_name = '店铺直营商品，条码是'.$goods_info['goods_barcode'];
    			}else{
    				$goods_name = $goods_info['goods_name'];
    			}
    			$order_goods[$i]['goods_name'] = $goods_name;
    			$order_goods[$i]['goods_price'] = $goods_info['goods_price'];
    			$order_goods[$i]['goods_num'] = $goods_info['goods_num'];
    			$order_goods[$i]['goods_image'] = $goods_info['goods_image'];
    			$order_goods[$i]['goods_spec'] = $goods_info['goods_spec'];
    			$order_goods[$i]['buyer_id'] = $member_id;
    			if ($goods_info['ifgroupbuy']) {
    				$ifgroupbuy = true;
    				$order_goods[$i]['goods_type'] = 2;
    			}elseif ($goods_info['ifxianshi']) {
    				$order_goods[$i]['goods_type'] = 3;
    			}elseif ($goods_info['ifzengpin']) {
    				$order_goods[$i]['goods_type'] = 5;
    			}else {
    				$order_goods[$i]['goods_type'] = 1;
    			}
    			$order_goods[$i]['promotions_id'] = $goods_info['promotions_id'] ? $goods_info['promotions_id'] : 0;
    			$order_goods[$i]['commis_rate'] = 200;
    			$order_goods[$i]['gc_id'] = $goods_info['gc_id'] ? $goods_info['gc_id'] : 0;
    			//计算商品金额
    			$goods_total = $goods_info['goods_price'] * $goods_info['goods_num'];
    			//计算本件商品优惠金额
    			$promotion_value = floor($goods_total*($promotion_rate));
    			$order_goods[$i]['goods_pay_price'] = $goods_total - $promotion_value;
    			//推广员提成金额 推广处理
    			if (OPEN_STORE_EXTENSION_STATE > 0){
    				$order_goods[$i]['mb_commis_totals'] = ($goods_info['mb_commis_totals']>0)?$goods_info['mb_commis_totals']:0;
    			}
    			$promotion_sum += $promotion_value;
    			$i++;
    		
    			//存储库存报警数据
    			if ($goods_info['goods_storage_alarm'] >= ($goods_info['goods_storage'] - $goods_info['goods_num'])) {
    				$param = array();
    				$param['common_id'] = $goods_info['goods_commonid'];
    				$param['sku_id'] = $goods_info['goods_id'];
    				$notice_list['goods_storage_alarm'][$goods_info['store_id']] = $param;
    			}
    		}
    	}  	
    	
    	//将因舍出小数部分出现的差值补到最后一个商品的实际成交价中(商品goods_price=0时不给补，可能是赠品)
    	if($promotion_total > $promotion_sum) {
    		$i--;
    		for($i;$i>=0;$i--) {
    			if (floatval($order_goods[$i]['goods_price']) > 0) {
    				$order_goods[$i]['goods_pay_price'] -= $promotion_total - $promotion_sum;
    				break;
    			}
    		}
    	}
    	$insert = $model_order->addOrderGoods($order_goods);
    	if (!$insert) {
    		throw new Exception('订单商品保存失败[未生成商品数据]');
    	}
    	//echo "订单保存成功[已生成订单商品数据]".$insert.'<br>';
    	//保存数据
    	$this->_order_data['pay_sn'] = $pay_sn;
    	$this->_order_data['pay_sn_total'] = $pay_sn_total;
    	$this->_order_data['order_info'] = $order;
    	$this->_order_data['order_list'] = $order;
    }
    
    
    /**
     * 第二步 根据用户的支付情况给予处理
     * 如果订单中需要扣减云币和冻结余额的在此处操作
     */
    private function _createOrderStep2(){
    	$member_points = $this->_member_info['member_points'];
    	$member_exppoints = $this->_member_info['member_exppoints'];
    	$order_info = $this->_order_data['order_info'];
    	$model_pd = Model('predeposit');
    	//下单，支付被冻结的充值卡
    	$rcb_amount = floatval($order_info['rcb_amount']);
    	if ($rcb_amount > 0) {
    		$data_pd = array();
    		$data_pd['member_id'] = $order_info['buyer_id'];
    		$data_pd['member_name'] = $order_info['buyer_name'];
    		$data_pd['amount'] = $rcb_amount;
    		$data_pd['order_sn'] = $order_info['order_sn'];
    		$insertpd = $model_pd->changeRcb('order_freeze',$data_pd);
    		
    	}    	
    	//下单，支付被冻结的积分
    	$pd_amount = floatval($order_info['pd_amount']);
    	if ($pd_amount > 0) {
    		$data_pd = array();
    		$data_pd['member_id'] = $order_info['buyer_id'];
    		$data_pd['member_name'] = $order_info['buyer_name'];
    		$data_pd['amount'] = $pd_amount;
    		$data_pd['order_sn'] = $order_info['order_sn'];
    		$insertpd = $model_pd->changePd('order_freeze',$data_pd);
    	}
    	//下单，直接扣减云币
    	$usable_points = $order_info['usable_points'];
    	if($usable_points>0){
    		$model_points = Model('points');
    		$insertarr = array(
    				'pl_memberid'=>$order_info['buyer_id'], //会员编号
    				'pl_membername'=>$order_info['buyer_name'],//会员名称
    				'pl_points'=>0-$usable_points,//云币抵扣的金额
    				'orderprice'=>$order_info['order_amount'],//订单金额
    				'order_sn'=>$order_info['order_sn'],//订单编号
    				'order_id'=>$order_id,//订单序号
    				'pl_desc'=>'订单'.$order_info['order_sn'].'需要支付的云币'
    		);
    		$res_addplog = $model_points->savePointsLog('order',$insertarr,true);
    		
    		if ($res_addplog){
    			$goods_amount = $order_info['order_amount'];
    			//更新member内容
    			$model_member = Model('member');
    			$upmember_array = array();
    			$upmember_array['member_points'] = $member_points - $usable_points;
    			
    			$model_member->editMember(array('member_id'=>$order_info['buyer_id']),$upmember_array);    			
    		}
    	}
    	
    }
    /** 推广员 推广处理
     * 计算商品的推广导购提成金额
     * @param array $goods_list 商品列表
     * @return array 导购IDe、导购名称、总提成金额5
     */
    private function _getOrderGoodsCommisTotals(&$goods_list,$saleman_id='',$store_id='') {
    	if(empty($goods_list) || !is_array($goods_list)) return array();
    	if(empty($saleman_id)) return array();
    
    	$saleman_info=Model('extension')->getExtensionByMemberID($saleman_id,'member_id,member_name,mc_id,store_id');
    	//if (empty($saleman_info)) return array();
    	//如果是推广员，并且推广员不是这家店铺，则没有佣金，直接返回
    	//if (OPEN_STORE_EXTENSION_STATE == 1){
    	//	if ($saleman_info['mc_id']<10 && $saleman_info['store_id']!=$store_id){
    	//		return array();
    	//	}
    	//}
    	$goods_commis_id='promotion_cid';
    	if ($saleman_info['mc_id']==10 && !empty($saleman_info['mc_id'])){$goods_commis_id='saleman_cid';}
    	//提成比率库
    	$src_store_id = (OPEN_STORE_EXTENSION_STATE == 10)?GENERAL_PLATFORM_EXTENSION_ID:$store_id;
    	$commis_info=Model('extension_commis_class')->getCommisList($src_store_id);
    	if (empty($commis_info) || !is_array($commis_info)) return array();
    		
    	$commis_totals=0;
    	foreach ($goods_list as $vk=>$goods_info) {
    		if($goods_info['commis_amount']>0){
    			$commis_value = $goods_info['commis_amount']*$goods_info['goods_num'];
    		}else{
	    		//正常商品
	    		$commis_id=$goods_info[$goods_commis_id];
	    		if ($commis_id==0) continue;//商品佣金代码是否有效
	    		if (empty($commis_info[$commis_id]) || !is_array($commis_info[$commis_id])) continue;//商品佣金模板是否存在
	    
	    		$commis_class=$commis_info[$commis_id]['commis_class'];//佣金类型
	    		$commis_rate=$commis_info[$commis_id]['commis_rate'];//佣金比率
	    		$commis_value = 0;//商品佣金
	    		switch ($commis_class){
	    			case 0:  //无提成
	    				$commis_value = 0;
	    				break;
	    			case 1:  //固定佣金
	    				$commis_value = $commis_rate*$goods_info['goods_num'];
	    				break;
	    			case 2:  //售价佣金
	    				$commis_value = ($goods_info['goods_price']*$commis_rate/100)*$goods_info['goods_num'];
	    				break;
	    			case 3:  //利润佣金
	    				$commis_value = (($goods_info['goods_price']-$goods_info['goods_tradeprice'])*$commis_rate/100)*$goods_info['goods_num'];
	    				break;
	    			default:  //无提成
	    				$commis_value = 0;
	    				break;
	    		}
    		}
    		if ($commis_value<0) $commis_value=0;
    		$commis_totals += $commis_value;
    		$goods_list[$vk]['mb_commis_totals']=$commis_value;
    		
    	}
    	$CommisTotals_info=array();
    	$CommisTotals_info['saleman_id']=$saleman_info['member_id'];
    	$CommisTotals_info['saleman_name']=$saleman_info['member_name'];
    	$CommisTotals_info['CommisTotals']=$commis_totals;
    	return $CommisTotals_info;
    }
    
    /*****************************************---导购操作商家后台相关动作-----分割线----*******************************************/
    
    /**
     * 添加商品条码库
     */
    public function add_barcodeOp() {
    
    	$model_goods = Model('goods');
    	$store_id = $_POST['store_id'];
    	$g_barcode = $_POST['g_barcode'];
    	if(empty($store_id)){ //没有商家
    		$store_id = 0;
    	}
    	$gwhere['store_id'] = $store_id;
    	$gwhere['goods_barcode|goods_gbcode'] = $g_barcode;
    	$gbarcode_info = $model_goods->getGoodsBarcodeInfo($gwhere,'bid');
    	if(!empty($gbarcode_info)){
    		output_error("商品条码已存在");
    	}
    	$goods_costprice = $_POST['g_marketprice'] * 0.4;
    	if($goods_costprice>$_POST['g_price']){
    		$goods_array['goods_costprice'] = $_POST['g_price'];
    	}else{
    		$goods_array['goods_costprice'] = $goods_costprice;
    	}
    	$goods_array['goods_barcode'] 		= $g_barcode;
    	$goods_array['goods_marketprice'] 	= $_POST['g_marketprice'];
    	$goods_array['goods_price'] 		= $_POST['g_price'];
    	$goods_array['goods_discount'] 		= $_POST['g_discount'];
    	$goods_array['goods_tradeprice'] 	= $_POST['g_tradeprice'];
    	$goods_array['goods_gbcode'] 		= $_POST['g_gbcode'];
    	$goods_array['store_id'] 			= $store_id;
    	$goods_array['saleman_id'] 			= $_SESSION['member_id'];
    	$goods_array['commis_amount'] 		= $_POST['commis_amount'];
    	$goods_array['rebate_amount'] 		= $_POST['rebate_amount'];
    	$bid = $model_goods->addGoodsBarcode($goods_array);
    	if(empty($bid)) {
    		output_error("商品条码入库失败");
    	}
    	$result['bid'] = $bid;
    	$result['msg'] = "商品条码成功入库";
    	output_data($result);
    }
    
    /**
     * 根据商品条形码，获取商品ID
     */
    public function ajax_get_goodsIDOp() {
    	$scancode = !empty($_GET['scancode'])?$_GET['scancode']:$_POST['scancode'];
    	if(strpos($scancode,',')){
	    	$gscancode = htmlspecialchars_decode($scancode); //获取的格式:"3533631781002"
	    	$scan_temp = explode(",",$gscancode);//处理后的格式:3533631781002"
	    	$scancode = substr($scan_temp[1], 0, -1);
    	}
    	$store_id = $_GET['store_id'];
    	$gwhere = array();
    	if(!empty($store_id)){
    		$gwhere['store_id'] = $store_id;
    	}
    	$gwhere['goods_barcode|goods_serial'] = $scancode;
    	// 商品详细信息
    	$model_goods = Model('goods');
    	$goods_info = $model_goods->getGoodsInfo($gwhere,'goods_id,goods_price,goods_marketprice,goods_tradeprice,goods_serial');
    	if (empty($goods_info)) {
    		if(empty($store_id)){
    			$store_id = 0;
    		}
    		$gbwhere['store_id'] = $store_id;
    		$gbwhere['goods_barcode|goods_gbcode'] = $scancode;
    		$goods_barcode_info = $model_goods->getGoodsBarcodeInfo($gbwhere,'goods_price,goods_marketprice,goods_tradeprice');
    		if(empty($goods_barcode_info)){
    			output_error('商品条形码：'.$scancode.'不存在'.$goods_barcode_info['goods_marketprice']);
    		}else{
    			$goods_info = $goods_barcode_info;
    		}
    	}
    	$goods_info['scancode'] = $scancode;
    	output_data(array('goods_info'=>$goods_info));
    }
    /**
     * 根据会员的推广二维码获取到会员的ID及其等级相关的信息
     */
    public function ajax_member_scancodeOp() {
    	$scancode = $_GET['scancode']; //获取的格式:http://www.zlin-e.com/wap/tmpl/member/extension_join.html?extension=MTA3NDA"
    	$str_pos = strpos($scancode, '=');
		$extension = substr($scancode, $str_pos+1);
		$extension_id = urlsafe_b64decode($extension);
		
		$g_barcode = $_GET['g_barcode'];
		$store_id = $_GET['store_id'];
		
		$model_member = Model('member');
		$memberInfo = $model_member->getMemberInfoByID($extension_id,'member_id,member_name,member_truename,member_mobile,member_points,available_predeposit,available_rc_balance,member_exppoints');
    	if(empty($memberInfo)){
    		output_error('会员不存在，请提供正确的会员信息！');
    	}
    	$goodsInfo = $this->_mer_store_goodsInfo($extension_id, $store_id, $g_barcode);
    	//云币不足，需要现金补足
    	if($memberInfo['member_points']<$goodsInfo['vip_points']){
    		$goodsInfo['vip_price'] = $goodsInfo['vip_price'] + ($goodsInfo['vip_points']-$memberInfo['member_points'])/C("points_trade");
    		$goodsInfo['vip_points'] = $memberInfo['member_points'];
    	}
    	output_data(array('goodsInfo'=>$goodsInfo,'memberInfo'=>$memberInfo));
    }
    /**
     * 根据会员名获取到会员的相关信息
     */
    public function getMemberInfoOp(){
    	
    	$member_name = $_POST['member_name'];
    	$g_barcode = $_POST['g_barcode'];
    	$store_id = $_POST['store_id'];
    	$model_member = Model('member');
    	$memberInfo = $model_member->getMemberInfo(array('member_name|member_truename|member_mobile'=>$member_name),'member_id,member_name,member_truename,member_mobile,member_points,available_predeposit,available_rc_balance,member_exppoints');
    	if(empty($memberInfo)){
    		output_error('会员不存在，请提供正确的会员信息！');
    	}
    	$goodsInfo = $this->_mer_store_goodsInfo($memberInfo['member_id'], $store_id, $g_barcode);
    	//云币不足，需要现金补足
    	if($memberInfo['member_points']<$goodsInfo['vip_points']){
    		$goodsInfo['vip_price'] = $goodsInfo['vip_price'] + ($goodsInfo['vip_points']-$memberInfo['member_points'])/C("points_trade");
    		$goodsInfo['vip_points'] = $memberInfo['member_points'];
    	}
    	output_data(array('goodsInfo'=>$goodsInfo,'memberInfo'=>$memberInfo));
    }
    
    /**
     * 根据会员ID、商店ID、商品条码组 获取到商品信息
     */
    private function _mer_store_goodsInfo($member_id,$store_id,$g_barcode){
    	
    	$memberInfo = array();
    	$goods_list = array();
   		$gbarcode_list = array();
   		$gwhere = array();
   		if(empty($member_id)){
   			return false;
   		}
   		$gwhere['store_id'] = $gcwhere['store_id'] = $store_id;
		if(strpos($g_barcode,',')){
    		$scodeArr = explode(',',$g_barcode);
    		$unscodeArr = array_unique($scodeArr); //获取去重复后的数组
    		$gcodecount = count($unscodeArr);//获取 去重后的商品总数
    		$scodeArrNum = array_count_values($scodeArr); //计算每个商品数量
    		$gwhere['goods_barcode|goods_serial'] = array('in',$g_barcode);
    	}else{
    		$gwhere['goods_barcode|goods_serial'] = $g_barcode;
    		$gcodecount = 1;
    	}
    	// 商品详细信息
    	$model_goods = Model('goods');
    	$goods_list = $model_goods->getGoodsList($gwhere,'goods_id,store_id,goods_barcode,goods_price,goods_marketprice,goods_tradeprice,goods_serial,goods_promotion_price,promotion_cid,rebate_cid,up_id,up_name');
    	if(empty($goods_list)){//是否有查询到商品
    		$wgoodscount = 0;
    	}else{
	    	$wgoodscount = count($goods_list);//商品库的数量
    	}
    	$price_amount = 0.00; //商品总额
    	$marketprice_amount = 0.00; //吊牌价总额
    	$goods_sum = 0;//商品总数
    	$commis_amount = 0;//返佣金额
    	$rebate_amount = 0;//返利金额
    	$vip_price = 0.00; //应付金额
    	$vip_points = 0; //云币必须取整 向上取整
    	$rebate_price = 0.00; //返利积分
    	$rebate_points = 0;//返利云币
    	$logic_goods = Logic('goods');
    	//商品库里是否可查询到数据
    	if($wgoodscount>0){
    		//有，则先处理商品库里的数据
    		$grop_code = array();//查到的条码组
    		foreach ($goods_list as $key=>$gvar){
    			//计算商品价格之和
    			$var_gbarcode = $gvar['goods_barcode'];
    			if(empty($scodeArrNum)){
    				$gnum = 1;
    			}else{
    				$gnum = $scodeArrNum[$var_gbarcode];//获得对应商品码数量
    			}
    			//计算VIP的价格
    			$vipInfo = $logic_goods->goodsVip($gvar['goods_id'],$member_id,$gvar);
    			$goods_sum 		+= $gnum;//商品数汇总
    			$vip_price 		+= $vipInfo['vip_price']*$gnum; //应付金额汇总
    			$vip_points 	+= $vipInfo['vip_points']*$gnum; //应付云币汇总
    			$rebate_price 	+= $vipInfo['rebate_price']*$gnum; //返利积分汇总
    			$rebate_points 	+= $vipInfo['rebate_points']*$gnum;//返利云币汇总
    			$price_amount 	+= $gvar['goods_price']*$gnum;//会员价汇总
    			$marketprice_amount += $gvar['goods_marketprice']*$gnum;//吊牌价汇总
    			
    			$goods_list[$key]['goods_num'] = $gnum;
    			$grop_code[] = $var_gbarcode;
    		}
    		$nocodeArr = array_merge(array_diff($unscodeArr,$grop_code),array_diff($grop_code,$unscodeArr));//对比两个数组找出不同项
    		if(!empty($nocodeArr)){//是否存在未查询到的商品码
    			$zh_barcode = implode(',',$nocodeArr);
	    		$gcwhere['goods_barcode|goods_gbcode'] = array('in',$zh_barcode);    			
    		}
    	}
    	
    	//如果查询出结果的数量小于实际提交的数量时
    	if ($wgoodscount<$gcodecount) {
    		if($gcodecount == 1){
    			if(strpos($g_barcode,',')){
   					$g_barcode = $scodeArr[0];
   				}
   				$gcwhere['goods_barcode|goods_gbcode'] = $g_barcode;
   			}
    		$gbarcode_list = $model_goods->getGoodsBarcodeList($gcwhere,'goods_price,goods_marketprice,goods_tradeprice,goods_barcode,commis_amount,rebate_amount');
    		if(!empty($gbarcode_list)){
    			foreach ($gbarcode_list as $key=>$gvar){
   					//计算商品价格之和
   					$var_gbarcode = $gvar['goods_barcode'];
   					if(empty($scodeArrNum)){
   						$gnum = 1;
    				}else{
    					$gnum = $scodeArrNum[$var_gbarcode];
    				}
    				//计算VIP的价格
    				$vipInfo = $logic_goods->goodsVip('',$member_id,$gvar);
    				$goods_sum 		+= $gnum;//商品数汇总
    				$vip_price 		+= $vipInfo['vip_price']*$gnum; //应付金额汇总
    				$vip_points 	+= $vipInfo['vip_points']*$gnum; //应付云币汇总
    				$rebate_price 	+= $vipInfo['rebate_price']*$gnum; //返利积分汇总
    				$rebate_points 	+= $vipInfo['rebate_points']*$gnum;//返利云币汇总    				
    				$price_amount 	+= $gvar['goods_price']*$gnum;
   					$marketprice_amount += $gvar['goods_marketprice']*$gnum;   					
    				$commis_amount += $gbarcode_list[$key]['commis_amount'] = $gvar['commis_amount']*$gnum;
    				$rebate_amount += $gbarcode_list[$key]['rebate_amount'] = $gvar['rebate_amount']*$gnum;
   					//默认值
    				$gbarcode_list[$key]['goods_id'] = 0;
    				$gbarcode_list[$key]['up_id'] = 0;
    				$gbarcode_list[$key]['up_name'] = '';
    				$gbarcode_list[$key]['goods_num'] = $gnum;
    			}
    		}
    	}
    	
    	if(empty($goods_list)&&empty($gbarcode_list)){
    		output_error('该商品不存在，请提供正确的商品条码！');
    	}
    	$log_message = '';
    	if(!empty($goods_list)){
    		$log_message .= json_encode($goods_list);
    	}
    	if(!empty($gbarcode_list)){
    		if(!empty($goods_list)){
    			$log_message = substr($log_message,0,-1);
    			$json_gb_list = json_encode($gbarcode_list);
    			$log_message .= ','.substr($json_gb_list,1);
    		}else{
    			$log_message .= json_encode($gbarcode_list);
    		}
    	}
    	$goods_info = array();
    	$goods_info['store_id'] = $store_id;
    	$goods_info['goods_sum'] = $goods_sum;
    	$goods_info['goods_marketprice'] = $marketprice_amount;//市场价 goods_marketprice
    	$goods_info['goods_price'] = $price_amount;
    	$goods_info['goods_tradeprice'] = $tradeprice_amount; //批发价即为店铺的成本价
    	$goods_info['commis_amount'] = $commis_amount;
    	$goods_info['rebate_amount'] = $rebate_amount;
    	$goods_info['vip_price'] = imPriceFormat($vip_price);//应付金额
    	$goods_info['vip_points'] = ceil($vip_points); //云币必须取整 向上取整
    	$goods_info['rebate_price'] = imPriceFormat($rebate_price);//返利积分
    	$goods_info['rebate_points'] = imPriceFormat($rebate_points);//返利云币
    	$goods_info['goods_list'] = $log_message;
    	
    	return $goods_info;
    }
    
    /**
     * VIP会员使用云币和店铺成本价购买商品的算法 zhangc
     * @param unknown $goods_id
     * @return multitype:unknown
     */
    public function goodsVip($store_id,$member_id,$goods_info){
    	// 商品详细信息
    	$model_goods = Model('goods');
    	$model_store = Model('store');
    	$market_price = $goods_info['goods_marketprice'];//市场价 goods_marketprice
    	$goods_price = $goods_info['goods_price'];
    	$trade_price = $goods_info['goods_tradeprice']; //批发价即为店铺的成本价
    	$points_trade = C("points_trade");
    	if(OPEN_STORE_EXTENSION_STATE==10){
    		$mall_store_id = GENERAL_PLATFORM_EXTENSION_ID; //全站
    	}else{
    		$mall_store_id = $store_id;
    	}
    	//店铺管理奖比率
    	$rate_manage  = Model('extension_commis_rate')->getRate_Manage($mall_store_id);
    	//最初时的模式：会员支付金额 = 店铺成本价 + 管理奖的分成（利润*管理奖比例）
    	$vip_price = imPriceFormat($trade_price + ($goods_price - $trade_price) * ($rate_manage/100));
	    //根据商家的ID 查询出该商家是直接云币抵扣 还是返利云币
    	$points_way = $model_store->getStorePoints_WayByID($store_id);
    	if($points_way == 1){
    		$goods_info['vip_price'] = $goods_price;
    		$goods_info['rebate_price'] = 0;
    	}elseif ($points_way == 2){
    		$goods_info['vip_price'] = $goods_price;
    		$goods_info['rebate_price'] = imPriceFormat(($goods_price - $trade_price) * (1-($rate_manage/100)));//会员价-批发价的差额，还要扣除返利管理奖的部分
    		//该方式就是市场价-会员价 用于抵扣云币
    		$vip_price = $goods_price;
    		$goods_price = $market_price;
    	}else{
    		$goods_info['vip_price'] = $vip_price;
    		$goods_info['rebate_price'] = 0;
    	}
    	//会员支付的云币 = （商城价 - 会员支付的金额）/云币换金额比例
    	$vip_points = ($goods_price - $vip_price)/$points_trade;
    
    	$storeInfo = $model_store->getStoreInfo(array('store_id'=>$store_id),'is_membergrade');
    	//根据会员ID 查询会员信息
    	$model_member = Model('member');
    	$member_info = $model_member->getMemberInfoByID($member_id);
    	$member_points  = $member_info['member_points'];
    	if($member_points<0){
    		$member_points = 0;
    	}
    	//检查该店铺是否开启等级制度
    	if(!empty($storeInfo['is_membergrade'])){
    		$grade_level = $model_member->getOneMemberGradeLevel($member_info['member_exppoints']);//会员等级
    		$gradeInfo = $model_member->getMemberGradeInfo(array('store_id'=>$store_id,'grade_level'=>$grade_level),'points_rate');//获得会员云币使用率
    		if(!empty($gradeInfo)){
    			$points_rate = $gradeInfo['points_rate'];
    		}else{
    			$points_rate = 100;
    		}
    		$vip_level_points = $vip_points*$points_rate*0.01;
    		$wanting_points = $vip_points - $vip_level_points;
    		if($member_points<$vip_level_points){
    			$vip_level_points = $wanting_points + ($vip_level_points-$member_points);
    			$vip_level_points = $member_points;
    		}
    		$vip_level_price = $goods_info['vip_price'] + $wanting_points*$points_trade;
    		$goods_info['vip_price'] = $vip_level_price;
    		$goods_info['vip_points'] = ceil($vip_level_points);
    	}else{
    		if($member_points<$vip_points){
    			$goods_info['vip_price'] = $goods_info['vip_price'] + ($vip_points-$member_points)*$points_trade;
    			$vip_points = $member_points;
    		}
    		$goods_info['vip_points'] = ceil($vip_points); //云币必须取整 向上取整
    	} 
    	return $goods_info;
    }    
    
}