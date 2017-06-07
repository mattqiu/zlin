<?php
/**
 * 店铺供货商注册
 *
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */



defined('InIMall') or exit('Access Invalid!');

class supplier_registerControl extends BaseSupplierControl {

    public function __construct() {
        parent::__construct();
        if (!empty($_SESSION['supplier_id'])) {
            @header('location: index.php?act=supplier_center');die;
        }
    }

    public function indexOp() {
        $this->registerOp();
    }

    public function registerOp() {
    	
        Language::read("home_login_register");
        $lang	= Language::getLangContent();
        $model_member	= Model('member');
        $model_member->checkloginMember();
        $result = chksubmit(true,C('captcha_status_register'),'num');
        $register_info = array();
        if ($result){
        	//重复注册验证
        	if (process::islock('reg')){
        		showDialog(Language::get('im_common_op_repeat'),'','error');
        	}
        	//邀请码验证 zhangchao
        	if(C('invite_open')==1){
        		//检验邀请码,并获取上级ID
        		$pid = $this->check_inviteCode($_POST['invite_code']);
        		if(!empty($pid)){
        			$register_info['parent_id'] = $pid;
        		}
        	}
        	if ($result === -11){
        		showDialog($lang['invalid_request'],'','error');
        	}elseif ($result === -12){
        		showDialog($lang['login_usersave_wrong_code'],'','error');
        	}
	        
	        
	        $register_info['member_name'] = $_POST['supplier_name'];
	        $register_info['member_passwd'] = $_POST['password'];
	        $register_info['password_confirm'] = $_POST['password_confirm'];
	        $register_info['email'] = $_POST['supplier_email'];
	        $member_info = $model_member->register($register_info);
	        if(!isset($member_info['error'])) {
	        	$model_member->createSession($member_info,true);
	        	process::addprocess('reg');
	        	// cookie中的cart存入数据库
	        	Model('cart')->mergecart($member_info,$_SESSION['store_id']);
	        	// cookie中的浏览记录存入数据库
	        	Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);
	        		
	        	if ($_GET['inajax'] == 1){
	        		showDialog('',$_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'],'js');
	        	}else{
	        		$_POST['ref_url']	= (strstr($_POST['ref_url'],'logout')=== false && !empty($_POST['ref_url']) ? $_POST['ref_url'] : 'index.php?act=member_information&op=member');
	        		redirect($_POST['ref_url']);
	        	}
	        } else {
	        	showDialog($member_info['error'],'','error');
	        }
       	} else {
        	Tpl::output('nchash', getIMhash());
	        Tpl::setLayout('null_layout');
	        Tpl::showpage('register');
        }
    }
}
