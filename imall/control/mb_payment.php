<?php
/**
 * 手机支付方式
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
class mb_paymentControl extends BaseSellerControl{
    public function __construct(){
        parent::__construct();
    }

    public function indexOp() {
        $this->payment_listOp();
    }

    public function payment_listOp() {
        $model_mb_payment = Model('mb_payment');
        $mb_payment_list = $model_mb_payment->getMbPaymentList(array(),$_SESSION['store_id'],'shop');
		if (empty($mb_payment_list) || !is_array($mb_payment_list)){
			$model_mb_payment->InitPayment($_SESSION['store_id']);
			$mb_payment_list = $model_mb_payment->getMbPaymentList(array(),$_SESSION['store_id'],'shop');
		}
        Tpl::output('mb_payment_list', $mb_payment_list);
		
		self::profile_menu('mb_payment');
        Tpl::showpage('mb_payment.list');
    }

    /**
     * 编辑
     */
    public function payment_editOp() {
        $payment_id = intval($_GET["payment_id"]);

        $model_mb_payment = Model('mb_payment');

        $mb_payment_info = $model_mb_payment->getMbPaymentInfo(array('payment_id' => $payment_id),$_SESSION['store_id'],'shop');
        Tpl::output('payment', $mb_payment_info);		
		
		self::profile_menu('mb_payment');
        Tpl::showpage('mb_payment.edit');
    }

    /**
     * 编辑保存
     */
    public function payment_saveOp() {
        $payment_id = intval($_POST["payment_id"]);

        $data = array();
        $data['payment_state'] = intval($_POST["payment_state"]);

        switch ($_POST['payment_code']) {
            case 'alipay':
                $payment_config = array(
                    'alipay_account' => $_POST['alipay_account'],
                    'alipay_key'     => $_POST['alipay_key'],
                    'alipay_partner' => $_POST['alipay_partner'],
					'alipay_server'  => $_POST['alipay_server'],
                );
                break;
            case 'wxpay':
                $payment_config = array(
				    'wxpay_appid'      => $_POST['wxpay_appid'],
					'wxpay_appsecret'  => $_POST['wxpay_appsecret'],				
					'wxpay_mchid'      => $_POST['wxpay_mchid'],
					'wxpay_mchkey'     => $_POST['wxpay_mchkey'],					
                );
                break;
			case 'llpay':
                $payment_config = array(
				    'llpay_partner' => $_POST['llpay_partner'],
					'llpay_encrypt' => $_POST['llpay_encrypt'],				
                    'llpay_rsa_key' => $_POST['llpay_rsa_key'],
                    'llpay_md5_key' => $_POST['llpay_md5_key'],		
                );
                break;
            default:
                break;
        }
        $data['payment_config'] = $payment_config;

        $model_mb_payment = Model('mb_payment');

        $result = $model_mb_payment->editMbPayment($data, array('payment_id' => $payment_id));
        if($result) {
			showDialog(Language::get('im_common_save_succ'), urlShop('mb_payment', 'payment_list'),'succ');
        } else {
            showDialog(Language::get('im_common_save_fail'), urlShop('mb_payment', 'payment_list'));
        }
    }
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_key='') {
		Language::read('member_layout');
        $menu_array = array(
            1=>array('menu_key'=>'mb_payment','menu_name'=>'手机支付','menu_url'=>'index.php?act=mb_payment'),
        );
        Tpl::output('member_menu',$menu_array);
        Tpl::output('menu_key',$menu_key);
    }
}
