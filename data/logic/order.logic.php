<?php
/**
 * 实物订单行为
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class orderLogic {

    /**
     * 取消订单
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param string $msg 操作备注
     * @param boolean $if_update_account 是否变更账户金额
     * @param boolean $if_queue 是否使用队列
     * @return array
     */
    public function changeOrderStateCancel($order_info, $role, $user = '', $msg = '', $if_update_account = true, $if_quque = true) {
        try {
            $model_order = Model('order');
            $model_order->beginTransaction();
            $order_id = $order_info['order_id'];

            //库存销量变更
            $goods_list = $model_order->getOrderGoodsList(array('order_id'=>$order_id));
            $data = array();
            foreach ($goods_list as $goods) {
                $data[$goods['goods_id']] = $goods['goods_num'];
            }
            if ($if_quque) {
                QueueClient::push('cancelOrderUpdateStorage', $data);
            } else {
                Logic('queue')->cancelOrderUpdateStorage($data);
            }

            if ($if_update_account) {
                $model_pd = Model('predeposit');			
                //解冻充值卡
                $rcb_amount = floatval($order_info['rcb_amount']);
                if ($rcb_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_info['buyer_id'];
                    $data_pd['member_name'] = $order_info['buyer_name'];
                    $data_pd['amount'] = $rcb_amount;
                    $data_pd['order_sn'] = $order_info['order_sn'];
                    $model_pd->changeRcb('order_cancel',$data_pd);
                }
                
                //解冻积分
                $pd_amount = floatval($order_info['pd_amount']);
                if ($pd_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_info['buyer_id'];
                    $data_pd['member_name'] = $order_info['buyer_name'];
                    $data_pd['amount'] = $pd_amount;
                    $data_pd['order_sn'] = $order_info['order_sn'];
                    $model_pd->changePd('order_cancel',$data_pd);
                }                
            }

            //更新订单信息
            $update_order = array('order_state' => ORDER_STATE_CANCEL, 'pd_amount' => 0);
            $update = $model_order->editOrder($update_order,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception('保存失败');
            }
	    	//抵扣的云币 给赠送
            if(!empty($order_info['points_amount'])&&$order_info['points_amount']>0){
            	$model_points = Model('points');
            	$insertarr = array(
            			'pl_memberid'=>$order_info['buyer_id'],
            			'pl_membername'=>$order_info['buyer_name'],
            			'pl_points'=>$order_info['points_amount'],
            			'orderprice'=>$order_info['order_amount'],
            			'order_sn'=>$order_info['order_sn'],
            			'order_id'=>$order_id,
            			'pl_desc'=>'订单'.$order_info['order_sn'].'已被取消-云币赠送'
            	);
            	$model_points->savePointsLog('order',$insertarr,true);
            	
            	$insertearr = array(
            			'exp_memberid'=>$order_info['buyer_id'],
            			'exp_membername'=>$order_info['buyer_name'],
            			'exp_points'=>$order_info['points_amount'],
            			'orderprice'=>$order_info['order_amount'],
            			'order_sn'=>$order_info['order_sn'],
            			'order_id'=>$order_id,
            			'exp_desc'=>'订单'.$order_info['order_sn'].'已被取消-经验扣回'
            	);
            	Model('exppoints')->saveExppointsLog('order',$insertearr,true);
            }
            
            //添加订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_msg'] = '取消了订单';
            $data['log_user'] = $user;
            if ($msg) {
                $data['log_msg'] .= ' ( '.$msg.' )';
            }
            $data['log_orderstate'] = ORDER_STATE_CANCEL;
            $model_order->addOrderLog($data);
            $model_order->commit();
			
            return callback(true,'操作成功');
        } catch (Exception $e) {
            $this->rollback();
            return callback(false,'操作失败');
        }
    }

    /**
     * 收货
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param string $msg 操作备注
     * @return array
     */
    public function changeOrderStateReceive($order_info, $role, $user = '', $msg = '') {
        try {

            $order_id = $order_info['order_id'];
            $commis_points = $order_info['mb_commis_points']; //返佣云币
            $commis_totals = $order_info['mb_commis_totals']; //返佣积分
            $rebate_amount = $order_info['rebate_amount']; //返利积分
            $rebate_points = $order_info['extend_order_common']['rebate_points']; //返利云币
            $ex_extension_id = $order_info['saleman_id'];
            $ex_order_sn = $order_info['order_sn'];
            $ex_order_amount = $order_info['order_amount'];
            $model_order = Model('order');

            //更新订单状态
            $update_order = array();
            $update_order['finnshed_time'] = TIMESTAMP;
            $update_order['order_state'] = ORDER_STATE_SUCCESS;
            $update = $model_order->editOrder($update_order,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception('保存失败');
            }
			//更新推广员、导购员提成 推广处理
		    if (OPEN_STORE_EXTENSION_STATE > 0 && ($commis_totals>0||$commis_points>0)){
								
				$ex_order_id = $order_info['order_id']; 
				$ex_store_id = $order_info['store_id']; 
				$ex_store_name = $order_info['store_name'];
				$model_extension = Model('extension');
				$model_extension_commis_detail = Model('extension_commis_detail');
				//返拥云币
				
				//如果购买者 不是推广员则 推送推广员上去
				//$typeByID = Model('member')->getMemberTypeByID($order_info['buyer_id']);
				//$extensionCount = $model_extension_commis_detail->getOrdersCount(array('extension_id'=>$order_info['buyer_id']));
				if($order_info['buyer_id']!=$ex_extension_id){
					$extensionCount = $model_extension_commis_detail->getOrdersCount(array('extension_id'=>$order_info['buyer_id'],'saleman_id'=>$ex_extension_id));
				}else{
					$extensionCount =1;
				}
				if($extensionCount==0){
					$model_extension->firstDistributeRebate($order_info['buyer_id'],$commis_totals,$ex_order_id,$ex_order_sn,$ex_order_amount,$ex_store_id,$ex_store_name,$commis_points);
				}else{
					$model_extension->DistributeRebate($ex_extension_id,$commis_totals,$ex_order_id,$ex_order_sn,$ex_order_amount,$ex_store_id,$ex_store_name,$commis_points);
				}
				
		    }
			
            //添加订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = 'buyer';
            $data['log_msg'] = '签收了货物';
            $data['log_user'] = $user;
            if ($msg) {
                $data['log_msg'] .= ' ( '.$msg.' )';
            }
            $data['log_orderstate'] = ORDER_STATE_SUCCESS;
            $model_order->addOrderLog($data);
			//返利积分
			if($rebate_amount>0){
				$pddata = array(
					'pde_sn' => $order_info['order_sn'],
					'amount' => $rebate_amount,
					'member_id' => $order_info['buyer_id'],
					'member_name' => $order_info['buyer_name'],
					'admin_name' => empty($order_info['saleman_name'])?'系统自动':$order_info['saleman_name'],
				);
				Model('predeposit')->changePd('rebate',$pddata);
			}
            //添加会员云币 返利云币
            if (C('points_isuse') == 1 || $rebate_points>0){
                Model('points')->savePointsLog('order',array('pl_memberid'=>$order_info['buyer_id'],'pl_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
            }
            //添加会员经验值
            Model('exppoints')->saveExppointsLog('order',array('exp_memberid'=>$order_info['buyer_id'],'exp_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
            if($order_info['order_type'] == 3){
            	//来自收银的订单，需要将系统生成的文件给删除
            	$file = BASE_UPLOAD_PATH.'/mobile/cashier/'.date('Y-m-d').'_'.$order_info['buyer_id'].'.log';
            	$un = unlink($file);
            }
            return callback(true,'操作成功');
        } catch (Exception $e) {
            return callback(false,'操作失败');
        }
    }

    /**
     * 更改运费
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param float $price 运费
     * @return array
     */
    public function changeOrderShipPrice($order_info, $role, $user = '', $price) {
        try {

            $order_id = $order_info['order_id'];
            $model_order = Model('order');

            $data = array();
            $data['shipping_fee'] = abs(floatval($price));
            $data['order_amount'] = array('exp','goods_amount+'.$data['shipping_fee']);
            $update = $model_order->editOrder($data,array('order_id'=>$order_id));
            if (!$update) {
                throw new Exception('保存失败');
            }
            //记录订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_user'] = $user;
            $data['log_msg'] = '修改了运费'.'( '.$price.' )';;
            $data['log_orderstate'] = $order_info['payment_code'] == 'offline' ? ORDER_STATE_PAY : ORDER_STATE_NEW;
            $model_order->addOrderLog($data);
            return callback(true,'操作成功');
        } catch (Exception $e) {
            return callback(false,'操作失败');
        }
    }

    /**
     * 回收站操作（放入回收站、还原、永久删除）
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $state_type 操作类型
     * @return array
     */
    public function changeOrderStateRecycle($order_info, $role, $state_type) {
        $order_id = $order_info['order_id'];
        $model_order = Model('order');
        //更新订单删除状态
        $state = str_replace(array('delete','drop','restore'), array(ORDER_DEL_STATE_DELETE,ORDER_DEL_STATE_DROP,ORDER_DEL_STATE_DEFAULT), $state_type);
        $update = $model_order->editOrder(array('delete_state'=>$state),array('order_id'=>$order_id));
        if (!$update) {
            return callback(false,'操作失败');
        } else {
            return callback(true,'操作成功');
        }
    }

    /**
     * 发货
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @return array
     */
    public function changeOrderSend($order_info, $role, $user = '', $post = array()) {
        $order_id = $order_info['order_id'];
        $model_order = Model('order');
        try {
            $model_order->beginTransaction();
            $data = array();
            $data['reciver_name'] = $post['reciver_name'];
            $data['reciver_info'] = $post['reciver_info'];
            $data['deliver_explain'] = $post['deliver_explain'];
            $data['daddress_id'] = intval($post['daddress_id']);
            $data['shipping_express_id'] = intval($post['shipping_express_id']);
            $data['shipping_time'] = TIMESTAMP;

            $condition = array();
            $condition['order_id'] = $order_id;
            $condition['store_id'] = $_SESSION['store_id'];
            $update = $model_order->editOrderCommon($data,$condition);
            if (!$update) {
                throw new Exception('操作失败');
            }

            $data = array();
            $data['shipping_code']  = $post['shipping_code'];
            $data['order_state'] = ORDER_STATE_SEND;
            $data['delay_time'] = TIMESTAMP;
            $update = $model_order->editOrder($data,$condition);
            if (!$update) {
                throw new Exception('操作失败');
            }
            $model_order->commit();
		} catch (Exception $e) {
		    $model_order->rollback();
		    return callback(false,$e->getMessage());
		}

		//更新表发货信息
		if ($post['shipping_express_id'] && $order_info['extend_order_common']['reciver_info']['dlyp']) {
		    $data = array();
		    $data['shipping_code'] = $post['shipping_code'];
		    $data['order_sn'] = $order_info['order_sn'];
		    $express_info = Model('express')->getExpressInfo(intval($post['shipping_express_id']));
		    $data['express_code'] = $express_info['e_code'];
		    $data['express_name'] = $express_info['e_name'];
		    Model('delivery_order')->editDeliveryOrder($data,array('order_id' => $order_info['order_id']));
		}

		//添加订单日志
		$data = array();
		$data['order_id'] = intval($_GET['order_id']);
		$data['log_role'] = 'seller';
		$data['log_user'] = $_SESSION['member_name'];
		$data['log_msg'] = '发出了货物 ( 编辑了发货信息 )';
		$data['log_orderstate'] = ORDER_STATE_SEND;
		$model_order->addOrderLog($data);

		// 发送买家消息
        $param = array();
        $param['code'] = 'order_deliver_success';
        $param['member_id'] = $order_info['buyer_id'];
        $param['param'] = array(
            'order_sn' => $order_info['order_sn'],
            'order_url' => urlShop('member_order', 'show_order', array('order_id' => $order_id))
        );
        QueueClient::push('sendMemberMsg', $param);

        return callback(true,'操作成功');
    }

    /**
     * 收到货款
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @return array
     */
    public function changeOrderReceivePay($order_list, $role, $user = '', $post = array()) {
        $model_order = Model('order');

        try {
            $model_order->beginTransaction();

            $data = array();
            $data['api_pay_state'] = 1;
            $update = $model_order->editOrderPay($data,array('pay_sn'=>$order_list[0]['pay_sn']));
            if (!$update) {
                throw new Exception('更新支付单状态失败');
            }
	    $model_pd = Model('predeposit');
            foreach($order_list as $order_info) {
	    
                	
                $order_id = $order_info['order_id'];
                if ($order_info['order_state'] != ORDER_STATE_NEW) continue;
				
                //下单，支付被冻结的充值卡
                $rcb_amount = floatval($order_info['rcb_amount']);
                if ($rcb_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_info['buyer_id'];
                    $data_pd['member_name'] = $order_info['buyer_name'];
                    $data_pd['amount'] = $rcb_amount;
                    $data_pd['order_sn'] = $order_info['order_sn'];
                    $model_pd->changeRcb('order_comb_pay',$data_pd);
                }

                //下单，支付被冻结的积分
                $pd_amount = floatval($order_info['pd_amount']);
                if ($pd_amount > 0) {
                    $data_pd = array();
                    $data_pd['member_id'] = $order_info['buyer_id'];
                    $data_pd['member_name'] = $order_info['buyer_name'];
                    $data_pd['amount'] = $pd_amount;
                    $data_pd['order_sn'] = $order_info['order_sn'];
                    $model_pd->changePd('order_comb_pay',$data_pd);
                }
                //订单不是来自POS收银
                if($order_info['points_amount']>0 && $order_info['order_from'] !=3){
                	$payable_points = floor($order_info['points_amount']/C("points_trade"));//订单实际使用云币抵扣的金额转换云币              	
                	$model_member = Model('member');
                	$member_info = $model_member->getMemberInfoByID($order_info['buyer_id'],'member_points');
                	$member_points = $member_info['member_points'];
                	//计算时需要抵扣掉的云币数：不足时,不足部分的云币需要多支付现金；支付时就要扣减掉云币
                		//会员云币 不足的就为负数
                	$member_points = $member_points - $payable_points; //订单可用云币-会员云币
                	//修改会员的云币
                	$model_member->editMember(array('member_id'=>$order_info['buyer_id']),array('member_points'=>$member_points));
                	if($payable_points>0){
                		$model_points = Model('points');
                		$insertarr = array(
                				'pl_memberid'=>$order_info['buyer_id'], //会员编号
                				'pl_membername'=>$order_info['buyer_name'],//会员名称
                				'pl_points'=>0-$payable_points,//云币抵扣的金额
                				'orderprice'=>$order_info['order_amount'],//订单金额
                				'order_sn'=>$order_info['order_sn'],//订单编号
                				'order_id'=>$order_id,//订单序号
                				'pl_desc'=>'订单'.$order_info['order_sn'].'需要支付的云币'
                		);
                		$model_points->savePointsLog('order',$insertarr,true);
                	}
                }
                //购买商品赠送云币数 暂时屏蔽需要确定收货才送
                //$OrderCommonInfo = $model_order->getOrderCommonList(array('order_id'=>$order_id));
                //$order_pointscount = $OrderCommonInfo[0]['order_pointscount'];
                //Model('points')->savePointsLog('order',array('pl_memberid'=>$order_info['buyer_id'],'pl_membername'=>$order_info['buyer_name'],'pl_points'=>$order_pointscount,'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
            	
                //赠送对应的代金券
                $OrderGoodsList = $model_order->getOrderGoodsList(array('order_id'=>$order_id),'goods_id,buyer_id');
                $goodsIdArr = array();
                foreach ($OrderGoodsList as $ogoods){
                	$goodsIdArr[] = $ogoods['goods_id'];
                	$member_id = $ogoods['buyer_id'];
                }
                if(in_array('114971',$goodsIdArr)){
                	$model_voucher = Model('voucher');
                	$voucher_where['voucher_t_id'] = 15;
                	$voucher_where['voucher_store_id'] = 100;
                	$voucher_where['voucher_owner_id'] = array('lt',1);
                	$voucherCodeArr = $model_voucher->getVoucherUnusedList($voucher_where,'voucher_id');
                	$where['voucher_id'] = $voucherCodeArr[0]['voucher_id'];
		            $update_arr = array();
		            $update_arr['voucher_owner_id'] = $member_id;
		            $memberInfo = Model('member')->getMemberInfoByID($member_id,'member_name');
		            $update_arr['voucher_owner_name'] = $memberInfo['member_name'];
		            $update_arr['voucher_active_date'] = time();
		            $result = $model_voucher->editVoucher($update_arr, $where, $member_id);
                }

            //更新订单状态
            $update_order = array();
                if($order_info['order_from'] == 3){
                	$model_member = Model('member');
                	$member_info = $model_member->getMemberInfoByID($order_info['buyer_id'],'member_mobile,member_exppoints');
                	$member_mobile = $member_info['member_mobile'];
                	$member_exppoints = $member_info['member_exppoints'];
                	//周年庆活动,返还到充值卡里
                	$data_rbc = array();
                	$data_rbc['member_id'] = $order_info['buyer_id'];
                	$data_rbc['member_name'] = $order_info['buyer_name'];
                	$data_rbc['order_sn'] = $order_info['order_sn'];
                	if($order_info['order_type'] == 4){
                		$data_rbc['amount'] = $order_amount = $order_info['order_amount'];
                		$model_pd->changeRcb('order_stages_discount',$data_rbc);//分期返还
                		$whole_amount = $order_amount/12;
                		$dismsg = "分期返还 ".$order_amount."现金，每期返现￥".$whole_amount;
                	}elseif ($order_info['order_type'] == 5){
                		//
                		$goods_amount = $order_info['goods_amount'];
                		$data_rbc['amount'] = $order_amount = $order_info['order_amount'];
                		$model_pd->changeRcb('order_whole_discount',$data_rbc);//全额返还
                		$dismsg = "系统已经成功返还 ".$order_amount."现金到您的账户上，请注意查收";
                	}
                	if($order_info['order_type'] > 3){//活动
                		//发短信
                		$model_member = Model('member');
                		$member_info = $model_member->getMemberInfoByID($order_info['buyer_id'],'member_mobile');
                		$member_mobile = $member_info['member_mobile'];
                		$param = array();
                		$param['site_name'] = C('site_name');
                		$param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
                		$message    = "您成功参加了".$order_info['store_name']."活动，".$dismsg."。";
                		$sms = new Sms();
                		$result = $sms->send($member_mobile,$message);
                	}                	
            }else{
            $update_order['order_state'] = ORDER_STATE_PAY;
            }
            $update_order['payment_time'] = ($post['payment_time'] ? strtotime($post['payment_time']) : TIMESTAMP);
            $update_order['payment_code'] = $post['payment_code'];
            $update_order['trade_no'] 	  = $post['trade_no'];
            $update = $model_order->editOrder($update_order,array('pay_sn'=>$order_info['pay_sn'],'order_state'=>ORDER_STATE_NEW));
//                 if (!$update) {
//                 	throw new Exception('操作失败');
//                 }
            }
	    //来自收银的订单，状态可以直接修改为收货
	    $this->changeOrderStateReceive($order_info, $role);
            $model_order->commit();
        } catch (Exception $e) {
            $model_order->rollback();
            return callback(false,$e->getMessage());
        }
        
        foreach($order_list as $order_info) {

            $order_id = $order_info['order_id'];
            // 支付成功发送买家消息
            $param = array();
            $param['code'] = 'order_payment_success';
            $param['member_id'] = $order_info['buyer_id'];
            $param['param'] = array(
                    'order_sn' => $order_info['order_sn'],
                    'order_url' => urlShop('member_order', 'show_order', array('order_id' => $order_info['order_id']))
            );
            QueueClient::push('sendMemberMsg', $param);

            // 支付成功发送店铺消息
            $param = array();
            $param['code'] = 'new_order';
            $param['store_id'] = $order_info['store_id'];
            $param['param'] = array(
                    'order_sn' => $order_info['order_sn']
            );
            QueueClient::push('sendStoreMsg', $param);

            //添加订单日志
            $data = array();
            $data['order_id'] = $order_id;
            $data['log_role'] = $role;
            $data['log_user'] = $user;
            $data['log_msg'] = '收到了货款 ( 支付平台交易号 : '.$post['trade_no'].' )';
            $data['log_orderstate'] = ORDER_STATE_PAY;
            $model_order->addOrderLog($data);            
        }

        return callback(true,'操作成功');
    }
    
    /**
     * 商家确定收到 用户退货退款的货
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param string $msg 操作备注
     * @param boolean $if_update_account 是否变更账户金额
     * @param boolean $if_queue 是否使用队列
     * @return array
     */
    public function changeOrderApplyRefundReceive($order_id, $role, $user = 'seller', $msg = '', $if_update_account = true, $if_quque = true) {
    	try {
    		$model_order = Model('order');
    		$condition = array();
    		$condition['order_id'] = $order_id;
    		$order_info = $model_order->getOrderInfo($condition);
    		$commis_points = $order_info['mb_commis_points']; //返佣云币
    		$commis_totals = $order_info['mb_commis_totals']; //返佣积分
    		
    		$rebate_amount = $order_info['rebate_amount'];
    		//更新订单状态
    		$update_order = array();
    		$update_order['order_state'] = ORDER_STATE_REFUND;
    		$update_order['refund_state'] = 1;//部分退款
    		$update_order['refund_amount'] = $order_info['order_amount'];//退款金额
    		$update = $model_order->editOrder($update_order,array('order_id'=>$order_id));
    		if (!$update) {
    			throw new Exception('保存失败');
    		}
    		//退还返利积分
    		if($rebate_amount>0){
    			$pddata = array(
    					'order_sn' => $order_info['order_sn'],
    					'amount' => $rebate_amount,
    					'member_id' => $order_info['buyer_id'],
    					'member_name' => $order_info['buyer_name'],
    					'admin_name' => empty($order_info['saleman_name'])?'系统自动':$order_info['saleman_name'],
    			);
    			Model('predeposit')->changePd('refund_confirm',$pddata);
    		}
    		//退还 返利云币,此处无需处理，再用户申请的时候 已经扣过一次
    		//退返经验值
    		Model('exppoints')->saveExppointsLog('refund',array('exp_memberid'=>$order_info['buyer_id'],'exp_membername'=>$order_info['buyer_name'],'orderprice'=>$order_info['order_amount'],'order_sn'=>$order_info['order_sn'],'order_id'=>$order_info['order_id']),true);
    
    		/**
    		 * 找回佣金，再此处提供一下思路
    		 * 根据订单的ID，找到当时给发放的佣金的人extension_commis_detail可以查到明细
    		 * 根据明细做负数即可
    		 */
    		//是否有返佣积分或云币
    		if($commis_points>0||$commis_totals>0){
    			//
    			$model_extension_commis_detail = Model('extension_commis_detail');
    			$commis_list = $model_extension_commis_detail->getCommisdetailList(array('order_id'=>$order_id));
    			if(!empty($commis_list)){
    				foreach ($commis_list as $commis_info){
    					//返佣 积分和云币大于0，并且还未结算的情况下。如果已经结算，就要去对应人那边去扣减
    					if(($commis_info['mb_commis_totals']>0||$commis_info['mb_commis_points']>0)&&$commis_info['give_status']==1){
    						//已经结算佣金，需要赠送
    						$model_extension_commis_detail->editCommisdetail(array('give_status'=>3),array('order_id'=>$order_id));
    						$mb_commis_totals = $commis_info['mb_commis_totals'];//抽拥积分数
    						$mb_commis_points = $commis_info['mb_commis_points'];//抽拥云币数
    						if ($mb_commis_totals > 0) {
    							$data_pd = array();
    							$data_pd['member_id'] = $commis_info['saleman_id'];
    							$data_pd['member_name'] = $commis_info['saleman_name'];
    							$data_pd['amount'] = 0-$mb_commis_totals;
    							$data_pd['order_sn'] = $commis_info['order_sn'];
    							$model_pd->changePd('order_book_cancel',$data_pd);//只需要释放冻结部分的积分即可
    						}
    						if (C('points_isuse') == 1 || $mb_commis_points>0){
    							$data_points = array(
    								'pl_memberid'=>$commis_info['saleman_id'],
    								'pl_membername'=>$commis_info['saleman_name'],
    								'points'=>$mb_commis_points,
    								'order_sn'=>$commis_info['order_sn']
    							);
    							Model('points')->savePointsLog('refund_apply',$data_points,true);
    						}
    					}
    				}
    			}
    		}
    		 
    		return callback(true,'操作成功');
    	} catch (Exception $e) {
    		
    		return callback(false,'操作失败');
    	}
    }
    /**
     * 用户申请退货退款，并且订单状态是客户已经确实收货的情况下触发
     * @param array $order_info
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param string $msg 操作备注
     * @param boolean $if_update_account 是否变更账户金额 //暂时用不上默认是需要处理的
     * @param boolean $if_queue 是否使用队列
     * @return array
     */
    public function changeOrderSuccessApplyRefund($order_info, $role, $user = '', $msg = '',$if_update_account,$if_queue) {
    	try {    			
    		$order_id = $order_info['order_id'];
    		$commis_points = $order_info['mb_commis_points']; //返佣云币
    		$commis_totals = $order_info['mb_commis_totals']; //返佣积分
    		$ex_extension_id = $order_info['saleman_id'];
    		$model_order = Model('order');
    		//更新订单状态
    		$update_order = array();
    		$update_order['order_state'] = ORDER_STATE_REFUND;
    		$update = $model_order->editOrder($update_order,array('order_id'=>$order_id));
    		if (!$update) {
    			throw new Exception('保存失败');
    		}
    		$model_pd = Model('predeposit');
    		//申请退款，冻结返利的积分
    		$rebate_amount = floatval($order_info['rebate_amount']); //返利积分
    		if ($rebate_amount > 0) {
    			$data_pd = array();
    			$data_pd['member_id'] = $order_info['buyer_id'];
    			$data_pd['member_name'] = $order_info['buyer_name'];
    			$data_pd['amount'] = $rebate_amount;
    			$data_pd['order_sn'] = $order_info['order_sn'];
    			$model_pd->changePd('refund_apply',$data_pd);
    		}
    		//申请退款，扣减返利的云币
    		$rebate_points = $order_info['extend_order_common']['rebate_points']; //返利云币
    		if (C('points_isuse') == 1 || $rebate_points>0){
    			$data_points = array(
    					'pl_memberid'=>$order_info['buyer_id'],
    					'pl_membername'=>$order_info['buyer_name'],
    					'points'=>$rebate_points,
    					'order_sn'=>$order_info['order_sn']   					
    			);
    			Model('points')->savePointsLog('refund_apply',$data_points,true);
    		}
    		//是否有返佣积分或云币
    		if($commis_points>0||$commis_totals>0){
    			//
    			$model_extension_commis_detail = Model('extension_commis_detail');
    			$commis_list = $model_extension_commis_detail->getCommisdetailList(array('order_id'=>$order_id));
    			if(!empty($commis_list)){
    				$okey = 0;//防止重复去冻结表
    				foreach ($commis_list as $commis_info){
    					//返佣 积分和云币大于0，并且还未结算的情况下。如果已经结算，就要去对应人那边去扣减
    					if(($commis_info['mb_commis_totals']>0||$commis_info['mb_commis_points']>0)&&$okey==0&&$commis_info['give_status']==0){
    						//冻结结算佣金，只要存在就全部冻结
    						$model_extension_commis_detail->editCommisdetail(array('give_status'=>2),array('order_id'=>$order_id));
    						$okey++;
    					}
    				}
    			}
    		}
    		//添加订单日志
    		$data = array();
    		$data['order_id'] = $order_id;
    		$data['log_role'] = 'buyer';
    		$data['log_msg'] = '申请退货款';
    		$data['log_user'] = $user;
    		if ($msg) {
    			$data['log_msg'] .= ' ( '.$msg.' )';
    		}
    		$data['log_orderstate'] = ORDER_STATE_REFUND;
    		$model_order->addOrderLog($data);
    		return callback(true,'操作成功');
    	} catch (Exception $e) {
    		return callback(false,'操作失败');
    	}    	
    }
    /**
     * 用户申请退货退款，商家不同意申请的情况下触发。要把用户申请时锁住的状态给释放
     * @param array $order_id
     * @param string $role 操作角色 buyer、seller、admin、system 分别代表买家、商家、管理员、系统
     * @param string $user 操作人
     * @param string $msg 操作备注
     * @param boolean $if_update_account 是否变更账户金额 //暂时用不上默认是需要处理的
     * @param boolean $if_queue 是否使用队列
     * @return array
     */
    public function changeOrderCancelApplyRefund($order_id, $role, $user = '', $msg = '',$if_update_account,$if_queue) {
    	try {
    		$model_order = Model('order');
    		$condition = array();
    		$condition['order_id'] = $order_id;
    		$order_info = $model_order->getOrderInfo($condition,array('order_common'));
    		$commis_points = $order_info['mb_commis_points']; //返佣云币
    		$commis_totals = $order_info['mb_commis_totals']; //返佣积分
    		$ex_extension_id = $order_info['saleman_id'];
    		$model_pd = Model('predeposit');
    		//取消申请退款，解冻返利的积分
    		$rebate_amount = floatval($order_info['rebate_amount']); //返利积分
    		if ($rebate_amount > 0) {
    			$data_pd = array();
    			$data_pd['member_id'] = $order_info['buyer_id'];
    			$data_pd['member_name'] = $order_info['buyer_name'];
    			$data_pd['amount'] = $rebate_amount;
    			$data_pd['order_sn'] = $order_info['order_sn'];
    			$model_pd->changePd('refund_cancel',$data_pd);
    		}
    		//申请退款，扣减返利的云币
    		$rebate_points = $order_info['extend_order_common']['rebate_points']; //返利云币
    		if (C('points_isuse') == 1 || $rebate_points>0){
    			$data_points = array(
    					'pl_memberid'=>$order_info['buyer_id'],
    					'pl_membername'=>$order_info['buyer_name'],
    					'points'=>$rebate_points,
    					'order_sn'=>$order_info['order_sn']
    			);
    			Model('points')->savePointsLog('refund_cancel',$data_points,true);
    		}
    		//是否有返佣积分或云币
    		if($commis_points>0||$commis_totals>0){
    			//
    			$model_extension_commis_detail = Model('extension_commis_detail');
    			$commis_list = $model_extension_commis_detail->getCommisdetailList(array('order_id'=>$order_id));
    			if(!empty($commis_list)){
    				$okey = 0;//防止重复去冻结表
    				foreach ($commis_list as $commis_info){
    					//返佣 积分和云币大于0，并且还未结算的情况下。如果已经结算，就要去对应人那边去扣减
    					if(($commis_info['mb_commis_totals']>0||$commis_info['mb_commis_points']>0)&&$okey==0&&$commis_info['give_status']==2){
    						//冻结结算佣金，只要存在就全部冻结
    						$model_extension_commis_detail->editCommisdetail(array('give_status'=>0),array('order_id'=>$order_id));
    						$okey++;
    					}
    				}
    			}
    		}
    		//添加订单日志
    		$data = array();
    		$data['order_id'] = $order_id;
    		$data['log_role'] = 'seller';
    		$data['log_msg'] = '取消申请退货款';
    		$data['log_user'] = $user;
    		if ($msg) {
    			$data['log_msg'] .= ' ( '.$msg.' )';
    		}
    		$data['log_orderstate'] = ORDER_STATE_SUCCESS;
    		$model_order->addOrderLog($data);
    		return callback(true,'操作成功');
    	} catch (Exception $e) {
    		return callback(false,'操作失败');
    	}
    }
}