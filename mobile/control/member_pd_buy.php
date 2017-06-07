<?php
/**
 * 充值卡购买 或积分充值
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

defined('InIMall') or exit('Access Invalid!');

class member_pd_buyControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

	/**
	 * 充值卡第一步，即可直接生成订单
	 * POST
	 * 传入：充值卡的ID 即可知道金额
	 */
	public function buy_step1Op() {
		//获取充值卡ID
		$card_id = (string) $_POST['card_id'];
		$rechargecard_model = Model('rechargecard');
		$model_pdr = Model('predeposit');
		//查询出相关符合的充值卡
		$card = $rechargecard_model->getRechargeCardByID($card_id);
		if(!empty($card)){
			//如果该卡已经绑定，则看出同类型的为绑定的卡
			if(!empty($card['state'])&&!empty($card['member_id'])){
				$rcb_where = array();
				$rcb_where['denomination'] = $card['denomination']; //面额一样
				$rcb_where['card_grant'] = $card['card_grant']; //类型一样
				$rcb_where['card_points'] = $card['card_points']; //赠送云币一样
				$rcb_where['pd_amount'] = $card['pd_amount']; //积分也一样
				$rcb_where['state'] = 0; //未被使用过的卡
				$card_list = $rechargecard_model->getVipCardList($rcb_where,'id,sn,pd_amount,card_points'); //查询出同类型的 还未使用的卡
				if(empty($card_list)){
					output_error("VIP充值卡已经用完，请联系店长");
				}
				//取第一个卡号
				$card_id = $card_list[0]['id'];
				$cart_sn = $card_list[0]['sn'];
				$cart_amount = $card_list[0]['pd_amount']; //充值积分
				$card_points = $card_list[0]['card_points']; //赠送云币数
				
			}else{
				$cart_sn = $card['sn'];
				$cart_amount = $card['pd_amount']; //充值积分
				$card_points = $card['card_points']; //赠送云币数
			}
			//该卡是否有购买次数限制
			if($card['buy_limit']>0){
				$pdr_where['pdr_member_id'] = $_SESSION['member_id'];
				$pdr_where['pdr_admin'] = $card_id;
				$pdr_where['pdr_payment_state'] = 1;
				$pdrc_count = $model_pdr->getPdRechargeCount($pdr_where);
				if($pdrc_count >= $card['buy_limit']){
					output_error("抱歉您已经充值过，并且充值的次数已经达到该类型充值卡的限制上限，不能再充值。");
				}
			}
			if (!$cart_sn || strlen($cart_sn) > 50) {
				output_error("平台充值卡卡号不能为空且长度不能大于50");
			}
			
			try {
				
				if($cart_amount == 0){
					$cart_amount = $card['denomination']; //积分的金额为充值卡的
				}
				//生成 积分的订单
				$data = array();
				$data['pdr_sn'] = $pay_sn = $model_pdr->makeSn();
				$data['pdr_member_id'] = $_SESSION['member_id'];
				$data['pdr_member_name'] = $_SESSION['member_name'];
				$data['pdr_payment_code'] = "online";
				$data['pdr_payment_name'] = "在线支付";
				$data['pdr_amount'] = $cart_amount;
				$data['pdr_add_time'] = TIMESTAMP;
				$data['pdr_admin'] = $card_id;
				$result = $model_pdr->addPdRecharge($data);
				
				if($result){
					$this->pd_payOp($pay_sn);
				}else{
					output_error("该卡可能已经失效，请联系店长");
				}
				
			} catch (Exception $e) {
				output_error($e->getMessage());
			}
		}else{
			output_error("该卡可能已经失效，请联系店长");
		}
		
	}

    /**
     * 积分充值下单时支付页面
     */
    public function pd_payOp($pay_sn) {
    	if(empty($pay_sn)){
	    	$pay_sn	= $_GET['pay_sn'];
    	}
    	if (!preg_match('/^\d{18}$/',$pay_sn)){
    		output_error('无效订单');
    	}
    	//查询支付单信息
    	$model_order= Model('predeposit');
    	$pd_info = $model_order->getPdRechargeInfo(array('pdr_sn'=>$pay_sn,'pdr_member_id'=>$_SESSION['member_id']));
    	if(empty($pd_info)){
    		output_error('无效订单');
    	}
    	if (intval($pd_info['pdr_payment_state'])) {
    		output_error('您的订单已经支付，请勿重复支付');
    	}
    	//显示支付接口列表
    	$model_payment = Model('payment');
    	$condition = array();
    	$condition['payment_code'] = array('not in',array('offline','predeposit'));
    	$condition['payment_state'] = 1;
    	$payment_list = $model_payment->getPaymentList($condition);//支付方式_待修改
    	if (empty($payment_list)) {
    		output_error('您的订单已经支付，请勿重复支付');
    	}
    	$pay_in["pay_info"]=$pd_info;
    	$pay_in["pay_info"]["payment_list"]=$payment_list;
    	output_data($pay_in);
    }
    
    
    /**
     * 确定买单
     */
    public function pay_stepOp() {
    	
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
    	$pay_info['member_available_points'] = $this->member_info['member_points']*C("points_trade");	//会员可用云币
    	//会员支付密码是否已设
    	if(empty($this->member_info['member_paypwd'])){
    		$pay_info['member_paypwd'] = false;
    	}else{
    		$pay_info['member_paypwd'] = true; 
    	}
    	//显示支付接口列表
    	$payment_list = array();
    	$store_id = $_POST['store_id'];
    	//根据店铺的ID获取，统一支付方式
    	$store_info = Model('store')->getStoreInfo(array('store_id'=>$store_id));
    	if ($store_info['payment_method']!=1){
    		$Payment_info['pay_method']=$order_info['pay_method'];
    		$Payment_info['pay_store_id']=$order_info['pay_store_id'];
    		$Payment_info['token']='shop';
    	}else{
    		$Payment_info = array('pay_method'=>1,'pay_store_id'=>0,'token'=>'root');
    	}
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
    	$needpassword = $_POST['password'];
    	if($this->member_info['member_paypwd'] != md5($_POST['password']) && $needpassword){
    		output_error('支付密码错误');
    	}
    	$logic_buy_virtual = Logic('buy_virtual');
        $input = array();
        $input['store_id'] = $_POST['store_id'];
        $input['buyer_msg'] = "异业联盟店铺消费";
        $input['pay_price'] = $_POST['pay_price'];
        $input['total_price'] = $_POST['total_price'];
        //支付密码
        $input['password'] = $_POST['password'];
        //是否使用亲诚币支付0是/1否
        $input['qcb_pay'] = intval($_POST['qcb_pay']);
        //是否使用充值卡支付0是/1否
        $input['rcb_pay'] = intval($_POST['rcb_pay']);
        //是否使用云币支付0是/1否
        $input['jf_pay'] = intval($_POST['jf_pay']);
        //是否使用积分支付0是/1否
        $input['pd_pay'] = intval($_POST['pd_pay']);
		//获取支付方式
        $input['payment_code'] = $_POST['payment_code'];
        
        
        $input['order_from'] = 2;
        $result = $logic_buy_virtual->vrpayStep($input,$this->member_info['member_id']);
        if (!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data(array('pay_sn' => $result['data']['order_sn']));
        }
    }
    
    
    
    /**
     * 验证密码
     */
    public function check_passwordOp() {
    	if(empty($_POST['password'])) {
    		output_error('参数错误');
    	}
    
    	$model_member = Model('member');
    
    	$member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
    	if($member_info['member_paypwd'] == md5($_POST['password'])) {
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
