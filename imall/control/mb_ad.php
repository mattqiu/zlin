<?php
/**
 * 手机端广告
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
class mb_adControl extends BaseSellerControl{
	public function __construct(){
		parent::__construct();
		Language::read('member_store_index');
	}
	/**
	 * 
	 */
	public function indexOp(){
		$model_mb_ad = Model('mb_ad');

		$link_list = $model_mb_ad->getMbAdList(array(),null,'link_id asc','*',$_SESSION['store_id']);
		Tpl::output('link_list',$link_list);
         
		self::profile_menu('store_mb_ad','store_mb_ad');
		Tpl::showpage('mb_ad.list');
	}
	
	/**
	 * 添加
	 */
	public function mb_ad_addOp(){
		$model_mb_ad = Model('mb_ad');
		//最多发布6条
		$count = $model_mb_ad->getMbAdCount($_SESSION['store_id']);
		if ($count > 4){
			showMessage(L('link_add_count_limit'));
		}
		if ($_POST['form_submit'] == 'ok'){
			/**
			 * 验证
			 */			 
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["link_title"], "require"=>"true", "message"=>'广告标题不能为空'),
				array("input"=>$_POST["link_sort"], "require"=>"true", 'validator'=>'Number', "message"=>'序号必须是数字'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showDialog($error);
			}else {
				/**
				 * 上传图片
				 */
				if ($_FILES['link_pic']['name'] != ''){
					$upload = new UploadFile();
					$upload->set('default_dir',ATTACH_MOBILE.'/ad');
					
					$result = $upload->upfile('link_pic');
					if ($result){
						$_POST['link_pic'] = $upload->file_name;
					}else {
						showMessage($upload->error);
					}
				}

				$insert_array = array();
				$insert_array['link_title'] = trim($_POST['link_title']);
				$insert_array['link_keyword'] = trim($_POST['link_keyword']);
				$insert_array['link_pic'] = trim($_POST['link_pic']);
				$insert_array['link_sort'] = trim($_POST['link_sort']);
				$insert_array['store_id'] = $_SESSION['store_id'];

				$result = $model_mb_ad->addMbAd($insert_array);
				if ($result){
					showDialog('广告添加成功！','index.php?act=mb_ad&op=mb_ad_list','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
				}else {
					showDialog('广告添加失败！');
				}
			}
		}
		
		Tpl::showpage('mb_ad.add', 'null_layout');
	}
	
		/**
	 * 编辑
	 */
	public function mb_ad_editOp(){
		$model_mb_ad = Model('mb_ad');
		if ($_POST['form_submit'] == 'ok'){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["link_title"], "require"=>"true", "message"=>'广告标题不能为空'),
				array("input"=>$_POST["link_sort"], "require"=>"true", 'validator'=>'Number', "message"=>'序号必须是数字'),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showDialog($error);
			}else {
				/**
				 * 上传图片
				 */
				if ($_FILES['link_pic']['name'] != ''){
					$upload = new UploadFile();
					$upload->set('default_dir',ATTACH_MOBILE.'/ad');
					
					$result = $upload->upfile('link_pic');
					if ($result){
						$_POST['link_pic'] = $upload->file_name;
					}else {
						showDialog($upload->error);
					}
				}
				$link_array = $model_mb_ad->getMbAdInfoByID(intval($_POST['link_id']));
				$update_array = array();
				$update_array['link_title'] = trim($_POST['link_title']);
				$update_array['link_keyword'] = trim($_POST['link_keyword']);
				if ($_POST['link_pic']){
					$update_array['link_pic'] = $_POST['link_pic'];
				}
				$update_array['link_sort'] = trim($_POST['link_sort']);
				$update_array['store_id'] = $_SESSION['store_id'];

                $result = $model_mb_ad->editMbAd($update_array, array('link_id' => intval($_POST['link_id'])));
				if ($result){
					//删除图片
				    if (!empty($_POST['link_pic']) && !empty($link_array['link_pic'])){
				        @unlink(BASE_ROOT_PATH.DS.DIR_UPLOAD.DS.ATTACH_MOBILE.'/ad'.DS.$link_array['link_pic']);
				    }
					showDialog('广告添加成功！','index.php?act=mb_ad&op=mb_ad_list','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
				}else {
					showDialog('广告添加失败！');
				}
			}
		}
		
		$link_array = $model_mb_ad->getMbAdInfoByID(intval($_GET['link_id']));
		if (empty($link_array)){
			showDialog('无效参数！');
		}

		Tpl::output('link_array',$link_array);
		Tpl::showpage('mb_ad.edit', 'null_layout');
	}

	/**
	 * 广告删除
	 */
	public function mb_ad_delOp(){
        $link_id = intval($_GET['link_id']);
		if ($link_id > 0){
			$model_mb_ad = Model('mb_ad');

			//删除图片
			$model_mb_ad->delMbAd($link_id);
			showDialog('广告删除成功！','index.php?act=mb_ad&op=mb_ad_list');
		}else {
			showDialog('广告删除失败！','index.php?act=mb_ad&op=mb_ad_list');
		}
	}
	
	
	

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 合作伙伴 排序
			 */
			case 'link_sort':
				$model_mb_ad = Model('mb_ad');
				$update_array = array();
				$update_array[$_GET['column']] = trim($_GET['value']);
                $condition = array();
				$condition['link_id'] = intval($_GET['id']);
				$result = $model_mb_ad->editMbAd($update_array, $condition);
				echo 'true';exit;
				break;
		}
	}
	
	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {
		Language::read('member_layout');
		$menu_array		= array();
		switch ($menu_type) {
			case 'store_mb_ad':
				$menu_array = array(
				1=>array('menu_key'=>'store_mb_ad','menu_name'=>'手机端广告',	'menu_url'=>'index.php?act=mb_ad&op=index'));
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}
}
