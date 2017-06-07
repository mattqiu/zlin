<?php
/**
 * 商户收款
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class seller_paymentControl extends BaseSellerControl {

    private $payment_code;//支付方式代码
    private $payment_name;//支付方式名称
    private $payment_config;

	public function __construct() {
		parent::__construct();
		
		if (!empty($_REQUEST['payment_code'])) {
			$this->payment_code = $_REQUEST['payment_code'];
		
			$this->payment_name = orderPaymentName($this->payment_code);
			if(in_array($this->payment_code, array('skpay','xjpay','predeposit','offline'))){
				$this->payment_config = '';
			}else{
       	$model_mb_payment = Model('mb_payment');
       	$store_id = $this->store_id;
        $condition = array();
        if($this->store_info['payment_method']==1){//支付到平台
        	$store_id = 0;
        }
			$condition['payment_code'] = $this->payment_code;			
        $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition,$store_id); //支付方式_待修改
        if(!$mb_payment_info) {
        	output_error('支付方式未开启');
       	}
				$this->payment_config = $mb_payment_info['payment_config'];
			}
	}
	}	

	/**
	 * 扫码支付:目前只支持两种支付方式，微信、支付宝
	 * 
	 */
	public function pay_scanOp() {
		
		$order_sn = $_REQUEST['order_sn'];	
		if(!preg_match('/^\d{16}$/',$order_sn)){
			output_error('订单号不正确');
		}
		//验证支付单信息
		$model_order = Model('order');
		$condition = array();
		$condition['order_sn'] = $order_sn;
		if (!empty($this->store_id)) {
			$condition['store_id'] = $this->store_id;
		}
		//同步异步通知时,预定支付尾款时需要用到已经支付状态
		$condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
		$order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'),'order_id, order_sn, pay_sn, buyer_id, store_id, payment_code, goods_amount, order_amount, rcb_amount, pd_amount, points_amount,saleman_id,saleman_name');
		if (empty($order_info)){
				output_error('该订单可能已经支付，无需再支付');
		}
		$order_id = $order_pay_info['order_id'] = $order_info['order_id'];
		$order_pay_info['order_sn'] = $order_sn;
		$order_pay_info['pay_sn'] = $order_info['pay_sn'];
		$store_info = Model('store')->getStoreInfoByID($order_info['store_id'],'store_name');
		$order_pay_info['promotion_discount'] = $promotion_discount = $order_info['extend_order_common']['discount'];//优惠折扣
		$order_pay_info['promotion_total'] = $promotion_total = $order_info['extend_order_common']['promotion_total'];//优惠金额
		$order_pay_info['store_name'] = $store_info['store_name'];
		$order_pay_info['subject'] = '您刚刚购买了 '.$store_info['store_name'].'的商品_支付单：'.$order_pay_info['pay_sn'];
		$order_pay_info['order_type'] = 'real_order';
		$pay_amount = $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
		//修改支付方式
		if(!empty($this->payment_code) && $this->payment_code != $order_info['payment_code']){
			$payment_code = $this->payment_code;
		
			$model_order->editOrder(array('payment_code'=>$payment_code),$condition);
		}else{
			$this->payment_code = $payment_code = $order_info['payment_code'];
			$this->payment_name = orderPaymentName($this->payment_code);
		}
		//修改商品价格
		if(!empty($_REQUEST['realAmount']) && $_REQUEST['realAmount'] != $pay_amount){
			$realAmount = $_REQUEST['realAmount'];
			//$pay_amount = $realAmount;//修改后的金额
			if($_REQUEST['changePriceType']=='2'){
				//折扣类型
				$discount = ($order_info['goods_amount']/$realAmount); //折扣 = 商品总额/实际应付金额				
				$model_order->editOrderCommon(array('discount'=>$discount),array('order_id'=>$order_info['order_id']));
			}else{
				//金额修改类型
				$promotion_total = ($pay_amount - $realAmount); //优惠金额 = 订单总额 - 实际应付金额
				$model_order->editOrderCommon(array('promotion_total'=>$promotion_total),array('order_id'=>$order_info['order_id']));
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
				$order_pay_info['pay_sn'] = $pay_sn;
			}
			$editOrder['order_amount'] = $realAmount;
    	
			$model_order->editOrder($editOrder,$condition);
			//记录订单日志
			$data = array();
			$data['order_id'] = $order_id;
			$data['log_role'] = 'seller';
			$data['log_user'] = empty($order_info['saleman_name'])?'导购ID：'.$order_info['saleman_id']:$order_info['saleman_name'];
			$data['log_msg'] = '支付单号：'.$order_info['pay_sn']."，给优惠了￥".$pay_amount - $realAmount;
			$model_order->addOrderLog($data);
		}
		$order_pay_info['payment_code'] = $payment_code; //支付方式代码
		$order_pay_info['payment_name'] = $this->payment_name;
		$order_pay_info['goods_amount'] = $order_info['goods_amount'];
		$order_pay_info['order_amount'] = $order_pay_info['payableAmount'] = $order_pay_info['realAmount'] = imPriceFormat($pay_amount);//实际需要支付的金额
		if($payment_code=='wxpay'||$payment_code=='alipay'){
			
			if(empty($this->payment_config)){
			$condition_pcode = array();
			if($this->store_info['payment_method']==1){//支付到平台
				$store_id = 0;
			} else {
				$store_id = $this->store_id;
			}
				$model_mb_payment = Model('mb_payment');			
			$condition_pcode['payment_code'] = $payment_code;			
			$mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition_pcode,$store_id); //支付方式_待修改
				$this->payment_config = $mb_payment_info['payment_config'];
			}
			$order_pay_info['payment_config'] = $this->payment_config;
			//获取第三方API收款二维码 
			$resPay = $this->_api_pay($order_pay_info);
			if(!empty($resPay)){
				if($resPay['result_code']=='FAIL'){

					
					
					output_error($resPay['err_code_des']);
										
				}else{
			$order_pay_info['pay_qrcode'] = "http://paysdk.weixin.qq.com/example/qrcode.php?data=".urlencode($resPay["code_url"]);
				}
			}else{
				$order_pay_info['pay_qrcode'] = '';
				$order_pay_info['no_scanpay'] = $this->payment_name.'不支持扫码支付';
			}
		}else{
			$order_pay_info['pay_qrcode'] = '';
			$order_pay_info['no_scanpay'] = '';
		}
		output_data($order_pay_info);
	}
	/**
	 * 获取支付名称
	 */	
	public function payment_nameOp(){
		$payment_code = $_REQUEST['payment_code'];
		$payment_name = orderPaymentName($payment_code);
		output_data($payment_name);
	}
	/**
	 * 扫码收款
	 */
	public function qrcode_payOp(){
		$order_sn = $_REQUEST['order_sn'];
		if(!preg_match('/^\d{16}$/',$order_sn)){
			output_error('订单号不正确');
		}
		//验证支付单信息
		$model_order = Model('order');
		$condition = array();
		$condition['order_sn'] = $order_sn;
		if (!empty($this->store_id)) {
			$condition['store_id'] = $this->store_id;
		}
		//同步异步通知时,预定支付尾款时需要用到已经支付状态
		$condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
		$order_info = $model_order->getOrderInfo($condition,'','order_id, order_sn, pay_sn, buyer_id, store_id, payment_code, goods_amount, order_amount, rcb_amount, pd_amount, points_amount');
		if (empty($order_info)){
			output_error('该订单可能已经支付，无需再支付');
		}
		if(!empty($this->payment_code) && $this->payment_code != $order_info['payment_code']){
			$payment_code = $this->payment_code;
			$model_order->editOrder(array('payment_code'=>$payment_code),$condition);
		}else{
			$this->payment_code = $payment_code = $order_info['payment_code'];
		}		
		$param = array();
		$param['body']  = "刷卡测试样例-支付";//$order_pay_info['subject'];
		$param['auth_code'] = $_REQUEST["auth_code"];
		$param['out_trade_no'] 	= $order_info['pay_sn'];
		$param['total_fee'] = $order_info['order_amount'];
		$inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
		if(!is_file($inc_file)){
			output_error('支付接口不存在');
		}
		require($inc_file);
		$payment_api = new $this->payment_code;
		$return = $payment_api->micropay($param);
		output_data($return,"支付成功");
	}
	/**
	 * 第三方在线支付接口
	 *
	 */
	private function _api_pay($order_pay_info) {
		$this->payment_code = $order_pay_info['payment_code'];
		$this->payment_config = $order_pay_info['payment_config'];
		$inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
		if(!is_file($inc_file)){
			output_error('支付接口不存在');
		}
		require($inc_file);		
		
		$param = array();
		$param['payment_config'] = $this->payment_config;
		$param['order_amount'] = $order_pay_info['payableAmount'];
		if ($order_pay_info['order_type'] == 'real_order'){
			$param['order_type'] = 'r';
		}else if($order_pay_info['order_type'] == 'vr_order'){
			$param['order_type'] = 'v';
		}else if($order_pay_info['order_type'] == 'pd_order'){
			$param['order_type'] = 'p';
		}
		$param['buyer_id'] = $member_id = $order_pay_info['buyer_id'];
		$param['store_name']  = $order_pay_info['store_name'];
		$param['subject']  = $order_pay_info['subject'];
		$param['order_sn'] = $order_sn = $order_pay_info['order_sn'];
		$param['pay_sn'] 	= $order_pay_info['pay_sn'];
		$param['total_fee'] = $order_pay_info['order_amount'];
		$payment_api = new $this->payment_code;
		
		$return = $payment_api->native($param);
		if($return['result_code']=='FAIL'){
			if(strpos($return['err_code_des'],'201')!== false){
				//重新生成支付单
				$model_order = Model('order');
				$condition = array();
				$condition['order_sn'] = $order_sn;
				$logic_buy_1 = Logic('buy_1');
				$pay_sn = $logic_buy_1->makePaySn($member_id);
				$order_pay = array();
				$order_pay['pay_sn'] = $pay_sn;
				$order_pay['buyer_id'] = $member_id;
				$order_pay_id = $model_order->addOrderPay($order_pay);
				if($order_pay_id){
					$editOrder['pay_sn']  = $param['pay_sn'] = $pay_sn;
				}
				$model_order->editOrder($editOrder,$condition);
				//记录订单日志
				$data = array();
				$data['order_id'] = $order_id;
				$data['log_role'] = 'seller';
				$data['log_user'] = '导购';
				$data['log_msg'] = "修改订单支付单号，原来支付单号是：".$order_pay_info['pay_sn'];
				$model_order->addOrderLog($data);
				$return = $payment_api->native($param);
			}
		}
		return $return;
	}
	
	
    /**
     * 实物订单支付
     */
    public function pay_newOp() {
		$pay_sn = $_REQUEST['pay_sn'];

		if(!preg_match('/^\d{18}$/',$pay_sn)){
            output_error('参数错误');
        }
        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }
        $payment_code = $_REQUEST['payment_code'];
        //站内余额支付		
        $order_list = $this->_pd_pay($pay_info['data']['order_list'],$_REQUEST);
		
        //计算本次需要在线支付（分别是含站内支付、纯第三方支付接口支付）的订单总金额
        $pay_amount = 0;
        $api_pay_amount = 0;
        $points_amount = 0;  //云币支付过一部分
        $pay_order_id_list = array();
        if (!empty($order_list)) {
            foreach ($order_list as $order_info) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $api_pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'] ;//- $order_info['points_amount'];
                    $pay_order_id_list[] = $order_info['order_id'];
                }else{
                	$points_amount += $order_info['points_amount'];
                }
                $pay_amount += $order_info['order_amount'];
            }
        }
        if (empty($api_pay_amount)) {
            output_error('该订单已经完成付款，不需要再支付');
        }
        //如果使用云币支付则需要再支付一部分现金
        if($pay_info['data']['api_pay_amount'] != $pay_amount && $points_amount > 0){
        	$pay_info['data']['api_pay_amount'] = $pay_amount;
        }
        
        $result = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>array('in',$pay_order_id_list)));
        if(!$result) {
            output_error('更新订单信息发生错误，请重新支付');
        }
        $payment_info = $result['data'];
        //$pay_info['data']['api_pay_amount'] = imPriceFormat($api_pay_amount);

        //如果是开始支付尾款，则把支付单表重置了未支付状态，因为支付接口通知时需要判断这个状态
        if ($pay_info['data']['if_buyer_repay']) {
            $update = Model('order')->editOrderPay(array('api_pay_state'=>0),array('pay_id'=>$pay_info['data']['pay_id']));
            if (!$update) {
                output_error('订单支付失败');
            }
            $pay_info['data']['api_pay_state'] = 0;
        }
        //第三方API支付		
        $this->_api_pay($pay_info['data']);
    }

    /**
     * 虚拟订单支付
     */
    public function vr_pay_newOp() {
        $pay_sn = $_GET['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }
        $api_pay_amount = $pay_info['data']['api_pay_amount']; //需要支付的金额
        $order_state = $pay_info['data']['order_state'];	//订单状态
		if($api_pay_amount==0.00 && $order_state == ORDER_STATE_PAY){
			 echo '<!DOCTYPE html>
				<html>
				<head>
				<meta http-equiv="Content-type" content="text/html;charset=utf-8" />
				<title>支付成功</title>
				</head>
				<body>
				正在加载…
			 	window.onload = function() {
				<script type="text/javascript">
					window.onload = function() {
						alert("支付操作完成！如果您的订单状态没有改变，请耐心等待支付网关的返回结果。");
					    location.href = "'.WAP_SITE_URL.'/tmpl/member/vr_order_list.html";
					}
				</script>
				</body>
				</html>';
			exit;
		}else{
			//第三方API支付
			$this->_api_pay($pay_info['data']);
		}
    }
	
    /**
     * VIP卡 或 积分充值
     */
    public function pd_pay_newOp(){
    	$pdr_sn = $_GET['pdr_sn'];
    	$payment_code = $_GET['payment_code'];
    	if(!preg_match('/^\d{18}$/',$pdr_sn)){
    		output_error('参数错误');
    	}
    	
    	$pay_info = $this->_get_pd_order_info($pdr_sn,$payment_code);
    	if(isset($pay_info['error'])) {
    		output_error($pay_info['error']);
    	}
    	$api_pay_amount = $pay_info['data']['api_pay_amount']; //需要支付的金额
    	$order_state = $pay_info['data']['order_state'];	//订单状态
    	
    	//转到第三方API支付
    	$this->_api_pay($pay_info['data']);
    }
    
	/**
     * 站内余额支付(充值卡、积分支付、云币) 实物订单
     *
     */
    private function _pd_pay($order_list, $post) {
        if (empty($post['password'])) {
            return $order_list;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_list;
        }
        
        if ($buyer_info['available_rc_balance'] == 0) {
            $post['rcb_pay'] = null;
        }
        if ($buyer_info['member_points'] == 0) {
            $post['jf_pay'] = null;
        }
        if ($buyer_info['available_predeposit'] == 0) {
            $post['pd_pay'] = null;
        }
        if (floatval($order_list[0]['rcb_amount']) > 0 || floatval($order_list[0]['pd_amount']) > 0) {
            return $order_list;
        }
        
        try {
            $model_member->beginTransaction();
            $logic_buy_1 = Logic('buy_1');
            
            //使用充值卡支付
            if (!empty($post['rcb_pay'])) {
                $order_list = $logic_buy_1->rcbPay($order_list, $post, $buyer_info);
            }else if (!empty($post['jf_pay'])) { //使用云币支付
                $order_list = $logic_buy_1->jfPay($order_list, $post, $buyer_info);
            }
			
            //使用积分支付
            if (!empty($post['pd_pay'])) {
                $order_list = $logic_buy_1->pdPay($order_list, $post, $buyer_info);
            }
            //特殊订单站内支付处理
            $logic_buy_1->extendInPay($order_list);

            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            output_error($e->getMessage());
        }

        return $order_list;
    }

    /**
     * 站内余额支付(充值卡、积分支付) 虚拟订单
     *
     */
    private function _pd_vr_pay($order_info, $post) {
        if (empty($post['password'])) {
            return $order_info;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_info;
        }

        if ($buyer_info['available_rc_balance'] == 0) {
            $post['rcb_pay'] = null;
        }
        if ($buyer_info['available_predeposit'] == 0) {
            $post['pd_pay'] = null;
        }
        if (floatval($order_info['rcb_amount']) > 0 || floatval($order_info['pd_amount']) > 0) {
            return $order_info;
        }

        try {
            $model_member->beginTransaction();
            $logic_buy = Logic('buy_virtual');
            //使用充值卡支付
            if (!empty($post['rcb_pay'])) {
                $order_info = $logic_buy->rcbPay($order_info, $post, $buyer_info);
            }

            //使用积分支付
            if (!empty($post['pd_pay'])) {
                $order_info = $logic_buy->pdPay($order_info, $post, $buyer_info);
            }

            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            showMessage($e->getMessage(), '', 'html', 'error');
        }

        return $order_info;
    }
    /**
     * 获取订单支付信息
     */
    private function _get_real_order_info($pay_sn) {
    	
    	//验证订单信息
    	$condition = array();
    	$condition['pay_sn'] = $pay_sn;
    	$order_pay_info = $model_order->getOrderPayInfo($condition,true);
    	if(empty($order_pay_info)){
    		return callback(false,'该支付单不存在');
        }
    	$order_pay_info['subject'] = '实物订单_'.$order_pay_info['pay_sn'];
    	$order_pay_info['order_type'] = 'real_order';    	
    	
    	$model_order = Model('order');
    	if (!empty($store_id)) {
    		$condition['store_id'] = $store_id;
        }
    	//同步异步通知时,预定支付尾款时需要用到已经支付状态
    	$condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
    	$order_list = $model_order->getOrderList($condition,'','*','','',array(),true);
    	if(empty($order_list)){
    		return array('error' => '该订单无需再支付');
		}
    	//计算本次需要在线支付的订单总金额
		
    	$api_pay_amount = 0;
    	if (!empty($order_list) && is_array($order_list)){
    		foreach ($order_list as $order_info) {
    			$api_pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'] + ($order_info['usable_points'] - $order_info['points_amount']);//
					
			}
			}				
    	$order_pay_info['api_pay_amount'] = $api_pay_amount;

    	$order_pay_info['order_list']     = $order_list;
    	$order_pay_info['if_buyer_repay'] = $result['data']['if_buyer_repay'];

       
        //计算本次需要在线支付的订单总金额
        $pay_amount = 0;
        $pay_order_id_list = array();
        if (!empty($result['data']['order_list'])) {
            foreach ($result['data']['order_list'] as $order_info) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'] ;//-$points_amount;
                    $pay_order_id_list[] = $order_info['order_id'];
                }
            }
        }
        $result['data']['api_pay_amount'] = imPriceFormat($pay_amount);

        $update = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>array('in',$pay_order_id_list)));
        if(!$update) {
            return array('error' => '更新订单信息发生错误，请重新支付');
        }
        
        //如果是开始支付尾款，则把支付单表重置了未支付状态，因为支付接口通知时需要判断这个状态
        if ($result['data']['if_buyer_repay']) {
            $update = Model('order')->editOrderPay(array('api_pay_state'=>0),array('pay_id'=>$result['data']['pay_id']));
            if (!$update) {
                return array('error' => '订单支付失败');
            }
            $result['data']['api_pay_state'] = 0;
        }

        return $result['data'];
    }

    /**
     * 获取虚拟订单支付信息
     */
    private function _get_vr_order_info($pay_sn) {
        $logic_payment = Logic('payment');

        //取得订单信息
        $order_info = $logic_payment->getVrOrderInfo($pay_sn, $this->member_info['member_id']);
        if(!$order_info['state']) {
            output_error($order_info['msg']);
        }
		if(!empty($order_info['data']['api_pay_amount'])){
			//计算本次需要在线支付的订单总金额
			$pay_amount = $order_info['data']['order_amount'] - $order_info['data']['pd_amount'] - $order_info['data']['rcb_amount']- $order_info['data']['points_amount'];
			$order_info['data']['api_pay_amount'] = imPriceFormat($pay_amount);
		}
		
        return $order_info;
    }
    

    /**
     * 获取 VIP充值 和 积分 订单支付信息
     */
    private function _get_pd_order_info($pay_sn,$payment_code) {
    	
    	$logic_payment = Logic('payment');
    	//取得订单信息
    	$order_info = $logic_payment->getPdOrderInfo($pay_sn,$this->member_info['member_id']);
    	if(!$order_info['state']) {
    		output_error($order_info['msg']);
    	}
    	if(!empty($order_info['data']['api_pay_amount'])){
    		//计算本次需要在线支付的订单总金额
    		$pay_amount = $order_info['data']['order_amount'];
    		$order_info['data']['api_pay_amount'] = imPriceFormat($pay_amount);
    	}
    	
    	return $order_info;
    }
    

    /**
     * 可用支付参数列表
     */
    public function payment_listOp() {
        $model_mb_payment = Model('mb_payment');

        $payment_list = $model_mb_payment->getMbPaymentOpenList();

        $payment_array = array();
        if(!empty($payment_list)) {
            foreach ($payment_list as $value) {
                $payment_array[] = $value['payment_code'];
            }
        }

        output_data(array('payment_list' => $payment_array));
    }
	
    /**
     * 微信APP订单支付
     */
    public function wx_app_payOp() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
    }

    /**
     * 微信APP虚拟订单支付
     */
    public function wx_app_vr_payOp() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $result['data']['subject'];
        $param['amount'] = $result['data']['api_pay_amount'];

        $data = $this->_get_wx_pay_info($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
   }

    /**
     * 获取支付参数
     */
    private function _get_wx_pay_info($pay_param) {
        $access_token = $this->_get_wx_access_token();
        if(empty($access_token)) {
            return array('error' => '支付失败code:1001');
        }

        $package = $this->_get_wx_package($pay_param);

        $noncestr = md5($package + TIMESTAMP);
        $timestamp = TIMESTAMP;
        $traceid = $this->member_info['member_id'];

        // 获取预支付app_signature
        $param = array();
        $param['appid'] = $this->payment_config['wxpay_appid'];
        $param['noncestr'] = $noncestr;
        $param['package'] = $package;
        $param['timestamp'] = $timestamp;
        $param['traceid'] = $traceid;
        $app_signature = $this->_get_wx_signature($param);

        // 获取预支付编号
        $param['sign_method'] = 'sha1';
        $param['app_signature'] = $app_signature;
        $post_data = json_encode($param);
        $prepay_result = http_postdata('https://api.weixin.qq.com/pay/genprepay?access_token=' . $access_token, $post_data);
        $prepay_result = json_decode($prepay_result, true);
        if($prepay_result['errcode']) {
            return array('error' => '支付失败code:1002');
        }
        $prepayid = $prepay_result['prepayid'];

        // 生成正式支付参数
        $data = array();
        $data['appid'] = $this->payment_config['wxpay_appid'];
        $data['noncestr'] = $noncestr;
        $data['package'] = 'Sign=WXPay';
        $data['partnerid'] = $this->payment_config['wxpay_partnerid'];
        $data['prepayid'] = $prepayid;
        $data['timestamp'] = $timestamp;
        $sign = $this->_get_wx_signature($data);
        $data['sign'] = $sign;
        return $data;
    }

    /**
     * 获取微信access_token
     */
    private function _get_wx_access_token() {
        // 尝试读取缓存的access_token
        $access_token = rkcache('wx_access_token');
        if($access_token) {
            $access_token = unserialize($access_token);
            // 如果access_token未过期直接返回缓存的access_token
            if($access_token['time'] > TIMESTAMP) {
                return $access_token['token'];
            }
        }

        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=%s&secret=%s';
        $url = sprintf($url, $this->payment_config['wxpay_appid'], $this->payment_config['wxpay_appsecret']);
        $re = http_get($url);
        $result = json_decode($re, true);
        if($result['errcode']) {
            return '';
        }

        // 缓存获取的access_token
        $access_token = array();
        $access_token['token'] = $result['access_token'];
        $access_token['time'] = TIMESTAMP + $result['expires_in'];
        wkcache('wx_access_token', serialize($access_token));

        return $result['access_token'];
    }

    /**
     * 获取package
     */
    private function _get_wx_package($param) {
        $array = array();
        $array['bank_type'] = 'WX';
        $array['body'] = $param['subject'];
        $array['fee_type'] = 1;
        $array['input_charset'] = 'UTF-8';
        $array['notify_url'] = WXAPP_SITE_URL . '/api/payment/wxpay/notify_url.php';
        $array['out_trade_no'] = $param['pay_sn'];
        $array['partner'] = $this->payment_config['wxpay_partnerid'];
        $array['total_fee'] = $param['amount'];
        $array['spbill_create_ip'] = get_server_ip();

        ksort($array);

        $string = '';
        $string_encode = '';
        foreach ($array as $key => $val) {
            $string .= $key . '=' . $val . '&';
            $string_encode .= $key . '=' . urlencode($val). '&';
        }

        $stringSignTemp = $string . 'key=' . $this->payment_config['wxpay_partnerkey'];
        $signValue = md5($stringSignTemp);
        $signValue = strtoupper($signValue);

        $wx_package = $string_encode . 'sign=' . $signValue;
        return $wx_package;
    }

    /**
     * 获取微信支付签名
     */
    private function _get_wx_signature($param) {
        $param['appkey'] = $this->payment_config['wxpay_appkey'];

        $string = '';

        ksort($param);
        foreach ($param as $key => $value) {
            $string .= $key . '=' . $value . '&';
        }
        $string = rtrim($string, '&');

        $sign = sha1($string);

        return $sign;
    }

    /**
     * 微信APP订单支付
     */
    public function wx_app_pay3Op() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_real_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info3($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
    }

    /**
     * 微信APP虚拟订单支付
     */
    public function wx_app_vr_pay3Op() {
        $pay_sn = $_POST['pay_sn'];

        $pay_info = $this->_get_vr_order_info($pay_sn);
        if(isset($pay_info['error'])) {
            output_error($pay_info['error']);
        }

        $param = array();
        $param['pay_sn'] = $pay_sn;
        $param['subject'] = $pay_info['data']['subject'];
        $param['amount'] = $pay_info['data']['api_pay_amount'] * 100;

        $data = $this->_get_wx_pay_info3($param);
        if(isset($data['error'])) {
            output_error($data['error']);
        }
        output_data($data);
   }

    /**
     * 获取支付参数
     */
    private function _get_wx_pay_info3($pay_param) {
        $noncestr = md5(rand());

        $param = array();
        $param['appid'] = $this->payment_config['wxpay_appid'];
        $param['mch_id'] = $this->payment_config['wxpay_partnerid'];
        $param['nonce_str'] = $noncestr;
        $param['body'] = $pay_param['subject'];
        $param['out_trade_no'] = $pay_param['pay_sn'];
        $param['total_fee'] = $pay_param['amount'];
        $param['spbill_create_ip'] = get_server_ip();
        $param['notify_url'] = WXAPP_SITE_URL . '/api/payment/wxpay3/notify_url.php';
        $param['trade_type'] = 'APP';

        $sign = $this->_get_wx_pay_sign3($param);
        $param['sign'] = $sign;

        $post_data = '<xml>';
        foreach ($param as $key => $value) {
            $post_data .= '<' . $key .'>' . $value . '</' . $key . '>';
        }
        $post_data .= '</xml>';

        $prepay_result = http_postdata('https://api.mch.weixin.qq.com/pay/unifiedorder', $post_data);
        $prepay_result = simplexml_load_string($prepay_result);
        if($prepay_result->return_code != 'SUCCESS') {
            return array('error' => '支付失败code:1002');
        }

        // 生成正式支付参数
        $data = array();
        $data['appid'] = $this->payment_config['wxpay_appid'];
        $data['noncestr'] = $noncestr;
        $data['package'] = 'prepay_id=' . $prepay_result->prepay_id;
        $data['partnerid'] = $this->payment_config['wxpay_partnerid'];
        $data['prepayid'] = (string)$prepay_result->prepay_id;
        $data['timestamp'] = TIMESTAMP;
        $sign = $this->_get_wx_pay_sign3($data);
        $data['sign'] = $sign;
        return $data;
    }

    private function _get_wx_pay_sign3($param) {
        ksort($param);
        foreach ($param as $key => $val) {
            $string .= $key . '=' . $val . '&';
        }
        $string .= 'key=' . $this->payment_config['wxpay_partnerkey'];
        return strtoupper(md5($string));
    }	
}