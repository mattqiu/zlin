<?php
/**
 * 我的订单
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

class member_orderControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 订单列表
     */
    public function order_listOp() {
        $model_order = Model('order');
        $condition = array();        
		$condition = $this->order_type_no($_POST["state_type"]);
		$condition['buyer_id'] = $this->member_info['member_id'];
        //$order_list_array = $model_order->getNormalOrderList($condition, $this->page, '*', 'order_id desc','', array('order_goods'));
		$order_list_array = $model_order->getOrderList($condition, 20, '*', 'order_id desc','', array('order_common','order_goods','store'));

        $order_group_list = $order_pay_sn_array = array();
        foreach ($order_list_array as $value) {
			
			//$value['zengpin_list'] = false;
            //显示取消订单
            $value['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$value);
            //显示收货
            $value['if_receive'] = $model_order->getOrderOperateState('receive',$value);
            //显示锁定中
            $value['if_lock'] = $model_order->getOrderOperateState('lock',$value);
            //显示物流跟踪
            $value['if_deliver'] = $model_order->getOrderOperateState('deliver',$value);
			
			$value['if_evaluation'] = false;
			$value['if_evaluation_again'] = false;
			$value['if_delete'] = false;
			$value['ownshop'] = true;
            
			$value['zengpin_list'] = false;
			if (is_array($value['extend_order_goods'])) {
                foreach ($value['extend_order_goods'] as $val) {                   
                    if ($val['goods_type'] == 5) {
                        $value['zengpin_list'][] = $val;
                    } 
                }
            }
			
			//商品图
            foreach ($value['extend_order_goods'] as $k => $goods_info) {
                
				 if ($goods_info['goods_type'] == 5) {
					unset($value['extend_order_goods'][$k]);  
				} else {
					$value['extend_order_goods'][$k] = $goods_info;
					$value['extend_order_goods'][$k]['goods_image_url'] = cthumb($goods_info['goods_image'], 240, $value['store_id']);			
				}
            }

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

        output_data(array('order_group_list' => $new_order_group_list), mobile_page($page_count));
    }
	

	private function order_type_no($stage) { 
		switch ($stage){
			case 'state_new':
				$condition['order_state'] = '10';
				break;
			case 'state_send':
				$condition['order_state'] = '30';
				break;
			case 'state_notakes':
				$condition['order_type'] = '3';
				$condition['order_state'] = '30';
				break;
			case 'state_noeval':
				$condition['order_state'] = '40';
				break;
		}
		return $condition;
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
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_type'] = 1;
        $order_info = $model_order->getOrderInfo($condition);
        $if_allow = $model_order->getOrderOperateState('buyer_cancel',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }
        if (TIMESTAMP - 86400 < $order_info['api_pay_time']) {
            $_hour = ceil(($order_info['api_pay_time']+86400-TIMESTAMP)/3600);
            output_error('该订单曾尝试使用第三方支付平台支付，须在'.$_hour.'小时以后才可取消');
        }
        $result = $logic_order->changeOrderStateCancel($order_info,'buyer', $this->member_info['member_name'], '其它原因');
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data('1');
        }
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
		$order_id = intval($_GET['order_id']);
        if ($order_id <= 0) {
			output_error('订单不存在');
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_goods','order_common','store'));

        if (empty($order_info) || $order_info['delete_state'] == ORDER_DEL_STATE_DROP) {
            output_error('订单不存在');
        }

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
		$order_info['real_pay_amount'] = $order_info['order_amount']+$order_info['shipping_fee'];
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


        //卖家发货信息
        if (!empty($order_info['extend_order_common']['daddress_id'])) {
            $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
            Tpl::output('daddress_info',$daddress_info);
        }
		
		
		
		$data = '{"code":200,"datas":{"order_info":{"order_id":"202","order_sn":"7000000000018101","store_id":"6","store_name":"\u7231\u5bb6\u4e50\u751f\u6d3b\u5bb6\u5c45\u4e13\u8425\u5e97","add_time":"2015-10-29 06:41:30","payment_time":"","shipping_time":"","finnshed_time":"","order_amount":"74.00","shipping_fee":"13.00","real_pay_amount":"74.00","state_desc":"\u5f85\u4ed8\u6b3e","payment_name":"\u5728\u7ebf\u4ed8\u6b3e","order_message":"","reciver_phone":"15950031003","reciver_name":"\u8def\u4eba\u4e01","reciver_addr":"\u5c71\u897f \u9633\u6cc9\u5e02 \u76c2\u53bf \u4eba\u6c11\u8def","store_member_id":"7","store_phone":null,"order_tips":"\u8bf7\u4e8e1\u5c0f\u65f6\u5185\u5b8c\u6210\u4ed8\u6b3e\uff0c\u903e\u671f\u672a\u4ed8\u8ba2\u5355\u81ea\u52a8\u5173\u95ed","promotion":[],"invoice":"","if_deliver":false,"if_buyer_cancel":true,"if_refund_cancel":false,"if_receive":false,"if_evaluation":false,"if_lock":false,"goods_list":[],"zengpin_list":[],"ownshop":false}}}';
		//exit($data);
		$order_id   = intval($_GET['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在100');
        }

        $model_order    = Model('order');
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_goods'),'order_id','order_sn','store_id','store_name','add_time','payment_time','shipping_time','finnshed_time','order_amount','shipping_fee','real_pay_amount','state_desc','payment_name','order_message','reciver_phone','reciver_name','reciver_addr','store_member_id','store_phone','order_tips');
        
		$order_info['promotion']=array();
		$order_info['if_deliver']=	false;
		$order_info['if_buyer_cancel']=	false;
		$order_info['if_refund_cancel']=	false;
		$order_info['if_receive']=	false;
		$order_info['if_evaluation']=	false;
		$order_info['if_lock']=	false;

		$order_info['goods_list']=array();
		$order_info['zengpin_list']=array();
		$order_info['ownshop']=	false;
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

        $order_list_array = $model_order->getNormalOrderList($condition, $page_nums, '*', 'order_id desc','', array('order_goods','order_common','store'));

        $order_group_list = array();
        $order_pay_sn_array = array();
        foreach ($order_list_array as $value) {
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
		
        output_data($array_data, mobile_page($page_count));
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
    	$ordersInfo['member_available_points'] = $this->member_info['member_points'];//会员可用积分数
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
    	$usable_points = ceil($usable_points/C("points_trade")); //需要抵扣的云币数
    	$ordersInfo['usable_points'] = $usable_points;
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
    	$ordersInfo['usable_points_total'] = $usable_points;
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