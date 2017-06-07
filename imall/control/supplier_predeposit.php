<?php
/**
 *
 *
 */



defined('InIMall') or exit('Access Invalid!');
class supplier_predepositControl extends BaseSupplierControl {

    public function __construct() {
        parent::__construct() ;

        //读取语言包
        Language::read('member_layout,promotion_xianshi');
        //检查限时折扣是否开启
        if (intval(C('promotion_allow')) !== 1){
            showMessage(Language::get('promotion_unavailable'),'index.php?act=store','','error');
        }

    }

    public function indexOp() {
        Tpl::showpage('store_predeposit.index');
    }

    /**
     * 发布的限时折扣活动列表
     **/
    public function pd_logOp() {
        
        Tpl::showpage('store_predeposit.pd_log');
    }
	
    public function pd_cash_listOp() {
    	Tpl::showpage('store_predeposit.pd_cash_list');
    }
    
    public function pd_pswOp() {
    	Tpl::showpage('store_predeposit.pd_psw');
    }
    
}
