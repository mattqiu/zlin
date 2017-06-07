<?php
/**
 * 菜单
 *
 */
defined('InIMall') or exit('Access Invalid!');
$_menu['cms'] = array (
        'name' => $lang['im_cms'],
        'child' => array(
                array(
                        'name' => $lang['im_config'],
                        'child' => array(
                                'cms_manage' => $lang['im_cms_manage'],
                                'cms_index' => $lang['im_cms_index_manage'],
                                'cms_navigation' => $lang['im_cms_navigation_manage'],
                                'cms_tag' => $lang['im_cms_tag_manage'],
                                'cms_comment' => $lang['im_cms_comment_manage']
                        )
                ),
                array(
                        'name' => '专题',
                        'child' => array(
                                'cms_special' => $lang['im_cms_special_manage']
                        )
                ),
                array(
                        'name' => '文章',
                        'child' => array(
                                'cms_article_class' => '文章分类',
                                'cms_article' => '文章管理'
                        )
                ),
                array(
                        'name' => '画报',
                        'child' => array(
                                'cms_picture_class' => '画报分类',
                                'cms_picture' => '画报管理'
                        )
                )
));