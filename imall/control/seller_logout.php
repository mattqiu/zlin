<?php
/**
 * 店铺卖家注销
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

class seller_logoutControl extends BaseSellerControl {

	public function __construct() {
		parent::__construct();
	}

    public function indexOp() {
        $this->logoutOp();
    }

    public function logoutOp() {
        $this->recordSellerLog('注销成功');
        // 清除店铺消息数量缓存
        setIMCookie('storemsgnewnum'.$_SESSION['seller_id'],0,-3600);
        session_destroy();
        redirect('index.php?act=seller_login');
    }

}