<?php
/**
 * 菜单
 *
 */
defined('InIMall') or exit('Access Invalid!');
$_menu['microshop'] = array (
        'name' => '微商城',
        'child' => array(
                array(
                        'name' => $lang['im_config'], 
                        'child' => array(
                                'manage' => $lang['im_microshop_manage'],
                                'comment' => $lang['im_microshop_comment_manage'],
                                'adv' => $lang['im_microshop_adv_manage']
                        )
                ),
                array(
                        'name' => '随心看', 
                        'child' => array(
                                'goods' => $lang['im_microshop_goods_manage'],
                                'goods_class' => $lang['im_microshop_goods_class']
                        )
                ),
                array(
                        'name' => '个人秀', 
                        'child' => array(
                                'personal' => $lang['im_microshop_personal_manage'],
                                'personal_class' => $lang['im_microshop_personal_class']
                        )
                        
                ),
                array(
                        'name' => '店铺街',
                        'child' => array(
                                'store' => $lang['im_microshop_store_manage']
                        )
                )
        )
);