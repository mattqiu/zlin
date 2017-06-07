<?php
/**
 * 菜单
 *
 */
defined('InIMall') or exit('Access Invalid!');
$_menu['mobile'] = array (
        'name'=>$lang['im_mobile'],
        'child'=>array(
                array(
                        'name'=>'设置',
                        'child' => array(						        
                                'mb_app' => 'APP应用',
								'mb_payment' => '手机支付',                               
                                'mb_wx' => '微信二维码'
                        )
                ),
				array(
                        'name'=>'装修',
                        'child' => array(
                                'mb_special' => '模板设置',
								'mb_ad' => '首页广告',
                                'mb_navigation' => '分类导航'
                        )
                ),
				array(
                        'name'=>'反馈',
                        'child' => array(
                                'mb_feedback' => $lang['im_mobile_feedback']
                        )
                )
        )
);