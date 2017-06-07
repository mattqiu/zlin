<?php
defined('InIMall') or exit('Access Invalid!');

$lang['promotion_unavailable'] = '商品促销功能尚未开启';

$lang['promotion_funding'] = '众筹';

$lang['promotion_active_list'] 	= '商品列表';
$lang['promotion_quota_list'] 	= '套餐列表';
$lang['promotion_join_active'] 	= '添加商品';
$lang['promotion_buy_product'] 	= '购买套餐';
$lang['promotion_goods_manage'] = '商品管理';
$lang['promotion_add_goods'] 	= '添加商品';


$lang['state_new'] = '新申请';
$lang['state_verify'] = '已审核';
$lang['state_cancel'] = '已取消';
$lang['state_verify_fail'] = '审核失败';
$lang['funding_state_unpublished'] = '未发布';
$lang['funding_state_published'] = '已发布';
$lang['funding_state_cancel'] = '已取消';
$lang['all_state'] = '全部状态';


$lang['funding_quota_start_time'] = '开始时间';
$lang['funding_quota_end_time'] = '结束时间';
$lang['funding_quota_times_limit'] = '活动次数限制';
$lang['funding_quota_times_published'] = '已发布活动次数';
$lang['funding_quota_times_publish'] = '剩余活动次数';
$lang['funding_quota_goods_limit'] = '商品限制';
$lang['funding_name'] = '活动名称';
$lang['funding_apply_date'] = '套餐申请时间';
$lang['funding_apply_quantity'] = '申请数量（月）';
$lang['apply_date'] = '申请时间';
$lang['apply_quantity'] = '申请数量';
$lang['apply_quantity_unit'] = '（包月）';
$lang['funding_discount'] = '折扣';
$lang['funding_buy_limit'] = '购买限制';

$lang['start_time'] = '开始时间';
$lang['end_time'] = '结束时间';
$lang['funding_list'] = '众筹';
$lang['mansong_list'] = '满即送';
$lang['funding_add'] = '添加活动';
$lang['funding_index'] = '活动列表';
$lang['funding_quota'] = '套餐列表';
$lang['funding_apply'] = '申请列表';
$lang['funding_manage'] = '活动管理';
$lang['funding_quota_add'] = '购买套餐';
$lang['funding_quota_add_quantity'] = '套餐购买数量';
$lang['funding_quota_add_confirm'] = '确认购买?您总共需要支付';
$lang['goods_add'] = '添加商品';
$lang['choose_goods'] = '选择活动商品';
$lang['goods_name'] = '商品名称';
$lang['goods_store_price'] = '商品价格';
$lang['funding_goods_selected'] = '已选商品';
$lang['funding_publish'] = '发布活动';
$lang['ensure_publish'] = '确认发布该活动?';
$lang['funding_no_goods'] = '您还没有选择活动商品';
$lang['funding_goods_exist'] = '已参加';

$lang['funding_price'] = '购买众筹所需金币数';
$lang['funding_explain1'] = '1、点击购买套餐和套餐续费按钮可以购买或续费套餐';
$lang['funding_explain2'] = '2、点击添加活动按钮可以添加众筹活动，点击管理按钮可以对众筹活动内的商品进行管理';
$lang['funding_explain3'] = '3、点击删除按钮可以删除众筹活动';
$lang['funding_manage_goods_explain1'] = '1、众筹商品的时间段不能重叠';
$lang['funding_manage_goods_explain2'] = '2、点击添加商品按钮可以搜索并添加参加活动的商品，点击删除按钮可以删除该商品';
$lang['funding_price_explain1'] = '购买单位为月(30天)，您可以在所购买的周期内发布众筹活动';
$lang['funding_price_explain2'] = '每月(30天)您需要支付';
$lang['funding_add_explain1'] = '您本期还可以创建%s个活动';
$lang['funding_add_start_time_explain'] = '开始时间不能为空且不能早于%s';
$lang['funding_add_end_time_explain'] = '结束时间不能为空且不能晚于%s';
$lang['funding_discount_explain'] = '折扣必须为0.1-9.9之间的数字';
$lang['funding_buy_limit_explain'] = '购买限制必须为正整数';
$lang['time_error'] = '时间格式错误';
$lang['param_error'] = '参数错误';
$lang['greater_than_start_time'] = '结束时间必须大于开始时间';
$lang['funding_price_error'] = '不能为空且必须为正整数';
$lang['funding_name_explain'] = '活动名称将显示在众筹活动列表中，方便商家管理使用，最多可输入25个字符。';
$lang['funding_title_explain'] = '活动标题是商家对众筹活动的别名操作，请使用例如“新品打折”、“月末折扣”类短语表现，最多可输入10个字符；<br/>非必填选项，留空商品优惠价格前将默认显示“众筹”字样。';
$lang['funding_explain_explain'] = '活动描述是商家对众筹活动的补充说明文字，在商品详情页-优惠信息位置显示；<br/>非必填选项，最多可输入30个字符。';
$lang['funding_name_error'] = '活动名称不能为空';
$lang['funding_quota_quantity_error'] = '数量不能为空，且必须为1-12之间的整数';
$lang['funding_quota_add_success'] = '众筹套餐购买成功';
$lang['funding_quota_add_fail'] = '众筹套餐购买申请失败';
$lang['funding_quota_add_fail_nogold'] = '众筹套餐购买申请失败，您没有足够的金币';
$lang['funding_quota_current_error'] = '当前没有可用的众筹套餐，请先购买套餐';
$lang['funding_quota_current_error1'] = '您还没有申请购买“众筹”促销活动套餐或促销活动已过期<br/>请购买新的“众筹”促销活动套餐。';
$lang['funding_quota_current_error2'] = '您已经购买了众筹套餐';
$lang['funding_quota_current_error3'] = '您本期套餐已用完不能再发布新活动';
$lang['funding_add_success'] = '众筹活动添加成功，请您选择要参加该活动的商品，并发布该活动';
$lang['funding_active_status'] = '活动状态';
$lang['funding_add_fail'] = '众筹活动添加失败';
$lang['funding_goods_none'] = '您还没有添加活动商品';
$lang['funding_goods_add_success'] = '众筹活动商品添加成功';
$lang['funding_goods_add_fail'] = '众筹活动商品添加失败';
$lang['funding_goods_delete_success'] = '众筹活动商品删除成功';
$lang['funding_goods_delete_fail'] = '众筹活动商品删除失败';
$lang['funding_goods_cancel_fail'] = '众筹活动商品取消失败';
$lang['funding_goods_limit_error'] = '已经超过了活动商品数限制';
$lang['funding_goods_is_exist'] = '该商品已经参加了本期众筹，请选择其它商品';
$lang['funding_publish_success'] = '众筹活动发布成功';
$lang['funding_publish_fail'] = '众筹活动发布失败';
$lang['funding_cancel_success'] = '众筹活动取消成功';
$lang['funding_cancel_fail'] = '众筹活动取消失败';

$lang['setting_save_success'] = '设置保存成功';
$lang['setting_save_fail'] = '设置保存失败';

$lang['text_month'] = '月';
$lang['text_gold'] = '金币';
$lang['text_jian'] = '件';
$lang['text_ci'] = '次';
$lang['text_goods'] = '商品';
$lang['text_normal'] = '正常';
$lang['text_invalidation'] = '失效';
$lang['text_default'] = '默认';
$lang['text_add'] = '添加';

$lang['funding_apply_verify_success_glog_desc'] = '购买众筹活动%s个月，单价%s金币，总共花费%s金币';
