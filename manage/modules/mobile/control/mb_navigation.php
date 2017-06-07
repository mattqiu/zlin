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

class mb_navigationControl extends SystemControl {
	const DEFAULT_NAVIGATION_INDEX = 0;
	
    public function __construct() {
        parent::__construct();
		Language::read('mobile');
    }
	
	public function indexOp() {
        $this->navigation_listOp();
    }

    public function navigation_listOp() {
        $model_mb_navigation = Model('mb_navigation');
        $navigation_list = $model_mb_navigation->getMobileNavigationList(array('mn_store_id' => DEFAULT_NAVIGATION_INDEX));
		
		Tpl::output('navigation_list', $navigation_list);
		
		Tpl::setDirquna('mobile');
		Tpl::showpage('mb_navigation.list');
    }

    public function navigation_addOp() {
        Tpl::setDirquna('mobile');
        Tpl::showpage('mb_navigation.edit');
    }

    public function navigation_editOp() {
        $mn_id = intval($_GET['mn_id']);
        if($mn_id <= 0) {
           showDialog('非法操作', urlAdminMobile('mb_navigation', 'navigation_list'), '', 'error');
        }
        $model_mb_navigation = Model('mb_navigation');
        $mn_info = $model_mb_navigation->getMobileNavigationInfo(array('mn_id' => $mn_id));
        if(empty($mn_info) || intval($mn_info['mn_store_id']) != DEFAULT_NAVIGATION_INDEX) {
           showDialog('非法操作', urlAdminMobile('mb_navigation', 'navigation_list'), '', 'error');
        }	
        Tpl::output('mn_info', $mn_info);
		
        Tpl::setDirquna('mobile');
        Tpl::showpage('mb_navigation.edit');
    }

    public function navigation_saveOp() {
		//上传图片
        if ($_FILES['mn_thumb']['name'] != ''){
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_MOBILE.'/category');

            $result = $upload->upfile('mn_thumb');
            if ($result){
                $_POST['mn_thumb'] = $upload->file_name;
            }else {
                showDialog($upload->error);
            }
        }
			
        $mn_info = array(
            'mn_title' => $_POST['mn_title'],
			'mn_thumb' => $_POST['mn_thumb'],
            'mn_content' => $_POST['mn_content'],
			'mn_url' => $_POST['mn_url'],
			'mn_if_show' => $_POST['mn_if_show'],
            'mn_sort' => empty($_POST['mn_sort'])?255:$_POST['mn_sort'],
            'mn_store_id' => DEFAULT_NAVIGATION_INDEX     
        );
        $model_mb_navigation = Model('mb_navigation');
        if(!empty($_POST['mn_id']) && intval($_POST['mn_id']) > 0) {
            $condition = array('mn_id' => $_POST['mn_id']);
            $result = $model_mb_navigation->editMobileNavigation($mn_info, $condition);
        } else {
            $result = $model_mb_navigation->addMobileNavigation($mn_info);
        }
        showDialog('操作成功', urlAdminMobile('mb_navigation', 'navigation_list'), 'succ');
    }

    public function navigation_delOp() {
        $mn_id = intval($_GET['mn_id']);
        if($mn_id > 0) {
            $condition = array(
                'mn_id' => $mn_id,
                'mn_store_id' => DEFAULT_NAVIGATION_INDEX
            );
            $model_mb_navigation = Model('mb_navigation');
            $model_mb_navigation->delMobileNavigation($condition);

            showDialog('操作成功', urlAdminMobile('mb_navigation', 'navigation_list'), 'succ');
        } else {
            showDialog('操作失败', urlAdminMobile('mb_navigation', 'navigation_list'), 'error');
        }
    }
	
	public function quick_linkOp() {
		//专辑列表
		$special_list = Model('mb_special')->getMbSpecialList();
        Tpl::output('special_list', $special_list);
		
		Tpl::setDirquna('mobile');
        Tpl::showpage('mb_quick_link_select','null_layout');	
    }
}