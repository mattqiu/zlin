<?php
/**
 * 店铺支付方式
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class seller_paymentControl extends BaseSellerControl {

    public function __construct() {
    	parent::__construct() ;
    	Language::read('member_layout');
    }

	/**
	 * 支付列表
	 *
	 */
    public function indexOp() {
        $model_payment = Model('payment');
		$payment_list = $model_payment->getPaymentList(array(),$_SESSION['store_id'],'shop');
		if (empty($payment_list) || !is_array($payment_list)){
			$model_payment->InitPayment($_SESSION['store_id']);
			$payment_list = $model_payment->getPaymentList(array(),$_SESSION['store_id'],'shop');
		}
		$menu_array = array(1=>array('menu_key'=>'list','menu_name'=>'支付方式', 'menu_url'=>'index.php?act=seller_payment&op=index'),);		
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key','list');
		
		Tpl::output('payment_list',$payment_list);
        Tpl::showpage('seller_payment.index');
    }

	/**
	 * 编辑
	 */
	public function editOp(){

		$model_payment = Model('payment');
		if (chksubmit()){
			$payment_id = intval($_POST["payment_id"]);
			$data = array();
			$data['payment_state'] = intval($_POST["payment_state"]);

			$payment_config	= '';
			$config_array = explode(',',$_POST["config_name"]);//配置参数
			if(is_array($config_array) && !empty($config_array)) {
				$config_info = array();
				foreach ($config_array as $k) {
					$config_info[$k] = trim($_POST[$k]);
				}
				$payment_config	= serialize($config_info);
			}
			$data['payment_config'] = $payment_config;//支付接口配置信息
			$model_payment->editPayment($data,array('payment_id'=>$payment_id,'store_id'=>$_SESSION['store_id']));
			showDialog(Language::get('im_common_save_succ'),'index.php?act=seller_payment&op=index','succ');
		}

		$payment_id = intval($_GET["payment_id"]);
		$payment = $model_payment->getPaymentInfo(array('payment_id'=>$payment_id),$_SESSION['store_id'],'shop');
		if ($payment['payment_config'] != ''){
			Tpl::output('config_array',unserialize($payment['payment_config']));
		}
		$menu_array = array(1=>array('menu_key'=>'list','menu_name'=>'支付方式', 'menu_url'=>'index.php?act=seller_payment&op=index'),
		                    2=>array('menu_key'=>'edit','menu_name'=>'编辑', 'menu_url'=>'index.php?act=seller_payment&op=edit&payment_id='.$payment_id),
		                   );		
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key','edit');
		
		Tpl::output('payment',$payment);
		Tpl::showpage('seller_payment.edit');
	}
}