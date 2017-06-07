<?php
/**
 * 促销活动
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

class promotionControl extends mobileControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 代金券列表
     */
    public function voucher_listOp() {
		//判断系统是否开启代金券功能
		if (C('voucher_allow') != 1){
			output_error('系统未开启代金券功能');
		}
		$store_id = $_GET['id'];
		$model_voucher = Model('voucher');
		//代金券模板状态		
		$templatestate_arr = $model_voucher->getTemplateState();
		
		$where = array();
		$where['voucher_t_state'] = $templatestate_arr['usable'][0];
		$where['voucher_t_end_date'] = array('gt',TIMESTAMP);
		if ($store_id>0){
			$where['voucher_t_store_id'] = $store_id;
		}
		
		$orderby = 'voucher_t_id desc';
		$voucher_list = $model_voucher->getVoucherTemplateList($where, '*', 0, 10, $orderby);
        $page_count = $model_voucher->gettotalpage();

        output_data(array('voucher_list' => $voucher_list), mobile_page($page_count));
    }
	
	/**
	 * 兑换代金券保存信息
	 *
	 */
	public function voucherexchangeOp(){
		$model_mb_user_token = Model('mb_user_token');
        $key = $_POST['key'];
        if(empty($key)) {
            $key = $_GET['key'];
        }
        $mb_user_token_info = $model_mb_user_token->getMbUserTokenInfoByToken($key);
        if(empty($mb_user_token_info)) {
            output_error('请登录');
        }

        $model_member = Model('member');
        $this->member_info = $model_member->getMemberInfoByID($mb_user_token_info['member_id']);
        if(empty($this->member_info)) {
            output_error('请登录');
        }
		//读取卖家信息
        $seller_info = Model('seller')->getSellerInfo(array('member_id'=>$this->member_info['member_id']));
        $this->member_info['myself_store_id'] = $seller_info['store_id'];
		
		$vid = intval($_POST['vid']);
		if ($vid <= 0){
			output_error('非法操作!');
		}
		$model_voucher = Model('voucher');
		//验证是否可以兑换代金券
		$data = $model_voucher->getCanChangeTemplateInfo($vid,intval($this->member_info['member_id']),intval($this->member_info['myself_store_id']));
		if ($data['state'] == false){
			output_error($data['msg']);
		}
		//添加代金券信息
		$data = $model_voucher->exchangeVoucher($data['info'],$this->member_info['member_id'],$this->member_info['member_name']);
		if ($data['state'] == true){
			$datas = array();
		    $datas['exchange_info'] = $data['msg'];		
		    output_data($datas);
		} else {
		    output_error($data['msg']);
		}
	}
}
