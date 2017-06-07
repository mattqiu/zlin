<?php
/**
 * 导购退换货
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
 
defined('InIMall') or exit('Access Invalid!');

class seller_refundControl extends BaseSellerControl {

	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * 退款记录列表页
	 *
	 */
	public function indexOp(){
		$model_refund = Model('refund_return');
		$state_data = $model_refund->getRefundStateArray('seller');
	
		$condition = array();
		$condition['store_id'] = $this->store_id;
	
		$keyword_type = array('order_sn','refund_sn','goods_name');
		if (trim($_REQUEST['keyword']) != '' && in_array($_REQUEST['type'],$keyword_type)){
			$type = $_REQUEST['type'];
			$condition[$type] = array('like','%'.$_REQUEST['key'].'%');
		}
		if (trim($_REQUEST['add_time_from']) != '' || trim($_REQUEST['add_time_to']) != ''){
			$add_time_from = strtotime(trim($_REQUEST['add_time_from']));
			$add_time_to = strtotime(trim($_REQUEST['add_time_to']));
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
				$refund_list[$k]['buyer_avatar'] = getMemberAvatarForID($value['buyer_id']);//购买人的头像
				$goods_num = intval($value['goods_num']);//退货数量
				if(empty($goods_num) || empty($value['goods_id'])){
					$refund_list[$k]['goods_num'] = $goods_num = 1;
					$refund_list[$k]['is_refund'] = "全部退款";
				}else{
					$refund_list[$k]['is_refund'] = "部分退款";
				}
			}
		}
		$refund_count = count($refund_list);
	
		output_data(array('refund_list'=>$refund_list,'refund_count'=>$refund_count),wxapp_page($page_count));
	}	
	
	/**
	 * 添加订单商品部分退款
	 *
	 */
	public function add_refundOp(){
		
		$model_refund = Model('refund_return');
		
		$order_id = intval($_REQUEST['order_id']);
		$goods_id = intval($_REQUEST['order_goods_id']);//订单商品表编号
		if (empty($order_id)|| empty($goods_id)) {//参数验证
			output_error('非法操作，没有查到该订单');
		}
		$condition = array();
		$condition['store_id'] = $this->store_id;
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
			output_error("订单不支持退换货~");
		}
		if ($goods_id > 0){
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
			$pic_array['buyer'] = $this->upload_pic();//上传凭证
			$info = serialize($pic_array);
			$refund_array['pic_info'] = $info;
	
			$model_trade = Model('trade');
			$order_shipped = $model_trade->getOrderState('order_shipped');//订单状态30:已发货
			if ($order['order_state'] == $order_shipped) {
				$refund_array['order_lock'] = '2';//锁定类型:1为不用锁定,2为需要锁定
			}
			if($_POST['refund_type']=='2'){
				$refund_array['return_type'] = 2;//需要退货
			}
			$refund_array['refund_type'] = $_POST['refund_type'];//类型:1为退款,2为退货
			if($order['order_state'] == $order_paid ){
				$refund_array['goods_state'] = '0';//0表示货还未发给客户
			}
			$refund_array['seller_state'] = '1';//状态:1为待审核,2为同意,3为不同意
			$refund_array['refund_amount'] = imPriceFormat($refund_amount);
			$refund_array['goods_num'] = $goods_num;
			$refund_array['buyer_message'] = $_POST['buyer_message'];
			$refund_array['add_time'] = time();
			$state = $model_refund->addRefundReturn($refund_array,$order,$goods);
			
			if ($state) {
				$model_refund->editOrderLock($order_id);//锁定订单
				output_error("退换货创建成功！");
			} else {
				output_error("退换货创建失败！");
			}
		}
	}
	/**
	 * 添加全部退款即取消订单
	 *
	 */
	public function add_refund_allOp(){
		$model_order = Model('order');
		$model_trade = Model('trade');
		$model_refund = Model('refund_return');
		$order_id = intval($_REQUEST['order_id']);
		$condition = array();
		$condition['store_id'] = $this->store_id;
		$condition['order_id'] = $order_id;
		$order = $model_refund->getRightOrderList($condition);//查询订单信息
		if(empty($order)){
			output_error("订单不存在！");
		}
		$order_amount = $order['order_amount'];//订单金额
		
		$refund_array = array();
		if($_POST['refund_type']=='2'){
			$refund_array['return_type'] = 2;//需要退货
		}
		$refund_array['refund_type'] = $_REQUEST['refund_type'];//类型:1为退款,2为退货
		$refund_array['seller_state'] = '1';//状态:1为待审核,2为同意,3为不同意
		$refund_array['order_lock'] = '2';//锁定类型:1为不用锁定,2为需要锁定
		$refund_array['goods_id'] = '0';
		$refund_array['order_goods_id'] = '0';
		$refund_array['reason_id'] = '0';
		$refund_array['reason_info'] = '取消订单，全部退款';
		$refund_array['goods_name'] = '订单商品全部退款';
		$refund_array['refund_amount'] = imPriceFormat($order_amount);
		$refund_array['buyer_message'] = $_REQUEST['buyer_message'];
		$refund_array['add_time'] = time();
		$order_paid = $model_trade->getOrderState('order_paid');//订单状态20:已付款
		if($order['order_state'] == $order_paid ){
			$refund_array['goods_state'] = '0';//0表示货还未发给客户
		}
		$pic_array = array();
		$pic_array['buyer'] = $this->upload_pic();//上传凭证
		$info = serialize($pic_array);
		$refund_array['pic_info'] = $info;
		$state = $model_refund->addRefundReturn($refund_array,$order);
		
		if ($state) {
			$model_refund->editOrderLock($order_id);//锁定订单
			output_error("退换货创建成功！");
		} else {
			output_error("退换货创建失败！");
		}		
	}

	/**
	 * 退款/退货审核 注：此处不管什么情况都需要将商品重新入库的
	 * @param store_id 订单ID
	 * @param refund_id 退换货记录ID
	 * @param refund_type 申请类型:1为退款,2为退货
	 * @param seller_state 卖家处理状态:1为待审核,2为同意,3为不同意
	 * @param seller_message 处理导购昵称+商家备注
	 * 说明：如果会员下单付款后，商家还未发货前，申请退款时，无需审核直接通过；
	 * 	         如果商家已经发货，客户还未显示收到货，申请退货时，需要审核是否通过，一般情况是直接通过的，但不排除客户收到了货并且试穿过，完成后商品还需入库
	 *     如果商家已经发货，客户已经收到货，申请退货时，需要审核是否通过，通过后还需要确定是否收到客户寄回的货，确定收到货后才可退款，还需入库
	 *     如果订单已经完成的情况下，客户申请退货时，需要审核是否通过（是否影响到二次销售），通过后还需要确定是否收到客户寄回的货，确定收到货后才可退款，还需入库
	 *     如果订单已经完成的情况下，并且超过15天，就不允许客户申请退货
	 */
	public function returnsAuditingOp() {
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['store_id'] = $this->store_id;
		$condition['refund_id'] = intval($_REQUEST['refund_id']);
		$condition['refund_type'] = intval($_REQUEST['refund_type']);//1、退款；2、退货
		$refund = $model_refund->getRefundReturnInfo($condition);
		if (!empty($refund)) {
			if ($refund['seller_state'] != '1') {//检查状态,防止页面刷新不及时造成数据错误
				output_error("订单已被商家审核");
			}
			$order_id = $refund['order_id'];
			$refund_array = array();
			$refund_array['seller_time'] = time();
			$refund_array['seller_state'] = $_POST['seller_state'];//卖家处理状态:1为待审核,2为同意,3为不同意
			if(!empty($_POST['seller_name'])){
				$seller_message = $_POST['seller_name']."备注：";
			}else{
				$seller_message = "店长备注：";
			}
			$refund_array['seller_message'] = $seller_message + $_POST['seller_message']; //商家备注
			if ($refund_array['seller_state'] == '2' && empty($_POST['return_type'])) {
				$refund_array['return_type'] = '1';//退货类型:1为不用退货,2为需要退货
			} elseif ($refund_array['seller_state'] == '3') {//不同意
				$refund_array['refund_state'] = '3';//状态:1为处理中,2为待管理员处理,3为已完成
				//如果用户申请了退货，并且该订单状态是 客户已经确定收货，那么就要锁定该订单赠送给客户的返利（积分、云币）由于云币是无法锁定所以只能暂时扣减
				//商家不同意退货，需要云币再退还给客户另外，还需要释放暂时锁住 返佣的订单 zhangc
				Logic('order')->changeOrderCancelApplyRefund($order_id,'seller', '店长','收到用户退货');
			} else {
				$refund_array['seller_state'] = '2';//同意申请
				$refund_array['refund_state'] = '2';//申请类型:1为退款,2为退货
				$refund_array['return_type'] = '2';//需要退货，并且已经收到货
			}
			$state = $model_refund->editRefundReturn($condition, $refund_array);
			if ($state) {
				if ($refund_array['seller_state'] == '3' && $refund['order_lock'] == '2') {//不同意并且订单已经被锁定
					$model_refund->editOrderUnlock($order_id);//订单解锁
				}
				$this->recordSellerLog('退款处理，退款编号：'.$refund['refund_sn']);
				// 发送买家消息
				$param = array();
				$param['code'] = 'refund_return_notice';
				$param['member_id'] = $refund['buyer_id'];
				$param['param'] = array(
						'refund_url'=> urlShop('member_refund', 'view', array('refund_id' => $refund['refund_id'])),
						'refund_sn' => $refund['refund_sn']
				);
				QueueClient::push('sendMemberMsg', $param);
				output_error("订单操作成功");
			} else {
				output_error("订单操作失败");
			}
		}else{
			output_error("订单不符该审核条件，所以无法完成此操作");
		}		
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
		$condition['refund_id'] = intval($_REQUEST['refund_id']);
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
	public function upload_pic() {
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
			//商家处理可以不需要凭证 
			//output_error('请选择上传文件！');
		}
	}
}