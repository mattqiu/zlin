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

class member_buyControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 购物车、直接购买第一步:选择收获地址和配置方式
     */
    public function buy_step1Op() {
        $cart_id = explode(',', $_POST['cart_id']);
		$address_id = $_POST['address_id'];

        $logic_buy = logic('buy');
		
    	//得到会员等级
        $model_member = Model('member');

        $member_info = $this->member_info;
        $member_level = $this->member_info['member_grade'];
        //得到购买数据
        $result = $logic_buy->buyStep1($cart_id, $_POST['ifcart'], $this->member_info['member_id'], $this->member_info['store_id'],$member_level);

        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            $result = $result['data'];
        }
        //整理数据
        $store_cart_list = array();
		$sum = 0;
		$is_ikjtao = 0;
        foreach ($result['store_cart_list'] as $key => $value) {
        	//key就是店铺ID
        	$store_id = $key;
        	//如果云币方式是第三种情况，则商品的售价 = 吊牌价 zhangc
        	$points_way = Model('store')->getStorePoints_WayByID($store_id);
        	$store_cart_list[$key]['points_way'] = $points_way; //店铺云币方式 
            $store_cart_list[$key]['goods_list'] = $value;
			//店铺商品总价小计
            $store_cart_list[$key]['store_goods_total'] = $result['store_goods_total'][$key];
            //店铺商品可需支付云币总数
            $store_cart_list[$key]['store_vip_points_total'] = ceil($result['store_point_total'][$key]['store_vip_points_total']/C("points_trade"));//转换云币;
            //店铺商品返利积分数
            $store_cart_list[$key]['rebate_amount'] = $result['store_other_total'][$key]['store_rebate_amount'];
            //店铺商品返利云币数
            $store_cart_list[$key]['order_pointscount'] = $result['store_other_total'][$key]['order_pointscount'];
			//取得店铺优惠 - 满即送(赠品列表，店铺满送规则列表)
            //if(!empty($result['store_premiums_list'][$key])) {
            //    $result['store_premiums_list'][$key][0]['premiums'] = true;
            //    $result['store_premiums_list'][$key][0]['goods_total'] = 0.00;
            //    $store_cart_list[$key]['goods_list'][] = $result['store_premiums_list'][$key][0];
            //}
            $store_cart_list[$key]['store_mansong_rule_list'] = $result['store_mansong_rule_list'][$key];
			$store_cart_list[$key]['store_premiums_list'] = $result['store_premiums_list'][$key];			
			
			//返回店铺可用的代金券
            $store_cart_list[$key]['store_voucher_list'] = $result['store_voucher_list'][$key];			
			$store_cart_list[$key]['store_voucher_info'] = array();
			if($store_cart_list[$key]['store_voucher_list']){
				$voucher_price = 0;
				foreach ($store_cart_list[$key]['store_voucher_list'] as $k => $voucher_info) {
					if ($voucher_info['voucher_price']>$voucher_price){				        
						$voucher_t_id = $voucher_info['voucher_t_id'];
						$voucher_price = $voucher_info['voucher_price'];
						$voucher_desc = $voucher_info['desc'];
					}
				}
				$store_cart_list[$key]['store_voucher_info'] = array('voucher_t_id'=>$voucher_t_id,'voucher_price'=>$voucher_price,'voucher_desc'=>$voucher_desc);
			}
			//返回需要计算运费的店铺ID数组 和 不需要计算运费(满免运费活动的)店铺ID及描述
            if(!empty($result['cancel_calc_sid_list'][$key])) {
                $store_cart_list[$key]['freight'] = '0';
                $store_cart_list[$key]['freight_message'] = $result['cancel_calc_sid_list'][$key]['desc'];
            } else {
                $store_cart_list[$key]['freight'] = '1';
            }
            $store_cart_list[$key]['store_name'] = $value[0]['store_name'];
			$sum += $store_cart_list[$key]['store_goods_total'];
			if($store_id == C('ikjtao_store_id')||!empty($result['store_other_total'][$key]['is_ikjtao'])){
				$is_ikjtao = 1;
			}
        }
		
        $buy_list = array();
		//商品金额计算(分别对每个商品/优惠套装小计、每个店铺小计)
        $buy_list['store_cart_list'] = $store_cart_list;
		$buy_list['store_goods_total'] = $result['store_goods_total'];	
		//将商品ID、数量、运费模板、运费序列化，加密，输出到模板，选择地区AJAX计算运费时作为参数使用
        $buy_list['freight_hash'] = $result['freight_list'];
		//输出用户默认收货地址
		if (isset($address_id) && !empty($address_id)){
		    $buy_list['address_info'] = Model('address')->getAddressInfo(array('address_id'=>$address_id));
		}else{
            $buy_list['address_info'] = $result['address_info'];
		}
		if(empty($buy_list['address_info'])){
			$buy_list['member_mobile'] = $member_info['member_mobile'];
			$buy_list['member_truename'] = $member_info['member_truename'];
			$buy_list['member_idcard'] = $member_info['member_idcard'];
		}
		//检查身份证是否存在，并且有跨境淘的商品
		if(empty($buy_list['address_info']['member_idcard'])&&empty($member_info['member_idcard'])&&$is_ikjtao==1){
			$buy_list['is_idcard'] = 0;//表示不存在
		}
		//选择不同地区时，异步处理并返回每个店铺总运费以及本地区是否能使用货到付款
		$buy_list['address_api'] = logic('buy')->changeAddr($buy_list['freight_hash'],$buy_list['address_info']['city_id'],$buy_list['address_info']['area_id'], $this->member_info['member_id']);
		
		//输出有货到付款时，在线支付和货到付款及每种支付下商品数量和详细列表		
        $buy_list['ifshow_offpay'] = $result['ifshow_offpay'];
		//增值税发票哈希值(php验证使用)
        $buy_list['vat_hash'] = $result['vat_hash'];
		//输出默认使用的发票信息
        $buy_list['inv_info'] = $result['inv_info'];
        
		//显示积分、支付密码、充值卡
        $buy_list['available_predeposit'] = $result['available_predeposit'];
        $buy_list['available_rc_balance'] = $result['available_rc_balance'];
        //显示使用云币额
        $buy_list['available_points'] = $result['available_points'];
        //显示使用云币数
        $buy_list['member_points'] = $result['member_points'];
        $buy_list['member_grade'] 	= $member_level;//会员等级
		$buy_list['member_paypwd'] = $result['member_paypwd'];
		//红包
		if (is_array($result['rpt_list']) && !empty($result['rpt_list'])) {
            foreach ($result['rpt_list'] as $k => $v) {
                unset($result['rpt_list'][$k]['rpacket_id']);
                unset($result['rpt_list'][$k]['rpacket_end_date']);
                unset($result['rpt_list'][$k]['rpacket_owner_id']);
                unset($result['rpt_list'][$k]['rpacket_code']);
            }
        }
        $buy_list['rpt_list'] = $result['rpt_list'] ? $result['rpt_list'] : array();
		$buy_list['rpt_info'] = '';
		//折扣
        $buy_list['zk_list'] = $result['zk_list'];
		//订单总价
		$buy_list['order_amount'] = $sum;
		$buy_list['store_final_total_list'] = $result['store_goods_total'];//array('1'=>imPriceFormat($sum));		
		//是否存在跨境淘的商品
		$buy_list['is_ikjtao'] = $is_ikjtao;
				
        output_data($buy_list);
    }

    /**
     * 购物车、直接购买第二步:保存订单入库，产生订单号，开始选择支付方式
     *
     */
    public function buy_step2Op() {
        $param = array();
        $param['ifcart']            = $_POST['ifcart']; //是否来自购物车
        $param['cart_id']           = explode(',', $_POST['cart_id']); //购物车ID
        $param['address_id']        = $_POST['address_id']; //收货地址ID
        $param['vat_hash']          = $_POST['vat_hash']; //是否保存增值税发票判断标志
        $param['offpay_hash']       = $_POST['offpay_hash']; //
        $param['offpay_hash_batch'] = $_POST['offpay_hash_batch']; //
        $param['pay_name']          = $_POST['pay_name']; //支付方式名称 online 在线支付, offline:货到付款
        $param['invoice_id']        = $_POST['invoice_id']; //发票ID
		$param['rpt']               = $_POST['rpt']; //红包支付金额	
        //处理代金券
        $voucher = array();
        $post_voucher = explode(',', $_POST['voucher']);
        if(!empty($post_voucher)) {
            foreach ($post_voucher as $value) {
                list($voucher_t_id, $store_id, $voucher_price) = explode('|', $value);
                $voucher[$store_id] = $value;
            }
        }
        $param['voucher'] = $voucher;
        
		$param['pd_pay']   = $_POST['pd_pay']; //余额支付金额
		$param['rcb_pay']  = $_POST['rcb_pay']; //充值卡支付金额
		$param['js_pay']  = $_POST['js_pay']; //云币支付金额
        $param['password'] = $_POST['password']; //支付密码
        $param['fcode']    = $_POST['fcode']; //F码
		//手机端暂时不做支付留言，页面内容太多了   
		//$param['pay_message'] = json_decode($_POST['pay_message']);		
        $param['order_from'] = 2;
        $logic_buy = logic('buy');
		
		//得到会员等级
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($this->member_info['member_id']);
        if ($member_info){
            $member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
            $member_discount = $member_gradeinfo['orderdiscount'];
            $member_level = $member_gradeinfo['level'];
        } else {
            $member_discount = $member_level = 0;
        }
        
        //$result = $logic_buy->buyStep2($param, $this->member_info['member_id'], $this->member_info['member_name'], $this->member_info['member_email'],$member_discount,$member_level);
        $result = $logic_buy->buyStep2($param, $this->member_info['member_id'], $this->member_info['member_name'], $this->member_info['member_email']);
        
        if(!$result['state']) {
            output_error($result['msg']);
        }

        output_data(array('pay_sn' => $result['data']['pay_sn']));
    }
	
	/**
     * 下单时支付页面
     */
    public function payOp() {
		$pay_sn	= $_GET['pay_sn'];
		if (empty($pay_sn)){
			$pay_sn	= $_POST['pay_sn'];
		}
        if (!preg_match('/^\d{18}$/',$pay_sn)){
            output_error('无效订单');
        }
		
		//查询支付单信息
        $model_order= Model('order');
        $pay_info = $model_order->getOrderPayInfo(array('pay_sn'=>$pay_sn,'buyer_id'=>$this->member_info['member_id']),true);
        if(empty($pay_info)){
            output_error('该订单不存在');
        }
        Tpl::output('pay_info',$pay_info);

        //取子订单列表
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition,'','order_id,order_sn,store_id,store_name,order_state,payment_code,order_amount,points_amount,usable_points,rcb_amount,pd_amount,rebate_amount,add_time,shipping_fee,pay_sn,pay_method, pay_store_id','','',array('order_common'),true);
        if (empty($order_list)) {
            output_error('未找到需要支付的订单');
        }
		
		//订单总支付金额(不包含货到付款)
        $pay_amount = 0;
		//重新计算在线支付金额
        $pay_amount_online = 0;
        //订单商品支付总额
        $pay_goods_amount = 0;
        //订单总快递费
        $pay_shipping_fee = 0;
		//订单已支付金额
		$payed_amount = 0;
		//已用充值卡支付金额
		$payed_rcb_amount = 0;
		//已用余额支付金额
		$payed_pd_amount = 0;
		//已用云币支付金额
		$payed_points_amount = 0;
		
		//折扣店和会员店模式 是需要在结算时 就抵扣掉
		$payjb_deduct_points = 0;
		//会员云币
		$member_points = $this->member_info['member_points'];
		//返利积分、云币
		$rebate_points_amount = 0;
		$rebate_points_total = 0;
		//add by yangbaiyan 2015-02-26
		$Payment_info = array('pay_method'=>1,'pay_store_id'=>0,'token'=>'root');
		foreach ($order_list as $key => $order_info) {
			$payed_amount = 0;
			if ($order_info['rcb_amount']>0){
				$payed_rcb_amount += floatval($order_info['rcb_amount']);
				$payed_amount += floatval($order_info['rcb_amount']);
			}
			if($order_info['points_amount']>0){
				$payed_points_amount += floatval($order_info['points_amount']);
			}
			if ($order_info['pd_amount']>0){
				$payed_pd_amount += floatval($order_info['pd_amount']);
				$payed_amount += floatval($order_info['pd_amount']);
			}
			//返利积分
			if($order_info['rebate_amount']>0){
				$rebate_points_amount += floatval($order_info['rebate_amount']);
			}
			//返利云币
			if($order_info['extend_order_common']['order_pointscount']>0){
				$rebate_points_total += floatval($order_info['extend_order_common']['order_pointscount']);
			}
			$store_id = $order_info['store_id'];
			//用户云币不足的时候是需要现金补足的 zhangc
			if($order_info['usable_points']>0){//折扣模式和会员店模式
				//计算出在此模式下，实际应该支付的云币数
				$payjb_deduct_points += $order_info['usable_points'];
				$payed_points_amount = $payjb_deduct_points;//使用金额
				$payjb_deduct_points = ceil($payjb_deduct_points/C("points_trade"));//金额转换云币
			}
            //计算相关支付金额
            if ($order_info['payment_code'] != 'offline') {
				$pay_amount += floatval($order_info['order_amount']);
				$pay_qcbamount += 0;//操 压根都没有这个字段floatval($order_info['order_qcbamount']);
				
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $pay_amount_online += imPriceFormat(floatval($order_info['order_amount'])-$payed_amount);
                }
                //快递费 不能抵扣
                $payed_shipping_fee += floatval($order_info['shipping_fee']);
            }
			//add by yangbaiyan 2015-02-26
			if ($order_info['pay_method']!=1){
				$Payment_info['pay_method']=$order_info['pay_method'];
				$Payment_info['pay_store_id']=$order_info['pay_store_id'];
				$Payment_info['token']='shop';
			}
        }
		
		//显示支付方式与支付结果		
		$payed_tips = '';
		$payed_points_tips = "";
		//返利显示
		if($rebate_points_amount>0){
			$payed_tips .= "完成订单可获得".$rebate_points_amount."积分";
		}
		if($rebate_points_total>0){
			$payed_tips .= "完成订单可获得".$rebate_points_total."云币";
		}
		//计算时需要抵扣掉的云币数：不足时,不足部分的云币需要多支付现金；支付时就要扣减掉云币
		if(!empty($payjb_deduct_points)){
			if ($payjb_deduct_points <= $member_points) {
				//会员云币
				$member_points = $member_points - $payjb_deduct_points; //订单可用云币-会员云币
				$payed_tips .= "还需支付 ".$payjb_deduct_points." 云币";
			}else{
			//云币不足时
			$deduct_points = $payjb_deduct_points - $member_points; //订单可用云币-会员云币
			$pay_amount_online += floatval($deduct_points*C("points_trade")); //差额部分的云币需要转换成现金
				//云币转换现金的小数点部分.注意：以下代码先后顺序不能错，很重要
				$decimal_amount = $payjb_deduct_points - $payed_points_amount;
				$payjb_deduct_points = $member_points;
				$payed_points_amount = floatval($payjb_deduct_points*C("points_trade"))-$decimal_amount;
			//另外会员云币余额是需要减去自动扣除的部分
				$member_points = 0;
			//订单可用云币额 大于 用户可用云币额
				$payed_tips .= "还需支付 ".$payjb_deduct_points." 云币,您当前云币不足,还差 ".$deduct_points." 云币";
			}			
		}
		
		//此次订单可用云币是
		$usable_points_total = $payjb_deduct_points;
		$usable_points_amount = $payed_points_amount;//扣减完成后 需要转换会现金
		//注意先后顺序 不然结果就会发生变化 zhangc
		//站内已支付的金额 ： 充值卡支付金额+积分支付金额 
		$payed_amount = $payed_rcb_amount + $payed_pd_amount ;//+ $payed_points_amount;
		$member_available_points = $member_points;	//会员剩余云币	
        if ($payed_amount > 0) {        
            if ($payed_rcb_amount > 0) {				
                $payed_tips = '充值卡已支付：￥'.$payed_rcb_amount;
            }
            if ($payed_pd_amount > 0) {
                $payed_tips .= ' 积分已支付：￥'.$payed_pd_amount;
            }			
        }
		$pay_info['pay_sn'] = $pay_sn; //支付码		
		$pay_info['pay_amount'] = $pay_amount; //订单总金额
		$pay_info['payed_amount'] = $payed_amount;	//订单已支付金额
		$pay_info['payed_tips'] = $payed_tips; //订单已支付金额说明
		$pay_info['usable_points_amount'] = $usable_points_amount; //订单使用云币抵扣总额
		$pay_info['usable_points_total'] = $usable_points_total; //订单使用云币总数
		$pay_info['payed_points_tips'] = $payed_points_tips; //订单已支付金额说明
		$pay_info['pay_amount_online'] = $pay_amount_online; //订单待在线支付金额
		

		$pay_info['member_available_pd'] = $this->member_info['available_predeposit']; //会员帐户可用余额
		$pay_info['member_available_rcb'] = $this->member_info['available_rc_balance'];	//会员充值卡可用余额
		$pay_info['member_available_points'] = $member_available_points;//会员剩余云币 = $this->member_info['member_points']*C("points_trade");	//会员可用云币
		$pay_info['member_points'] = $member_points;	//会员云币
		$pay_info['member_paypwd'] = true; //会员支付密码是否已设
		if(empty($this->member_info['member_paypwd'])){
			$pay_info['member_paypwd'] = false;
		}
		//显示支付接口列表
		$payment_list = array();
        if ($pay_amount_online > 0) {
			$model_payment = Model('mb_payment');
            $condition = array();
			$payment_arr = $model_payment->getMbPaymentOpenList($condition,$Payment_info['pay_store_id'],$Payment_info['token']);//支付方式_待修改
            if (!empty($payment_arr)) {
				//移除非在线支付方式
			    $unpayments=array();
			    $unpayments[]='predeposit';
			    $unpayments[]='offline';
			    if (!$this->is_weixin()){
				    //$unpayments[]='wxpay';
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
     * 支付密码确认
     */
    public function check_pd_pwdOp() {
		if($this->member_info['member_paypwd'] != md5($_POST['password'])){
			output_error('支付密码错误');
		}else{
			output_data('OK');
		}
	}

    /**
     * 更换收货地址
     */
    public function change_addressOp() {
        $logic_buy = Logic('buy');
		
		if (empty($_POST['city_id'])) {
            $_POST['city_id'] = $_POST['area_id'];
        }

        $data = $logic_buy->changeAddr($_POST['freight_hash'], $_POST['city_id'], $_POST['area_id'], $this->member_info['member_id']);
        if(!empty($data) && $data['state'] == 'success' ) {
            output_data($data);
        } else {
            output_error('地址修改失败');
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