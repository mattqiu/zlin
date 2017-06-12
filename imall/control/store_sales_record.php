<?php
/**
 * 销售记录
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

class store_sales_recordControl extends BaseSellerControl {
	public function __construct() {
		parent::__construct();
	}
	/**
	 * 销售记录列表页
	 *
	 */
	public function indexOp() {
		$model_sales_record = Model('order');		
		
		//分店列表
		$model_branch = Model('store');
		$condition=array();
		$condition['parent_id'] = $_SESSION['store_id'];
		$branch_list = $model_branch->getStoreList($condition);	
		Tpl::output('branch_list',$branch_list);
		
		$condition = array();
		$stores_id=array();
		$stores_id[]=$_SESSION['store_id'];
		if (!empty($branch_list)) { 
          foreach ($branch_list as $val) {
			$stores_id[]=$val['store_id'];
		  }
		}	
			
		if (intval($_GET['sales_id']) > 0) {
          $condition['order_goods.store_id'] = intval($_GET['sales_id']);
        }else{
		  $condition['order_goods.store_id'] = array('in', $stores_id);
		}		

		if (trim($_GET['keyword']) != '') {
            switch ($_GET['search_type']) {
                case 0:
                    $condition['goods_name'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 1:
                    $condition['order_sn'] = array('like', '%' . trim($_GET['keyword']) . '%');
                    break;
                case 2:
                    $condition['goods_id'] = intval($_GET['keyword']);
                    break;
            }
        }
		
		if (trim($_GET['add_time_from']) != '' || trim($_GET['add_time_to']) != '') {
			$add_time_from = strtotime(trim($_GET['add_time_from']));
			$add_time_to = strtotime(trim($_GET['add_time_to']));
			if ($add_time_from !== false || $add_time_to !== false) {
				$condition['add_time'] = array('time',array($add_time_from,$add_time_to));
			}
		}

		$sales_list = $model_sales_record->getOrderAndOrderGoodsSalesRecordList($condition,'*',20);
		Tpl::output('sales_list',$sales_list);
		Tpl::output('show_page',$model_sales_record->showpage());		
		
		self::profile_menu('index','index');
		Tpl::showpage('store_sales_record.index');
	}
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {
		$menu_array = array();
		switch ($menu_type) {
			case 'index':
				$menu_array = array(
					array('menu_key'=>'index','menu_name'=>'销售明细',	'menu_url'=>'index.php?act=store_return&lock=2'),
				);
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
