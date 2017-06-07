<?php
/**
 * 支付入口
 *
 * 
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class paymentControl extends BaseHomeControl{

    public function __construct() {
        //向前兼容
        $_GET['extra_common_param'] = str_replace(array('predeposit','product_buy'),array('pd_order','real_order'),$_GET['extra_common_param']);
        $_POST['extra_common_param'] = str_replace(array('predeposit','product_buy'),array('pd_order','real_order'),$_POST['extra_common_param']);
    }

	/**
	 * 实物商品订单
	 */
	public function real_orderOp(){		
	    $pay_sn = $_POST['pay_sn'];
		$payment_code = $_POST['payment_code'];
        $url = 'index.php?act=member_order';

        if(!preg_match('/^\d{18}$/',$pay_sn)){
            showMessage('参数错误','','html','error');
        } 
		    
		//取订单列表
		$logic_payment = Logic('payment');
        $order_pay_info = $logic_payment->getRealOrderInfo($pay_sn, $_SESSION['member_id']);
        if(!$order_pay_info['state']) {
            showMessage($order_pay_info['msg'], $url, 'html', 'error');
        }
		
		//站内余额支付
        $order_list = $this->_pd_pay($order_pay_info['data']['order_list'],$_POST);
		
		//计算本次需要在线支付（分别是含站内支付、纯第三方支付接口支付）的订单总金额
		//add by yangbaiyan 2015-02-26
		$Payment_info = array('pay_method'=>1,'pay_store_id'=>0,'token'=>'root');
		
        $pay_amount = 0;
        $api_pay_amount = 0;
        $pay_order_id_list = array();
        if (!empty($order_list)) {
            foreach ($order_list as $order_info) {
                if ($order_info['order_state'] == ORDER_STATE_NEW) {
                    $api_pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                    $pay_order_id_list[] = $order_info['order_id'];
                }
                $pay_amount += $order_info['order_amount'];
				//add by yangbaiyan 2015-02-26
			    if ($order_info['pay_method']!=1){
				    $Payment_info['pay_method']=$order_info['pay_method'];
				    $Payment_info['pay_store_id']=$order_info['pay_store_id'];
				    $Payment_info['token']='shop';
			    }
            }
        }
        if (empty($api_pay_amount)) {
            redirect(SHOP_SITE_URL.'/index.php?act=buy&op=pay_ok&pay_sn='.$order_pay_info['data']['pay_sn'].'&pay_amount='.imPriceFormat($pay_amount));
        }
		
		$result = Model('order')->editOrder(array('api_pay_time'=>TIMESTAMP),array('order_id'=>array('in',$pay_order_id_list)));
        if(!$result) {
            showMessage('更新订单信息发生错误，请重新支付', $url, 'html', 'error');
        }
		
		//获取支付信息		
        $result = $logic_payment->getPaymentInfo($payment_code,$Payment_info['pay_store_id'],$Payment_info['token']);		
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }
        $payment_info = $result['data'];
		
		$order_pay_info['data']['api_pay_amount'] = imPriceFormat($api_pay_amount);
		
		//如果是开始支付尾款，则把支付单表重置了未支付状态，因为支付接口通知时需要判断这个状态
        if ($order_pay_info['data']['if_buyer_repay']) {
            $update = Model('order')->editOrderPay(array('api_pay_state'=>0),array('pay_id'=>$order_pay_info['data']['pay_id']));
            if (!$update) {
                showMessage('订单支付失败', $url, 'html', 'error');
            }
            $order_pay_info['data']['api_pay_state'] = 0;
        }

        //转到第三方API支付
        $this->_api_pay($order_pay_info['data'], $payment_info);
	}

	/**
	 * 虚拟商品购买
	 */
	public function vr_orderOp(){
	    $order_sn = $_POST['order_sn'];
	    $payment_code = $_POST['payment_code'];
	    $url = 'index.php?act=member_vr_order';

	    if(!preg_match('/^\d{18}$/',$order_sn)){
            showMessage('参数错误','','html','error');
        }

        $logic_payment = Logic('payment');
        $result = $logic_payment->getPaymentInfo($payment_code);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }
        $payment_info = $result['data'];

        //计算所需支付金额等支付单信息
        $result = $logic_payment->getVrOrderInfo($order_sn, $_SESSION['member_id']);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }

        if ($result['data']['order_state'] != ORDER_STATE_NEW || empty($result['data']['api_pay_amount'])) {
            showMessage('该订单不需要支付', $url, 'html', 'error');
        }

        //转到第三方API支付
        $this->_api_pay($result['data'], $payment_info);
	}

	/**
	 * 积分充值
	 */
	public function pd_orderOp(){
	    $pdr_sn = $_POST['pdr_sn'];
	    $payment_code = $_POST['payment_code'];
	    $url = 'index.php?act=predeposit';

	    if(!preg_match('/^\d{18}$/',$pdr_sn)){
	        showMessage('参数错误',$url,'html','error');
	    }

	    $logic_payment = Logic('payment');
	    $result = $logic_payment->getPaymentInfo($payment_code);
	    if(!$result['state']) {
	        showMessage($result['msg'], $url, 'html', 'error');
	    }
	    $payment_info = $result['data'];

        $result = $logic_payment->getPdOrderInfo($pdr_sn,$_SESSION['member_id']);
        if(!$result['state']) {
            showMessage($result['msg'], $url, 'html', 'error');
        }
        if ($result['data']['pdr_payment_state'] || empty($result['data']['api_pay_amount'])) {
            showMessage('该充值单不需要支付', $url, 'html', 'error');
        }

	    //转到第三方API支付
	    $this->_api_pay($result['data'], $payment_info);
	}
	
	/**
     * 站内余额支付(充值卡、积分支付) 实物订单
     *
     */
    private function _pd_pay($order_list, $post) {
        if (empty($post['password'])) {
            return $order_list;
        }
        $model_member = Model('member');
        $buyer_info = $model_member->getMemberInfoByID($_SESSION['member_id']);
        if ($buyer_info['member_paypwd'] == '' || $buyer_info['member_paypwd'] != md5($post['password'])) {
            return $order_list;
        }
		
        if ($buyer_info['available_rc_balance'] == 0) {
            $post['rcb_pay'] = null;
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
            }

            //使用积分支付
            if (!empty($post['pd_pay'])) {
                $order_list = $logic_buy_1->pdPay($order_list, $post, $buyer_info);
            }

            //特殊订单站内支付处理
            //$logic_buy_1->extendInPay($order_list);

            $model_member->commit();
        } catch (Exception $e) {
            $model_member->rollback();
            showMessage($e->getMessage(), '', 'html', 'error');
        }

        return $order_list;
    }

	/**
	 * 第三方在线支付接口
	 *
	 */
	private function _api_pay($order_info, $payment_info) {
    	$payment_api = new $payment_info['payment_code']($payment_info,$order_info);
    	if($payment_info['payment_code'] == 'chinabank') {
    		$payment_api->submit();
    	} else {
    		@header("Location: ".$payment_api->get_payurl());
    	}
    	exit();
	}

	/**
	 * 通知处理(支付宝异步通知和网银在线自动对账)
	 *
	 */
	public function notifyOp(){
        switch ($_GET['payment_code']) {
            case 'alipay':
                $success = 'success'; $fail = 'fail'; break;
            case 'chinabank':
                $success = 'ok'; $fail = 'error'; break;
            default: 
                exit();
        }

        $order_type = $_POST['extra_common_param'];
        $out_trade_no = $_POST['out_trade_no'];
        $trade_no = $_POST['trade_no'];

		//参数判断
		if(!preg_match('/^\d{18}$/',$out_trade_no)) exit($fail);

		$model_pd = Model('predeposit');
		$logic_payment = Logic('payment');

		if ($order_type == 'real_order') {

		    $result = $logic_payment->getRealOrderInfo($out_trade_no);
		    if (intval($result['data']['api_pay_state'])) {
		        exit($success);
		    }
		    $order_list = $result['data']['order_list'];

	    } elseif ($order_type == 'vr_order'){

	        $result = $logic_payment->getVrOrderInfo($out_trade_no);
	        if ($result['data']['order_state'] != ORDER_STATE_NEW) {
	            exit($success);
	        }

		} elseif ($order_type == 'pd_order') {

		    $result = $logic_payment->getPdOrderInfo($out_trade_no);
		    if ($result['data']['pdr_payment_state'] == 1) {
		        exit($success);
		    }

		} else {
		    exit();
		}
		$order_pay_info = $result['data'];

		//取得支付方式
		$result = $logic_payment->getPaymentInfo($_GET['payment_code']);
		if (!$result['state']) {
		    exit($fail);
		}
		$payment_info = $result['data'];

		//创建支付接口对象
		$payment_api	= new $payment_info['payment_code']($payment_info,$order_pay_info);

		//对进入的参数进行远程数据判断
		$verify = $payment_api->notify_verify();
		if (!$verify) {
		    exit($fail);
		}

        //购买商品
		if ($order_type == 'real_order') {
            $result = $logic_payment->updateRealOrder($out_trade_no, $payment_info['payment_code'], $order_list, $trade_no);
		} elseif($order_type == 'vr_order'){
		    $result = $logic_payment->updateVrOrder($out_trade_no, $payment_info['payment_code'], $order_pay_info, $trade_no);
		} elseif ($order_type == 'pd_order') {
		    $result = $logic_payment->updatePdOrder($out_trade_no,$trade_no,$payment_info,$order_pay_info);
		}

		exit($result['state'] ? $success : $fail);
	}

	/**
	 * 支付接口返回
	 *
	 */
	public function returnOp(){
		$out_trade_no = $_GET['out_trade_no'];
		$trade_no = $_GET['trade_no'];
	    	$order_type = !empty($_GET['extra_common_param'])?$_GET['extra_common_param']:'real_order';
		
		//因为虚拟了订单推送给连连支付的时候 只能以实物推送，所以返回时候需要查询
		$predeposit_info = Model('predeposit')->getPdRechargeInfo(array('pdr_sn'=>$out_trade_no));
		if(empty($predeposit_info)){
			$vr_order_info = Model('vr_order')->getOrderInfo(array('order_sn'=>$out_trade_no));
			if(!empty($vr_order_info)){
				$order_pay_info = Model('order')->getOrderPayInfo(array('pay_sn'=>$out_trade_no),true);
				if(empty($order_pay_info)){
					$order_type = 'real_order';
				}
			}else{
				$order_type = 'vr_order';
			}
		}else{
			$order_type = 'pd_order';
		}
		if ($order_type == 'real_order') {
		    $act = 'member_order';
		} elseif($order_type == 'vr_order') {
			$act = 'member_vr_order';
		} elseif($order_type == 'pd_order') {
		    $act = 'predeposit';
		} else {
		    $act = 'member_order';
		}
		
		$url = SHOP_SITE_URL.'/index.php?act='.$act;

		//对外部交易编号进行非空判断
		if(!preg_match('/^\d{18}$/',$out_trade_no)) {
		    showMessage('参数错误',$url,'','html','error');
		}

		$logic_payment = Logic('payment');
		$store_arr = array();
		if ($order_type == 'real_order') {

		    $result = $logic_payment->getRealOrderInfo($out_trade_no);
		    if(!$result['state']) {
		        showMessage($result['msg'], $url, 'html', 'error');
		    }
		    if ($result['data']['api_pay_state']) {
		        $payment_state = 'success';
		    }
		    $order_list = $result['data']['order_list'];

            //支付成功页面展示在线支付了多少金额
            $result['data']['api_pay_amount'] = 0;
            if (!empty($order_list)) {
                foreach ($order_list as $order_info) {
                	$store_arr[] = $order_info['store_id'];
                    $result['data']['api_pay_amount'] += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                }
            }

        }elseif ($order_type == 'vr_order') {

            $result = $logic_payment->getVrOrderInfo($out_trade_no);
            if(!$result['state']) {
                showMessage($result['msg'], $url, 'html', 'error');
            }

            if (!in_array($result['data']['order_state'],array(ORDER_STATE_NEW))) {
                $payment_state = 'success';
            }

            //支付成功页面展示在线支付了多少金额
            $result['data']['api_pay_amount'] = $result['data']['order_amount'] - $result['data']['pd_amount'] - $result['data']['rcb_amount'];

        } elseif ($order_type == 'pd_order') {

            $result = $logic_payment->getPdOrderInfo($out_trade_no);
            if(!$result['state']) {
                showMessage($result['msg'], $url, 'html', 'error');
            }
            if ($result['data']['pdr_payment_state'] == 1) {
                $payment_state = 'success';
            }
            $result['data']['api_pay_amount'] = $result['data']['pdr_amount'];
        }
        $order_pay_info = $result['data'];
        $api_pay_amount = $result['data']['api_pay_amount'];

		if ($payment_state != 'success') {
		    //取得支付方式
		    $result = $logic_payment->getPaymentInfo($_GET['payment_code']);
		    if (!$result['state']) {
		        showMessage($result['msg'],$url,'html','error');
		    }
		    $payment_info = $result['data'];

		    //创建支付接口对象
		    $payment_api	= new $payment_info['payment_code']($payment_info,$order_pay_info);

		    //返回参数判断
		    $verify = $payment_api->return_verify();
		    if(!$verify) {
		        showMessage('支付数据验证失败',$url,'html','error');
		    }

		    //取得支付结果
		    $pay_result	= $payment_api->getPayResult($_GET);
		    if (!$pay_result) {
		        showMessage('非常抱歉，您的订单支付没有成功，请您后尝试',$url,'html','error');
		    }
            	    //更改订单支付状态
		    if ($order_type == 'real_order') {
		        $result = $logic_payment->updateRealOrder($out_trade_no, $payment_info['payment_code'], $order_list, $trade_no);
		    } else if($order_type == 'vr_order') {
		        $result = $logic_payment->updateVrOrder($out_trade_no, $payment_info['payment_code'], $order_pay_info, $trade_no);
		    } else if ($order_type == 'pd_order') {
		        $result = $logic_payment->updatePdOrder($out_trade_no, $trade_no, $payment_info, $order_pay_info);
		    }
		    if (!$result['state']) {
		        showMessage('支付状态更新失败',$url,'html','error');
		    }else{
		    	//如果是跨境淘商品，需要将商品同步给跨境淘ERP zhangc  2016-07-25
		    	if(C("ikjtao_api_isuse")&&in_array(C("ikjtao_store_id"),$store_arr)){
		    		$ikjtao_res = $model_order->push_ikjtao_order($out_trade_no);
		    	}
		
		    }
		}

		//支付成功后跳转
		if ($order_type == 'real_order') {
		    $pay_ok_url = SHOP_SITE_URL.'/index.php?act=buy&op=pay_ok&pay_sn='.$out_trade_no.'&pay_amount='.imPriceFormat($api_pay_amount);
		} elseif ($order_type == 'vr_order') {
		    $pay_ok_url = SHOP_SITE_URL.'/index.php?act=buy_virtual&op=pay_ok&order_sn='.$out_trade_no.'&order_id='.$order_pay_info['order_id'].'&order_amount='.imPriceFormat($api_pay_amount);
		} elseif ($order_type == 'pd_order') {
		    $pay_ok_url = SHOP_SITE_URL.'/index.php?act=predeposit';
		}

        if ($payment_info['payment_code'] == 'tenpay') {
            showMessage('',$pay_ok_url,'tenpay');
        } else {
            redirect($pay_ok_url);
        }
	}
}