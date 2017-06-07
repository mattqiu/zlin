<?php
/**
 * 导购-我的入库单
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class seller_erp_rkControl extends BaseSellerControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 入库单列表
     */
    public function rkbill_listOp() {
    	   
        $model_rk = Model('erp_rk');
        $condition = array();
        //列表出未支付、未发货、未收货状态
        $condition['store_id'] = $this->store_id;
        
        if(!empty($_REQUEST['keywords'])){
        	$condition['rk_sn|seller_name'] = array('like', '%' . $_REQUEST['keywords'] . '%');
        }
        //列表出未支付、未发货、未收货状态
	$condition = $this->rk_state_type($_REQUEST["state_type"]);
        $rk_list = $model_rk->getRKBillList($condition, $this->page, '*', 'rk_id desc','',array('rk_goods','seller'));
		
        $new_rk_list = array();
        $i=0;//小程序 排序就是从小到大的
        foreach ($rk_list as $okey=>$value) {
        	
        	$new_rk_list[$i] = $value;
			$new_rk_list[$i]['add_time'] = date('Y-m-d H:i',$value['add_time']);
			$new_rk_list[$i]['rk_state'] = $rk_state = $value['rk_state'];//入库单状态
			$new_rk_list[$i]['seller_avatar'] = getMemberAvatarForID($value['extend_seller']['member_id']);//购买人的头像
				
			$i++;		
        }        
        $page_count = $model_rk->gettotalpage();
        output_data(array('rk_list' => $new_rk_list),'入库单列表', wxapp_page($page_count));
    }
	
    /**
     * 财务记账入库
     *
     */
    public function accountingOp(){
    	$rk_id = intval($_REQUEST['rk_id']);
    	if ($rk_id <= 0) {
    		output_error('入库单不存在');
    	}
    	$model_rk = Model('erp_rk');
    	$condition = array();
    	$condition['rk_id'] = $rk_id;
    	$condition['store_id'] = intval($this->store_id);
    	$rk_info = $model_rk->getRKInfo($condition,array('rk_goods','seller'));
    	if(empty($rk_info['rk_state']) || $rk_info['rk_state'] == 30){//已取消或已入库
    		output_error('该入库单当前状态无需操作');
    	}
    	$res_upd = $model_rk->editRKBill(array('rk_state'=>$_REQUEST['rk_state']),$condition);
    	if($res_upd){
    		$rk_goods_list = $rk_info['extend_rk_goods'];
    		if (!empty($rk_goods_list)) {
    			foreach ($rk_goods_list as $goods) {
    				if(!empty($goods['goods_id'])){
    					$update['goods_storage'] = array('exp', "goods_storage + ({$goods['goods_num']})");
    					$res_ugStorage = Model('goods')->editGoods($update,array('goods_id'=>$goods['goods_id']));
    					if($res_ugStorage){
    						$rk_log = array();
    						$rk_log['rk_id'] = $rk_id;
    						$rk_log['log_msg'] = $rk_info['seller']['seller_name'].'将商品ID'.$goods['goods_id'].'入库销售';
    						$rk_log['log_role'] = "seller";
    						$rk_log['log_user'] = $rk_info['seller']['seller_name'];
    						$rk_log['log_rkstate'] = $rk_state;
    						$reslog = $model_erprk->addRKLog($rk_log);
    					}
    				}
    			}
    		} else {
    			output_error('入库单没有找到对应的入库商品！');
    		}
    		output_data($res_upd,'入库单状态为'.rkState($_REQUEST['rk_state']));
    	}else{
    		output_error('该入库单状态修改失败！');
    	}
    }
    /**
     * 入库单详情
     */
    public function rk_infoOp(){
    	$rk_id = intval($_REQUEST['rk_id']);
    	if ($rk_id <= 0) {
    		output_error('入库单不存在');
    	}
    	$model_rk = Model('erp_rk');
    	$condition = array();
    	$condition['rk_id'] = $rk_id;
    	$condition['store_id'] = intval($this->store_id);
    	$rk_info = $model_rk->getRKInfo($condition,array('rk_goods','seller'));
    	if (empty($rk_info) || $rk_info['delete_state'] == ORDER_DEL_STATE_DROP) {
    		output_error('入库单不存在或已被删除');
    	}
    	$rk_info['seller_avatar'] = getMemberAvatarForID($rk_info['extend_seller']['member_id']);//购买人的头像
    	$rk_info['add_time'] = date('Y-m-d H:i:s',$rk_info['add_time']);
    	$rk_info['state_desc'] = rkState($rk_info['rk_state']);
    	output_data(array('rk_info'=>$rk_info),'入库单信息加载成功');
    }
    /**
     * 生成入库单编号(两位字母 + 如2017-01-02 13:59 实际：1701021359+微秒+导购ID%1000)
     * 长度 =2位 + 10位 + 3位 + 3位  = 18位
     * 1000个会员同一微秒提订单，重复机率为1/100
     * @return string
     */
    public function makeRKSn($seller_id) {
    	return 'rk'
    	. date("ymdHi", time())
    	. sprintf('%03d', (float) microtime() * 1000)
    	. sprintf('%03d', (int) $seller_id % 1000);
    }
    /**
     * 保存入库单
     *
     */
    public function createRKBillOp() {
    	
    	try {
	    	$model_erprk = Model('erp_rk');
	    	$model_erprk->beginTransaction();
    	$seller_id = $_REQUEST['seller_id'];
	$param['rk_sn']   = $this->makeRKSn($seller_id);//'入库单编号',
    	$param['store_id']   = $store_id = $this->store_id;
    	$param['store_name']  = $store_name	= $this->store_info['store_name'];// '卖家店铺名称',
    	$param['add_time']   = TIMESTAMP;//'单据生成时间',
    	$param['rk_amount']   = $rk_amount = 0.00;//入库总价格
    	$param['shipping_fee']   = 0.00;//'运费',
	    	$param['rk_state']   = $rk_state = 10;//'单据状态：0(已取消)10(默认):待复核;11:已复核;20:已审核;30:已入库',
    	$param['lock_state']   = 0;//'锁定状态:0是正常,大于0是锁定,默认是0',
    	$param['delete_state']   = 0;//'删除状态0未删除1放入回收站2彻底删除',
    	$param['delay_time']   = 0;//'延迟入库时间,默认为0',
    	$param['bill_from']   = 0;//'单据来源：0 自建 1 商家 2 系统',
    	$param['bill_type']   = 0;//'单据类型：0普通单据(默认),1 预定订单,3自提订单',
    	$param['shipping_code']   = '';//物流单号
    	$param['seller_id']   = $seller_id;//入库导购
    	$param['seller_name']   = $seller_name = $_REQUEST['seller_name'];//入库导购名称
    	$rk_id = $model_erprk->addRKBill($param);
	    	if (!$rk_id) {
	    		throw new Exception('入库单保存失败[未生成入库单据]');
	    	}
	    	$get_goodsList = stripslashes(htmlspecialchars_decode($_REQUEST['goods_list']));
	    	$goods_list = json_decode($get_goodsList,true); //解析商品列表
	    	$rk_goods = array();
	    	if(!empty($goods_list)){			
	    		foreach ($goods_list as $i=>$goods_info) {
	    			if(is_object($goods_info)) {
	    				$goods_info = (array)$goods_info;
	    				print_r($goods_info);
	    			}
	    			//如果不是优惠套装
	    			$rk_goods[$i]['rk_id'] = $rk_id;
	    			$rk_goods[$i]['goods_id'] = $goods_info['goods_id'];
	    			$rk_goods[$i]['store_id'] = $store_id;
	    			$rk_goods[$i]['goods_name'] = $goods_info['goods_name'];
	    			$goods_price  = empty($goods_info['goods_marketprice'])?$goods_info['goods_price']:$goods_info['goods_marketprice'];
	    			$rk_goods[$i]['goods_price'] = $goods_price;
	    			$rk_goods[$i]['goods_num'] = $goods_info['goods_num'];
	    			$rk_goods[$i]['goods_image'] = $goods_info['goods_image'];
	    			$rk_goods[$i]['gc_id'] = $goods_info['gc_id'];
	    			$rk_goods[$i]['goods_spec'] = $goods_info['goods_spec'];
	    		}
	    	}
	    	$insert = $model_erprk->addRKGoods($rk_goods);
	    	if (!$insert) {
	    		throw new Exception('入库商品保存失败[未生成商品数据]');
	    	}
	    	$rk_log = array();
	    	$rk_log['rk_id'] = $rk_id;
	    	$rk_log['log_msg'] = $seller_name.'创建了入库单';
	    	$rk_log['log_role'] = "seller";
	    	$rk_log['log_user'] = $seller_name;
	    	$rk_log['log_rkstate'] = $rk_state;
	    	$reslog = $model_erprk->addRKLog($rk_log);
	    	if (!$reslog) {
	    		throw new Exception('入库单日志记录失败[未生成入库单记录数据]');
	    	}
	    	$model_erprk->commit();
    	}catch (Exception $e){
    		$model_erprk->rollback();
    		output_error($e->getMessage());
    	}
    	output_data($rk_id,'入库单创建成功');
    }
    
    //入库单据状态
    private function rk_state_type($state) {
    	switch ($state){
    		case '1':
    			$condition['rk_state'] = 10;//10
    			break;
    		case '11':
    			$condition['rk_state'] = 11;//20
    			break;
    		case '2':
    			$condition['rk_state'] = 20;//20
    			break;
    		case '3':
    			$condition['rk_state'] = 30;//30
    			break;
    		case '0':
    			$condition['rk_state'] = 0; //0
    			break;
    		default:
    			$condition['rk_state'] = array('in',array(10,11,20,30));
    	}
    	return $condition;
    }
    
    /**
     * 取消订单
     */
    public function order_cancelOp() {
    	$model_order = Model('erp_rk');
    	$logic_order = Logic('order');
    	$order_id = intval($_POST['order_id']);
    	$condition = array();
    	$condition['order_id'] = $order_id;
    	$condition['store_id'] = $this->store_id;
    	$order_info = $model_order->getOrderInfo($condition);
    	$if_allow = $model_order->getOrderOperateState('store_cancel',$order_info);
    	if (!$if_allow) {
    		output_error('您无权操作该订单');
    	}
    	$result = $logic_order->changeOrderStateCancel($order_info,'seller', $order_info['saleman_name'], '导购取消订单');
    	if(!$result['state']) {
    		output_error($result['msg']);
    	} else {
    		output_data($result,"成功取消订单");
    	}
    }
	/**
	 * 取得订单状态文字输出形式
	 *
	 * @param array $order_info 订单数组
	 * @return string $order_state 描述输出
	 */
	public function orderStateName($order_state) {
		switch ($order_state) {
			case ORDER_STATE_CANCEL:
				$order_state = '已取消';
				break;
			case ORDER_STATE_NEW:
				$order_state = '待付款';
				break;
			case ORDER_STATE_PAY:
				$order_state = '已支付';
				break;
			case ORDER_STATE_SUCCESS:
				$order_state = '已完成';
				break;
		}
		return $order_state;
	}
}