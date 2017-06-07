<?php
/**
 * 小程序-快捷收银
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 * @author zhangc
 */
 
defined('InIMall') or exit('Access Invalid!');

class cashier_quickControl extends wxappHomeControl{
	public function __construct() {
        parent::__construct();
    }
	
    /**
     * 微信小程序-快捷收银
     */
    public function indexOp() {
    	
    }    

    /**
     * 扫描商品条形码
     * @param 条形码 scancode
     * @return 返回商品基础信息:goods_price,goods_marketprice,goods_tradeprice
     */
    public function scan_barcodeOp() {
    	$goods_barcode = !empty($_REQUEST['goods_barcode'])?$_REQUEST['goods_barcode']:0;
    	if(strpos($goods_barcode,' ')){
    		$scan_temp = explode(" ",$goods_barcode);
    		$goods_barcode = end($scan_temp);
    	}
    	if(strpos($goods_barcode,',')){
    		$scan_temp = explode(",",$goods_barcode);
    		$goods_barcode = end($scan_temp);
    	}
    	if(empty($goods_barcode)){
    		output_error('条形码不可为空,是否存在特殊字符如：，或 空格');
    	}
    	$subgnum = substr_count($_REQUEST['goodsCode'],' ');
    	$dsubgnum = substr_count($_REQUEST['goodsCode'],',');
    	$goods_num = $subgnum + $dsubgnum;
    	$store_id = $_REQUEST['store_id'];
    	$gwhere = array();
    	$gwhere['store_id'] = $store_id;
    	$gwhere['goods_barcode|goods_serial'] = $goods_barcode;
    	// 商品详细信息
    	$model_goods = Model('goods');
    	$goods_info = $model_goods->getGoodsInfo($gwhere,'goods_id,goods_price,goods_name,goods_marketprice,goods_tradeprice,goods_serial');
    	if (empty($goods_info)) {
    		if(empty($store_id)){
    			$store_id = 0;
    		}
    		$gbwhere['store_id'] = $store_id;
    		$gbwhere['goods_barcode|goods_gbcode'] = $goods_barcode;
    		$goods_barcode_info = $model_goods->getGoodsBarcodeInfo($gbwhere,'goods_price,goods_marketprice,goods_tradeprice');
    		if(empty($goods_barcode_info)){
    			output_error('商品条形码：'.$goods_barcode.'不存在', array('error_code'=>404));
    		}else{
    			$goods_info = $goods_barcode_info;
    		}
    	}
    	$goods_info['goods_num'] = $goods_num+1;
    	$goods_info['goods_barcode'] = $goods_barcode;
    	output_data(array('goodsInfo'=>$goods_info));
    }
    /**
     * 扫描会员的二维码
     * @param 会员二维码（推广码） scancode     * 
     * @return 商品优惠后的信息:goodsInfo {
     * 		"store_id":133,"goods_sum":2,"goods_price":200.00,"goods_marketprice":500.00,"goods_tradeprice":100.00,
     * 		"commis_amount":"10.00","rebate_amount":100.00,"vip_price":"180.00","vip_points":20,"commis_points":"0","rebate_points":"10",
     * 		"goods_list":"{"goods_id":0,"goods_price":200.00,"goods_marketprice":500.00,"goods_tradeprice":100.00,"goods_serial":"AI123456"}"
     * 	}
     * 店铺ID、商品总数、商品会员价、商品吊牌价、商品批发价、返佣积分、返利积分、VIP总金额、还需支付云币数、返佣云币、返利云币
     * 商品ID、商品会员价、商品吊牌价、商品批发价、货号
     * @return 会员信息 格式：memberInfo {"member_id":10086,"member_name":"浩瀚星空","member_truename":"李浩","member_mobile":"13612345678","member_points":"186","available_predeposit":"1860.00","available_rc_balance":"0.00","member_exppoints":"1666"}
     * 说明：会员ID、会员名称、姓名、手机号、云币余额、可用积分余额、可用充值卡余额、经验值（等级有关）
     */
    public function scan_member_qrcodeOp() {
    	$memberQrcode = $_REQUEST['memberQrcode']; //获取的格式:http://www.zlin-e.com/wap/tmpl/member/extension_join.html?extension=MTA3NDA"
    	$g_barcode = $_REQUEST['g_barcode'];
    	if(empty($g_barcode)){
    		output_error('没有商品条码');
    	}
    	
    	$store_id = $_REQUEST['store_id'];
    	$model_member = Model('member');
    	
    	if(strpos($memberQrcode,'=')){
	    	$str_pos = strpos($memberQrcode, '=');
	    	$extension = substr($memberQrcode, $str_pos+1);
	    	$extension_id = urlsafe_b64decode($extension);
	    	$memberInfo = $model_member->getMemberInfoByID($extension_id,'member_id,member_name,member_truename,member_mobile,member_points,available_predeposit,available_rc_balance,member_exppoints');
    	}else{
    		$m_condition['member_name|member_mobile'] = $memberQrcode;
    		$memberInfo = $model_member->getMemberInfo($m_condition,'member_id,member_name,member_truename,member_mobile,member_points,available_predeposit,available_rc_balance,member_exppoints');
    		$extension_id = $memberInfo['member_id'];
    	}
    	
    	if(empty($memberInfo)){
    		output_error('会员不存在，请提供正确的会员信息！');
    	}else{
    		$memberInfo['points_trade'] = C("points_trade"); //云币换算比
    	}
    	$goodsInfo = $this->_mer_store_goodsInfo($extension_id, $store_id, $g_barcode);
    	//云币不足，需要现金补足
    	if($memberInfo['member_points']<$goodsInfo['vip_points']){
    		//补贴部分的金额 =  实际需要云币支付的金额 - 会员云币余额*云币换算比
    		$makeup_amount = $goodsInfo['makeup_amount'] = $goodsInfo['points_amount']-$memberInfo['member_points']/C("points_trade");    		
    		$goodsInfo['vip_price'] = $goodsInfo['vip_price'] + $makeup_amount;
    		$goodsInfo['vip_points'] = $goodsInfo['points_amount'] = $memberInfo['member_points'];
    	}else{
    		$goodsInfo['makeup_amount'] = 0;
    	}
    	$start = strtotime('2017-04-28 00:00:00');
    	$end = strtotime('2017-06-30 23:59:59');    		
    	if(!empty($store_id) && $store_id == '133' && $start <= TIMESTAMP && $end >= TIMESTAMP){    		
    		//满足条件   		
	    	$model_order = Model('order');
	    	$order_condition = array();
	    	$order_condition['buyer_id'] = $extension_id;//客户是否下单
	    	$order_condition['store_id'] = $store_id;
	    	$order_condition['add_time'] = array(array('egt',$start),array('lt',$end),'and'); //活动时间是5月份
	    	$order_condition['order_state'] = array('neq',ORDER_STATE_CANCEL);
	    	$goodsInfo['ordernum'] = $model_order->getOrderCount($order_condition); //正常交易中的订单
	    	$goodsInfo['isActivity'] = 1;//有活动
    	}else{
    		$goodsInfo['ordernum'] = 0;
    		$goodsInfo['isActivity'] = 0;    		
    	}
    	output_data(array('goodsInfo'=>$goodsInfo,'memberInfo'=>$memberInfo));
    }
    
