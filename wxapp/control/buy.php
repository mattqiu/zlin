<?php
/**
 * 购买流程
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class buyControl extends BaseBuyControl {

    public function __construct() {
        parent::__construct();
        Language::read('home_cart_index');
        if (!$_REQUEST['member_id']){
            redirect('index.php?act=login&ref_url='.urlencode(request_uri()));
        }
        //验证该会员是否禁止购买
        if(!$_REQUEST['is_buy']){
            showMessage(Language::get('cart_buy_noallow'),'','html','error');
        }
        Tpl::output('hidden_rtoolbar_cart', 1);
    }


    /**
     * 生成订单
     *
     */
    public function buy_step2Op() {
        $logic_buy = logic('buy');
		
        $result = $logic_buy->buyStep2($_REQUEST, $_REQUEST['member_id'], $_REQUEST['member_name'], $_REQUEST['member_email']);
        if(!$result['state']) {
            showMessage($result['msg'], 'index.php?act=cart', 'html', 'error');
        }

        //转向到商城支付页面
		if ($result['data']['pay_sn_total']>1){
		  redirect('index.php?act=member_order');
		}else{
          redirect('index.php?act=buy&op=pay&pay_sn='.$result['data']['pay_sn']);
		}
    }


    /**
     * 得到所购买的id和数量
     *
     */
    private function _parseItems($cart_id) {
        //存放所购商品ID和数量组成的键值对
        $buy_items = array();
        if (is_array($cart_id)) {
            foreach ($cart_id as $value) {
                if (preg_match_all('/^(\d{1,10})\|(\d{1,6})$/', $value, $match)) {
                    $buy_items[$match[1][0]] = $match[2][0];
                }
            }
        }
        return $buy_items;
    }
}