<?php
/**
 * 推广系统菜单
 *
 */
defined('InIMall') or exit('Access Invalid!');
$_menu['distribute'] = array (
        'name' => '供货',
        'child' => array(
        		array(
        				'name' => '供货',
        				'child' => array(
        						'suppllier' => '供货商管理',
        						'help_store' => '供货商帮助',
        						'fx_commission_setting' => '供货商佣金设置'
        				)
        		),
        		array(
        				'name' => '分销',
        				'child' => array(
        						'setting' => '分销设置',
        						'web_fx_config' => '分销市场',
        						'distributor' => '分销商管理'
        				)
        		)
));