<?php
/**
 * 店铺卖家注销
 *
 * 
 *
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://demo.hzlwo.com)
 * @license    http://demo.hzlwo.com
 * @link       http://demo.hzlwo.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');

class supplier_logoutControl extends BaseSupplierControl {

	public function __construct() {
		parent::__construct();
	}

    public function indexOp() {
        $this->logoutOp();
    }

    public function logoutOp() {
        $this->recordSupplierLog('注销成功');
        // 清除店铺消息数量缓存
        setIMCookie('storemsgnewnum'.$_SESSION['supplier_id'],0,-3600);
        session_destroy();
        redirect('index.php?act=supplier_login');
    }

}