<?php
/**
 * 图片空间操作
 *
 * @copyright  Copyright (c) 2007-2016 zlin-e Inc. (http://www.zlin-e.com)
 * @license    http://www.zlin-e.com
 * @link       http://www.zlin-e.com
 * @since      File available since Release v1.1
 */
defined('InIMall') or exit('Access Invalid!');
class sns_settingControl extends BaseSNSControl {
	public function __construct() {
		parent::__construct();
		/**
		 * 读取语言包
		 */
		Language::read('sns_setting');
	}
	public function change_skinOp(){
		Tpl::showpage('sns_changeskin', 'null_layout');
	}
	public function skin_saveOp(){
		$insert = array();
		$insert['member_id']	= $_SESSION['member_id'];
		$insert['setting_skin']	= $_GET['skin'];

		Model()->table('sns_setting')->insert($insert,true);
	}
}
?>