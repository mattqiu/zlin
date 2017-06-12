<?php
/**
 * 退货管理
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
class seller_distri_returnedControl extends BaseSellerControl {
	protected $Returned_appay_count = '';
	
    public function __construct() {
        parent::__construct ();
        Language::read ('member_store_goods_index, seller_branch');
		
		$model_apply = Model('branch_apply');
		//等处理退货申请数
		$this->Returned_appay_count = $model_apply->getApplyCount(array('bp_branch_id'=>$_SESSION['store_id'],'bp_type'=>2,'bp_dispose'=>array('neq',30) ));
		if ($this->Returned_appay_count<=0){
			$this->Returned_appay_count = '';
		}else{
			$this->Returned_appay_count = '('.$this->Returned_appay_count.')';
		}
    }
	/**
     * 退货单
     */
    public function indexOp() {
		$model_order = Model('branch_order');
		
		$where = array();
        $where['branch_id'] = $_SESSION['store_id'];
        if ($_GET['order_sn'] != '') {
            $where['order_sn'] = $_GET['order_sn'];
        }

        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $where['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }	
	
		$order_list = $model_order->getReturnedOrderList($where);
		if (!empty($order_list) && is_array($order_list)){
			$model_stubbs = Model('branch_stubbs');	
			$model_store = Model('store');
			foreach ($order_list as $key => $val) {
				$where = array();
				$where['b_order_sn'] = $val['order_sn'];
				$where['branch_id'] = $_SESSION['store_id'];
				$goods_list = $model_stubbs->getReturnedStubbsList($where);
				$order_list[$key]['goods_list']=$goods_list;
				$order_list[$key]['goods_count']=count($goods_list);
				
				$parent_info = $model_store->getStoreInfoByID($val['store_id']);
				$order_list[$key]['extend_parent']=$parent_info;
				
				$state_desc = '';
				$shipping_express_id = 0;
				$shipping_code = '';
				$if_cancel = false;
				$if_send = false;
				switch ($val['order_state']) {
				  case 0:
				    $state_desc = '已取消';
					break;
				  case 20:
				    $state_desc = '分店待发货';
					$if_cancel = true;
					$if_send = true;
					break;
				  case 30:
				    $state_desc = '分店已发货';
					$shipping_express_id = $val['tui_shipping_express_id'];
				    $shipping_code = $val['tui_shipping_code'];
					break;
				  case 40:
				    $state_desc = '总店已收货';
					$shipping_express_id = $val['tui_shipping_express_id'];
				    $shipping_code = $val['tui_shipping_code'];				    
					break;
				  case 50:
				    $state_desc = '总店已发货';
					$shipping_express_id = $val['shipping_express_id'];
				    $shipping_code = $val['shipping_code'];
					break;
				  case 60:
				    $state_desc = '分店已收货';
					$shipping_express_id = $val['shipping_express_id'];
				    $shipping_code = $val['shipping_code'];
					break;
				}
				$order_list[$key]['state_desc']=$state_desc;
				$order_list[$key]['shipping_express_id']=$shipping_express_id;
				$order_list[$key]['shipping_code']=$shipping_code;	
				
				$order_list[$key]['if_cancel']=$if_cancel;	
				$order_list[$key]['if_send']=$if_send;		
			}
		}
		
		Tpl::output('order_list',$order_list);
		Tpl::output('show_page', $model_order->showpage());
		
		$this->profile_menu('index');
        Tpl::showpage('seller_distri_returned.list');	
	}	
	
	/**
     * 分店退货申请
     */
    public function returnedapplyOp() {
		$model_apply = Model('branch_apply');
		
		$where = array();
        $where['bp_branch_id'] = $_SESSION['store_id'];
		
		$apply_list = $model_apply->getReturnedApplyList($where);
		Tpl::output('apply_list',$apply_list);
		Tpl::output('show_page', $model_apply->showpage());
		
		$this->profile_menu('returnedapply');
        Tpl::showpage('seller_distri_returnedapply.list');			
	}
	
	/**
     * 查看退货申请信息
     */
    public function returned_infoOp() {
		$bp_id = $_GET['id'];
		if (empty($bp_id)){
			showDialog('非法操作！', 'reload', 'succ');
		}
		$model_apply = Model('branch_apply');		
		
		$apply_info = $model_apply->getApplyInfo($bp_id);
		if (empty($apply_info) || !is_array($apply_info)){
			showDialog('没有找到审请信息！', 'reload', 'succ');
		}
		$id_list = array();
		$good_nums = array();
		foreach ($apply_info['goods_list'] as $k => $val) {
			if (!empty($val['b_id'])){
			    $id_list[] = $val['b_id'];
				$good_nums[$val['b_id']] = $val['nums'];
			}
		}
		$where = array();
		$where['goods_id'] = array('in',$id_list);
		$where['store_id'] = $_SESSION['store_id'];
		$goods_list = Model('goods')->getGoodsList($where,'goods_id,goods_name,goods_costprice,goods_price,goods_tradeprice,goods_storage,goods_image');
		foreach ($goods_list as $k => $val) {
			$goods_list[$k]['nums'] = $good_nums[$val['goods_id']];
		}				
		Tpl::output('goods_list',$goods_list);				
		Tpl::output('apply_info',$apply_info);		
		
		$this->profile_menu('returnedapply');
        Tpl::showpage('seller_distri_returnedapply.info', 'null_layout');	
	}	

	/**
     * 删除退货申请记录
     */
    public function returned_delOp() {
        $model_apply = Model('branch_apply');
		$bp_id = $_GET['id'];     
		
        $apply_info = $model_apply->getApplyInfo($bp_id,'bp_order_sn');		
		if (!empty($apply_info)){// && !empty($apply_info['bp_order_sn'])
			$order_sn = $apply_info['bp_order_sn'];
			//Model('branch_order')->delOrderInfo(array('order_sn'=>$order_sn,'store_id'=>$_SESSION['store_id'],'order_type'=>2));
			//Model('branch_stubbs')->delStubbsInfo(array('b_order_sn'=>$order_sn,'stubbs_type'=>2));
			
			$where = array();
            $where['bp_id'] = $bp_id;
			$where['bp_type'] = 2;
            $where['bp_branch_id'] = $_SESSION['store_id'];
			$return = $model_apply->delApplyInfo($where);
		}else{
			$return = false;
		}
        if ($return) {
            showDialog('退货申请删除成功！', 'reload', 'succ');
        } else {
            showDialog('退货申请删除失败！', '', 'error');
        }
    } 	
	
    /**
     * 用户中心右边，小导航
     * 
     * @param string $menu_type 导航类型
     * @param string $menu_key 当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key='') {
        $menu_array = array();	
		$menu_array[0] = array('menu_key' => 'index', 'menu_name' => '退货单', 'menu_url' => urlShop('seller_distri_returned', 'index'));
		$menu_array[1] = array('menu_key' => 'returnedapply', 'menu_name' => '退货申请'.$this->Returned_appay_count, 'menu_url' => urlShop('seller_distri_returned', 'returnedapply'));		
		
        Tpl::output ('member_menu', $menu_array);
        Tpl::output ('menu_key', $menu_key);
    }
}