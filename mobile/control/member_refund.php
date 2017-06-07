<?php
/**
 * 买家退款
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

class member_refundControl extends mobileMemberControl {
	public function __construct(){
		parent::__construct();	
	}
	
	/**
	 * 添加订单商品部分退款
	 *
	 */
	public function refund_formOp(){
		$model_refund = Model('refund_return');
		$condition = array();
		$reason_list = $model_refund->getReasonList($condition);//退款退货原因

		$order_id = intval($_GET['order_id']);
		$goods_id = intval($_GET['order_goods_id']);//订单商品表编号
		if ($order_id < 1 || $goods_id < 1) {//参数验证
			output_error('非法操作');
		}
		$condition = array();
		$condition['buyer_id'] = $this->member_info['member_id'];
		$condition['order_id'] = $order_id;
		$order = $model_refund->getRightOrderList($condition, $goods_id);
		$order_id = $order['order_id'];
		$order_amount = $order['order_amount'];//订单金额
		$order_refund_amount = $order['refund_amount'];//订单退款金额
		$goods_list = $order['goods_list'];
		$goods = $goods_list[0];
		$goods_pay_price = $goods['goods_pay_price'];//商品实际成交价
		if ($order_amount < ($goods_pay_price + $order_refund_amount)) {
		    $goods_pay_price = $order_amount - $order_refund_amount;
		    $goods['goods_pay_price'] = $goods_pay_price;
		}
		$goods['goods_img_360'] = thumb($goods,60);
		$goods['order_goods_id'] = $goods['rec_id'];

		$goods_id = $goods['rec_id'];
		$condition = array();
		$condition['buyer_id'] = $order['buyer_id'];
		$condition['order_id'] = $order['order_id'];
		$condition['order_goods_id'] = $goods_id;
		$condition['seller_state'] = array('lt','3');
		$refund_list = $model_refund->getRefundReturnList($condition);
		$refund = array();
		if (!empty($refund_list) && is_array($refund_list)) {
			$refund = $refund_list[0];
		}
	    $refund_state = $model_refund->getRefundState($order);//根据订单状态判断是否可以退款退货
		if ($refund['refund_id'] > 0 || $refund_state != 1) {//检查订单状态,防止页面刷新不及时造成数据错误
			output_error('非法操作');
		}		
		
		output_data(array('reason_list'=>$reason_list,'order'=>$order, 'goods'=>$goods));
	}
	/**
	 * 添加订单商品部分退款保存
	 *
	 */
	public function refund_postOp(){
		$order_id = intval($_POST['order_id']);
		$goods_id = intval($_POST['order_goods_id']);//订单商品表编号
		if ($order_id < 1 || $goods_id < 1) {//参数验证
			output_error('非法操作');
		}
		$model_refund = Model('refund_return');
		$condition = array();
		$reason_list = $model_refund->getReasonList($condition);//退款退货原因
		
		$condition = array();
		$condition['buyer_id'] = $this->member_info['member_id'];
		$condition['order_id'] = $order_id;
		$order = $model_refund->getRightOrderList($condition, $goods_id);
		$goods_list = $order['goods_list'];
		$goods = $goods_list[0];
		$goods_pay_price = $goods['goods_pay_price'];//商品实际成交价
		
		$refund_array = array();
		$refund_amount = floatval($_POST['refund_amount']);//退款金额
		if (($refund_amount < 0) || ($refund_amount > $goods_pay_price)) {
			$refund_amount = $goods_pay_price;
		}		
		$goods_num = intval($_POST['goods_num']);//退货数量
		if (($goods_num < 0) || ($goods_num > $goods['goods_num'])) {
			$goods_num = 1;
		}
	    $refund_array['reason_info'] = '';
		$reason_id = intval($_POST['reason_id']);//退货退款原因
		$refund_array['reason_id'] = $reason_id;
		$reason_array = array();
		$reason_array['reason_info'] = '其他';
		$reason_list[0] = $reason_array;
		if (!empty($reason_list[$reason_id])) {
			$reason_array = $reason_list[$reason_id];
			$refund_array['reason_info'] = $reason_array['reason_info'];
		}
        $pic_array = array();
        $pic_array['buyer'] = $_POST['refund_pic'];//上传凭证
        $info = serialize($pic_array);
        $refund_array['pic_info'] = $info;

		$model_trade = Model('trade');
		$order_shipped = $model_trade->getOrderState('order_shipped');//订单状态30:已发货
		if ($order['order_state'] == $order_shipped) {
			$refund_array['order_lock'] = '2';//锁定类型:1为不用锁定,2为需要锁定
		}
		$refund_array['refund_type'] = $_POST['refund_type'];//类型:1为退款,2为退货
		$show_url = 'index.php?act=member_return&op=index';
		if ($refund_array['refund_type'] != '2') {
			$refund_array['refund_type'] = '1';
			$show_url = 'index.php?act=member_refund&op=index';
		}
		$refund_array['seller_state'] = '1';//状态:1为待审核,2为同意,3为不同意
		$refund_array['refund_amount'] = imPriceFormat($refund_amount);
		$refund_array['goods_num'] = $goods_num;
		$refund_array['buyer_message'] = $_POST['buyer_message'];
		$refund_array['add_time'] = time();

		$state = $model_refund->addRefundReturn($refund_array,$order,$goods);
		if ($state) {
			//如果用户申请了退货，并且该订单状态是 客户已经确定收货，那么就要锁定该订单赠送给客户的返利（积分、云币）
			//由于云币是无法锁定所以只能暂时扣减，记住如果商家不同意退货，记得把云币再退还给客户
			//另外，还需要暂时锁住 返佣的订单 zhangc
			if(getOrderState == ORDER_STATE_SUCCESS){//是否收到货
				//用户收到货后 申请退款
				Logic('order')->changeOrderSuccessApplyRefund($order,'buyer', $this->member_info['member_name'],'申请退货款');				
			}
    		if ($order['order_state'] == $order_shipped) {
    			$model_refund->editOrderLock($order_id);
    		}
			output_data('操作成功');
		} else {
			output_error('操作失败');
		}
	}

	/**
	 * 退款记录列表页
	 *
	 */
	public function get_refund_listOp(){
		$model_refund = Model('refund_return');
		$state_data = $model_refund->getRefundStateArray('seller');	
		
		$condition = array();
		$condition['buyer_id'] = $this->member_info['member_id'];

		$keyword_type = array('order_sn','refund_sn','goods_name');
		if (trim($_GET['key']) != '' && in_array($_GET['type'],$keyword_type)){
			$type = $_GET['type'];
			$condition[$type] = array('like','%'.$_GET['key'].'%');
		}
		if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != ''){
			$add_time_from = strtotime(trim($_GET['add_time_from']));
			$add_time_to = strtotime(trim($_GET['add_time_to']));
			if ($add_time_from !== false || $add_time_to !== false){
				$condition['add_time'] = array('time',array($add_time_from,$add_time_to));
			}
		}
		
		$refund_list = $model_refund->getRefundList($condition,10);
		$page_count = $model_refund->gettotalpage();	
		if (!empty($refund_list) && is_array($refund_list)){
			foreach ($refund_list as $k => $v) {
				$goods_info = array();
				$goods_info['goods_img_360'] = cthumb($v['goods_image'],60);
				$goods_info['goods_name'] = $v['goods_name'];
				$goods_info['goods_spec'] = $v['goods_spec'];
				$refund_list[$k]['goods_list'][] = $goods_info;
				$refund_list[$k]['add_time'] = date("Y-m-d H:i:s",$v['add_time']);
				$refund_list[$k]['refund_amount'] = imPriceFormat($v['refund_amount']);
				$refund_list[$k]['seller_state'] = $state_data[$v['seller_state']];
			}
		}
				
		output_data(array('refund_list'=>$refund_list),mobile_page($page_count));
	}
	
	/**
	 * 退款记录查看
	 *
	 */
	public function get_refund_infoOp(){
		$model_refund = Model('refund_return');
		$state_data = $model_refund->getRefundStateArray();
		
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['refund_id'] = intval($_GET['refund_id']);
		$refund_list = $model_refund->getRefundList($condition);
		$refund = $refund_list[0];
		
		$refund['seller_state'] = $state_data['seller'][$refund['seller_state']];
		$refund['admin_state'] = $state_data['admin'][$refund['refund_state']];

		$info['buyer'] = array();
	    if(!empty($refund['pic_info'])) {
	        $info = unserialize($refund['pic_info']);
	    }
		$pic_list = $info['buyer'];
		if (!empty($pic_list)){
			foreach ($pic_list as $k => $v) {
				if (empty($v)){
					unset($pic_list[$k]);
				}else{
				    $pic_list[$k] = UPLOAD_SITE_URL.DS.ATTACH_PATH.DS.'refund'.DS.$v;
				}
			}			
		}
		output_data(array('refund'=>$refund,'pic_list'=>$pic_list));
	}
	/**
	 * 上传凭证
	 *
	 */
    public function upload_picOP() {		
        /**
         * 上传店铺图片
        */
        if (!empty($_FILES['refund_pic']['name'])){
			$upload = new UploadFile();
			
            $upload->set('default_dir', ATTACH_PATH.DS.'refund'.DS);
			$upload->set('allow_type',array('jpg','jpeg','gif','png'));
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','');
            $upload->set('ifremove',false);
            $result = $upload->upfile('refund_pic');
            if ($result){
				$full_file = UPLOAD_SITE_URL.DS.ATTACH_PATH.DS.'refund'.DS.$upload->file_name;
                output_data(array('pic'=>$full_file,'file_name'=>$upload->file_name));
            }else {
                output_error($upload->error);
            }
        }else{
			output_error('请选择上传文件！');
		}
    }
}