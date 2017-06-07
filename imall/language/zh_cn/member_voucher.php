<?php
defined('InIMall') or exit('Access Invalid!');
$lang['voucher_unavailable']    = '代金券功能尚未开启';
$lang['voucher_applystate_new']    = '待审核';
$lang['voucher_applystate_verify']    = '已审核';
$lang['voucher_applystate_cancel']    = '已取消';
$lang['voucher_quotastate_activity']	= '正常';
$lang['voucher_quotastate_cancel']    = '取消';
$lang['voucher_quotastate_expire']    = '结束';
$lang['voucher_templatestate_usable']	= '有效';
$lang['voucher_templatestate_disabled']= '失效';
$lang['voucher_quotalist']= '套餐列表';
$lang['voucher_applyquota']= '申请套餐';
$lang['voucher_applyadd']= '购买套餐';
$lang['voucher_templateadd']= '新增代金券';
$lang['voucher_templateedit']= '编辑代金券';
$lang['voucher_templateinfo']= '代金券详细';
/**
 * 套餐申请
 */
$lang['voucher_apply_num_error']= '数量不能为空，且必须为1-12之间的整数';
$lang['voucher_apply_goldnotenough']= "当前您拥有金币数为%s，不足以支付此次交易，请先充值";
$lang['voucher_apply_fail']= '套餐申请失败';
$lang['voucher_apply_succ']= '套餐申请成功，请等待审核';
$lang['voucher_apply_date']= '申请日期';
$lang['voucher_apply_num']    		= '申请数量';
$lang['voucher_apply_addnum']    		= '套餐购买数量';
$lang['voucher_apply_add_tip1']    		= '购买单位为月(30天)，一次最多购买12个月，您可以在所购买周期内以月为单位发布代金券活动';
$lang['voucher_apply_add_tip2']    		= '每月您需要支付%s元';
$lang['voucher_apply_add_tip3']    		= '每月最多发布活动%s次';
$lang['voucher_apply_add_tip4']    		= '套餐时间从审批后开始计算';
$lang['voucher_apply_add_confirm1']    	= '您总共需要支付';
$lang['voucher_apply_add_confirm2']    	= '元,确认购买吗？';
$lang['voucher_apply_goldlog']    		= '购买代金券活动%s个月，单价%s金币，总共花费%s金币';
$lang['voucher_apply_buy_succ']			= '套餐购买成功';

/**
 * 套餐
 */
$lang['voucher_quota_startdate']    	= '开始时间';
$lang['voucher_quota_enddate']    		= '结束时间';
$lang['voucher_quota_timeslimit']    	= '活动次数限制';
$lang['voucher_quota_publishedtimes']   = '已发布活动次数';
$lang['voucher_quota_residuetimes']    	= '剩余活动次数';
/**
 * 代金券模板
 */
$lang['voucher_template_quotanull']			= '当前没有可用的套餐，请先申请套餐';
$lang['voucher_template_noresidual']		= "当前套餐中活动已满%s条活动信息，不可再发布活动";
$lang['voucher_template_pricelisterror']	= '平台代金券面额设置出现问题，请联系客服帮助解决';
$lang['voucher_template_title_error'] 		= "模版名称不能为空且不能大于50个字符";
$lang['voucher_template_total_error'] 		= "可发放数量不能为空且必须为整数";
$lang['voucher_template_price_error']		= "模版面额不能为空且必须为整数，且面额不能大于限额";
$lang['voucher_template_limit_error'] 		= "模版使用消费限额不能为空且必须是数字";
$lang['voucher_template_describe_error'] 	= "模版描述不能为空且不能大于255个字符";
$lang['voucher_template_title']			= '代金券名称';
$lang['voucher_template_enddate']		= '有效期';
$lang['voucher_template_enddate_tip']		= '有效期应在套餐有效期内，正使用的套餐有效期为';
$lang['voucher_template_price']			= '面额';
$lang['voucher_template_total']			= '可发放总数';
$lang['voucher_template_eachlimit']		= '每人限领';
$lang['voucher_template_eachlimit_item']= '不限';
$lang['voucher_template_eachlimit_unit']= '张';
$lang['voucher_template_orderpricelimit']	= '消费金额';
$lang['voucher_template_describe']		= '代金券描述';
$lang['voucher_template_styleimg']		= '选择代金券皮肤';
$lang['voucher_template_styleimg_text']	= '店铺优惠券';
$lang['voucher_template_image']			= '代金券图片';
$lang['voucher_template_image_tip']		= '该图片将在积分中心的代金券模块中显示，建议尺寸为160*160px。';
$lang['voucher_template_list_tip1'] = "1、手工设置代金券失效后,用户将不能领取该代金券,但是已经领取的代金券仍然可以使用";
$lang['voucher_template_list_tip2'] = "2、代金券模版和已发放的代金券过期后自动失效";
$lang['voucher_template_backlist'] 	= "返回列表";
$lang['voucher_template_giveoutnum']= '已领取';
$lang['voucher_template_usednum']	= '已使用';
/**
 * 代金券
 */
$lang['voucher_voucher_state'] = "状态";
$lang['voucher_voucher_state_unused'] = "未使用";
$lang['voucher_voucher_state_used'] = "已使用";
$lang['voucher_voucher_state_expire'] = "已过期";
$lang['voucher_voucher_price'] = "金额";
$lang['voucher_voucher_storename'] = "适用店铺";
$lang['voucher_voucher_indate'] = "有效期";
$lang['voucher_voucher_usecondition'] = "使用条件";
$lang['voucher_voucher_usecondition_desc'] = "订单满";
$lang['voucher_voucher_vieworder'] = "查看订单";
$lang['voucher_voucher_readytouse'] = "马上使用";
$lang['voucher_voucher_code'] = "编码";



