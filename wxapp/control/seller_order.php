<?php
/**
 * 导购-我的订单
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class seller_orderControl extends BaseSellerControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 订单列表
     * 
     */
    public function order_listOp() {
        $model_order = Model('order');
        $condition = array();
        //列表出未支付、未发货、未收货状态
		$condition = $this->order_state_type($_REQUEST["state_type"]);
        $condition['store_id'] = $this->store_id;
        //角色ID：0 表示当前导购 ；1 表示全店
        if(empty($_REQUEST['roleIndex'])){
		if(!empty($_REQUEST['saleman_id']) && $_REQUEST['saleman_id']!="undefined"){
			$condition['saleman_id'] = $_REQUEST['saleman_id'];
        	}
		}
        if(!empty($_REQUEST['keywords'])){
        	$condition['order_sn|pay_sn|buyer_name|buyer_phone|payment_code|shipping_code|trade_no|chain_code|saleman_name'] = array('like', '%' . $_REQUEST['keywords'] . '%');
        }
        $page_nums = !empty($_REQUEST['page_count'])?$_REQUEST['page_count']:10; //每页显示的条数
        $page_curr = !empty($_REQUEST['curpage'])?$_REQUEST['curpage']:1; //当前显示第几页
        //列表出未支付、未发货、未收货状态
        //$condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND));//未支付订单
    	
        //$order_list = $model_order->getNormalOrderList($condition, $this->page, '*', 'order_id desc','', array('order_goods'));
		$order_list = $model_order->getOrderList($condition, $page_nums, '*', 'order_id desc','',array('order_goods'));
		
        $new_order_list = array();            
        $i=0;//小程序 排序就是从小到大的
        foreach ($order_list as $okey=>$value) {
        	//显示收货
        	//$value['if_receive'] = $model_order->getOrderOperateState('receive',$value);
        	//显示锁定中
        	$value['if_lock'] = $model_order->getOrderOperateState('lock',$value);
        	//显示物流跟踪
        	//$value['if_deliver'] = $model_order->getOrderOperateState('deliver',$value);
        	$new_order_list[$i] = $value;
			if(empty($value['pay_method'])=='1'){
				$value['pay_store_name'] = "平台";
                    }
			if(empty($value['saleman_name'])){
				if(!empty($value['saleman_id'])){
					$seller_condition['store_id'] = $this->store_id;
					$seller_condition['member_id'] = $value['saleman_id'];
					$sellerInfo = Model('seller')->getSellerInfo($seller_condition,'nick_name,seller_name');
					if(!empty($sellerInfo)){
						$new_order_list[$i]['saleman_name'] = $saleman_name = empty($sellerInfo['nick_name'])?$sellerInfo['seller_name']:$sellerInfo['nick_name'];
					}else{
						$new_order_list[$i]['saleman_name'] = $saleman_name = "店长";
					}
				}else{
					$new_order_list[$i]['saleman_name'] = $saleman_name = "店长";
				}
			}else{
				$saleman_name = $value['saleman_name'];
			}
        		$new_order_list[$i]['buyer_name'] = $value['buyer_name'];//购买人昵称
        		$new_order_list[$i]['buyer_avatar'] = getMemberAvatarForID($value['buyer_id']);//购买人的头像        	
			$new_order_list[$i]['add_time'] = date('Y-m-d H:i',$value['add_time']);

			$new_order_list[$i]['payment_name'] = $value['payment_name'];
			
			$order['order_state'] = $order_state = $value['order_state'];//订单状态
			$order['delay_time'] = $value['delay_time'];//收货时间
			//付款之后 + 订单锁定之前均可 退货 退款有此操作
			if ($order_state >= ORDER_STATE_PAY) {//已发货后对商品操作
				$refund = $this->getRefundState($order);//根据订单状态判断是否可以退款退货
				//$new_order_list[$i]['state_desc'] = '退款/退货中...'; //订单状态名称转换
			}else{
				$refund = 0;
			}
			$new_order_list[$i]['refund'] = $refund;
			$new_order_list[$i]['serve_type'] = $value['order_from']=='3'?'导购:'.$saleman_name:'自助购物';//下单类型
			$i++;		
        }
        
        $order_count = $model_order->getOrderCount($condition);
        $page_count = $model_order->gettotalpage();
	
	//file_put_contents("test.log",json_encode($new_order_list).PHP_EOL,FILE_APPEND);
        output_data(array('order_list' => $new_order_list,'order_count'=>$order_count),'订单列表', wxapp_page($page_count));
    }
	
    /**
     * 根据订单状态判断是否可以退款退货
     *
     * @param
     * @return array
     */
    public function getRefundState($order) {
    	$refund = '0';//默认不允许退款退货
    	$order_state = $order['order_state'];
    	$model_trade = Model('trade');
    	$order_paid = $model_trade->getOrderState('order_paid');//订单状态20:已付款
    	$order_shipped = $model_trade->getOrderState('order_shipped');//30:已发货
    	$order_completed = $model_trade->getOrderState('order_completed');//40:已收货
    	switch ($order_state) {
    		case $order_paid:
    			$refund = '2';//退款
    			break;
    		case $order_shipped:
    			$refund = '1';//退货
    			break;
    		case $order_completed:
    			$order_refund = $model_trade->getMaxDay('order_refund');//收货完成后15天内可以申请退款退货
    			$delay_time = $order['delay_time']+60*60*24*$order_refund;
    			if ($delay_time > time()) {
    				$refund = '1';//退货
    			}
    			break;
    		default:
    			$refund = '0';//不满足退货退款
    			break;
    	}
    	return $refund;
    }
    /**
     * 修改订单金额
     * @param order_id 订单ID
     * @param realAmount 应付金额
     * @param changePriceType 修改价格类型
     */
    public function alterOrderAmountOp(){
    	//验证支付单信息
    	$model_order = Model('order');
    	$condition = array();
    	$condition['order_id'] = $order_id = $_REQUEST['order_id'];
    	//同步异步通知时,预定支付尾款时需要用到已经支付状态
    	$condition['order_state'] = array('in',array(ORDER_STATE_NEW));//未支付订单
    	$order_info = $model_order->getOrderInfo($condition,'','order_id, order_sn, pay_sn, buyer_id, store_id, payment_code, goods_amount, order_amount, rcb_amount, pd_amount, points_amount,saleman_id,saleman_name');
    	if(empty($order_info)){
    		output_error("根据订单的最新状态，无法修改订单金额！");
    	}
    	$pay_amount = $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
    	//修改订单价格
		if(!empty($_REQUEST['realAmount']) && $_REQUEST['realAmount'] != $pay_amount){
			$realAmount = $_REQUEST['realAmount'];
			if($_REQUEST['changePriceType']=='2'){
				//折扣类型
				$discount = ($order_info['goods_amount']/$realAmount); //折扣 = 商品总额/实际应付金额				
				$model_order->editOrderCommon(array('discount'=>$discount),array('order_id'=>$order_id));
			}else{
				//金额修改类型
				$promotion_total = ($pay_amount - $realAmount); //优惠金额 = 订单总额 - 实际应付金额
				$model_order->editOrderCommon(array('promotion_total'=>$promotion_total),array('order_id'=>$order_id));
			}
			//重新生成支付单
			$member_id = $order_info['buyer_id'];
			$logic_buy_1 = Logic('buy_1');
			$pay_sn = $logic_buy_1->makePaySn($member_id);
			$order_pay = array();
			$order_pay['pay_sn'] = $pay_sn;
			$order_pay['buyer_id'] = $member_id;
			$order_pay_id = $model_order->addOrderPay($order_pay);
			if($order_pay_id){
				$editOrder['pay_sn']  = $pay_sn;
			}
			$editOrder['order_amount'] = $realAmount;
			$resOrder = $model_order->editOrder($editOrder,array('order_id'=>$order_id));
			if(empty($resOrder)){
				output_error("订单金额修改失败");
			}
			//记录订单日志
			$data = array();
			$data['order_id'] = $order_id;
			$data['log_role'] = 'seller';
			$data['log_user'] = empty($order_info['saleman_name'])?'导购ID：'.$order_info['saleman_id']:$order_info['saleman_name'];
			$data['log_msg'] = '支付单号：'.$order_info['pay_sn']."，给优惠了￥".$pay_amount - $realAmount;
			$model_order->addOrderLog($data);
			output_data($resOrder,"订单金额修改成功！");
		}else{
			output_error("订单金额并未发生变化");
		}	
    }
    /**
     * 修改订单支付方式
     * @param order_id 订单ID
     * @param payment_code 支付方式
     */
    public function alterOrderPayCodeOp(){
    	//验证支付单信息
    	$model_order = Model('order');
    	$condition = array();
    	$condition['order_id'] = $order_id = $_REQUEST['order_id'];
    	//同步异步通知时,预定支付尾款时需要用到已经支付状态
    	$condition['order_state'] = array('in',array(ORDER_STATE_NEW));//未支付订单
    	$order_info = $model_order->getOrderInfo($condition,'','order_id, payment_code, saleman_id,saleman_name');
    	if(empty($order_info)){
    		output_error("根据订单的最新状态，是无需修改支付方式的！");
    	}
    	//修改订单价格
    	if(!empty($_REQUEST['payment_code']) && $_REQUEST['payment_code'] != $order_info['payment_code']){
    		$payment_code = $_REQUEST['payment_code'];    		
    		$editOrder['payment_code'] = $payment_code;
    		$resOrder = $model_order->editOrder($editOrder,array('order_id'=>$order_id));
    		if(empty($resOrder)){
    			output_error("支付方式修改失败");
    		}
    		$payment_name = orderPaymentName($payment_code);
    		//记录订单日志
    		$data = array();
    		$data['order_id'] = $order_id;
    		$data['log_role'] = 'seller';
    		$data['log_user'] = empty($order_info['saleman_name'])?'导购ID：'.$order_info['saleman_id']:$order_info['saleman_name'];
    		$data['log_msg'] = '更换了支付方式：'.$order_info['payment_name'];
    		$model_order->addOrderLog($data);
    		output_data($payment_name,"支付方式修改成功！");
    	}else{
    		output_error("支付方式并未发生变化");
    	}
    }
    /**
     * 确定收款
     * @param order_id 订单ID
     * @param store_id 店铺ID
     * @param trade_no 支付单号
     */
    public function receive_payOp() {
    	$model_order = Model('order');
    	$logic_order = Logic('order');
    	$order_id = intval($_POST['order_id']);
            
    	$condition = array();
    	$condition['order_id'] = $order_id;
    	$condition['store_id'] = $this->store_id;
    	$order_info = $model_order->getOrderInfo($condition);
    	$if_allow = $model_order->getOrderOperateState('seller_receive_pay',$order_info);
    	if (!$if_allow) {
    		output_error('该订单您无权完成此操作');
    	}
    	$order_list = array();
    	$order_list[0] = $order_info;
    	$post['payment_code'] = $order_info['payment_code'];
    	$post['trade_no'] = $_REQUEST['trade_no'];
    	$result = $logic_order->changeOrderReceivePay($order_list,'seller',$order_info['saleman_name'],$post);
    	if(!$result['state']) {
    		output_error($result['msg']);
    	} else {
    		output_data($result,"订单已支付成功");
    	}
    }
    /**
     * 已发货：1、商家去发货；2、客户到点自提；
     */
    public function send_outOp() {
    	$model_order = Model('order');
    	$logic_order = Logic('order');
    	$order_id = intval($_POST['order_id']);    
    	$condition = array();
    	$condition['order_id'] = $order_id;
    	$condition['store_id'] = $this->store_id;
    	$order_info = $model_order->getOrderInfo($condition);
    	$if_allow = $model_order->getOrderOperateState('store_send',$order_info);//商家去发货
    	if (!$if_allow) {
    		output_error('您无权操作该订单');
    	}
		//判断是否自提，自提则无需更新地址
    	//if(!empty($order_info['chain_id'])){
    		$data = array();
    		$data['shipping_code']  = $_POST['shipping_code'];//物流单号
    		$data['order_state'] = ORDER_STATE_SEND;
    		$data['delay_time'] = TIMESTAMP;//延迟时间
    		$result = $model_order->editOrder($data,$condition);
    		$commdata['shipping_time'] = TIMESTAMP;
    		$expressInfo = Model('express')->getExpressInfoByECode($_REQUEST['express_code']);
    		$commdata['shipping_express_id'] = $expressInfo['id'];
    		$model_order->editOrderCommon($commdata,array('order_id'=>$order_id));
    	//}else{
    		//发货无需再去修改收货地址，除非是客户要求修改
    		//$_POST['reciver_info'] = $this->_get_reciver_info();
    		//否则就需要去发货
    		//$result = $logic_order->changeOrderSend($order_info,'seller', $order_info['saleman_name'], '导购取消订单');
    	//}    	
    	if (!$result) {
    		output_error('订单发货失败');
    	}else {
    		output_data($result,"订单发货成功");
    	}    	
    }
    /**
     * 生成并发送提货码
     */
    public function send_chainCodeOp() {
    	$model_order = Model('order');
    	$logic_order = Logic('order');
    	$order_id = intval($_POST['order_id']);
    	$condition = array();
    	$condition['order_id'] = $order_id;
    	$condition['store_id'] = $this->store_id;
    	$order_info = $model_order->getOrderInfo($condition,array('order_common'));
    	if($order_info['order_from']!=3 && !empty($order_info['chain_code'])){
    		output_error('提货码已经存在，无需再生成');
    	}
    	$data['chain_id'] = $this->store_id;
    	$data['chain_code'] = $chain_code = sprintf('%03d', (float) microtime() * 1000)
    		. sprintf('%03d', (int) $order_info['buyer_id'] % 1000);//
    	$update = $model_order->editOrder($data,array('order_id'=>$order_id));    	
    	if (!$update) {
    		output_error('提货码生成失败');
    	}else {
    		$param = array();
    		$param['site_name'] = C('site_name');
    		$param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
    		$param['verify_code'] = $chain_code;
    		$mobile = $order_info['extend_order_common']['reciver_info']['phone'];
    		$message    = "您有一笔订单即将在".$order_info['store_name']."门店提货，提货码是：".$chain_code."。";
    		$sms = new Sms();
    		$result = $sms->send($mobile,$message);
    		if($result){
    			output_data($result,"提货码已经发送到收货人的手机上");
    		}else{
	    		output_data($update,"提货码生成成功");
    		}
    	}
    }
    /**
     * 确定收货：1、客户到店自提，商家发货后即可自动收货；
     * 		 2、快递到用户手中后，需要用户自动收货也可根据物流的信息判断是否已经收货，还可根据系统定时任务，发货后7个工作日自动收货；
     */
    public function receiveOp() {
    	$model_order = Model('order');
    	$logic_order = Logic('order');
    	$order_id = intval($_POST['order_id']);
    	$condition = array();
    	$condition['order_id'] = $order_id;
    	$condition['store_id'] = $this->store_id;
    	$order_info = $model_order->getOrderInfo($condition);
    	if($order_info['order_from']!=3 && $order_info['chain_code']!=$_POST['chain_code']){
    		output_error($order_info['chain_code'].'提货码错误，客户无法提货'.$_POST['chain_code']);
    	}
    	$if_allow = $model_order->getOrderOperateState('store_receive',$order_info);//商家去发货
    	if (!$if_allow) {
    		output_error('该订单目前状态是不允许此操作');
    	}    	
    	//已收货
    	$result = $logic_order->changeOrderStateReceive($order_info,'seller', $order_info['saleman_name'], '导购代收货');
    	if (!$result) {
    		output_error('订单发货失败');
    	}else {
    		output_data($result,"订单发货成功");
    	}
    }
    /**
     * 申请退货：1、客户到店，由导购发起退换货
     * 		 2、客户自己发起退货，店长审批通过后。客户邮寄到店，签收后退款给客户
     */
    public function applyRefundOp() {
    	$model_order = Model('order');
    	$logic_order = Logic('order');
    	$order_id = intval($_POST['order_id']);
    	$condition = array();
    	$condition['order_id'] = $order_id;
    	$condition['store_id'] = $this->store_id;
    	$order_info = $model_order->getOrderInfo($condition);
    	$if_allow = $model_order->getOrderOperateState('refund_cancel',$order_info);//申请换货
    	if (!$if_allow) {
    		output_error('该订单目前状态是不允许此操作');
    	}
    	//已收货
    	$result = $logic_order->changeOrderSuccessApplyRefund($order_info,'seller', $order_info['saleman_name'], '导购申请退换货');
    	if (!$result) {
    		output_error('订单申请退换货失败');
    	}else {
    		output_data($result,"订单申请退换货成功，等待店长审核");
    	}
    }
    /**
     * 店长同意退货
     */
    public function refundReceiveOp() {
    	$model_order = Model('order');
    	$logic_order = Logic('order');
    	$order_id = intval($_POST['order_id']);
    	$condition = array();
    	$condition['order_id'] = $order_id;
    	$condition['store_id'] = $this->store_id;
    	$order_info = $model_order->getOrderInfo($condition);
    	$if_allow = $model_order->getOrderOperateState('refund_cancel',$order_info);//申请换货
    	if (!$if_allow) {
    		output_error('该订单目前状态是不允许此操作');
    	}
    	//已收货
    	$result = $logic_order->changeOrderApplyRefundReceive($order_id,'seller', $order_info['saleman_name'], '店长确定退货');
    	if (!$result) {
    		output_error('订单退换货失败');
    	}else {
    		output_data($result,"订单退换货成功");
    	}
    }
    /**
     * 取消订单
     */
    public function order_cancelOp() {
    	$model_order = Model('order');
    	$logic_order = Logic('order');
    	$order_id = intval($_POST['order_id']);
    	$condition = array();
    	$condition['order_id'] = $order_id;
    	$condition['store_id'] = $this->store_id;
    	$order_info = $model_order->getOrderInfo($condition);
    	$if_allow = $model_order->getOrderOperateState('store_cancel',$order_info);
    	if (!$if_allow) {
    		output_error('您无权操作该订单');
    	}
    	$result = $logic_order->changeOrderStateCancel($order_info,'seller', $order_info['saleman_name'], '导购取消订单');
    	if(!$result['state']) {
    		output_error($result['msg']);
    	} else {
    		output_data($result,"成功取消订单");
    	}
    }
    /**
     * 重建订单
     * @param order_id 订单ID
     * @param store_id 店铺ID
     * @param saleman_id 最新服务导购ID
     */
    public function again_createOp() {
    	$model_order = Model('order');
    	$logic_order = Logic('order');
    	$logic_buy_1 = Logic('buy_1');
    	$order_id = intval($_POST['order_id']);
    	$condition = array();
    	$condition['order_id'] = $order_id;
    	$condition['store_id'] = $this->store_id;
    	$order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
    	$member_id = $order_info['buyer_id'];
    	$pay_sn = $logic_buy_1->makePaySn($member_id);
    	$order_pay = array();
    	$order_pay['pay_sn'] = $pay_sn;
    	$order_pay['buyer_id'] = $member_id;
    	$order_pay_id = $model_order->addOrderPay($order_pay);
    	if (!$order_pay_id) {
    		output_error('订单保存失败[未生成支付单]');
    	}
    	$lastOInfo = $model_order->getOrderInfo('','','order_id','order_id desc');//最后一笔订单    	
    	
    	$order['order_id'] = $lastOInfo['order_id']+1;
    	$order['order_sn']     = $logic_buy_1->makeOrderSn($order_pay_id);
    	$order['pay_sn']       = $pay_sn;   	
    	$order['add_time']     = TIMESTAMP;
    	$order['payment_time'] = 0;//支付时间
    	$order['api_pay_time'] = 0;//组合付款时间
    	$order['order_state']  =  10;
    	$order['order_from']   = 3;//3、wxapp
    	$order['order_type'] = 1;//订单类型1普通订单(默认),2预定订单,3自提订单
    	$order['store_id']     = $order_info['store_id'];
    	$order['store_name']   = $order_info['store_name'];
    	$order['up_id']   = $order_info['up_id'];
    	$order['up_name'] = $order_info['up_name'];
    	$order['buyer_id']     = $order_info['buyer_id'];
    	$order['buyer_name']   = $order_info['buyer_name'];
    	$order['buyer_email']  = $order_info['buyer_email'];
    	$order['buyer_phone'] = $order_info['buyer_phone'];
    	$order['payment_code'] = $order_info['payment_code'];
    	$order['order_amount'] = $order_info['order_amount'];
    	$order['shipping_fee'] = $order_info['shipping_fee'];
    	$order['goods_amount'] = $order_info['goods_amount'];
    	$order['chain_id'] = $order_info['chain_id']; //自提门店ID
    	$order['pd_amount'] = $order_info['pd_amount'];
    	$order['rcb_amount'] = $order_info['rcb_amount'];
    	$order['rpt_amount'] = $order_info['rpt_amount'];//红包支付金额
    	$order['pay_method']   = $order_info['pay_method'];
    	$order['pay_store_id'] = $order_info['pay_store_id'];
    	$order['usable_points'] = $order_info['usable_points'];
    	$order['points_amount'] = $order_info['points_amount'];
    	$order['mb_commis_totals'] = $order_info['mb_commis_totals'];
    	$order['mb_commis_points'] = $order_info['mb_commis_points'];
    	//服务导购
    	$order['saleman_id'] = $saleman_id = $_POST['saleman_id'];
    	$seller_info = Model('seller')->getSellerInfo(array('store_id'=>$store_id,'member_id'=>$saleman_id),'seller_name,nick_name');
    	$order['saleman_name'] = empty($seller_info['nick_name'])?$seller_info['seller_name']:$seller_info['nick_name'];
	
    	$order_id = $model_order->addOrder($order);
    	if (!$order_id) {
    		output_error('订单保存失败[未生成订单数据]');
    	}
    	//echo "订单保存成功[已生成订单数据]".$order_id.'<br>';
    	$order_common = $order_info['extend_order_common'];
    	$order_common['order_id'] = $order_id;
    	$order_id = $model_order->addOrderCommon($order_common);
    	if (!$order_id) {
    		output_error('订单保存失败[未生成订单扩展数据]');
    	}
    	$order_goods = $order_info['extend_order_goods'];
    	$order_goods['order_id'] = $order_id;
    	$insert = $model_order->addOrderGoods($order_goods);
    	if (!$insert) {
    		output_error('订单商品保存失败[未生成商品数据]');
    	}
    	output_data($result,"订单新建成功");
    }
    //订单状态
	private function order_state_type($state) { 
		switch ($state){
			case '1':
				$condition['order_state'] = ORDER_STATE_NEW;//10
				break;
			case '2':
				$condition['order_from'] = array('in',array(1,2)); //普通订单
				$condition['order_state'] = ORDER_STATE_PAY;//20
				break;
			case '21'://导购下单都是自提单
				$condition['order_from'] = '3';
				$condition['order_state'] = ORDER_STATE_PAY;//20
				break;
			case '3':
				$condition['order_state'] = ORDER_STATE_SEND;//30
				break;
			case '4':
				$condition['order_state'] = ORDER_STATE_SUCCESS; //40
				break;
			case '0':
				$condition['order_state'] = ORDER_STATE_CANCEL; //0
				break;
			default:
				$condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY,ORDER_STATE_SEND));
		}
		return $condition;
	}
	/**
	 * 取得订单状态文字输出形式
	 *
	 * @param array $order_info 订单数组
	 * @return string $order_state 描述输出
	 */
	public function orderStateName($order_state) {
		switch ($order_state) {
			case ORDER_STATE_CANCEL:
				$order_state = '已取消';
				break;
			case ORDER_STATE_NEW:
				$order_state = '待付款';
				break;
			case ORDER_STATE_PAY:
				$order_state = '已支付';
				break;
			case ORDER_STATE_SUCCESS:
				$order_state = '已完成';
				break;
		}
		return $order_state;
	}

	

    /**
     * 订单确认收货
     */
    public function order_receiveOp() {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $order_id = intval($_POST['order_id']);

        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        //$condition['order_type'] = array('1','3');
        $order_info = $model_order->getOrderInfo($condition);
        if(empty($order_info)){
        	output_error('订单并非真实存在，请重新登录后再操作');
        }
        $if_allow = $model_order->getOrderOperateState('receive',$order_info);
        if (!$if_allow) {
            output_error('操作已被确定');
        }

        $result = $logic_order->changeOrderStateReceive($order_info,'buyer', $this->member_info['member_name'],'签收了货物');
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data('1');
        }
    }

    /**
     * 物流跟踪
     */
    public function search_deliverOp(){
        $order_id   = intval($_POST['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在');
        }

        $model_order    = Model('order');
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS))) {
            output_error('订单不存在');
        }

        $express = rkcache('express',true);
        $e_code = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
        $e_name = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];

        $deliver_info = $this->_get_express($e_code, $order_info['shipping_code']);
        output_data(array('express_name' => $e_name, 'shipping_code' => $order_info['shipping_code'], 'deliver_info' => $deliver_info));
    }

	/**
     * 订单详情
     */
    public function order_infoOp(){
		$order_id = intval($_REQUEST['order_id']);
        if ($order_id <= 0) {
			output_error('订单id不存在');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = intval($_REQUEST['store_id']);
        $order_info = $model_order->getOrderInfo($condition,array('order_goods','order_common','store'));

        if (empty($order_info) || $order_info['delete_state'] == ORDER_DEL_STATE_DROP) {
            output_error('订单不存在或已被删除');
        }
        //可用积分不为空
        if(!empty($order_info['usable_points'])&&!empty($order_info['buyer_id'])){
        	//查询会员的云币数
        	$model_member = Model('member');
        	$memberInfo = $model_member->getMemberInfoByID($order_info['buyer_id'],'member_points');
        	if($memberInfo['member_points']<$order_info['usable_points']){
        		//补贴部分的金额 =  实际需要云币支付的金额 - 会员云币余额*云币换算比
        		$makeup_amount = $order_info['makeup_amount'] = $order_info['points_amount']-$memberInfo['member_points']/C("points_trade");
        	}else{
        		$order_info['makeup_amount'] = 0;
        	}
        }else{
        	$order_info['makeup_amount'] = 0;
        }        
        $order_info['buyer_avatar'] = getMemberAvatarForID($order_info['buyer_id']);//购买人的头像
        $order_info['order_from'] =  str_replace(array(1,2,3), array('PC端','移动端', '店内开单'), $order_info['order_from']);//订单来源
        $order_info['order_type_name'] =  str_replace(array(1,2,3,4,5), array('普通订单','预定订单','收银订单','分期返还','全额返还'), $order_info['order_type']);//订单类型
        $model_refund_return = Model('refund_return');
        $order_list = array();
        $order_list[$order_id] = $order_info;
        $order_list = $model_refund_return->getGoodsRefundList($order_list,1);//订单商品的退款退货显示
        $order_info = $order_list[$order_id];
        $refund_all = $order_info['refund_list'][0];
        if (!empty($refund_all) && $refund_all['seller_state'] < 3) {//订单全部退款商家审核状态:1为待审核,2为同意,3为不同意
			output_error($refund_all);
        }
		
		$order_info['store_member_id'] = $order_info['extend_store']['member_id'];
		$order_info['store_phone'] = $order_info['extend_store']['store_phone']?$order_info['extend_store']['store_phone']:C('site_phone');


		if($order_info['payment_time']){
			$order_info['payment_time'] = date('Y-m-d H:i:s',$order_info['payment_time']);
		}else{
			$order_info['payment_time'] = '';
		}
		if($order_info['finnshed_time']){
			$order_info['finnshed_time'] = date('Y-m-d H:i:s',$order_info['finnshed_time']);
		}else{
			$order_info['finnshed_time'] = '';
		}
		if($order_info['add_time']){
			$order_info['add_time'] = date('Y-m-d H:i:s',$order_info['add_time']);
		}else{
			$order_info['add_time'] = '';
		}
		
		if($order_info['extend_order_common']['order_message']){
			$order_info['order_message'] = $order_info['extend_order_common']['order_message'];
		}
		$order_info['invoice'] = $order_info['extend_order_common']['invoice_info']['类型'].$order_info['extend_order_common']['invoice_info']['抬头'].$order_info['extend_order_common']['invoice_info']['内容'];
		 

		$order_info['reciver_phone'] = $order_info['extend_order_common']['reciver_info']['phone'];
		$order_info['reciver_name'] = $order_info['extend_order_common']['reciver_name'];
		$order_info['reciver_addr'] = $order_info['extend_order_common']['reciver_info']['address'];

		$order_info['promotion'] = array();
        //显示锁定中
        $order_info['if_lock'] = $model_order->getOrderOperateState('lock',$order_info);

        //显示取消订单
        $order_info['if_buyer_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$order_info);

        //显示退款取消订单
        $order_info['if_refund_cancel'] = $model_order->getOrderOperateState('refund_cancel',$order_info);

        //显示投诉
        $order_info['if_complain'] = $model_order->getOrderOperateState('complain',$order_info);

        //显示收货
        $order_info['if_receive'] = $model_order->getOrderOperateState('receive',$order_info);

        //显示物流跟踪
        $order_info['if_deliver'] = $model_order->getOrderOperateState('deliver',$order_info);		

        //显示评价
        $order_info['if_evaluation'] = $model_order->getOrderOperateState('evaluation',$order_info);

        //显示分享
        $order_info['if_share'] = $model_order->getOrderOperateState('share',$order_info);
		
		$order_info['ownshop'] = $model_order->getOrderOperateState('share',$order_info);
		
        //显示系统自动取消订单日期
        if ($order_info['order_state'] == ORDER_STATE_NEW) {
            $order_info['order_cancel_day'] = $order_info['add_time'] + ORDER_AUTO_CANCEL_DAY * 3600 * 24;
        }
		$order_info['if_deliver'] = false;
        //显示快递信息
        if ($order_info['shipping_code'] != '') {
			$order_info['if_deliver'] = true;
            $express = rkcache('express',true);
            $order_info['express_info']['e_code'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
            $order_info['express_info']['e_name'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
            $order_info['express_info']['e_url'] = $express[$order_info['extend_order_common']['shipping_express_id']]['e_url'];
        }

        //显示系统自动收获时间
        if ($order_info['order_state'] == ORDER_STATE_SEND) {
            $order_info['order_confirm_day'] = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 24 * 3600;
        }

        //如果订单已取消，取得取消原因、时间，操作人
        if ($order_info['order_state'] == ORDER_STATE_CANCEL) {
            $close_info = $model_order->getOrderLogInfo(array('order_id'=>$order_info['order_id']),'log_id desc');
			$order_info['close_info'] = $close_info;
			$order_info['state_desc'] = $close_info['log_orderstate'];
			$order_info['order_tips'] = $close_info['log_msg'];
        }
        //查询消费者保障服务
        if (C('contract_allow') == 1) {
            $contract_item = Model('contract')->getContractItemByCache();
        }
        foreach ($order_info['extend_order_goods'] as $value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            $goods_spec = unserialize($value['goods_spec']);
            $spec_name = '';
            if (!empty($goods_spec)) {
            	foreach ($goods_spec as $key => $val){
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
            }else{
            	$spec_name = $value['goods_spec'];
            }
            $value['goods_spec_name'] = $spec_name;
            //处理消费者保障服务
            if (trim($value['goods_contractid']) && $contract_item) {
                $goods_contractid_arr = explode(',',$value['goods_contractid']);
                foreach ((array)$goods_contractid_arr as $gcti_v) {
                    $value['contractlist'][] = $contract_item[$gcti_v];
                }
            }
            if ($value['goods_type'] == 5) {
                $order_info['zengpin_list'][] = $value;
            } else {
                $order_info['goods_list'][] = $value;
            }
        }

        if (empty($order_info['zengpin_list'])) {
            $order_info['goods_count'] = count($order_info['goods_list']);
        } else {
            $order_info['goods_count'] = count($order_info['goods_list']) + 1;
        }
		$order_info['real_pay_amount'] = $order_info['order_amount']+$order_info['shipping_fee']-$order_info['rcb_amount']-$order_info['pd_amount'];
        //取得其它订单类型的信息
        $model_order->getOrderExtendInfo($order_info);

		$order_info['zengpin_list']=array();
		if (is_array($order_info['extend_order_goods'])) {
			foreach ($order_info['extend_order_goods'] as $val) {                   
				if ($val['goods_type'] == 5) {
					$order_info['zengpin_list'][] = $val;
				} 
			}
		}
		output_data(array('order_info'=>$order_info));
		
	}
	
		
/**
     * 订单详情
     */
    public function get_current_deliverOp(){
		$order_id   = intval($_POST['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在');
        }



        $model_order    = Model('order');
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS))) {
            output_error('订单不存在');
        }

        $express = rkcache('express',true);
        $e_code = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
        $e_name = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];

        $deliver_info = $this->_get_express($e_code, $order_info['shipping_code']);


		$data = array();
		$data['deliver_info']['context'] = $e_name;
		$data['deliver_info']['time'] = $deliver_info['0'];
		output_data($data);
	}
    /**
     * 从第三方取快递信息
     *
     */
    public function _get_express($e_code, $shipping_code){
        $content = Model('express')->get_express($e_code, $shipping_code);        
        if (empty($content)) {
            output_error('物流信息查询失败');
        }
        $output = array();
        foreach ($content as $k=>$v) {
            if ($v['time'] == '') continue;
            $output[]= $v['time'].'&nbsp;&nbsp;'.$v['context'];
        }

        return $output;
    }
	
	/**-------------------------------------APP客户端---------------------------------------------------**/
    /**
     * 订单列表
     */
    public function app_order_listOp() {
		$model_order = Model('order');
		//处理app端发送过来的通用参数
		$json = str_replace('&quot;', '"', $_POST['json']);		
        $res = json_decode($json,true);
		$page_nums = $res['pagination']['count']?$res['pagination']['count']:10; //每页显示的条数
		$page_curr = $res['pagination']['page']?$res['pagination']['page']:1; //当前显示第几页
		$_GET['curpage'] = $page_curr;
		$session_uid = $res['session']['uid']; //用户ID
		$session_sid = $res['session']['sid']; //用户key
		//订单参数
		$state = $res['state']?$res['state']:ORDER_STATE_NEW; //订单状态	

        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
		if (isset($_GET['state'])){
			$condition['order_state'] = intval($_GET['state']);
		}

        $order_list = $model_order->getNormalOrderList($condition, $page_nums, '*', 'order_id desc','', array('order_goods','order_common','store'));

        $order_group_list = array();
        $order_pay_sn_array = array();
        foreach ($order_list as $value) {
            //显示取消订单
            $value['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$value);
            //显示收货
            $value['if_receive'] = $model_order->getOrderOperateState('receive',$value);
            //显示锁定中
            $value['if_lock'] = $model_order->getOrderOperateState('lock',$value);
            //显示物流跟踪
            $value['if_deliver'] = $model_order->getOrderOperateState('deliver',$value);

            //商品图
            foreach ($value['extend_order_goods'] as $k => $goods_info) {
                $value['extend_order_goods'][$k]['goods_image_url'] = cthumb($goods_info['goods_image'], 240, $value['store_id']);
				$value['extend_order_goods'][$k]['photo'] = getPhoto_array($value['extend_order_goods'][$k]['goods_image_url']);//app端需要
            }
			//订单信息
			$order_info = array();
			$order_info['order_id'] = $value['order_id'];
			$order_info['order_sn'] = $value['order_sn'];
			$order_info['order_amount'] = $value['order_amount'];
			$order_info['payment_code'] = $value['payment_code'];
			$order_info['order_message'] = $value['extend_order_common']['order_message'];//买家留言
			$order_info['deliver_explain'] = $value['extend_order_common']['deliver_explain'];//买家备注
            $value['order_info'] = $order_info;
					
            $order_group_list[$value['pay_sn']]['order_list'][] = $value;			

            //如果有在线支付且未付款的订单则显示合并付款链接
            if ($value['order_state'] == ORDER_STATE_NEW) {
                $order_group_list[$value['pay_sn']]['pay_amount'] += $value['order_amount'] - $value['rcb_amount'] - $value['pd_amount'];
            }
            $order_group_list[$value['pay_sn']]['add_time'] = $value['add_time'];

            //记录一下pay_sn，后面需要查询支付单表
            $order_pay_sn_array[] = $value['pay_sn'];
        }

        $new_order_group_list = array();
        foreach ($order_group_list as $key => $value) {
            $value['pay_sn'] = strval($key);
            $new_order_group_list[] = $value;
        }

        $page_count = $model_order->gettotalpage();
		
		$array_data = array('order_group_list' => $new_order_group_list);
        if(isset($_GET['getpayment'])&&$_GET['getpayment']=="true"){
            $model_mb_payment = Model('mb_payment');

            $payment_list = $model_mb_payment->getMbPaymentOpenList();
            $payment_array = array();
            if(!empty($payment_list)) {
                foreach ($payment_list as $value) {
                    $payment_array[] = array('payment_code' => $value['payment_code'],'payment_name' =>$value['payment_name']);
                }
            }
            $array_data['payment_list'] = $payment_array;
        }
		
        output_data($array_data, wxapp_page($page_count));
        //output_data(array('order_group_list' => $new_order_group_list), APP_page($page_count));
    }
    
    
    /*****************************************---导购操作商家后台相关动作-----分割线----*******************************************/
    
    /**
     * 根据定时程序，获取读取订单日志文件进行处理
     */
    public function getCashierOrderOp(){
    	
    	$member_id = $_SESSION['member_id'];
    	$log_name = BASE_UPLOAD_PATH.'/mobile/cashier/'.date('Y-m-d').'_'.$member_id.'.log';
    	if(file_exists($log_name)){
    		$fileInfo = file_get_contents($log_name);
	    	if(empty($fileInfo)){
	    		output_error("今日没有需要处理的订单");
	    	}else{
	    		if(strstr($fileInfo,"][")){
	    			$fileInfo = str_replace('][',',',$fileInfo);
	    		}
	    	}
    	}else{
    		output_error("今日没有需要处理的订单");
    	}
    	$orderlist = json_decode($fileInfo,true);
		$ordersInfo = array();
		$ordersInfo['pay_num_online'] = count($orderlist);
		$ordersInfo['member_paypwd'] = $this->member_info['member_paypwd'];
    	$ordersInfo['usable_points_amount'] = $this->member_info['member_points'];
    	$ordersInfo['member_available_rcb'] = $this->member_info['available_rc_balance'];
    	$ordersInfo['member_available_pd'] = $this->member_info['available_predeposit'];
    	$pay_code_online = $orderlist[0]['payment_code'];
    	foreach ($orderlist as $orderInfo){
	    	$pay_amount_online += $orderInfo['order_amount'];
	    	$usable_points += $orderInfo['usable_points'];
	    	$rcb_amount += $orderInfo['rcb_amount'];
	    	$pd_amount += $orderInfo['pd_amount'];
	    	if($pay_code_online == $orderInfo['payment_code']){
	    		$pay_code_online = $orderInfo['payment_code'];
	    	}elseif($orderInfo['payment_code']!='xjpay'||$orderInfo['payment_code']!='skpay'){ //不为现金和刷卡
	    		$pay_code_online = $orderInfo['payment_code'];
	    	}else{
	    		$pay_code_online .= ','.$orderInfo['payment_code'];
	    	}
	    	$pay_sn = $orderInfo['pay_sn'];
	    	$order_id = $orderInfo['order_id'];
    	}
    	$ordersInfo['usable_points']= $usable_points;
    	$ordersInfo['rcb_amount'] 	= $rcb_amount;
    	$ordersInfo['pd_amount']	= $pd_amount;
    	$payed_tips = '';
    	if(!empty($rcb_amount) &&$rcb_amount>0){
    		$payed_tips .= '使用充值卡支付 ￥'.$rcb_amount.' 元';
    	}
    	if(!empty($pd_amount) &&$pd_amount>0){
    		$payed_tips .= '使用积分支付 ￥'.$pd_amount.' 元';
    	}
    	if(!empty($usable_points) &&$usable_points>0){
    		$payed_tips .= '还需支付 '.$usable_points.' 云币';
    	}
    	$ordersInfo['payed_tips'] = $payed_tips;
    	$ordersInfo['order_id'] = $order_id;
    	$ordersInfo['pay_code'] = $pay_code_online;
    	$ordersInfo['pay_amount_online'] = $pay_amount_online;
    	$ordersInfo['usable_points_total'] = $usable_points_total;
    	$ordersInfo['pay_sn'] = $pay_sn;//取最新一单去支付
    	$result['pay_info'] = $ordersInfo;
    	$result['msg'] = "成功读取订单";
    	output_data($result);
    }
    /**
     * 订单完成后需要立即清除日志文件
     */
    public function unCashierOrderOp(){
    	$member_id = $this->member_info['member_id'];
    	$file = BASE_UPLOAD_PATH.'/mobile/cashier/'.date('Y-m-d').'_'.$member_id.'.log';
    	if (!unlink($file))
		{
		  	output_error("清除今日订单日志失败");
		}else{
			$result['status'] = 1;
		  	$result['msg'] = "今日订单已经完成";
    		output_data($result);
		}
    }
}