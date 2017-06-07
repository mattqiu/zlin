<?php
/**
 * 菜单
 *
 */
defined('InIMall') or exit('Access Invalid!');

$_menu['shop'] = array(
        'name' => '商城',
        'child' => array(
                array(
                        'name' => '设置',
                        'child' => array(
                                'setting' => '商城设置',
                                'upload' => '图片设置',
                                'seo' => $lang['im_seo_set'],
                                'message' => $lang['im_message_set'],
                                'payment' => $lang['im_pay_method'],
                                'express' => $lang['im_admin_express_set'],
                                'waybill' => '运单模板',
                                'web_config' => '首页管理',
                                'web_channel' => '频道管理',
								'web_special' => '专辑管理'
                        )
                ),
                array(
                        'name' => $lang['im_goods'],
                        'child'=>array(
                                'goods' => $lang['im_goods_manage'],
                                'goods_class' => $lang['im_class_manage'],
                                'brand' => $lang['im_brand_manage'],
                                'type' => $lang['im_type_manage'],
                                'spec' => $lang['im_spec_manage'],
                                'goods_album' => $lang['im_album_manage'],
                                'goods_recommend' => '商品推荐',
                        )),
                array(
                        'name' => $lang['im_store'],
                        'child' => array(
						        'ownshop' => '自营店铺',
                                'store' => '加盟店铺',
                                'store_grade' => $lang['im_store_grade'],
                                'store_class' => $lang['im_store_class'],
                                'domain' => $lang['im_domain_manage'],
                                'sns_strace' => $lang['im_s_snstrace'],
                                'help_store' => '店铺帮助',
                                'store_joinin' => '商家入驻'                                
                        )),
                array(
                        'name' => $lang['im_member'],
                        'child' => array(
                                'member' => $lang['im_member_manage'],
                                'member_exp' => '等级经验值',
                                'points' => $lang['im_member_pointsmanage'],
                                'sns_sharesetting' => $lang['im_binding_manage'],
                                'sns_malbum' => $lang['im_member_album_manage'],
                                'snstrace' => $lang['im_snstrace'],
                                'sns_member' => $lang['im_member_tag'],
                                'predeposit' => $lang['im_member_predepositmanage'],
                                'chat_log' => '聊天记录'
                        )),
                array(
                        'name' => $lang['im_trade'],
                        'child' => array(
                                'order' => $lang['im_order_manage'],
                                'vr_order' => '虚拟订单',
                                'refund' => '退款管理',
                                'return' => '退货管理',
                                'vr_refund' => '虚拟订单退款',
                                'consulting' => $lang['im_consult_manage'],
                                'inform' => $lang['im_inform_config'],
                                'evaluate' => $lang['im_goods_evaluate'],
                                'complain' => $lang['im_complain_config']
                        )),
                array(
                        'name' => $lang['im_operation'],
                        'child' => array(
						        'operation' => '运营配置',
                                'bill' => $lang['im_bill_manage'],
                                'vr_bill' => '虚拟订单结算',
                                'mall_consult' => '平台客服',
                                'rechargecard' => '平台充值卡',
                                'delivery' => '物流自提服务站'
                        )),
                array(
                        'name' => '促销',
                        'child' => array(
                                'promotion' => '促销设定',
                                'groupbuy' => $lang['im_groupbuy_manage'],
                                'vr_groupbuy' => '虚拟抢购设置',
                                'promotion_cou' => '加价购',
                                'promotion_xianshi' => $lang['im_promotion_xianshi'],
                                'promotion_mansong' => $lang['im_promotion_mansong'],
                                'voucher' => $lang['im_voucher_price_manage'],
                                'promotion_bundling' => $lang['im_promotion_bundling'],
                                'promotion_booth' => '推荐展位',
                                'promotion_book' => '预售商品',
                                'promotion_fcode' => 'Ｆ码商品',
                                'pointprod'=>$lang['im_pointprod'],
                                'activity' => $lang['im_activity_manage'],
                        )),
                array(
                        'name' => $lang['im_stat'],
                        'child' => array(
                                'stat_general' => $lang['im_statgeneral'],
                                'stat_industry' => $lang['im_statindustry'],
                                'stat_member' => $lang['im_statmember'],
                                'stat_store' => $lang['im_statstore'],
                                'stat_trade' => $lang['im_stattrade'],
                                'stat_goods' => $lang['im_statgoods'],
                                'stat_marketing' => $lang['im_statmarketing'],
                                'stat_aftersale' => $lang['im_stataftersale'],
                        		'stat_customer' => '客流统计'
                        )),
));
