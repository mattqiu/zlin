<?php
/**
 * 支付回调
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class paymentControl extends wxappHomeControl{

    private $payment_code;

    public function __construct() {
        parent::__construct();

        $this->payment_code = $_REQUEST['payment_code'];
		if($this->payment_code == ''){
			$this->payment_code = "wxpay";
		}
	}
	
	public function returnopenidOp(){		
        $payment_api = $this->_get_payment_api();
        
        $payment_api->getopenid();
	if($this->payment_code != 'wxpay'){
            output_error('支付参数异常');
            die;
        }
    }	
	
    /**
     * 支付回调
     */
    public function returnOp() {
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);		

        $payment_api = $this->_get_payment_api();
        $payment_config = $this->_get_payment_config();
        $callback_info = $payment_api->getReturnInfo($payment_config);

        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
            	
                Tpl::output('result', 'success');
                Tpl::output('message', '支付成功');
            } else {
                Tpl::output('result', 'fail');
                Tpl::output('message', '支付失败');
            }
        } else {
            //验证失败
            Tpl::output('result', 'fail');
            Tpl::output('message', '支付失败');
        }

        Tpl::showpage('payment_message');
    }

    /**
     * 支付提醒
     */
    public function notifyOp() {
        // wxpay_jsapi
        if ($this->payment_code == 'wxpay_jsapi') {
            $api = $this->_get_payment_api();
            $params = $this->_get_payment_config();
            $api->setConfigs($params);

            list($result, $output) = $api->notify();

            if ($result) {
                $internalSn = $result['out_trade_no'] . '_' . $result['attach'];
                $externalSn = $result['transaction_id'];
                $updateSuccess = $this->_update_order($internalSn, $externalSn);

                if (!$updateSuccess) {
                    // @todo
                    // 直接退出 等待下次通知
                    exit;
                }
            }

            echo $output;
            exit;
        }

        // 恢复框架编码的post值
        $_POST['notify_data'] = html_entity_decode($_POST['notify_data']);

        $payment_api = $this->_get_payment_api();
        $payment_config = $this->_get_payment_config();
        $callback_info = $payment_api->getNotifyInfo($payment_config);
        file_put_contents('pay.log',"获取z支付解果：".json_encode($callback_info).PHP_EOL,FILE_APPEND);
        if($callback_info) {
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
				if ($this->payment_code == 'llpay'){
					die("{'ret_code':'0000','ret_msg':'交易成功'}");
				}else{
                    echo 'success';
				    die;
				}
            }
        }
        //验证失败
		if ($this->payment_code == 'llpay'){
			die("{'ret_code':'9999','ret_msg':'验签失败'}");
		}else{
            echo "fail";
		    die;
		}        
    }
	
    /**
     * 获取支付接口实例
     */
    private function _get_payment_api() {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }else{
        	file_put_contents('pay.log',"文件不存在：".$inc_file.PHP_EOL,FILE_APPEND);
        	
        }

        $payment_api = new $this->payment_code();

        return $payment_api;
    }

    /**
     * 获取支付接口信息
     */
    private function _get_payment_config() {
        $model_mb_payment = Model('mb_payment');

        //读取接口配置信息
        $condition = array();
        if($this->payment_code == 'wxpay3') {
            $condition['payment_code'] = 'wxpay';
        } else {
            $condition['payment_code'] = $this->payment_code;
        }
        $payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition); //支付方式_待修改

        return $payment_info['payment_config'];
    }

    /**
     * 更新订单状态
     */
    private function _update_order($out_trade_no, $trade_no) {
        $model_order = Model('order');
        $logic_payment = Logic('payment');

        $tmp = explode('|', $out_trade_no);
        $out_trade_no = $tmp[0];
        if (!empty($tmp[1])) {
            $order_type = $tmp[1];
        } else {
            $order_pay_info = Model('order')->getOrderPayInfo(array('pay_sn'=> $out_trade_no));
            if(empty($order_pay_info)){
            	$vrorder_info = Model('vr_order')->getOrderInfo(array('order_sn'=> $out_trade_no));
            	if(empty($vrorder_info)){
            		$order_type = 'p';
            	}else{
	                $order_type = 'v';
            	}
            } else {
                $order_type = 'r';
            }
        }

        // wxpay_jsapi
        $paymentCode = $this->payment_code;
        if ($paymentCode == 'wxpay_jsapi') {
            $paymentCode = 'wx_jsapi';
        } elseif ($paymentCode == 'wxpay3') {
            $paymentCode = 'wxpay';
            $paymentName = "微信支付";
        }
        
        $store_arr = array();
        if ($order_type == 'r') {
            $result = $logic_payment->getRealOrderInfo($out_trade_no);
            if (intval($result['data']['api_pay_state'])) {
                return array('state'=>true);
            }
            $order_list = $result['data']['order_list'];
            $result = $logic_payment->updateRealOrder($out_trade_no, $paymentCode, $order_list, $trade_no);

            $api_pay_amount = 0;
            if (!empty($order_list)) {
                foreach ($order_list as $order_info) {
                	if(C("ikjtao_store_id")==$order_info['store_id']){
                		$store_arr[] = $order_info['store_id'];
                	}elseif(!empty($order_info['up_id'])&&C("ikjtao_store_id")==$order_info['up_id']){//可能是分销了跨境淘的商品
                		$store_arr[] = $order_info['up_id'];
                	}
                	
                    $api_pay_amount += $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'];
                }
            }
            $log_buyer_id = $order_list[0]['buyer_id'];
            $log_buyer_name = $order_list[0]['buyer_name'];
            $log_desc = '实物订单使用'.orderPaymentName($paymentCode).'成功支付，支付单号：'.$out_trade_no;

        } elseif ($order_type == 'v') {
            $result = $logic_payment->getVrOrderInfo($out_trade_no);
            $order_info = $result['data'];
            if (!in_array($result['data']['order_state'],array(ORDER_STATE_NEW,ORDER_STATE_CANCEL))) {
                return array('state'=>true);
            }
            $result = $logic_payment->updateVrOrder($out_trade_no, $paymentCode, $result['data'], $trade_no);

            $api_pay_amount = $order_info['order_amount'] - $order_info['pd_amount'] - $order_info['rcb_amount'] - $order_info['points_amount'];
            $log_buyer_id = $order_info['buyer_id'];
            $log_buyer_name = $order_info['buyer_name'];
            $log_desc = '虚拟订单使用'.orderPaymentName($paymentCode).'成功支付，支付单号：'.$out_trade_no;
        }else{
        	$result = $logic_payment->getPdOrderInfo($out_trade_no);
        	$order_info = $result['data'];
        	if ($result['data']['pdr_payment_state'] == 1 ) {
        		return array('state'=>true);
        	}
        	$payment_info['payment_code'] = $paymentCode;
        	$paymentName = orderPaymentName($paymentCode);
        	$payment_info['payment_name'] = $paymentName;
        	$result = $logic_payment->updatePdOrder($out_trade_no, $trade_no, $payment_info, $result['data']);
        	
        	$api_pay_amount = $order_info['order_amount'];
        	$log_buyer_id = $order_info['buyer_id'];
        	$log_buyer_name = $order_info['buyer_name'];
        	$log_desc = '会员充值订单使用'.orderPaymentName($paymentCode).'成功支付，支付单号：'.$out_trade_no;
        }
        if ($result['state']) {
        	
        	//如果是跨境淘商品，需要将商品同步给跨境淘ERP zhangc  2016-07-25
        	if(C("ikjtao_api_isuse")&&in_array(C("ikjtao_store_id"),$store_arr)){
        		if($paymentCode=='wxpay'||$paymentCode == 'wx_jsapi'){
        			
        		}
        		$ikjtao_res = $model_order->push_ikjtao_order($out_trade_no);
        	}
            //记录消费日志
            QueueClient::push('addConsume', array('member_id'=>$log_buyer_id,'member_name'=>$log_buyer_name,
            'consume_amount'=>imPriceFormat($api_pay_amount),'consume_time'=>TIMESTAMP,'consume_remark'=>$log_desc));
        }

        return $result;
    }
}