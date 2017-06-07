<?php
/**
 * 免费代金券
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

class voucherControl extends mobileHomeControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 代金券列表
     */
    public function voucher_tpl_listOp() {
		$voucher_model = Model('voucher');
		$param = array();
		$param['voucher_t_store_id'] = $_POST["store_id"];
		$param['voucher_t_state'] = 1;
		$model_voucher = Model('voucher');		
		$gettype_array = $model_voucher->getVoucherGettypeArray();
		$param['voucher_t_gettype'] = $gettype_array['free']['sign'];
		$voucher_list = $voucher_model->getVoucherTemplateList($param);
		if(!empty($voucher_list)){
			$model_voucher = Model('voucher');
			foreach($voucher_list as $key=>$value){
				$voucher_list[$key]['voucher_t_end_date_text'] = date('Y-m-d',$value['voucher_t_end_date']);
			}
		}

		output_data(array('voucher_list'=>$voucher_list));	
    }
	
	/**
     * 免费领取代金券
     */
    public function voucher_freeexOp() {
		$model_voucher = Model('voucher');
        $voucher_list = $model_voucher->getMemberVoucherList($this->member_info['member_id'], $_POST['voucher_state'], $this->page);
        $page_count = $model_voucher->gettotalpage();

        output_data(array('voucher_list' => $voucher_list), mobile_page($page_count));
    }
}
