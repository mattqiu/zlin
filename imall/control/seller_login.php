<?php
/**
 * 店铺卖家登录
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

class seller_loginControl extends BaseSellerControl {

	public function __construct() {
		parent::__construct();
        if (!empty($_SESSION['seller_id'])) {
            @header('location: index.php?act=seller_center');die;
        }
	}

    public function indexOp() {
        $this->show_loginOp();
    }

    public function show_loginOp() {
        Tpl::output('imhash', getIMhash());
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
        $seller_info = $model_seller->getSellerInfo(array('seller_name' => $_POST['seller_name']));
        if($seller_info) {

            $model_member = Model('member');
            $member_info = $model_member->getMemberInfo(
                array(
                    'member_id' => $seller_info['member_id'],
                    'member_passwd' => md5($_POST['password'])
                )
            );
            if($member_info) {
                // 更新卖家登陆时间
                $model_seller->editSeller(array('last_login_time' => TIMESTAMP), array('seller_id' => $seller_info['seller_id']));

                $model_seller_group = Model('seller_group');
                $seller_group_info = $model_seller_group->getSellerGroupInfo(array('group_id' => $seller_info['seller_group_id']));

                $model_store = Model('store');
                $store_info = $model_store->getStoreInfoByID($seller_info['store_id']);
                //会员信息
                $_SESSION['is_login'] = '1';
                $_SESSION['member_id'] = $member_info['member_id'];
                $_SESSION['member_name'] = $member_info['member_name'];
    			$_SESSION['member_email'] = $member_info['member_email'];
    			$_SESSION['is_buy']	= $member_info['is_buy'];
    			$_SESSION['avatar']	= $member_info['member_avatar'];				
				
				//微信处理
		        if (OPEN_MODULE_WEIXIN_STATE == 1){
				    $_SESSION['weixin_active']  = $member_info['weixin_active'];
				}				
				
				$_SESSION['M_grade_level'] 	= $model_member->getOneMemberGradeLevel($member_info['member_exppoints']);//会员等级
				//推广员、导购员处理
		        if (OPEN_STORE_EXTENSION_STATE > 0){
		            $_SESSION['M_mc_id'] 	    = $member_info['mc_id'];//会员类型
		            $_SESSION['M_store_id'] 	= $member_info['store_id'];//会员所在的推广店铺ID
					//缓存推广员、导购员ID
					if ($member_info['mc_id'] ==1 || $member_info['mc_id']==2){
                        $extension=urlsafe_b64encode($member_info['member_id']);
                        setIMCookie('iMall_extension',$extension,24*60*60);
					}
				}

				//店铺信息
				$_SESSION['S_parent_id']      = $store_info['parent_id'];//上级店铺
                $_SESSION['S_branch_op']      = intval($store_info['branch_op']); //充许开分店				
                $_SESSION['S_payment_method'] = $store_info['payment_method']; //支付方式
				$_SESSION['S_extension_op']   = intval($store_info['extension_op']); //充许开分店
    
                $_SESSION['grade_id'] = $store_info['grade_id'];
                $_SESSION['seller_id'] = $seller_info['seller_id'];
                $_SESSION['seller_name'] = $seller_info['seller_name'];
                $_SESSION['seller_is_admin'] = intval($seller_info['is_admin']);
                $_SESSION['store_id'] = intval($seller_info['store_id']);//会员自己的店铺ID
				$_SESSION['goods_edit'] = intval($seller_info['goods_edit']);//商品编辑模式：0:简洁 1：完整
                $_SESSION['store_name']	= $store_info['store_name'];
                $_SESSION['is_own_shop'] = (bool) $store_info['is_own_shop'];
                $_SESSION['bind_all_gc'] = (bool) $store_info['bind_all_gc'];
                $_SESSION['seller_limits'] = explode(',', $seller_group_info['limits']);
                if($seller_info['is_admin']) {
                    $_SESSION['seller_group_name'] = '管理员';
                    $_SESSION['seller_smt_limits'] = false;
                } else {
                    $_SESSION['seller_group_name'] = $seller_group_info['group_name'];
                    $_SESSION['seller_smt_limits'] = explode(',', $seller_group_info['smt_limits']);
                }
                if(!$seller_info['last_login_time']) {
                    $seller_info['last_login_time'] = TIMESTAMP;
                }
                $_SESSION['seller_last_login_time'] = date('Y-m-d H:i', $seller_info['last_login_time']);				
			    //管理员管理菜单及快捷菜单
                $seller_menu = $this->getSellerMenuList($seller_info['is_admin'], explode(',', $seller_group_info['limits']));
                $_SESSION['seller_menu'] = $seller_menu['seller_menu'];
                $_SESSION['seller_function_list'] = $seller_menu['seller_function_list'];
                if(!empty($seller_info['seller_quicklink'])&&$seller_info['seller_quicklink']!=='') {
                    $quicklink_array = explode(',', $seller_info['seller_quicklink']);
                    foreach ($quicklink_array as $value) {
                        $_SESSION['seller_quicklink'][$value] = $value ;
                    }
                }
                $this->recordSellerLog('登录成功');
                redirect('index.php?act=seller_center');
            } else {
                showMessage('用户名密码错误', '', '', 'error');
            }
        } else {
            showMessage('用户名密码错误', '', '', 'error');
        }
    }
}