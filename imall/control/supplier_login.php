<?php
/**
 * 店铺供货商登录
 *
 *
 *
 * * @网店运维 (c) 2015-2018 ShopWWI Inc. (http://www.shopwwi.com)
 * @license    http://www.shopwwi.c om
 * @link       交流群号：111731672
 * @since      网店运维提供技术支持 授权请购买shopnc授权
 */



defined('InIMall') or exit('Access Invalid!');

class supplier_loginControl extends BaseSupplierControl {

    public function __construct() {
        parent::__construct();
        if (!empty($_SESSION['supplier_id'])) {
            @header('location: index.php?act=supplier_center');die;
        }
    }

    public function indexOp() {
        $this->show_loginOp();
    }

    public function show_loginOp() {
        Tpl::output('nchash', getIMhash());
        Tpl::setLayout('null_layout');
        Tpl::showpage('login');
    }

    public function loginOp() {
        $result = chksubmit(true,true,'num');
        if ($result){
            if ($result === -11){
                showDialog('用户名或密码错误','','error');
            } elseif ($result === -12){
                showDialog('验证码错误','','error');
            }
        } else {
            showDialog('非法提交','','error');
        }

        $model_seller = Model('seller');
        $seller_info = $model_seller->getSellerInfo(array('seller_name' => $_POST['supplier_name']));
        if($seller_info) {

            $model_member = Model('member');
            $member_info = $model_member->getMemberInfo(
                array(
                    'member_id' => $seller_info['member_id'],
                    'member_passwd' => md5($_POST['password'])
                )
            );
            if($member_info) {
                // 更新供货商登陆时间
                $model_seller->editSeller(array('last_login_time' => TIMESTAMP), array('seller_id' => $seller_info['seller_id']));

                $model_seller_group = Model('seller_group');
                $seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $seller_info['seller_group_id']));

                $model_store = Model('store');
                $store_info = $model_store->getStoreInfoByID($seller_info['store_id']);

                $_SESSION['is_login'] = '1';
                $_SESSION['member_id'] = $member_info['member_id'];
                $_SESSION['member_name'] = $member_info['member_name'];
                $_SESSION['member_email'] = $member_info['member_email'];
                $_SESSION['is_buy'] = $member_info['is_buy'];
                $_SESSION['avatar'] = $member_info['member_avatar'];

                $_SESSION['grade_id'] = $store_info['grade_id'];
                $_SESSION['supplier_id'] = $seller_info['seller_id'];
                $_SESSION['supplier_name'] = $seller_info['seller_name'];
                $_SESSION['supplier_is_admin'] = intval($seller_info['is_admin']);
                $_SESSION['store_id'] = intval($seller_info['store_id']);
                $_SESSION['store_name'] = $store_info['store_name'];
                $_SESSION['store_avatar'] = $store_info['store_avatar'];
                $_SESSION['is_own_shop'] = (bool) $store_info['is_own_shop'];
                $_SESSION['bind_all_gc'] = (bool) $store_info['bind_all_gc'];
                $_SESSION['supplier_limits'] = explode(',', $seller_group_info['limits']);
                $_SESSION['supplier_group_id'] = $seller_info['seller_group_id'];
                $_SESSION['supplier_gc_limits'] = $seller_group_info['gc_limits'];
                if($seller_info['is_admin']) {
                    $_SESSION['supplier_group_name'] = '管理员';
                    $_SESSION['supplier_smt_limits'] = false;
                } else {
                    $_SESSION['supplier_group_name'] = $seller_group_info['group_name'];
                    $_SESSION['supplier_smt_limits'] = explode(',', $seller_group_info['smt_limits']);
                }
                if(!$seller_info['last_login_time']) {
                    $seller_info['last_login_time'] = TIMESTAMP;
                }
                $_SESSION['supplier_last_login_time'] = date('Y-m-d H:i', $seller_info['last_login_time']);
                $seller_menu = $this->getSupplierMenuList($seller_info['is_admin'], explode(',', $seller_group_info['limits']));
                $_SESSION['supplier_menu'] = $seller_menu['supplier_menu'];
                $_SESSION['supplier_function_list'] = $seller_menu['supplier_function_list'];
                if(!empty($seller_info['seller_quicklink'])) {
                    $quicklink_array = explode(',', $seller_info['seller_quicklink']);
                    foreach ($quicklink_array as $value) {
                        $_SESSION['supplier_quicklink'][$value] = $value ;
                    }
                }
                setIMCookie('auto_login', '', -3600);
                $this->recordSupplierLog('登录成功');
                redirect('index.php?act=supplier_center');
            } else {
                showMessage('用户名密码错误', '', '', 'error');
            }
        } else {
            showMessage('用户名密码错误', '', '', 'error');
        }
    }
}
