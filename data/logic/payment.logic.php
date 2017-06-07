<?php
/**
 * 支付行为
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class paymentLogic {

    /**
     * 取得实物订单所需支付金额等信息
     * @param int $pay_sn
     * @param int $member_id
     * @return array
     */
    public function getRealOrderInfo($pay_sn, $member_id = null) {
    
        //验证订单信息
        $model_order = Model('order');
        $condition = array();
        $condition['pay_sn'] = $pay_sn;
        if (!empty($member_id)) {
            $condition['buyer_id'] = $member_id;
        }
        $order_pay_info = $model_order->getOrderPayInfo($condition,true);
        if(empty($order_pay_info)){
            return callback(false,'该支付单不存在');
        }

        $order_pay_info['subject'] = '实物订单_'.$order_pay_info['pay_sn'];
        $order_pay_info['order_type'] = 'real_order';

        $condition = array();
        $condition['pay_sn'] = $pay_sn;
		
        //同步异步通知时,预定支付尾款时需要用到已经支付状态
        $condition['order_state'] = array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY));
        $order_list = $model_order->getOrderList($condition,'','*','','',array(),true);
		
		//取订单其它扩展信息
        $result = $this->getOrderExtendList($order_list);
        if (!$result['state']) {
            return $result;
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
    
        return callback(true,'',$order_pay_info);
    }
	
	/**
     * 取得订单其它扩展信息
     * @param unknown $order_list
     * @param string $role 操作角色 目前只用admin时需要传入
     */
    public function getOrderExtendList(& $order_list,$role = '') {

        //预定订单
        if ($order_list[0]['order_type'] == 2) {
            $order_info = $order_list[0];
            $result = Logic('order_book')->getOrderBookInfo($order_info);
            if (!$result['data']['if_buyer_pay'] && $role != 'admin') {
                return callback(false,'未找到需要支付的订单');
            }
            $order_list[0] = $result['data'];
            $order_list[0]['order_amount'] = $order_list[0]['pay_amount'];
            
            //如果是支付尾款，则把订单状态更改为未支付状态，方便执行统一支付程序
            if ($result['data']['if_buyer_repay']) {
                $order_list[0]['order_state'] = ORDER_STATE_NEW;
            }

            //当以下情况时不需要清除数据pd_amount,rcb_amount：
            //如果第2次支付尾款，并且已经锁定了站内款
            //当以下情形时清除站内余额数据pd_amount,rcb_amount：
            //如果第1次支付，两个均为空，如果第1.5次支付，不会POST扣款标识不会重复扣站内款，不需要该值，所以可以清空
            //如果第2次支付尾款，如果第一次选择站内支付，也需要清空原来的支付定金的金额
            if (!$order_list[0]['if_buyer_pay_lock']) {
                $order_list[0]['pd_amount'] = $order_list[0]['rcb_amount'] = 0;
            }
        }
        return callback(true);
    }

    /**
     * 取得虚拟订单所需支付金额等信息
     * @param int $order_sn
     * @param int $member_id
     * @return array
     */
    public function getVrOrderInfo($order_sn, $member_id = null) {
    
        //验证订单信息
        $model_order = Model('vr_order');
        $condition = array();
        $condition['order_sn'] = $order_sn;
        if (!empty($member_id)) {
            $condition['buyer_id'] = $member_id;
        }
        $order_info = $model_order->getOrderInfo($condition);
        if(empty($order_info)){
            return callback(false,'该订单不存在');
        }

        $order_info['subject'] = '虚拟订单_'.$order_sn;
        $order_info['order_type'] = 'vr_order';
        $order_info['pay_sn'] = $order_sn;

        //计算本次需要在线支付的订单总金额
        $pay_amount = imPriceFormat(floatval($order_info['order_amount']) - floatval($order_info['pd_amount']) - floatval($order_info['rcb_amount']) - floatval($order_info['points_amount']));

        $order_info['api_pay_amount'] = $pay_amount;
    
        return callback(true,'',$order_info);
    }

    /**
     * 取得充值单所需支付金额等信息
     * @param int $pdr_sn
     * @param int $member_id
     * @return array
     */
    public function getPdOrderInfo($pdr_sn, $member_id = null) {

        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_sn'] = $pdr_sn;
        if (!empty($member_id)) {
            $condition['pdr_member_id'] = $member_id;
        }
		
        $order_info = $model_pd->getPdRechargeInfo($condition);
        if(empty($order_info)){
            return callback(false,'该订单不存在');
        }
        //增加字段
        $order_info['subject'] = '会员充值订单_'.$pdr_sn;
        $order_info['order_type'] = 'pd_order';
        $order_info['pay_sn'] = $pdr_sn;
        $order_info['order_sn'] = $pdr_sn;
    	$order_info['buyer_id'] = $order_info['pdr_member_id'];
    	$order_info['buyer_name'] = $order_info['pdr_member_name'];
    	$order_info['api_pay_amount'] = $order_info['pdr_amount'];
    	$order_info['order_amount'] = $order_info['pdr_amount'];
	
        return callback(true,'',$order_info);
    }

    /**
     * 取得所使用支付方式信息
     * @param unknown $payment_code
     */
    public function getPaymentInfo($payment_code,$store_id=0,$token='root') {
        if (in_array($payment_code,array('offline','predeposit')) || empty($payment_code)) {
            return callback(false,'系统不支持选定的支付方式');
        }
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $payment_code;
        $payment_info = $model_payment->getPaymentOpenInfo($condition,$store_id,$token);//支付方式_待修改
        if(empty($payment_info)) {
            return callback(false,'系统不支持选定的支付方式');
        }

        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_info['payment_code'].DS.$payment_info['payment_code'].'.php';
        if(!file_exists($inc_file)){
            return callback(false,'系统不支持选定的支付方式');
        }
        require_once($inc_file);
        $payment_info['payment_config']	= unserialize($payment_info['payment_config']);

        return callback(true,'',$payment_info);
    }	

    /**
     * 支付成功后修改实物订单状态
     */
    public function updateRealOrder($out_trade_no, $payment_code, $order_list, $trade_no) {
        $post['payment_code'] = $payment_code;
        $post['trade_no'] = $trade_no;
        return Logic('order')->changeOrderReceivePay($order_list, 'system', '系统', $post);
    }

    /**
     * 支付成功后修改虚拟订单状态
     */
    public function updateVrOrder($out_trade_no, $payment_code, $order_info, $trade_no) {
        $post['payment_code'] = $payment_code;
        $post['trade_no'] = $trade_no;
        return Logic('vr_order')->changeOrderStatePay($order_info, 'system', $post);
    }

    /**
     * 支付成功后修改充值订单状态
     * @param unknown $out_trade_no
     * @param unknown $trade_no
     * @param unknown $payment_info
     * @throws Exception
     * @return multitype:unknown
     */
    public function updatePdOrder($out_trade_no,$trade_no,$payment_info,$recharge_info) {

        $condition = array();
        $condition['pdr_sn'] = $recharge_info['pdr_sn'];
        $condition['pdr_payment_state'] = 0;
        $update = array();
        $update['pdr_payment_state'] = 1;
        $update['pdr_payment_time'] = TIMESTAMP;
        $update['pdr_payment_code'] = $payment_info['payment_code'];
        $update['pdr_payment_name'] = $payment_info['payment_name'];
        $update['pdr_trade_sn'] = $trade_no;
	
        $model_pd = Model('predeposit');
        
	$pdr_where = array();
		        $pdr_where['pdr_sn'] = $out_trade_no;
		        $pdr_where['pdr_payment_state'] = 0;
		        $pdr_info = $model_pd->getPdRechargeInfo($pdr_where);
			if(empty($pdr_info)){
			    throw new Exception('充值已经成功!');
			}
		$member_id = $pdr_info['pdr_member_id'];
        	$member_name = $pdr_info['pdr_member_name'];
	
            //若是 会员充值 ，将此字段存了充值卡的ID
            $rechargecard_id = $recharge_info['pdr_admin'];
            $rechargecardInfo = array();
            $model_rechargecard = Model('rechargecard');
            //ID 存在用户购买的是充值卡类型 需要给会员的对应账号里充值卡的金额增加
            if(!empty($rechargecard_id) && $rechargecard_id >0){
            	//获取充值卡的额度
            	$rechargecardInfo = $model_rechargecard->getRechargeCardByID($rechargecard_id);
            	//此时的充值卡
            	$pdr_amount = $rechargecardInfo['pd_amount']; //充值送积分
            	$card_points = $rechargecardInfo['card_points']; //充值送云币
            	$give_exppoints = $rechargecardInfo['give_exppoints']; //赠送的经验值
	            $commis_amount = $rechargecardInfo['commis_amount']; //充值返佣
	            $commis_points = $rechargecardInfo['commis_points']; //返给上级的云币
            	$batchflag = $rechargecardInfo['batchflag']; //批次标识
            	
		        
		if($pdr_amount == 0 || empty($pdr_amount)){//充值卡
            		//充值卡的额度为面额
            		if($rechargecardInfo['card_grant'] == 1){//换购模式
            			$rcb_amount = $rechargecardInfo['rcb_amount']; //充值卡
            		}else{
            			$rcb_amount = $rechargecardInfo['denomination']; //充值卡
            		}
            		$pdr_amount = $rcb_amount; //充值卡
            		$data = array();
            		$data['member_id'] = $pdr_info['pdr_member_id'];
            		$data['member_name'] = $pdr_info['pdr_member_name'];
            		$data['sn'] = $rechargecardInfo['sn']; //充值卡号
            		$data['amount'] = $rcb_amount;
			
			if(!empty($rcb_amount)&&$rcb_amount>0.01){
			  $rcb_res = $model_pd->changeRcb('recharge',$data);	
			}
			
            	}else{
            		//变更会员积分
            		$data = array();
            		$data['member_id'] = $recharge_info['pdr_member_id'];
            		$data['member_name'] = $recharge_info['pdr_member_name'];
            		$data['amount'] = $recharge_info['pdr_amount'];
            		$data['pdr_sn'] = $recharge_info['pdr_sn'];
            		$pd_res = $model_pd->changePd('recharge',$data);
            	}
//更改充值状态
        $state = $model_pd->editPdRecharge($update,$condition);
		if (!$state) {
			throw new Exception('更新充值状态失败');
        }

            	//备注是否为数字，如果是则可能是赠送指定的商品
            	if(is_numeric($batchflag)&&!empty($batchflag)){
            		$mdl_goods = Model('goods');
            		$gcomnum = $mdl_goods->getGoodsCount(array('goods_id'=>$batchflag));
            		if($gcomnum>0){//商品存在，需要生成虚拟订单，以及税换码118022
            			$member_id = $pdr_info['pdr_member_id'];
            			$goodsInfo = $mdl_goods->getGoodsInfoByID($batchflag,'goods_name,store_id');
            			$member_info = Model('member')->getMemberInfoByID($member_id,'member_id,member_mobile,available_rc_balance');
            			$input['goods_id'] = $batchflag;
            			$input['quantity'] = 1;
            			$input['order_from'] = 2;
            			$input['buyer_msg'] = "VIP充值送".$goodsInfo['goods_name'];
            			$input['payment_code'] = !empty($payment_info['payment_code'])?$payment_info['payment_code']:'predeposit';
            			$input['buyer_phone'] = $member_info['member_mobile'];
            			if(empty($input['buyer_phone'])){
            				$add_info = Model('address')->getAddressInfo(array('member_id'=>$member_id));
            				$input['buyer_phone'] = $add_info['mob_phone'];
            				Model('member')->editMember(array('member_id'=>$member_id),array('member_mobile'=>$add_info['mob_phone']));
            			}
            			//生成订单
            			$vr_res = Logic("buy_virtual")->buyStep3($input,$member_id);
            			$vr_order_id = $vr_res['data']['order_id'];

            			$order_info['order_amount'] = 0;
            			$order_info['order_id'] = $vr_order_id;
            			// 订单状态 置为已支付
            			$data_order = array();
            			$order_info['order_state'] = $data_order['order_state'] = ORDER_STATE_PAY;
            			$data_order['payment_time'] = TIMESTAMP;
            			$data_order['payment_code'] = 'predeposit';
            			$data_order['rcb_amount'] = $data_order['order_amount'] = $order_info['order_amount'];
            			$data_order['trade_no'] = $_POST['trade_no'];
            			$model_vr_order = Model('vr_order');
            			$result = $model_vr_order->editOrder($data_order,array('order_id'=>$order_info['order_id']));

            			$order_info['order_amount'] = 0;
            			$order_info['goods_num'] = 1;
            			$order_info['store_id'] = $goodsInfo['store_id'];
            			$order_info['buyer_id'] = $member_id;
            			$order_info['vr_indate'] = TIMESTAMP + 30*24*3600;
            			$order_info['vr_invalid_refund'] = 0;
            			//发放兑换码
            			$insert = $model_vr_order->addOrderCode($order_info);
            			if($insert){
            				//发送兑换码到手机
            				$param = array('order_id'=>$order_info['order_id'],'buyer_id'=>$member_id,'buyer_phone'=>$input['buyer_phone']);
            				QueueClient::push('sendVrCode', $param);
            			}
            		}
            	}
            	
            	//赠送云币
            	if(!empty($card_points)&&$card_points>0){
            		$insertarr = array(
            				'pl_memberid'=>$recharge_info['pdr_member_id'], //会员编号
            				'pl_membername'=>$recharge_info['pdr_member_name'],//会员名称
            				'pl_points'=>$card_points,//云币抵扣的金额
            				'pl_addtime'=>TIMESTAMP,//订单编号
            				'pl_desc'=>"会员充值，订单".$recharge_info['pdr_sn'],//订单序号
            				'pl_stage'=>'other'//订单序号
            		);
			
            		$res_addplog = Model('points')->addPointsLog($insertarr);
			
            		if ($res_addplog){
            			//更新member内容
            			$obj_member = Model('member');
            			$upmember_array = array();
            			$upmember_array['member_points'] = array('exp','member_points+'.$insertarr['pl_points']);
            			//经验值升级VIP会员
            			$exppoints_rule = C("exppoints_rule")?unserialize(C("exppoints_rule")):array();
            			$exp_orderrate = floatval($exppoints_rule['exp_orderrate']);
	            		if(!empty($give_exppoints)){
	            			$member_exppoints = $give_exppoints;
	            		}elseif(!empty($pdr_amount) && $pdr_amount>0){
            				$member_exppoints = intval($pdr_amount/$exp_orderrate);
            			}else{
            				$member_exppoints = 0;
            			}
	            		if(!empty($member_exppoints)){
	            			$insertearr = array(
	            					'exp_memberid'=>$member_id,
	            					'exp_membername'=>$member_name,
	            					'exp_points'=>$member_exppoints,
	            					'exp_addtime'=>TIMESTAMP,
	            					'exp_stage'=>'order',
	            					'exp_desc'=>'会员卡充值送经验值，订单'.$pdr_info['pdr_sn'].''
	            			);
	            			Model('exppoints')->addExppointsLog($insertearr);
	            		}
				
            			$upmember_array['member_exppoints'] = array('exp','member_exppoints+'.$member_exppoints);
            
            			if ($insertarr['pl_signnum']>0){
            				$upmember_array['member_sign_num'] = $insertarr['pl_signnum'];
            			}
            			$obj_member->editMember(array('member_id'=>$insertarr['pl_memberid']),$upmember_array);
            
	            		if($commis_amount > 0 || $commis_points > 0){
            				//给上级返佣
            				$parentInfo = $obj_member->getMemberInfoByID($insertarr['pl_memberid'],'parent_id');
            				$parent_id = $parentInfo['parent_id'];
		            		Model('extension')->parentDistributeRebate($insertarr['pl_memberid'],$parent_id,$commis_amount,$pdr_info['pdr_id'],$pdr_info['pdr_sn'],$pdr_info['pdr_amount']);
		            		$pInfo = $obj_member->getMemberInfoByID($parent_id,'member_name');
		            		$insertarr = array(
		            				'pl_memberid'=>$parent_id, //会员编号
		            				'pl_membername'=>$pInfo['member_name'],//会员名称
		            				'pl_points'=>$commis_points,//云币抵扣的金额
		            				'pl_addtime'=>TIMESTAMP,//订单编号
		            				'pl_desc'=>"成功邀请会员".$member_name."加入团队,返".$commis_points."云币",//订单序号
		            				'pl_stage'=>'other'//订单序号
		            		);
		            		$insert = Model('points')->addPointsLog($insertarr);
		            		$upmber_arr['member_points'] = array('exp','member_points+'.$commis_points);
		            		$obj_member->editMember(array('member_id'=>$parent_id),$upmber_arr);
		            		if($insert){
		            			//发送兑换码到手机
		            			$param = array('member_id'=>$parent_id,'member_name'=>$pInfo['member_name']);
		            			QueueClient::push('addPoint',$param);
		            		}
            			}
            		}
            	}
            }else{
	            //变更会员积分
	            $data = array();
	            $data['member_id'] = $recharge_info['pdr_member_id'];
	            $data['member_name'] = $recharge_info['pdr_member_name'];
	            $data['amount'] = $recharge_info['pdr_amount'];
	            $data['pdr_sn'] = $recharge_info['pdr_sn'];
	            $model_pd->changePd('recharge',$data);
            }
            return callback(true,'充值成功');	
    }
}