    /**
     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式
     *
     */
    public function createOrderOp() {
    	$param = $_REQUEST;
    	$param['member_id']   = $member_id = $_REQUEST['member_id'];
    	$param['saleman_id']  = $saleman_id	= $_REQUEST['saleman_id'];
    	$param['store_id']   = $store_id = $_REQUEST['store_id'];
    	$param['g_barcode']   = $g_barcode = $_REQUEST['g_barcode'];
	$param['goods_amount']   = $goods_amount = $_REQUEST['goods_amount'];//商品总金额
    	if($_REQUEST['order_type']>3){//五一活动处理
    		$order_amount = $goods_amount;//订单优惠后的金额
    	}else{
    		$order_amount = $_REQUEST['order_amount'];//订单优惠后的金额
    	}    	
    	$param['order_amount']   = $order_amount;
    	$param['vip_points']  = $vip_points = $_REQUEST['payable_points']; //订单支付云币
    	$param['rebate_points']   = $_REQUEST['rebate_points']; //订单返利的云币
    	$param['rebate_price']   = $_REQUEST['rebate_price']; //订单返利的云币
    	$param['pd_amount']   = $_REQUEST['pd_amount']; //余额支付金额
    	$param['rcb_amount']  = $_REQUEST['rcb_amount']; //充值卡支付金额
    	$param['payment_code']    = $_REQUEST['payment_code']; //支付方式
    	//手机端暂时不做支付留言，页面内容太多了
    	$param['order_message'] = $_REQUEST['order_message'];
    	//默认值
    	$param['address_id'] = 0; //收货地址ID，店铺自提
    	$param['order_from'] = 3; //来自pos
    	$param['order_type']    = $_REQUEST['order_type']; //订单类型
    	 
    	if(empty($g_barcode)){
    		output_error('商品不可为空！');
    	}
    	if(empty($store_id)){
    		output_error('店铺不可为空！');
    	}
    	if(empty($member_id)){
    		output_error('会员不可为空！');
    	}
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
	$param['goods_list']   = $goods_list = $this->get_goodsList($store_id, $g_barcode,$member_id);//商品列表
    	//file_put_contents('test.log',"获取到的商品列表：".json_encode($goods_list).PHP_EOL,FILE_APPEND);
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
    	$post['goods_list'] = $post['goods_list'];
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
    	$member_points = $this->_member_info['member_points'];
    	$member_exppoints = $this->_member_info['member_exppoints'];
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
    	if(($this->_post_data['payment_code'] == 'predeposit' && $store_order_total == 0)){//暂时屏蔽直接付款状态||$this->_post_data['payment_code'] == 'xjpay'||$this->_post_data['payment_code'] == 'skpay'){
    		$order_state = ORDER_STATE_SEND;//直接发货
    		$order['payment_time']     = TIMESTAMP;//支付时间
    		$order['api_pay_time']     = TIMESTAMP;//组合付款时间
    	}else{
    		$order_state = ORDER_STATE_NEW;
    	}
    	$order['order_state']  =  $order_state;
    	$order['order_amount'] = $store_order_total + $this->_post_data['pd_amount'] +  $this->_post_data['rcb_amount'];
    	$order['shipping_fee'] = 0;
    	$order['goods_amount'] = $store_goods_total;
    	$order['order_from']   = $this->_post_data['order_from'];
    	$order['order_type'] = empty($this->_post_data['order_type'])?1:$this->_post_data['order_type'];//订单类型1普通订单(默认),2预定订单,3自提订单
    	$order['chain_id'] = $store_id; //自提门店ID
    	$order['pd_amount'] = $this->_post_data['pd_amount'];
    	$order['rcb_amount'] = $this->_post_data['rcb_amount'];
    	$order['rpt_amount'] = 0;//红包支付金额
    	$order['pay_method']   = $store_pm['pay_method'];
    	$order['pay_store_id'] = $store_pm['pay_store_id'];
    	 
    	//此处注意 该金额只是暂存数据库。真正使用云币在后面，确定付款的时候 在给予考虑 没有选择使用云币或账户的云币够不够 再给予扣除 。可使用云币抵扣的现金
    	$order['usable_points'] = $vip_points_total;
    	//返利云币数
    	$order_common['order_pointscount'] = $this->_post_data['rebate_points'];
    	
    	//下单，直接扣减云币
    	$usable_points = ceil($vip_points_total/C("points_trade")); //需要抵扣的云币数;
    	if($usable_points>0){
    		if($member_points<$usable_points){
    			$usable_points = $member_points;
    			$vip_points_total = $member_points*C("points_trade");
    		}
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
    	
    		$order['points_amount'] = $vip_points_total;
    	}else{
    		$order['points_amount'] = 0.00;
    	}
    	//计算推广员、导购员提成 - 推广处理
    	if (OPEN_STORE_EXTENSION_STATE > 0){
    		if(!empty($saleman_id)){
    			$extension_id = $saleman_id;
    		}else{
    			$extension_id = $_REQUEST['saleman_id'];//当前导购
    		}
    		$store_commis_amount = $this->_post_data['commis_amount'];
    		$store_commis_points = $this->_post_data['commis_points'];
    		if($store_commis_amount>0||$store_commis_points>0){
    			$order['mb_commis_totals'] = $store_commis_amount;
    			$order['mb_commis_points'] = $store_commis_points;
    			
    		}else{
    			$Commisinfo=$this->_getOrderGoodsCommisTotals($goods_list,$extension_id,$store_id);
    			if (!empty($Commisinfo)){
    				$order['mb_commis_totals'] =$Commisinfo['CommisTotals'];
    			}
    		}
    	}
    	//服务导购
    	$order['saleman_id'] = empty($saleman_id)?$Commisinfo['saleman_id']:$saleman_id;
    	$seller_info = Model('seller')->getSellerInfo(array('store_id'=>$store_id,'member_id'=>$saleman_id),'seller_name,nick_name');
    	$order['saleman_name'] = empty($seller_info['nick_name'])?$seller_info['seller_name']:$seller_info['nick_name'];
    	$order_id = $model_order->addOrder($order);
    	if (!$order_id) {
    		throw new Exception('订单保存失败[未生成订单数据]');
    	}
    	//echo "订单保存成功[已生成订单数据]".$order_id.'<br>';
    	$order['order_id'] = $order_id;
    	$order_list[$order_id] = $order;
    	$order_common['order_id'] = $order_id;
    	$order_common['store_id'] = $store_id;
    	$order_common['order_message'] = $this->_post_data['order_message'];
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
    	//file_put_contents('test.log',json_encode($goods_list).PHP_EOL,FILE_APPEND);
    	if(!empty($goods_list)){
    		foreach ($goods_list as $goods_info) {
    			//如果不是优惠套装
    			$order_goods[$i]['order_id'] = $order_id;
    			$order_goods[$i]['goods_id'] = $goods_info['goods_id'];
    			$order_goods[$i]['store_id'] = $store_id;
    			if(empty($goods_info['goods_id'])&&empty($goods_info['goods_name'])){
    				$goods_name = '店铺直营商品，条码是'.$goods_info['goods_barcode'];
    			}else{
    				$goods_name = $goods_info['goods_name'];
    			}
    			$order_goods[$i]['goods_name'] = $goods_name;
    			$goods_price  = empty($goods_info['goods_marketprice'])?$goods_info['goods_price']:$goods_info['goods_marketprice'];
    			$order_goods[$i]['goods_price'] = $goods_price;
    			$vip_price  = empty($goods_info['vip_price'])?$goods_info['goods_price']:$goods_info['vip_price'];
    			$order_goods[$i]['goods_pay_price'] = $vip_price;
    			$order_goods[$i]['goods_num'] = $goods_info['goods_num'];
    			$order_goods[$i]['goods_image'] = $goods_info['goods_image'];
    			//规格
    			$_tmp_name = unserialize($goods_info['spec_name']);
    			$_tmp_value = unserialize($goods_info['goods_spec']);
    			if(empty($goods_info['goods_spec'])){
    				$goods_spec = '';
    			}elseif (is_array($_tmp_name) && is_array($_tmp_value)) {
    				$_tmp_name = array_values($_tmp_name);$_tmp_value = array_values($_tmp_value);
    				foreach ($_tmp_name as $sk => $sv) {
    					$new_array['goods_spec'] .= $sv.'：'.$_tmp_value[$sk].'，';
    				}
    				$goods_spec = rtrim($new_array['goods_spec'],'，');
    			}else{    				
    				$g_spec = unserialize($goods_info['goods_spec']);
    				$spec_name = '';
    				if (!empty($g_spec)) {
    					foreach ($g_spec as $key => $val){
    						$model_spec = Model('spec');
    						$sp_varr = $model_spec->specValueOne(array('sp_value_id'=>$key)); //这里没必要加  'store_id'=>$store_id 主要查的是规格组
    						//根据 规格值 查询数据库中是否存在该规格
    						if(!empty($sp_varr)){
    							//存在则获取到规格组和规格的ID
    							$sp_id = $sp_varr['sp_id'];   			//规格组ID
    							//根据规格组ID 查出规格组的名称
    							$sp_info = $model_spec->getSpecInfo($sp_id, 'sp_name');
    							//可以获取到商品common的sp_name
    							$spec_name .= $sp_info['sp_name'].'：';
    						}
    						$spec_name .= $val.' ';
    					}
    				}
    				$goods_spec = $spec_name;
    			}
	    		$order_goods[$i]['goods_spec'] = $goods_spec;    				
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
    
    	$g_barcode = $_REQUEST['g_barcode'];
    	if(empty($g_barcode)){
    		output_error("商品条码不可为空！");
    	}
    	$store_id = $store_id = empty($_REQUEST['store_id'])?$_REQUEST['store_id']:$_REQUEST['store_id'];
    	if(empty($store_id)){ //没有商家
    		$store_id = 0;
    	}
    	$model_goods = Model('goods');
    	$gwhere['store_id'] = $store_id;
    	$gwhere['goods_barcode|goods_gbcode'] = $g_barcode;
    	$gbarcode_info = $model_goods->getGoodsBarcodeInfo($gwhere,'bid');
    	if(!empty($gbarcode_info)){
    		output_error("商品条码已存在");
    	}
    	$goods_costprice = $_REQUEST['g_marketprice'] * 0.6;
    	if($goods_costprice>$_REQUEST['g_price']){
    		$goods_array['goods_costprice'] = $_REQUEST['g_price'];
    	}else{
    		$goods_array['goods_costprice'] = $goods_costprice;
    	}
    	$goods_array['goods_barcode'] 		= $g_barcode;
    	$goods_array['goods_marketprice'] 	= $_REQUEST['g_marketprice'];
    	$goods_array['goods_price'] 		= $_REQUEST['g_price'];
    	$goods_array['goods_discount'] 		= $_REQUEST['g_discount'];
    	$goods_array['goods_tradeprice'] 	= $_REQUEST['g_tradeprice'];
    	$goods_array['goods_gbcode'] 		= $_REQUEST['g_gbcode'];
    	$goods_array['store_id'] 			= $store_id;
    	$goods_array['saleman_id'] 			= $_REQUEST['member_id'];
    	$goods_array['commis_amount'] 		= $_REQUEST['commis_amount'];
    	$goods_array['rebate_amount'] 		= $_REQUEST['rebate_amount'];
    	$bid = $model_goods->addGoodsBarcode($goods_array);
    	if(empty($bid)) {
    		output_error("商品条码入库失败");
    	}
    	$result['bid'] = $bid;
    	$result['msg'] = "商品条码成功入库";
    	output_data($result);
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
    	if(strpos($g_barcode,' ')){
    		$scodeArr = explode(' ',$g_barcode);
    		$unscodeArr = array_unique($scodeArr); //获取去重复后的数组
    		$gcodecount = count($unscodeArr);//获取 去重后的商品总数
    		$scodeArrNum = array_count_values($scodeArr); //计算每个商品数量
    		$g_barcode = implode(',',$scodeArr);//转换一下
    		$gwhere['goods_barcode|goods_serial'] = array('in',$g_barcode);
    	}elseif(strpos($g_barcode,',')){
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
    	$goodsList = $model_goods->getGoodsList($gwhere,'goods_id,store_id,goods_barcode,goods_price,goods_marketprice,goods_tradeprice,goods_name,goods_serial,goods_image,goods_spec,gc_id,goods_promotion_price,promotion_cid,rebate_cid,up_id,up_name');
    	if(empty($goodsList)){//是否有查询到商品
    		$wgoodscount = 0;
    	}else{
    		$wgoodscount = count($goodsList);//商品库的数量
    	}
    	$price_amount = 0.00; //商品总额
    	$marketprice_amount = 0.00; //吊牌价总额
    	$goods_sum = 0;//商品总数
    	$commis_price = 0;//返佣金额
    	$commis_points = 0;//返拥云币
    	$vip_price = 0.00; //应付金额
    	$vip_points = 0; //云币必须取整 向上取整
    	$rebate_price = 0.00; //返利积分
    	$rebate_points = 0;//返利云币
    	$logic_goods = Logic('goods');
    	//商品库里是否可查询到数据
    	if($wgoodscount>0){
    		//有，则先处理商品库里的数据
    		$grop_code = array();//查到的条码组
    		foreach ($goodsList as $key=>$gvar){
    			//计算商品价格之和
    			$var_gbarcode = $gvar['goods_barcode'];
    			//不存在查询的条码才可继续
    			if(!in_array($var_gbarcode,$grop_code) || empty($grop_code)){
    				$goods_list[$key] = $gvar;
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
    			$commis_price 	+= $vipInfo['commis_price']*$gnum; //返拥积分汇总
    			$commis_points 	+= $vipInfo['commis_points']*$gnum;//返拥云币汇总
    			$price_amount 	+= $gvar['goods_price']*$gnum;//会员价汇总
    			$marketprice_amount += $gvar['goods_marketprice']*$gnum;//吊牌价汇总
    			 
    			$goods_list[$key]['goods_num'] = $gnum;
    			$grop_code[] = $var_gbarcode;
    			}else{
    				$wgoodscount--;
    			}
    		}
    		$nocodeArr = array_merge(array_diff($unscodeArr,$grop_code),array_diff($grop_code,$unscodeArr));//对比两个数组找出不同项
    		if(!empty($nocodeArr)){//是否存在未查询到的商品码
    			$zh_barcode = implode(',',$nocodeArr);
    		}
    	}else{
    		$zh_barcode = $g_barcode;
    	}
    	
    	
	//如果查询出结果的数量小于实际提交的数量时
    	if ($wgoodscount<$gcodecount) {
    		if($gcodecount == 1){
    			if(strpos($g_barcode,',') || strpos($g_barcode,' ')){
    				$g_barcode = $scodeArr[0];
    			}
    			$gcwhere['goods_barcode|goods_gbcode'] = $g_barcode;
    		}else{
    			$gcwhere['goods_barcode|goods_gbcode'] = array('in',$zh_barcode);
    		}
    		$gbarcode_list = $model_goods->getGoodsBarcodeList($gcwhere,'*');
    		if(!empty($gbarcode_list)){
			$gbrop_code = array();//查到的条码组
    			foreach ($gbarcode_list as $key=>$gvar){
    				//计算商品价格之和
    				$var_gbarcode = $gvar['goods_barcode'];
    				//不存在查询的条码才可继续
    				if(!in_array($var_gbarcode,$gbrop_code)){
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
    				$commis_price 	+= $vipInfo['commis_price']*$gnum; //返拥积分汇总
    				$commis_points 	+= $vipInfo['commis_points']*$gnum;//返拥云币汇总
    				$price_amount 	+= $gvar['goods_price']*$gnum;
    				$marketprice_amount += $gvar['goods_marketprice']*$gnum;
    				//默认值
    				$gbarcode_list[$key]['goods_id'] = 0;
    				$gbarcode_list[$key]['up_id'] = 0;
    				$gbarcode_list[$key]['up_name'] = '';
    				$gbarcode_list[$key]['goods_num'] = $gnum;
	    				$gbrop_code[] = $var_gbarcode;
    				}
    			}
    		}
    	}
    	
    	if(empty($goods_list)&&empty($gbarcode_list)){
    		output_error('该商品不存在，请提供正确的商品条码！');
    	}
    	$log_message = '';
    	/* 屏蔽原因时因为小程序在传输时有字符限制，导致goods_list不完整。
    	 * 传输的数据如：[{&quot;bid&quot;:&quot;774&quot;,&quot;goods_name&quot;:&quot;\\u9ed1\\u767d\\u7ee3\\u82b1\\u77ed\\u886c\\u886b \\u9ed1\\u767d\\u82b1 155\\/80A(S) AI24161194102&quot;,&quot;store_id&quot;:&quot;133&quot;,&quot;goods
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
    	}*/
    	$goods_info = array();
    	$goods_info['store_id'] = $store_id;
    	$goods_info['goods_sum'] = $goods_sum;
    	$goods_info['goods_marketamount'] = $marketprice_amount;//市场价 goods_marketprice
    	$goods_info['goods_amount'] = $goods_price;
    	$goods_info['goods_tradeamount'] = $tradeprice_amount; //批发价即为店铺的成本价
    	$goods_info['commis_amount'] = $commis_price;
    	$goods_info['commis_points'] = $commis_points;
    	$goods_info['vip_amount'] = imPriceFormat($vip_price);//应付金额
    	$goods_info['points_amount'] = imPriceFormat($vip_points); //可用云币低金额
    	$goods_info['vip_points'] = ceil($vip_points); //云币必须取整 向上取整
    	$goods_info['rebate_amount'] = imPriceFormat($rebate_price);//返利积分
    	$goods_info['rebate_points'] = imPriceFormat($rebate_points);//返利云币
    	$goods_info['goods_list'] = $log_message;
    	 
    	return $goods_info;
    }
    
    /**
     * 获取到商品列表
     * 必须重新查询一次
     */
    private function get_goodsList($store_id,$g_barcode,$member_id){
    
    	$goods_list = array();
    	$gbarcode_list = array();
    	$logic_goods = Logic('goods');
    	$gwhere = array();
    	$gwhere['store_id'] = $gcwhere['store_id'] = $store_id;
    	if(strpos($g_barcode,' ')){
    		$scodeArr = explode(' ',$g_barcode);
    		$unscodeArr = array_unique($scodeArr); //获取去重复后的数组
    		$gcodecount = count($unscodeArr);//获取 去重后的商品总数
    		$scodeArrNum = array_count_values($scodeArr); //计算每个商品数量
    		$g_barcode = implode(',',$scodeArr);//转换一下
    		$gwhere['goods_barcode|goods_serial'] = array('in',$g_barcode);
    	}elseif(strpos($g_barcode,',')){
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
    	$goodsList = $model_goods->getGoodsList($gwhere,'goods_id,store_id,goods_barcode,goods_price,goods_marketprice,goods_tradeprice,goods_name,goods_serial,goods_image,spec_name,goods_spec,gc_id,goods_promotion_price,promotion_cid,rebate_cid,up_id,up_name');
    	if(empty($goodsList)){//是否有查询到商品
    		$wgoodscount = 0;
    	}else{
    		$wgoodscount = count($goodsList);//商品库的数量
    	}
    	$goods_sum = 0;//商品总数
    	//商品库里是否可查询到数据
    	if($wgoodscount>0){
    		//有，则先处理商品库里的数据
    		$grop_code = array();//查到的条码组
    		foreach ($goodsList as $key=>$gvar){
    			//计算商品价格之和
    			$var_gbarcode = $gvar['goods_barcode'];
    			//不存在查询的条码才可继续
    			if(!in_array($var_gbarcode,$grop_code) || empty($grop_code)){
    				$goods_list[$key] = $gvar;
    			if(empty($scodeArrNum)){
    				$gnum = 1;
    			}else{
    				$gnum = $scodeArrNum[$var_gbarcode];//获得对应商品码数量
    			}
    			//计算VIP的价格
    			$vipInfo = $logic_goods->goodsVip('',$member_id,$gvar);
    			$goods_list[$key]['vip_price'] = $vipInfo['vip_price'];
    			$goods_list[$key]['goods_num'] = $gnum;
    			$grop_code[] = $var_gbarcode;
    			}else{
    				$wgoodscount--;
    			}
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
    			if(strpos($g_barcode,',') || strpos($g_barcode,' ')){
    				$g_barcode = $scodeArr[0];
    			}
    			$gcwhere['goods_barcode|goods_gbcode'] = $g_barcode;
    		}
    		$gbarcode_list = $model_goods->getGoodsBarcodeList($gcwhere,'*');
    		if(!empty($gbarcode_list)){
    			$gbrop_code = array();
    			foreach ($gbarcode_list as $key=>$gvar){
    				//计算商品价格之和
    				$var_gbarcode = $gvar['goods_barcode'];
    				//不存在查询的条码才可继续
    				if(!in_array($var_gbarcode,$gbrop_code)){
    				if(empty($scodeArrNum)){
    					$gnum = 1;
    				}else{
    					$gnum = $scodeArrNum[$var_gbarcode];
    				}
    				//计算VIP的价格
    				$vipInfo = $logic_goods->goodsVip('',$member_id,$gvar);    				
    				//默认值
    				$gbarcode_list[$key]['goods_id'] = 0;
    				$gbarcode_list[$key]['up_id'] = 0;
    				$gbarcode_list[$key]['up_name'] = '';
    				$gbarcode_list[$key]['goods_num'] = $gnum;
    				$gbarcode_list[$key]['vip_price'] = $vipInfo['vip_price'];
	    				$gbrop_code[] = $var_gbarcode;
    				}
    			}
    		}
    	}    
    	if(empty($goods_list)&&empty($gbarcode_list)){
    		output_error('该商品不存在，请提供正确的商品条码！');
    	}
    	
    	if(!empty($gbarcode_list)){
    	if(empty($goods_list)){
    			$goods_list = $gbarcode_list;
    		}else{
    			$goods_list = array_merge($goods_list,$gbarcode_list);
    	}
    	}
	return $goods_list;
    }
}
