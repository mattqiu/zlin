<?php
/**
 * 手机分类导航
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
class mb_navigationControl extends BaseSellerControl {
    public function __construct() {
        parent::__construct();
        Language::read('member_store_index');
    }

    public function navigation_listOp() {
        $model_mb_navigation = Model('mb_navigation');
        $navigation_list = $model_mb_navigation->getMobileNavigationList(array('mn_store_id' => $_SESSION['store_id']));
		Tpl::output('navigation_list', $navigation_list);
		self::profile_menu('mb_navigation');
		Tpl::showpage('mb_navigation.list');
    }

    public function navigation_addOp() {
		$editor_multimedia = false;
		if ($this->store_grade['sg_function'] == 'editor_multimedia') {
            $editor_multimedia = true;
        }		
		Tpl::output('editor_multimedia', $editor_multimedia);
        $this->profile_menu('navigation_add');
        Tpl::showpage('mb_navigation.form');
    }

    public function navigation_editOp() {
        $mn_id = intval($_GET['mn_id']);
        if($mn_id <= 0) {
           showMessage(L('wrong_argument'), urlShop('mb_navigation', 'navigation_list'), '', 'error');
        }
        $model_mb_navigation = Model('mb_navigation');
        $mn_info = $model_mb_navigation->getMobileNavigationInfo(array('mn_id' => $mn_id));
        if(empty($mn_info) || intval($mn_info['mn_store_id']) !== intval($_SESSION['store_id'])) {
           showMessage(L('wrong_argument'), urlShop('mb_navigation', 'navigation_list'), '', 'error');
        }
		$editor_multimedia = false;
		if ($this->store_grade['sg_function'] == 'editor_multimedia') {
            $editor_multimedia = true;
        }		
		Tpl::output('editor_multimedia', $editor_multimedia);
		
        Tpl::output('mn_info', $mn_info);
        $this->profile_menu('navigation_edit');
        Tpl::showpage('mb_navigation.form');
    }

    public function navigation_saveOp() {
        $mn_info = array(
            'mn_title' => $_POST['mn_title'],
			'mn_thumb' => $_POST['mn_thumb'],
            'mn_content' => $_POST['mn_content'],
			'mn_url' => $_POST['mn_url'],
			'mn_if_show' => $_POST['mn_if_show'],
            'mn_sort' => empty($_POST['mn_sort'])?255:$_POST['mn_sort'],
            'mn_store_id' => $_SESSION['store_id']     
        );
        $model_mb_navigation = Model('mb_navigation');
        if(!empty($_POST['mn_id']) && intval($_POST['mn_id']) > 0) {
            $this->recordSellerLog('编辑店铺导航，导航编号'.$_POST['mn_id']);
            $condition = array('mn_id' => $_POST['mn_id']);
            $result = $model_mb_navigation->editMobileNavigation($mn_info, $condition);
        } else {
            $result = $model_mb_navigation->addMobileNavigation($mn_info);
            $this->recordSellerLog('新增店铺导航，导航编号'.$result);
        }
        showDialog(L('im_common_op_succ'), urlShop('mb_navigation', 'navigation_list'), 'succ');
    }

    public function navigation_delOp() {
        $mn_id = intval($_POST['mn_id']);
        if($mn_id > 0) {
            $condition = array(
                'mn_id' => $mn_id,
                'mn_store_id' => $_SESSION['store_id']
            );
            $model_mb_navigation = Model('mb_navigation');
            $model_mb_navigation->delMobileNavigation($condition);
            $this->recordSellerLog('删除店铺导航，导航编号'.$mn_id);
            showDialog(L('im_common_op_succ'), urlShop('mb_navigation', 'navigation_list'), 'succ');
        } else {
            showDialog(L('im_common_op_fail'), urlShop('mb_navigation', 'navigation_list'), 'error');
        }
    }
	
	public function quick_linkOp() {
		Tpl::output('mn_id', $_GET['mn_id']);
		// 商品分类
        $store_goods_class = Model('store_goods_class')->getClassTree(array('store_id' => $_SESSION['store_id'], 'stc_state' => '1'));
        Tpl::output('store_goods_class', $store_goods_class);
		//专辑列表
		$special_list = Model('mb_special')->getMbSpecialList(array('special_type'=>1), $_SESSION['store_id']);
        Tpl::output('special_list', $special_list);
		
        Tpl::showpage('mb_quick_link_select','null_layout');	
    }

    /**
     * 用户中心右边，小导航
     *
     * @param string 	$menu_key	当前导航的menu_key
     * @return
     */
    private function profile_menu($menu_key = '') {
        $menu_array = array();
        $menu_array[] = array(
            'menu_key' => 'mb_navigation',
            'menu_name' => '导航列表',
            'menu_url' => urlShop('mb_navigation', 'navigation_list')
        );
        if($menu_key == 'navigation_add') {
            $menu_array[] = array(
                'menu_key' => 'navigation_add',
                'menu_name' => '添加导航',
                'menu_url' => urlShop('mb_navigation', 'navigation_add')
            );
        }
        if($menu_key == 'navigation_edit') {
            $menu_array[] = array(
                'menu_key' => 'navigation_edit',
                'menu_name' => '编辑导航',
                'menu_url' => urlShop('mb_navigation', 'navigation_edit')
            );
        }
        Tpl::output('member_menu', $menu_array);
        Tpl::output('menu_key', $menu_key);
    }

}