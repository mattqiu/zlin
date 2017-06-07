<?php
/**
 * 积分管理
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */

defined('InIMall') or exit('Access Invalid!');

class predepositControl extends SystemControl{
    const EXPORT_SIZE = 1000;
    public function __construct(){
        parent::__construct();
        Language::read('predeposit');
    }

    public function indexOp() {
        $this->predepositOp();
    }

    /**
     * 充值列表
     */
    public function predepositOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('pd.list');
    }

    /**
     * 充值编辑(更改成收到款)
     */
    public function recharge_editOp(){
        $id = intval($_GET['id']);
        if ($id <= 0){
            showMessage(Language::get('admin_predeposit_parameter_error'),'index.php?act=predeposit&op=predeposit','','error');
        }
        //查询充值信息
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_id'] = $id;
        $condition['pdr_payment_state'] = 0;
        $info = $model_pd->getPdRechargeInfo($condition);
        if (empty($info)){
            showMessage(Language::get('admin_predeposit_record_error'),'index.php?act=predeposit&op=predeposit','','error');
        }
        if (!chksubmit()) {
            //显示支付接口列表
            $payment_list = Model('payment')->getPaymentOpenList();
            //去掉积分和货到付款
            foreach ($payment_list as $key => $value){
                if ($value['payment_code'] == 'predeposit' || $value['payment_code'] == 'offline') {
                    unset($payment_list[$key]);
                }
            }
            Tpl::output('payment_list',$payment_list);
            Tpl::output('info',$info);
			Tpl::setDirquna('shop');
            Tpl::showpage('pd.edit');
            exit();
        }
        $member_id = $info['pdr_member_id'];
        $member_name = $info['pdr_member_name'];
        //取支付方式信息
        $model_payment = Model('payment');
        $condition = array();
        $condition['payment_code'] = $_POST['payment_code'];
        $payment_info = $model_payment->getPaymentOpenInfo($condition);
        if(!$payment_info || $payment_info['payment_code'] == 'offline' || $payment_info['payment_code'] == 'offline') {
            showMessage(L('payment_index_sys_not_support'),'','html','error');
        }

        $condition = array();
        $condition['pdr_sn'] = $info['pdr_sn'];
        $condition['pdr_payment_state'] = 0;
        $update = array();
        $update['pdr_payment_state'] = 1;
        $update['pdr_payment_time'] = strtotime($_POST['payment_time']);
        $update['pdr_payment_code'] = $payment_info['payment_code'];
        $update['pdr_payment_name'] = $payment_info['payment_name'];
        $update['pdr_trade_sn'] = $_POST['trade_no'];
        $update['pdr_admin'] = $this->admin_info['name'];
        
        //更改充值状态
        $state = $model_pd->editPdRecharge($update,$condition);
        if (!$state) {
        	throw Exception(Language::get('predeposit_payment_pay_fail'));
        }
        $log_msg = L('admin_predeposit_recharge_edit_state').','.L('admin_predeposit_sn').':'.$info['pdr_sn'];

        //若是 会员充值 ，将此字段存了充值卡的ID
        $rechargecard_id = $info['pdr_admin'];
        $rechargecardInfo = array();
        $model_rechargecard = Model('rechargecard');
        //ID 存在用户购买的是充值卡类型 需要给会员的对应账号里充值卡的金额增加
        if(!empty($rechargecard_id) && $rechargecard_id >0){
        	//获取充值卡的额度
        	$rechargecardInfo = $model_rechargecard->getRechargeCardByID($rechargecard_id);
        	if(empty($rechargecardInfo)){
        		showMessage("充值卡已经不存在，请联系管理员",'index.php?act=predeposit&op=predeposit');
        	}
        	//此时的充值卡
            $pdr_amount = $rechargecardInfo['pd_amount']; //充值送积分
            $card_points = $rechargecardInfo['card_points']; //充值送云币
            $give_exppoints = $rechargecardInfo['give_exppoints']; //赠送的经验值
            $commis_amount = $rechargecardInfo['commis_amount']; //充值返佣
            $commis_points = $rechargecardInfo['commis_points']; //返给上级的云币
            $batchflag = $rechargecardInfo['batchflag']; //批次标识
            if(empty($pdr_amount)){//充值卡
            	//充值卡的额度为面额
            	if($rechargecardInfo['card_grant'] == 1){//换购模式
            		$rcb_amount = $rechargecardInfo['rcb_amount']; //充值卡
            	}else{
            		$rcb_amount = $rechargecardInfo['denomination']; //充值卡
            	}
            	$pdr_amount = $rcb_amount; //充值卡
            	$data = array();
            	$data['member_id'] = $info['pdr_member_id'];
            	$data['member_name'] = $info['pdr_member_name'];
            	$data['sn'] = $rechargecardInfo['sn']; //充值卡号
            	$data['amount'] = $rcb_amount;
            	$rcb_res = $model_pd->changeRcb('recharge',$data);
            	if(!$rcb_res){
            		showMessage("充值卡添加失败",'index.php?act=predeposit&op=predeposit');
            	}
            }else{
            	//变更会员积分
            	$data = array();
            	$data['member_id'] = $info['pdr_member_id'];
            	$data['member_name'] = $info['pdr_member_name'];
            	$data['amount'] = $info['pdr_amount'];
            	$data['pdr_sn'] = $info['pdr_sn'];
            	$data['admin_name'] = $this->admin_info['name'];
            	$pd_res = $model_pd->changePd('recharge',$data);
            	
            	if(!$pd_res){
	            	showMessage("积分添加失败",'index.php?act=predeposit&op=predeposit');
            	}
            }
	    	$this->log($log_msg,1);
	    	//备注是否为数字，如果是则可能是赠送指定的商品
	    	if(is_numeric($batchflag)){
	    		$mdl_goods = Model('goods');
	    		$gcomnum = $mdl_goods->getGoodsCount(array('goods_id'=>$batchflag));
	    		if($gcomnum>0){//商品存在，需要生成虚拟订单，以及税换码118022
	    			$member_id = $info['pdr_member_id'];
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
            			'pl_memberid'=>$member_id, //会员编号
            			'pl_membername'=>$member_name,//会员名称
            			'pl_points'=>$card_points,//云币抵扣的金额
            			'pl_addtime'=>TIMESTAMP,//订单编号
            			'pl_desc'=>"会员卡充值，订单".$info['pdr_sn'],//订单序号
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
            					'exp_desc'=>'会员卡充值送经验值，订单'.$info['pdr_sn'].''
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
	            		Model('extension')->parentDistributeRebate($insertarr['pl_memberid'],$parent_id,$commis_amount,$info['pdr_id'],$info['pdr_sn'],$info['pdr_amount']);
	            		$pInfo = $obj_member->getMemberInfoByID($parent_id,'member_name');
	            		$insertarr = array(
	            				'pl_memberid'=>$parent_id, //会员编号
	            				'pl_membername'=>$pInfo['member_name'],//会员名称
	            				'pl_points'=>$commis_points,//云币抵扣的金额
	            				'pl_addtime'=>TIMESTAMP,//订单编号
	            				'pl_desc'=>"成功邀请会员".$member_name."加入团队,返".$commis_points."云币",//订单序号
	            				'pl_stage'=>'other'//订单序号
	            		);
	            		Model('points')->addPointsLog($insertarr);
	            		$upmber_arr['member_points'] = array('exp','member_points+'.$commis_points);
	            		$obj_member->editMember(array('member_id'=>$parent_id),$upmber_arr);
            		}
            		showMessage(Language::get('admin_predeposit_recharge_edit_success'),'index.php?act=predeposit&op=predeposit');
            	}else {
            		showMessage("会员云币添加失败,请联系管理员",'index.php?act=predeposit&op=predeposit');
            	}
            }
        }else{
            //变更会员积分
            $data = array();
            $data['member_id'] = $info['pdr_member_id'];
            $data['member_name'] = $info['pdr_member_name'];
            $data['amount'] = $info['pdr_amount'];
            $data['pdr_sn'] = $info['pdr_sn'];
            $data['admin_name'] = $this->admin_info['name'];
            $model_pd->changePd('recharge',$data);
            $this->log($log_msg,1);
            showMessage(Language::get('admin_predeposit_recharge_edit_success'),'index.php?act=predeposit&op=predeposit');
        }
    }

    /**
     * 充值查看
     */
    public function recharge_infoOp(){
        $id = intval($_GET['id']);
        if ($id <= 0){
            showMessage(Language::get('admin_predeposit_parameter_error'),'index.php?act=predeposit&op=predeposit','','error');
        }
        //查询充值信息
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdr_id'] = $id;
        $info = $model_pd->getPdRechargeInfo($condition);
        if (empty($info)){
            showMessage(Language::get('admin_predeposit_record_error'),'index.php?act=predeposit&op=predeposit','','error');
        }
        Tpl::output('info',$info);
		Tpl::setDirquna('shop');
        Tpl::showpage('pd.info', 'null_layout');

    }

    /**
     * 充值删除
     */
    public function recharge_delOp(){
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_pd = Model('predeposit');
            $model_upload = Model('upload');
            $condition['pdr_payment_state'] = 0;
            $condition['pdr_id'] = $id;
            $result = $model_pd->delPdRecharge($condition);
            $this->log('充值申请删除[ID:'.$id.']',null);
            exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 积分日志
     */
    public function pd_log_listOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('pd_log.list');
    }

    /**
     * 提现列表
     */
    public function pd_cash_listOp(){
		Tpl::setDirquna('shop');
        Tpl::showpage('pd_cash.list');
    }

    /**
     * 删除提现记录
     */
    public function pd_cash_delOp(){
        $id = intval($_GET['id']);
        if ($id > 0) {
            $model_pd = Model('predeposit');
            $condition = array();
            $condition['pdc_id'] = $id;
            $condition['pdc_payment_state'] = 0;
            $info = $model_pd->getPdCashInfo($condition);
            if (!$info) {
                exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            }
            try {
                $result = $model_pd->delPdCash($condition);
                if (!$result) {
                    throw new Exception(Language::get('admin_predeposit_cash_del_fail'));
                }
                //退还冻结的积分
                $model_member = Model('member');
                $member_info = $model_member->getMemberInfo(array('member_id'=>$info['pdc_member_id']));
                //扣除冻结的积分
                $admininfo = $this->getAdminInfo();
                $data = array();
                $data['member_id'] = $member_info['member_id'];
                $data['member_name'] = $member_info['member_name'];
                $data['amount'] = $info['pdc_amount'];
                $data['order_sn'] = $info['pdc_sn'];
                $data['admin_name'] = $admininfo['name'];
                $model_pd->changePd('cash_del',$data);
                $model_pd->commit();

                $this->log('提现申请删除[ID:'.$id.']',null);
                exit(json_encode(array('state'=>true,'msg'=>'删除成功')));
            } catch (Exception $e) {
                $model_pd->commit();
                exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
            }
        } else {
            exit(json_encode(array('state'=>false,'msg'=>'删除失败')));
        }
    }

    /**
     * 更改提现为支付状态
     */
    public function pd_cash_payOp(){
        $id = intval($_GET['id']);
        if ($id <= 0){
            showMessage(Language::get('admin_predeposit_parameter_error'),'index.php?act=predeposit&op=pd_cash_list','','error');
        }
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdc_id'] = $id;
        $condition['pdc_payment_state'] = 0;
        $info = $model_pd->getPdCashInfo($condition);
        if (!is_array($info) || count($info)<0){
            showMessage(Language::get('admin_predeposit_record_error'),'index.php?act=predeposit&op=pd_cash_list','','error');
        }

        //查询用户信息
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfo(array('member_id'=>$info['pdc_member_id']));

        $update = array();
        $admininfo = $this->getAdminInfo();
        $update['pdc_payment_state'] = 1;
        $update['pdc_payment_admin'] = $admininfo['name'];
        $update['pdc_payment_time'] = TIMESTAMP;
        $log_msg = L('admin_predeposit_cash_edit_state').','.L('admin_predeposit_cs_sn').':'.$info['pdc_sn'];

        try {
            $model_pd->beginTransaction();
            $result = $model_pd->editPdCash($update,$condition);
            if (!$result) {
                throw new Exception(Language::get('admin_predeposit_cash_edit_fail'));
            }
            //扣除冻结的积分
            $data = array();
            $data['member_id'] = $member_info['member_id'];
            $data['member_name'] = $member_info['member_name'];
            $data['amount'] = $info['pdc_amount'];
            $data['order_sn'] = $info['pdc_sn'];
            $data['admin_name'] = $admininfo['name'];
            $model_pd->changePd('cash_pay',$data);
            $model_pd->commit();
            $this->log($log_msg,1);
            showMessage(Language::get('admin_predeposit_cash_edit_success'),'index.php?act=predeposit&op=pd_cash_list');
        } catch (Exception $e) {
            $model_pd->rollback();
            $this->log($log_msg,0);
            showMessage($e->getMessage(),'index.php?act=predeposit&op=pd_cash_list','html','error');
        }
    }

    /**
     * 查看提现信息
     */
    public function pd_cash_viewOp(){
        $id = intval($_GET['id']);
        $model_pd = Model('predeposit');
        $condition = array();
        $condition['pdc_id'] = $id;
        $info = $model_pd->getPdCashInfo($condition);
        Tpl::output('info',$info);
		Tpl::setDirquna('shop');
        Tpl::showpage('pd_cash.view', 'null_layout');
    }


    /**
     * 导出积分充值记录
     *
     */
    public function export_step1Op(){
        $condition = array();
        if ($_GET['member_name']) {
            $condition['pdr_member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if ($_GET['member_id']) {
            $condition['pdr_member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['pdr_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if ($_GET['pdr_payment_state'] != '') {
            $condition['pdr_payment_state'] = $_GET['pdr_payment_state'] == 1 ? 1 : 0;
        }
        if ($_GET['query'] != '') {
            $condition[$_GET['qtype']] = array('like', '%' . $_GET['query'] . '%');
        }
        $order = '';
        $param = array('pdr_id', 'pdr_sn', 'pdr_member_id', 'pdr_member_name', 'pdr_amount', 'pdr_add_time', 'pdr_payment_name', 'pdr_trade_sn', 'pdr_payment_state', 'pdr_payment_time', 'pdr_admin');
        if (in_array($$_GET['sortname'], $param) && in_array($_GET['sortorder'], array('asc', 'desc'))) {
            $order = $_GET['sortname'] . ' ' . $_GET['sortorder'];
        }
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['pdr_id'] = array('in', $id_array);
        }
        $model_pd = Model('predeposit');
        if (!is_numeric($_GET['curpage'])){
            $count = $model_pd->getPdRechargeCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=predeposit&op=predeposit');
				Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
            }else{  //如果数量小，直接下载
                $data = $model_pd->getPdRechargeList($condition,'','*','pdr_id desc',self::EXPORT_SIZE);
                $rechargepaystate = array(0=>'未支付',1=>'已支付');
                foreach ($data as $k=>$v) {
                    $data[$k]['pdr_payment_state'] = $rechargepaystate[$v['pdr_payment_state']];
                }
                $this->createExcel($data);
            }
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_pd->getPdRechargeList($condition,'','*',$order,"{$limit1},{$limit2}");
            $rechargepaystate = array(0=>'未支付',1=>'已支付');
            foreach ($data as $k=>$v) {
                $data[$k]['pdr_payment_state'] = $rechargepaystate[$v['pdr_payment_state']];
            }
            $this->createExcel($data);
        }
    }

    /**
     * 生成导出积分充值excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_no'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_member'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_ctime'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_ptime'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_pay'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_money'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_paystate'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_yc_memberid'));
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['pdr_sn']);
            $tmp[] = array('data'=>$v['pdr_member_name']);
            $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['pdr_add_time']));
            if (intval($v['pdr_payment_time'])) {
                if (date('His',$v['pdr_payment_time']) == 0) {
                   $tmp[] = array('data'=>date('Y-m-d',$v['pdr_payment_time']));
                } else {
                   $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['pdr_payment_time']));
                }
            } else {
                $tmp[] = array('data'=>'');
            }
            $tmp[] = array('data'=>$v['pdr_payment_name']);
            $tmp[] = array('format'=>'Number','data'=>imPriceFormat($v['pdr_amount']));
            $tmp[] = array('data'=>$v['pdr_payment_state']);
            $tmp[] = array('data'=>$v['pdr_member_id']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_yc_yckcz'),CHARSET));
        $excel_obj->generateXML($excel_obj->charset(L('exp_yc_yckcz'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

    /**
     * 导出积分提现记录
     *
     */
    public function export_cash_step1Op(){
        $condition = array();
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['stime']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['etime']);
        $start_unixtime = $if_start_date ? strtotime($_GET['stime']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['etime']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if (!empty($_GET['member_name'])){
            $condition['pdc_member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if (!empty($_GET['member_id'])){
            $condition['pdc_member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }
        if (!empty($_GET['user_name'])){
            $condition['pdc_bank_user'] = array('like', '%' . $_GET['user_name'] . '%');
        }
        if ($_GET['pdc_payment_state'] != ''){
            $condition['pdc_payment_state'] = $_GET['pdc_payment_state'];
        }
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['pdc_id'] = array('in', $id_array);
        }

        if ($_GET['query'] != '') {
            $condition[$_GET['qtype']] = array('like', '%' . $_GET['query'] . '%');
        }
        $order = '';
        $param = array('pdr_id', 'pdr_sn', 'pdr_member_id', 'pdr_member_name', 'pdr_amount', 'pdr_add_time', 'pdr_payment_name', 'pdr_trade_sn', 'pdr_payment_state', 'pdr_payment_time', 'pdr_admin');
        if (in_array($_GET['sortname'], $param) && in_array($_GET['sortorder'], array('asc', 'desc'))) {
            $order = $_GET['sortname'] . ' ' . $_GET['sortorder'];
        }
        $model_pd = Model('predeposit');

        if (!is_numeric($_GET['curpage'])){
            $count = $model_pd->getPdCashCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=predeposit&op=pd_cash_list');
				Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
            }else{  //如果数量小，直接下载
                $data = $model_pd->getPdCashList($condition,'','*',$order,self::EXPORT_SIZE);
                $cashpaystate = array(0=>'未支付',1=>'已支付');
                foreach ($data as $k=>$v) {
                    $data[$k]['pdc_payment_state'] = $cashpaystate[$v['pdc_payment_state']];
                }
                $this->createCashExcel($data);
            }
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_pd->getPdCashList($condition,'','*','pdc_id desc',"{$limit1},{$limit2}");
            $cashpaystate = array(0=>'未支付',1=>'已支付');
            foreach ($data as $k=>$v) {
                $data[$k]['pdc_payment_state'] = $cashpaystate[$v['pdc_payment_state']];
            }
            $this->createCashExcel($data);
        }
    }

    /**
     * 生成导出积分提现excel
     *
     * @param array $data
     */
    private function createCashExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_tx_no'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_tx_member'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_tx_money'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_tx_ctime'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_tx_state'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_tx_memberid'));
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['pdc_sn']);
            $tmp[] = array('data'=>$v['pdc_member_name']);
            $tmp[] = array('format'=>'Number','data'=>imPriceFormat($v['pdc_amount']));
            $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['pdc_add_time']));
            $tmp[] = array('data'=>$v['pdc_payment_state']);
            $tmp[] = array('data'=>$v['pdc_member_id']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_tx_title'),CHARSET));
        $excel_obj->generateXML($excel_obj->charset(L('exp_tx_title'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

    /**
     * 积分明细信息导出
     */
    public function export_mx_step1Op(){
        $condition = array();
        if ($_GET['id'] != '') {
            $id_array = explode(',', $_GET['id']);
            $condition['lg_id'] = array('in', $id_array);
        }
        if ($_GET['query'] != '') {
            $condition[$_GET['qtype']] = array('like', '%' . $_GET['query'] . '%');
        }
        $order = '';
        $param = array('lg_id', 'lg_member_id', 'lg_member_name', 'lg_av_amount', 'lg_freeze_amount', 'lg_add_time', 'lg_desc', 'lg_admin_name');
        if (in_array($_GET['sortname'], $param) && in_array($_GET['sortorder'], array('asc', 'desc'))) {
            $order = $_GET['sortname'] . ' ' . $_GET['sortorder'];
        }
        $model_pd = Model('predeposit');
        if (!is_numeric($_GET['curpage'])){
            $count = $model_pd->getPdLogCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){   //显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=predeposit&op=pd_log_list');
				Tpl::setDirquna('shop');
                Tpl::showpage('export.excel');
            }else{  //如果数量小，直接下载
                $data = $model_pd->getPdLogList($condition,'','*','lg_id desc',self::EXPORT_SIZE);
                $this->createmxExcel($data);
            }
        }else{  //下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_pd->getPdLogList($condition,'','*','lg_id desc',"{$limit1},{$limit2}");
            $this->createmxExcel($data);
        }
    }

    /**
     * 导出积分明细excel
     *
     * @param array $data
     */
    private function createmxExcel($data = array()){
        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_member'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_ctime'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_av_money'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_freeze_money'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_system'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_mx_mshu'));
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            $tmp[] = array('data'=>$v['lg_member_name']);
            $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['lg_add_time']));
            if (floatval($v['lg_av_amount']) == 0){
                $tmp[] = array('data'=>'');
            } else {
                $tmp[] = array('format'=>'Number','data'=>imPriceFormat($v['lg_av_amount']));
            }
            if (floatval($v['lg_freeze_amount']) == 0){
                $tmp[] = array('data'=>'');
            } else {
                $tmp[] = array('format'=>'Number','data'=>imPriceFormat($v['lg_freeze_amount']));
            }
            $tmp[] = array('data'=>$v['lg_admin_name']);
            $tmp[] = array('data'=>$v['lg_desc']);
            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_mx_rz'),CHARSET));
        $excel_obj->generateXML($excel_obj->charset(L('exp_mx_rz'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }

    /**
     * 输出充值XML数据
     */
    public function get_xmlOp() {
        $model_pd = Model('predeposit');
        $condition = array();
        if ($_GET['member_name']) {
            $condition['pdr_member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if ($_GET['member_id']) {
            $condition['pdr_member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['pdr_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if ($_GET['pdr_payment_state'] != '') {
            $condition['pdr_payment_state'] = $_GET['pdr_payment_state'] == 1 ? 1 : 0;
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('pdr_id', 'pdr_sn', 'pdr_member_id', 'pdr_member_name', 'pdr_amount', 'pdr_add_time', 'pdr_payment_name', 'pdr_trade_sn', 'pdr_payment_state', 'pdr_payment_time', 'pdr_admin');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $recharge_list = $model_pd->getPdRechargeList($condition,$page,'*',$order);

        $data = array();
        $data['now_page'] = $model_pd->shownowpage();
        $data['total_num'] = $model_pd->gettotalnum();
        foreach ($recharge_list as $value) {
            $param = array();
            $operation = '';
            if ($value['pdr_payment_state'] == 0) {
                $operation .= "<a class='btn red' href=\"JavaScript:void(0);\" onclick=\"fg_delete('" . $value['pdr_id'] . "')\"><i class='fa fa-trash-o'></i>删除</a>";
            }
            $operation .= "<a class='btn green' href='javascript:void(0)' onclick=\"ajax_form('recharge_info','查看充值编号“". $value['pdr_sn'] ."”的明细','index.php?act=predeposit&op=recharge_info&id=".$value['pdr_id']."', '640')\"><i class='fa fa-list-alt'></i>查看</a>";
            $param['operation'] = $operation;
            $param['pdr_id'] = $value['pdr_id'];
            $param['pdr_sn'] = $value['pdr_sn'];
            $param['pdr_member_id'] = $value['pdr_member_id'];
            $param['pdr_member_name'] = "<img src=".getMemberAvatarForID($value['pdr_member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['pdr_member_id']).">\")'>" .$value['pdr_member_name'];
            $param['pdr_amount'] = imPriceFormat($value['pdr_amount']);
            $param['pdr_add_time'] = date('Y-m-d', $value['pdr_add_time']);
            $param['pdr_payment_name'] = $value['pdr_payment_name'];
            $param['pdr_trade_sn'] = $value['pdr_trade_sn'];
            $param['pdr_payment_state'] = $value['pdr_payment_state'] == '0' ? '未支付' : '已支付';
            $param['pdr_payment_time'] = $value['pdr_payment_time'] > 0 ? date('Y-m-d', $value['pdr_payment_time']) : '';
            $param['pdr_admin'] = $value['pdr_admin'];
            $data['list'][$value['pdr_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 输出提现XML数据
     */
    public function get_cash_xmlOp() {
        $model_pd = Model('predeposit');
        $condition = array();
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['stime']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['etime']);
        $start_unixtime = $if_start_date ? strtotime($_GET['stime']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['etime']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['pdc_add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        if (!empty($_GET['member_name'])){
            $condition['pdc_member_name'] = array('like', '%' . $_GET['member_name'] . '%');
        }
        if (!empty($_GET['member_id'])){
            $condition['pdc_member_id'] = array('like', '%' . $_GET['member_id'] . '%');
        }
        if (!empty($_GET['user_name'])){
            $condition['pdc_bank_user'] = array('like', '%' . $_GET['user_name'] . '%');
        }
        if ($_GET['pdc_payment_state'] != ''){
            $condition['pdc_payment_state'] = $_GET['pdc_payment_state'];
        }
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('pdc_id', 'pdc_sn', 'pdc_member_id', 'pdc_member_name', 'pdc_amount', 'pdc_add_time', 'pdc_bank_name', 'pdc_bank_no'
                ,'pdc_bank_user','pdc_payment_state','pdc_payment_time','pdc_payment_admin'
        );
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $cash_list = $model_pd->getPdCashList($condition,$page,'*',$order);
        $data = array();
        $data['now_page'] = $model_pd->shownowpage();
        $data['total_num'] = $model_pd->gettotalnum();
        foreach ($cash_list as $value) {
            $param = array();
            $param['operation'] = "";
            if ($value['pdc_payment_state'] == 0) {
                $param['operation'] .= "<a class='btn red' href=\"javascript:void(0)\" onclick=\"fg_delete('" . $value['pdc_id'] . "')\"><i class='fa fa-trash-o'></i>删除</a>";
            }
            $param['operation'] .= "<a class='btn green' href='javascript:void(0)' onclick=\"ajax_form('cash_info','查看提现编号“". $value['pdc_sn'] ."”的明细', 'index.php?act=predeposit&op=pd_cash_view&id=". $value['pdc_id'] ."', 640)\" ><i class='fa fa-list-alt'></i>查看</a>";
            $param['pdc_id'] = $value['pdc_id'];
            $param['pdc_sn'] = $value['pdc_sn'];
            $param['pdc_member_id'] = $value['pdc_member_id'];
            $param['pdc_member_name'] = "<img src=".getMemberAvatarForID($value['pdc_member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['pdc_member_id']).">\")'>" .$value['pdc_member_name'];
            $param['pdc_amount'] = imPriceFormat($value['pdc_amount']);
            $param['pdc_add_time'] = date('Y-m-d', $value['pdc_add_time']);
            $param['pdc_bank_name'] = $value['pdc_bank_name'];
            $param['pdc_bank_no'] = $value['pdc_bank_no'];
            $param['pdc_bank_user'] = $value['pdc_bank_user'];
            $param['pdc_payment_state'] = $value['pdc_payment_state'] == '0' ? '未支付' : '已支付';
            $param['pdc_payment_time'] = $value['pdc_payment_time'] > 0 ? date('Y-m-d', $value['pdc_payment_time']) : '';
            $param['pdc_payment_admin'] = $value['pdc_payment_admin'];
            $data['list'][$value['pdc_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }

    /**
     * 输出积分明细XML数据
     */
    public function get_log_xmlOp() {
        $model_pd = Model('predeposit');
        $condition = array();
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
        $order = '';
        $param = array('lg_id', 'lg_member_id', 'lg_member_name', 'lg_av_amount', 'lg_freeze_amount', 'lg_add_time', 'lg_desc', 'lg_admin_name');
        if (in_array($_POST['sortname'], $param) && in_array($_POST['sortorder'], array('asc', 'desc'))) {
            $order = $_POST['sortname'] . ' ' . $_POST['sortorder'];
        }
        $page = $_POST['rp'];
        $log_list = $model_pd->getPdLogList($condition,$page,'*',$order);
        $data = array();
        $data['now_page'] = $model_pd->shownowpage();
        $data['total_num'] = $model_pd->gettotalnum();
        foreach ($log_list as $value) {
            $param = array();
            $param['operation'] = "--";
            $param['lg_id'] = $value['lg_id'];
            $param['lg_member_id'] = $value['lg_member_id'];
            $param['lg_member_name'] = "<img src=".getMemberAvatarForID($value['lg_member_id'])." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".getMemberAvatarForID($value['lg_member_id']).">\")'>" .$value['lg_member_name'];
            $param['lg_av_amount'] = imPriceFormat($value['lg_av_amount']);
            $param['lg_freeze_amount'] = imPriceFormat($value['lg_freeze_amount']);
            $param['lg_add_time'] = date('Y-m-d', $value['lg_add_time']);
            $param['lg_desc'] = $value['lg_desc'];
            $param['lg_admin_name'] = $value['lg_admin_name'];
            $data['list'][$value['lg_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
}
