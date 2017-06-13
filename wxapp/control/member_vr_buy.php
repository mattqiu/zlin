<?php
/**
 * 购买
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

class member_vr_buyControl extends wxappMemberControl {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 虚拟商品购买第一步，设置购买数量
	 * POST
	 * 传入：cart_id:商品ID，quantity:购买数量
	 */
	public function buy_step1Op() {
	    $_REQUEST['goods_id'] = $_REQUEST['cart_id'];

	    $logic_buy_virtual = Logic('buy_virtual');
	    $result = $logic_buy_virtual->getBuyStep2Data($_REQUEST['goods_id'], $_REQUEST['quantity'], $this->member_info['member_id']);
	    if(!$result['state']) {
	        output_error($result['msg']);
	    } else {
	        $result = $result['data'];
	    }
	    unset($result['member_info']);
	    output_data($result);
	}

    /**
     * 虚拟商品购买第二步，设置接收手机号
	 * POST
	 * 传入：goods_id:商品ID，quantity:购买数量
	 */
    public function buy_step2Op() {

        $logic_buy_virtual = Logic('buy_virtual');
        $result = $logic_buy_virtual->getBuyStep2Data($_REQUEST['goods_id'], $_REQUEST['quantity'], $this->member_info['member_id']);
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
	        $result = $result['data'];
            $member_info = array();
            $member_info['member_mobile'] = $result['member_info']['member_mobile'];
            $member_info['available_predeposit'] = $result['member_info']['available_predeposit'];
            $member_info['available_rc_balance'] = $result['member_info']['available_rc_balance'];
            unset($result['member_info']);
            $result['member_info'] = $member_info;
            output_data($result);
        }
    }

    /**
     * 虚拟订单第三步，产生订单
	 * POST
	 * 传入：goods_id:商品ID，quantity:购买数量，buyer_phone：接收手机，buyer_msg:下单留言,pd_pay:是否使用积分支付0否1是，password：支付密码
	 */
    public function buy_step3Op() {
        $logic_buy_virtual = Logic('buy_virtual');
        $input = array();
        $input['goods_id'] = $_REQUEST['goods_id'];
        $input['quantity'] = $_REQUEST['quantity'];
        $input['buyer_phone'] = $_REQUEST['buyer_phone'];
        $input['buyer_msg'] = $_REQUEST['buyer_msg'];
        //支付密码
        $input['password'] = $_REQUEST['password'];

        //是否使用充值卡支付0是/1否
        $input['rcb_pay'] = intval($_REQUEST['rcb_pay']);

        //是否使用积分支付0是/1否
        $input['pd_pay'] = intval($_REQUEST['pd_pay']);

        $input['order_from'] = 2;
        $result = $logic_buy_virtual->buyStep3($input,$this->member_info['member_id']);
        if (!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data($result['data']);
        }
    }
    
    /**
     * 确定买单
     */
    public function pay_stepOp() {
    	$store_id = $_REQUEST['store_id'];
    	if(empty($store_id)){
    		output_error('商家信息不存在，请联系店长');
    	}
    	
    	//会员支付密码是否已设
    	if(empty($this->member_info['member_paypwd'])){
    		$pay_info['member_paypwd'] = false;
    	}else{
    		$pay_info['member_paypwd'] = true; 
    	}
    	//显示支付接口列表
    	$payment_list = array();
    	//根据店铺的ID获取，统一支付方式
    	$store_info = Model('store')->getStoreInfo(array('store_id'=>$store_id));
    	if ($store_info['payment_method']!=1){
    		$Payment_info['pay_method']=$order_info['pay_method'];
    		$Payment_info['pay_store_id']=$order_info['pay_store_id'];
    		$Payment_info['token']='shop';
    	}else{
    		$Payment_info = array('pay_method'=>1,'pay_store_id'=>0,'token'=>'root');
    	}
    	$store_trade = unserialize($store_info['store_trade']);
    	$pay_in['points_trade'] = C("points_trade");
    	$pay_in['jf_ratio'] = $jf_ratio = $store_trade['jf_ratio'];
    	$pay_in['jf_limit'] = $jf_limit = $store_trade['jf_limit']; //订单最高可用云币数
    	$member_points = $this->member_info['member_points'];//会员可用云币数
    	if($member_points>$jf_limit){
    		$member_points = $jf_limit;
    	}
    	if(empty($jf_ratio)){
    		$member_points = 0; //云币不可用
    	}
    	//查询支付单信息
    	$model_order= Model('vr_order');
    	
    	$pay_info = array();
    	//订单总支付金额(不包含货到付款)
    	$pay_info['payed_amount'] = $pay_amount = 0;
    	//重新计算在线支付金额
    	$pay_info['pay_amount_online'] = $pay_amount_online = 0;
    	//订单已支付金额
    	$pay_info['payed_amount'] = $payed_amount = 0;
    	//已用充值卡支付金额
    	$pay_info['payed_rcb_amount'] = $payed_rcb_amount = 0;
    	//已用余额支付金额
    	$pay_info['payed_pd_amount'] = $payed_pd_amount = 0;
    	//已用云币支付金额
    	$pay_info['payed_points_amount'] = $payed_points_amount = 0;
    	
    	$pay_info['member_available_pd'] = $this->member_info['available_predeposit']; //会员帐户可用余额
    	$pay_info['member_available_rcb'] = $this->member_info['available_rc_balance'];	//会员充值卡可用余额
    	$pay_info['member_available_points'] = $member_points;	//会员可用云币
    	
    	$model_payment = Model('mb_payment');
    	$condition = array();
    	$payment_arr = $model_payment->getMbPaymentOpenList($condition,$Payment_info['pay_store_id'],$Payment_info['token']);//支付方式_待修改
    	if (!empty($payment_arr)) {
    		//移除非在线支付方式
    		$unpayments=array();
    		$unpayments[]='predeposit';
    		$unpayments[]='offline';
    		if (!$this->is_weixin()){
    			$unpayments[]='wxpay';
    		}
    		foreach ($payment_arr as $k => $value) {
    			if (in_array($value['payment_code'],$unpayments)){
    					unset($payment_arr[$k]);
    			}else{
    					$payment_list[] = $value;
    			}
    		}
    	}
    	$pay_in["pay_info"]=$pay_info;
    	$pay_in["pay_info"]["payment_list"]=$payment_list;
    	output_data($pay_in);
    }
    
    /**
     * 异业联盟店铺消费时:直接保存订单入库，产生订单号，开始选择支付方式
     *
     */
    public function vrpay_stepOp() {
    	$needpassword = $_REQUEST['password'];
    	if($this->member_info['member_paypwd'] != md5($_REQUEST['password']) && $needpassword){
    		output_error('支付密码错误');
    	}
    	$logic_buy_virtual = Logic('buy_virtual');
        $input = array();
        $input['store_id'] = $_REQUEST['store_id'];
        $input['buyer_msg'] = "异业联盟店铺消费";
        $input['pay_price'] = $_REQUEST['pay_price'];
        $input['total_price'] = $_REQUEST['total_price'];
        //支付密码
        $input['password'] = $_REQUEST['password'];
        //是否使用亲诚币支付0是/1否
        $input['qcb_pay'] = intval($_REQUEST['qcb_pay']);
        //是否使用充值卡支付0是/1否
        $input['rcb_pay'] = intval($_REQUEST['rcb_pay']);
        //是否使用云币支付0是/1否
        $input['jf_pay'] = intval($_REQUEST['jf_pay']);
        //是否使用积分支付0是/1否
        $input['pd_pay'] = intval($_REQUEST['pd_pay']);
		//获取支付方式
        $input['payment_code'] = $_REQUEST['payment_code'];
        //需要支付支付
        $input['jf_price'] = $_REQUEST['jf_price'];
        
        $input['order_from'] = 2;
        $result = $logic_buy_virtual->vrpayStep($input,$this->member_info['member_id']);
        if (!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data(array('pay_sn' => $result['data']['order_sn']));
        }
    }
    
    /**
     * 下单时支付页面
     */
    public function payOp() {
    	$pay_sn	= $_GET['pay_sn'];
    	if (empty($pay_sn)){
    		$pay_sn	= $_REQUEST['pay_sn'];
    	}
    	if (!preg_match('/^\d{18}$/',$pay_sn)){
    		output_error('无效订单');
    	}
    
    	//查询支付单信息
    	$model_order= Model('vr_order');
    	$pay_info = $model_order->getOrderInfo(array('order_sn'=>$pay_sn,'buyer_id'=>$this->member_info['member_id']),'*',true);
    	if(empty($pay_info)){
    		output_error('该订单不存在');
    	}
    	Tpl::output('pay_info',$pay_info);
    
    	//取子订单列表
    	$condition = array();
    	$condition['order_sn'] = $pay_sn;
    	$condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
    	$order_list = $model_order->getOrderList($condition,'','order_id,order_state,payment_code,order_amount,rcb_amount,points_amount,pd_amount,order_sn,store_id,store_name,add_time');
    	if (empty($order_list)) {
    		output_error('未找到需要支付的订单');
    	}
    
    	//订单总支付金额(不包含货到付款)
    	$pay_amount = 0;
    	//重新计算在线支付金额
    	$pay_amount_online = 0;
    	//订单已支付金额
    	$payed_amount = 0;
    	//已用充值卡支付金额
    	$payed_rcb_amount = 0;
    	//已用余额支付金额
    	$payed_pd_amount = 0;
    	//已用云币支付金额
    	$payed_points_amount = 0;
    	//add by yangbaiyan 2015-02-26
    	$payment_info = array('pay_method'=>1,'pay_store_id'=>0,'token'=>'root');
    	foreach ($order_list as $key => $order_info) {
    		$payed_amount = 0;
    		if ($order_info['rcb_amount']>0){
    			$payed_rcb_amount += floatval($order_info['rcb_amount']);
    			$payed_amount += floatval($order_info['rcb_amount']);
    		}
    		if ($order_info['points_amount']>0){
    			$payed_points_amount += floatval($order_info['points_amount']);
    			$payed_amount += floatval($order_info['points_amount']);
    		}
    		if ($order_info['pd_amount']>0){
    			$payed_pd_amount += floatval($order_info['pd_amount']);
    			$payed_amount += floatval($order_info['pd_amount']);
    		}
    		//计算相关支付金额
    		if ($order_info['payment_code'] != 'offline') {
    			$pay_amount += floatval($order_info['order_amount']);
    			//操 压根都没有这个字段 $pay_qcbamount += floatval($order_info['order_qcbamount']);
    
    			if ($order_info['order_state'] == ORDER_STATE_NEW) {
    				$pay_amount_online += imPriceFormat(floatval($order_info['order_amount'])-$payed_amount);
    			}
    		}
    		
    	}
    	//站内已支付的金额 ： 充值卡支付金额+积分支付金额 + 云币支付
    	$payed_amount = $payed_rcb_amount + $payed_pd_amount +$payed_points_amount;
    	//显示支付方式与支付结果
    	$payed_tips = '';
    	if ($payed_amount > 0) {
    		if ($payed_rcb_amount > 0) {
    			$payed_tips = '充值卡已支付：￥'.$payed_rcb_amount;
    		}
    		if ($payed_points_amount > 0) {
    			$payed_tips = '云币已支付：￥'.$payed_points_amount;
    		}
    		if ($payed_pd_amount > 0) {
    			$payed_tips .= ' 积分已支付：￥'.$payed_pd_amount;
    		}
    	}
    	$pay_info['pay_sn'] = $pay_sn; //支付码
    	$pay_info['pay_amount'] = $pay_amount; //订单总金额
    	$pay_info['payed_amount'] = $payed_amount;	//订单已支付金额
    	$pay_info['payed_tips'] = $payed_tips; //订单已支付金额说明
    	$pay_info['pay_amount_online'] = $pay_amount_online; //订单待在线支付金额
    	$pay_info['member_available_pd'] = $this->member_info['available_predeposit']; //会员帐户可用余额
    	$pay_info['member_available_rcb'] = $this->member_info['available_rc_balance'];	//会员充值卡可用余额
    	$pay_info['member_available_points'] = $this->member_info['member_points']*C("points_trade");	//会员可用云币
    	$pay_info['member_paypwd'] = true; //会员支付密码是否已设
    	if(empty($this->member_info['member_paypwd'])){
    		$pay_info['member_paypwd'] = false;
    	}
    	//显示支付接口列表
    	$payment_list = array();
    	if ($pay_amount_online > 0) {
    		$model_payment = Model('mb_payment');
    		$condition = array();
    		$payment_arr = $model_payment->getMbPaymentOpenList($condition,$payment_info['pay_store_id'],$payment_info['token']);//支付方式_待修改
    		if (!empty($payment_arr)) {
    			//移除非在线支付方式
    			$unpayments=array();
    			$unpayments[]='predeposit';
    			$unpayments[]='offline';
    			if (!$this->is_weixin()){
    				$unpayments[]='wxpay';
    			}
    			foreach ($payment_arr as $k => $value) {
    				if (in_array($value['payment_code'],$unpayments)){
    					unset($payment_arr[$k]);
    				}else{
    					$payment_list[] = $value;
    				}
    			}
    		}
    	}
    
    	$pay_in["pay_info"]=$pay_info;
    	$pay_in["pay_info"]["payment_list"]=$payment_list;
    	output_data($pay_in);
    }
    
    /**
     * 验证密码
     */
    public function check_passwordOp() {
    	if(empty($_REQUEST['password'])) {
    		output_error('参数错误');
    	}
    
    	$model_member = Model('member');
    
    	$member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
    	if($member_info['member_paypwd'] == md5($_REQUEST['password'])) {
    		output_data('1');
    	} else {
    		output_error('密码错误');
    	}
    }
    
    
    /**
     * 验证是否是微信浏览器
     */
    public function is_weixin(){
    	$user_agent = $_SERVER['HTTP_USER_AGENT'];
    	if (strpos($user_agent, 'MicroMessenger') === false){
    		return false;
    	}else{
    		return true;
    	}
    }
    
}
