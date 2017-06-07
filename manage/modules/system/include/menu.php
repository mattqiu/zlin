<?php
/**
 * 菜单
 *
 */
defined('InIMall') or exit('Access Invalid!');

$_menu['system'] = array (
        'name'  => '平台',
        'child' => array (
                array(
                        'name' => '控制台',
                        'child' => array(
						        'welcome' => $lang['im_welcome_page'],
                                'setting' => $lang['im_web_set'],
                                'upload' => $lang['im_upload_set'],
                                'message' => '消息设置',
                                'taobao_api' => '淘宝接口',
                        		'ikjtao_api' => '跨境淘接口',
								'qrcode_build' => '二维码生成',
                                'admin' => '权限设置',
                                'admin_log' => $lang['im_admin_log'],
                                'cache' => $lang['im_admin_clear_cache'],
                        )
                ),
                array(
                        'name' => $lang['im_member'],
                        'child' => array(
                                'member' => $lang['im_member_manage'],
                                'account' => $lang['im_web_account_syn']
                        )
                ),
                array(
                        'name' => $lang['im_website'],
                        'child' => array(
                                'article_class' => $lang['im_article_class'],
                                'article' => $lang['im_article_manage'],
                                'document' => $lang['im_document'],
                                'navigation' => $lang['im_navigation'],
                                'adv' => $lang['im_adv_manage'],
                                'rec_position' => $lang['im_admin_res_position'],
								'link' => '合作伙伴'
                        )
                )
        ) 
);