$lang['voucher_gold']				= '元';
$lang['voucher_start_time']		= '开始时间';
$lang['voucher_end_time']			= '结束时间';
$lang['voucher_add']				= '添加活动';
$lang['voucher_edit']				= '管理';
$lang['voucher_name']				= '活动名称';
$lang['voucher_status']			= '活动状态';
$lang['voucher_status_all']		= '全部状态';
$lang['voucher_status_0']			= '关闭';
$lang['voucher_status_1']			= '开启';
$lang['voucher_published']			= '已发布活动活动次数';
$lang['voucher_surplus']			= '剩余可发布活动数量';
$lang['voucher_add_fail_quantity_beyond']	= '剩余可发布数量不足，不能在添加代金券活动';
$lang['promotion_unavailable']		= '商品促销功能尚未开启';
$lang['voucher_list']				= '活动列表';
$lang['voucher_purchase_history']	= '购买代金券记录';
$lang['voucher_quota_add']			= '购买代金券';
$lang['voucher_quota_current_error']	= '您还没有购买代金券，或该促销活动已经关闭。<br />请先购买代金券，再查看活动列表。';
$lang['voucher_list_null']				= '您还没有添加活动。';
$lang['voucher_delete_success']		= '活动删除成功。';
$lang['voucher_delete_fail']			= '活动删除失败。';

/**
 * 购买活动
 */
$lang['voucher_quota_add_quantity']	= '代金券购买数量';
$lang['voucher_price_explain1']		= '购买单位为月(30天)，一次最多购买12个月，购买后立即生效，即可发布代金券活动。';
$lang['voucher_price_explain2']		= '每月您需要支付%d元。';
$lang['voucher_quota_price_fail']		= '参数错误，购买失败。';
$lang['voucher_quota_price_succ']		= '购买成功。';
$lang['voucher_quota_quantity_error']	= '不能为空，且必须为1~12之间的整数';
$lang['voucher_quota_add_confirm']		= '确认购买?您总共需要支付';
$lang['voucher_quota_success_glog_desc']= '购买代金券活动%d个月，单价%d元，总共花费%d元';

/**
 * 添加活动
 */
$lang['voucher_add_explain1']				= '您只能发布%d个代金券活动；每个活动最多可以添加%d个商品。';
$lang['voucher_add_explain2']				= '每个活动最多可以添加%d个商品。';
$lang['voucher_add_goods_explain']			= '搭配销售的商品可上下<br/>拖移商品列可自定义显<br/>示排序；编辑、删除、<br/>排序等操作提交后生效。';
$lang['voucher_goods']						= '商品';
$lang['voucher_show_name']					= '显示名称';
$lang['voucher_cost_price']				= '原价';
$lang['voucher_cost_price_note']			= '&nbsp;(已添加搭配商品的默认价格总计)';
$lang['voucher_goods_add']					= '添加商品';
$lang['voucher_add_price']					= '代金券价格';
$lang['voucher_add_price_title']			= '自定义代金券商品的优惠价格总计';
$lang['voucher_add_img']					= '活动图片';
$lang['voucher_add_pic_list_tip']			= '该图组用于组合详情页<br/>可由相册选择图片代替<br/>默认产品图；左右拖移<br/>图片可更改显示排序。';
$lang['voucher_add_form_album']			= '从相册选择图片';
$lang['voucher_add_freight_method']		= '运费承担';
$lang['voucher_add_freight_method_seller']	= '卖家承担运费';
$lang['voucher_add_freight_method_buyer']	= '买家承担运费（快递）';
$lang['voucher_add_desc']					= '活动描述';
$lang['voucher_add_form_album_to_desc']	= '插入相册图片';
$lang['voucher_add_name_error']			= '请填写活动名称';
$lang['voucher_add_goods_error']			= '请选择2件及以上的商品';
$lang['voucher_add_price_error_null']		= '请填写活动价格';
$lang['voucher_add_price_error_not_num']	= '价格只能为数字';
$lang['voucher_add_not_add_img']			= '不能在继续添加图片。';
$lang['voucher_add_goods_show_note']		= '商品已下架，请重新上架或选择其他商品';

/**
 * 添加代金券商品
 */
$lang['voucher_goods_store_class']			= '店铺分类';
$lang['voucher_goods_name']				= '商品名称';
$lang['voucher_goods_code']				= '货号';
$lang['voucher_goods_price']				= '价格';
$lang['voucher_goods_storage']				= '库存';
$lang['voucher_goods_storage_not_enough']	= '库存不足，不能添加商品。';
$lang['voucher_goods_add_voucher']		= '添加到代金券商品组';
$lang['voucher_goods_add_voucher_exit']	= '从代金券商品组移除';
$lang['voucher_goods_add_enough_prompt']	= '您已经添加了%d个，不能在继续添加商品。';
$lang['voucher_goods_remove']				= '移除';

/**
 * 购买记录
 */
$lang['voucher_history_quantity']			= '购买数量（月）';
$lang['voucher_history_consumption_gold']	= '价格';

/**
 * 活动列表
 */
$lang['voucher_list_goods_count']			= '商品数量';
?>
