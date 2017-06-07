<?php
/**
 * 合作伙伴管理
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
class linkControl extends SystemControl{
	public function __construct(){
		parent::__construct();
		Language::read('link');
	}
	/**
	 * 首页
	 */
	public function indexOp(){
		$this->linkOp();
	}
	/**
	 * 合作伙伴
	 */
	public function linkOp(){
		Tpl::setDirquna('system');		
		Tpl::showpage('link.index');		
	}
	
	/**
     * 输出XML数据
     */
    public function get_xmlOp() {
        $model_link = Model('link');		
		
        $condition = array();
		//快速搜索
        if ($_POST['query'] != '') {
            $condition[$_POST['qtype']] = array('like', '%' . $_POST['query'] . '%');
        }
		//分页数
        $page = $_POST['rp'];
		
		$link_list = $model_link->getLinkList($condition,$page);
		
        $data = array();
        $data['now_page'] = $model_link->shownowpage();
        $data['total_num'] = $model_link->gettotalnum();
        foreach ($link_list as $value) {
            $param = array();
			$param['operation'] = "<a class='btn red' href='javascript:void(0);' onclick=\"op_del(".$value['link_id'].")\"><i class='fa fa-trash-o'></i>删除</a>";
			$param['operation'] .= "<a class='btn blue' href='javascript:void(0);' onclick=\"op_edit(".$value['link_id'].")\"><i class='fa fa-pencil-square-o'></i>编辑</a>";
            $param['link_sort'] = $value['link_sort'];
			$param['link_title'] = $value['link_title'];
            $param['link_pic'] = "<img src=".UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/common'.DS.$value['link_pic']." class='user-avatar' onMouseOut='toolTip()' onMouseOver='toolTip(\"<img src=".UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/common'.DS.$value['link_pic'].">\")'>";
			$param['link_url'] = $value['link_url'];
   
            $data['list'][$value['link_id']] = $param;
        }
        echo Tpl::flexigridXML($data);exit();
    }
	
	/**
	 * 合作伙伴删除
	 */
	public function link_delOp(){
		$lang	= Language::getLangContent();
		if (intval($_GET['link_id']) > 0){
			$model_link = Model('link');
			/**
			 * 删除图片
			 */
			$tmp = $model_link->getOneLink(intval($_GET['link_id']));
			if (!empty($tmp['link_pic'])){
				@unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.$tmp['link_pic']);
			}
			$model_link->del($tmp['link_id']);
			//delCacheFile('link');
			exit(json_encode(array('state'=>true,'msg'=>'删除成功！')));
		}else {
			exit(json_encode(array('state'=>false,'msg'=>'无效参数！')));
		}
	}
	
	/**
	 * 合作伙伴 添加
	 */
	public function link_addOp(){
		$lang	= Language::getLangContent();
		$model_link = Model('link');
		if ($_POST['form_submit'] == 'ok'){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["link_title"], "require"=>"true", "message"=>$lang['link_add_title_null']),
				//array("input"=>$_POST["link_url"], "require"=>"true", 'validator'=>'Url', "message"=>$lang['link_add_url_wrong']),
				array("input"=>$_POST["link_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['link_add_sort_int']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				/**
				 * 上传图片
				 */				 
				if ($_FILES['link_pic']['name'] != ''){
					$upload = new UploadFile();
					$upload->set('default_dir',ATTACH_COMMON);
					
					$result = $upload->upfile('link_pic');
					if ($result){
						$_POST['link_pic'] = $upload->file_name;
					}else {
						showMessage($upload->error);
					}
				}
				
				$insert_array = array();
				$insert_array['link_title'] = trim($_POST['link_title']);
				$insert_array['link_url'] = trim($_POST['link_url']);
				$insert_array['link_pic'] = trim($_POST['link_pic']);
				$insert_array['link_sort'] = trim($_POST['link_sort']);
				
				$result = $model_link->add($insert_array);
				if ($result){
					//delCacheFile('link');
					$url = array(
						array(
							'url'=>'index.php?act=link&op=link_add',
							'msg'=>$lang['link_add_again'],
						),
						array(
							'url'=>'index.php?act=link&op=link',
							'msg'=>$lang['link_add_back_to_list'],
						)
					);
					showMessage($lang['link_add_succ'],$url);
				}else {
					showMessage($lang['link_add_fail']);
				}
			}
		}
		
		Tpl::setDirquna('system');	
		Tpl::showpage('link.add');
	}
	
	/**
	 * 合作伙伴 编辑
	 */
	public function link_editOp(){
		$lang	= Language::getLangContent();
		$model_link = Model('link');
		
		if ($_POST['form_submit'] == 'ok'){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>$_POST["link_title"], "require"=>"true", "message"=>$lang['link_add_title_null']),
				//array("input"=>$_POST["link_url"], "require"=>"true", 'validator'=>'Url', "message"=>$lang['link_add_url_wrong']),
				array("input"=>$_POST["link_sort"], "require"=>"true", 'validator'=>'Number', "message"=>$lang['link_add_sort_int']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				/**
				 * 上传图片
				 */
				if ($_FILES['link_pic']['name'] != ''){
					$upload = new UploadFile();
					$upload->set('default_dir',ATTACH_PATH.'/common');
					
					$result = $upload->upfile('link_pic');
					if ($result){
						$_POST['link_pic'] = $upload->file_name;
					}else {
						showMessage($upload->error);
					}
				}
				
				$update_array = array();
				$update_array['link_id'] = intval($_POST['link_id']);
				$update_array['link_title'] = trim($_POST['link_title']);
				$update_array['link_url'] = trim($_POST['link_url']);
				if ($_POST['link_pic']){
					$update_array['link_pic'] = $_POST['link_pic'];
				}
				$update_array['link_sort'] = trim($_POST['link_sort']);
				
				$result = $model_link->update($update_array);
				if ($result){
					//delCacheFile('link');
					/**
					 * 删除图片
					 */
					if (!empty($_POST['link_pic']) && !empty($_POST['old_link_pic'])){
						@unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.$_POST['old_link_pic']);
					}
					$url = array(
						array(
							'url'=>'index.php?act=link&op=link_edit&link_id='.intval($_POST['link_id']),
							'msg'=>$lang['link_edit_again']
						),
						array(
							'url'=>'index.php?act=link&op=link',
							'msg'=>$lang['link_add_back_to_list'],
						)
					);
					showMessage($lang['link_edit_succ'],$url);
				}else {
					showMessage($lang['link_edit_fail']);
				}
			}
		}
		
		$link_array = $model_link->getOneLink(intval($_GET['link_id']));
		if (empty($link_array)){
			showMessage($lang['wrong_argument']);
		}
		
		Tpl::output('link_array',$link_array);
		Tpl::setDirquna('system');	
		Tpl::showpage('link.edit');
	}
}
