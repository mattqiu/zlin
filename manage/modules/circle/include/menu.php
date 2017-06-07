<?php
/**
 * 菜单
 *
 */
defined('InIMall') or exit('Access Invalid!');
$_menu['circle'] = array (
        'name' => $lang['im_circle'],
        'child' => array (
                array (
                        'name' => $lang['im_config'],
                        'child' => array(
                                'circle_setting' => $lang['im_circle_setting'],
                                'circle_adv' => '首页幻灯'
                        )
                ),
                array (
                        'name' => '成员',
                        'child' => array(
                                'circle_member' => $lang['im_circle_membermanage'],
                                'circle_memberlevel' => '成员头衔'
                        )
                ),
                array (
                        'name' => '圈子',
                        'child' => array(
                                'circle_manage' => $lang['im_circle_manage'],
                                'circle_class' => $lang['im_circle_classmanage'],
                                'circle_theme' => $lang['im_circle_thememanage'],
                                'circle_inform' => '举报管理'
                        )
                )
        ) 
);