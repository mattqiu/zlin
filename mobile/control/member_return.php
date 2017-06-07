<?php
/**
 * 买家退货
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

class member_returnControl extends mobileMemberControl {
	public function __construct(){
		parent::__construct();			
	}

	/**
	 * 退款退货记录列表页
	 *
	 */
	public function get_return_listOp(){
		$model_return = Model('refund_return');
		
		$model_return = Model('refund_return');
		$state_data = $model_return->getRefundStateArray('seller');	
		
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
		
		$return_list = $model_return->getReturnList($condition,10);
		$page_count = $model_return->gettotalpage();	
		if (!empty($return_list) && is_array($return_list)){
			foreach ($return_list as $k => $v) {
				$return_list[$k]['goods_img_360'] = cthumb($v['goods_image'],60);
				$return_list[$k]['goods_list'][] = $goods_info;
				$return_list[$k]['add_time'] = date("Y-m-d H:i:s",$v['add_time']);
				$return_list[$k]['refund_amount'] = imPriceFormat($v['refund_amount']);
				$return_list[$k]['seller_state'] = $state_data[$v['seller_state']];
			}
		}

		output_data(array('return_list'=>$return_list),mobile_page($page_count));
	}
	
	/**
	 * 退货记录查看
	 *
	 */
	public function get_return_infoOp(){
		$model_return = Model('refund_return');
		$state_data = $model_return->getRefundStateArray();	
		
		$condition = array();
		$condition['buyer_id'] = $_SESSION['member_id'];
		$condition['refund_id'] = intval($_GET['return_id']);
		$return_list = $model_return->getReturnList($condition);
		$return_info = $return_list[0];
		
		$return_info['seller_state'] = $state_data['seller'][$return_info['seller_state']];
		$return_info['admin_state'] = $state_data['admin'][$return_info['refund_state']];

		$info['buyer'] = array();
	    if(!empty($return_info['pic_info'])) {
	        $info = unserialize($return_info['pic_info']);
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
		output_data(array('return_info'=>$return_info,'pic_list'=>$pic_list));
	}

}