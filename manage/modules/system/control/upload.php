<?php
/**
 * 上传设置
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

class uploadControl extends SystemControl{
    private $links = array(
        array('url'=>'act=upload&op=param','lang'=>'upload_param'),
        array('url'=>'act=upload&op=default_thumb','lang'=>'default_thumb'),
        array('url'=>'act=upload&op=login','lang'=>'loginSettings'),
		array('url'=>'act=upload&op=upload_statics','lang'=>'upload_statics'),
    );
    public function __construct(){
        parent::__construct();
        Language::read('setting');
    }

    public function indexOp() {
        $this->paramOp();
    }

    /**
     * 上传参数设置
     *
     */
    public function paramOp(){
        if (chksubmit()){
            $obj_validate = new Validate();
            $obj_validate->validateparam = array(
                array("input"=>$_POST["image_max_filesize"], "require"=>"true", "validator"=>"Number", "message"=>L('upload_image_filesize_is_number')),
                array("input"=>trim($_POST["image_allow_ext"]), "require"=>"true", "message"=>L('image_allow_ext_not_null'))
            );
            $error = $obj_validate->validate();
            if ($error != ''){
                showMessage($error);
            }else {
                $model_setting = Model('setting');
                $result = $model_setting->updateSetting(array(
                    'image_max_filesize'=>intval($_POST['image_max_filesize']),
                    'image_allow_ext'=>$_POST['image_allow_ext'])
                );
                if ($result){
                    $this->log(L('im_edit,upload_param'),1);
                    showMessage(L('im_common_save_succ'));
                }else {
                    $this->log(L('im_edit,upload_param'),0);
                    showMessage(L('im_common_save_fail'));
                }
            }
        }

        //获取默认图片设置属性
        $model_setting = Model('setting');
        $list_setting = $model_setting->getListSetting();
        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'param'));
        Tpl::setDirquna('system');
        Tpl::showpage('upload.param');
    }

    /**
     * 默认图设置
     */
    public function default_thumbOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_COMMON);
            //默认会员头像
            if (!empty($_FILES['default_user_portrait']['tmp_name'])){
                $thumb_width    = '32';
                $thumb_height   = '32';

                $upload->set('thumb_width', $thumb_width);
                $upload->set('thumb_height',$thumb_height);
                $upload->set('thumb_ext',   '_small');
                $upload->set('file_name', '');
                $result = $upload->upfile('default_user_portrait');
                if ($result){
                    $_POST['default_user_portrait'] = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }
            $list_setting = $model_setting->getListSetting();
            $update_array = array();
            if (!empty($_POST['default_user_portrait'])){
                $update_array['default_user_portrait'] = $_POST['default_user_portrait'];
            }
            if (!empty($update_array)){
                $result = $model_setting->updateSetting($update_array);
            }else{
                $result = true;
            }
            if ($result === true){
                //判断有没有之前的图片，如果有则删除
                if (!empty($list_setting['default_user_portrait']) && !empty($_POST['default_user_portrait'])){
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.$list_setting['default_user_portrait']);
                    @unlink(BASE_UPLOAD_PATH.DS.ATTACH_COMMON.DS.str_ireplace(',', '_small.', $list_setting['default_user_portrait']));
                }
                $this->log(L('im_edit,default_thumb'),1);
                showMessage(L('im_common_save_succ'));
            }else {
                $this->log(L('im_edit,default_thumb'),0);
                showMessage(L('im_common_save_fail'));
            }
        }

        $list_setting = $model_setting->getListSetting();

        //模板输出
        Tpl::output('list_setting',$list_setting);

        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'default_thumb'));
        Tpl::setDirquna('system');
        Tpl::showpage('upload.thumb');
    }

    /**
     * 登录主题图片
     */
    public function loginOp(){
        $model_setting = Model('setting');
        if (chksubmit()){
            $input = array();
            //上传图片
            $upload = new UploadFile();
            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','1.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['login_pic1']['name'])){
                $result = $upload->upfile('login_pic1');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[] = $upload->file_name;
                }
            }elseif ($_POST['old_login_pic1'] != ''){
                $input[] = '1.jpg';
            }

            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','2.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['login_pic2']['name'])){
                $result = $upload->upfile('login_pic2');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[] = $upload->file_name;
                }
            }elseif ($_POST['old_login_pic2'] != ''){
                $input[] = '2.jpg';
            }

            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','3.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['login_pic3']['name'])){
                $result = $upload->upfile('login_pic3');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[] = $upload->file_name;
                }
            }elseif ($_POST['old_login_pic3'] != ''){
                $input[] = '3.jpg';
            }

            $upload->set('default_dir',ATTACH_PATH.'/login');
            $upload->set('thumb_ext',   '');
            $upload->set('file_name','4.jpg');
            $upload->set('ifremove',false);
            if (!empty($_FILES['login_pic4']['name'])){
                $result = $upload->upfile('login_pic4');
                if (!$result){
                    showMessage($upload->error,'','','error');
                }else{
                    $input[] = $upload->file_name;
                }
            }elseif ($_POST['old_login_pic4'] != ''){
                $input[] = '4.jpg';
            }

            $update_array = array();
            if (count($input) > 0){
                $update_array['login_pic'] = serialize($input);
            }

            $result = $model_setting->updateSetting($update_array);
            if ($result === true){
                $this->log(L('im_edit,loginSettings'),1);
                showMessage(L('im_common_save_succ'));
            }else {
                $this->log(L('im_edit,loginSettings'),0);
                showMessage(L('im_common_save_fail'));
            }
        }
        $list_setting = $model_setting->getListSetting();
        if ($list_setting['login_pic'] != ''){
            $list = unserialize($list_setting['login_pic']);
        }
        Tpl::output('list',$list);
        Tpl::output('top_link',$this->sublink($this->links,'login'));
        Tpl::setDirquna('system');
        Tpl::showpage('upload.login');
    }
	
	/**
     * 静态文件上传
     */
    public function upload_staticsOp(){
        if (chksubmit()){
            //上传图片
            $upload = new UploadFile();            
            //默认会员头像
            if (!empty($_FILES['web_files']['tmp_name'])){
				//文件后缀名
				$org_name = $_FILES['web_files']['name'];	
		        $tmp_ext = explode(".", $org_name);
		        $tmp_ext = $tmp_ext[count($tmp_ext) - 1];
		        $tmp_ext = strtolower($tmp_ext);
		        if ($tmp_ext == 'zip'){
					$upload->set('default_dir',ATTACH_TEMP);
                    $upload->set('file_name', '');
				}else{
					$upload->set('default_dir','statics');
					$upload->set('file_name', $org_name);
				}
                $result = $upload->upfile('web_files',false,false);
                if ($result){
                    $file_name = $upload->file_name;
                }else {
                    showMessage($upload->error,'','','error');
                }
            }
            if (!empty($file_name)){
				if ($tmp_ext == 'zip'){	
				    $zip = new phpzip();
				    $zip->unZip(BASE_UPLOAD_PATH.DS.ATTACH_TEMP.DS.$file_name, BASE_UPLOAD_PATH.DS.'statics');
					@unlink(BASE_UPLOAD_PATH.DS.ATTACH_TEMP.DS.$file_name);
				}               
                showMessage('文件上传成功！');
            }else {
                showMessage('文件上传失败！');
            }
        }
        //输出子菜单
        Tpl::output('top_link',$this->sublink($this->links,'upload_statics'));
        Tpl::setDirquna('system');
        Tpl::showpage('upload_statics');
    }

}
