<?php
/**
 * 推广系统菜单
 *
 */
defined('InIMall') or exit('Access Invalid!');
$_menu['extension'] = array (
        'name' => '推广系统',
        'child' => array(
                array(
                        'name' => '配置',
                        'child' => array(
                                'extension_config' => '推广配置'
                        )
                ),
                array(
                        'name' => '推广员',
                        'child' => array(
                                'extension_promotion' => '推广员管理'
								
                        )
                ),
                array(
                        'name' => '结算',
                        'child' => array(                                
								'extension_commisdetail' => '抽佣明细',
								'extension_commisputout' => '佣金结算',
								'extension_statistics' => '抽佣统计'
                        )
                )
));