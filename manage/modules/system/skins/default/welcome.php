<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['dashboard_wel_system_info'];?></h3>
        <h5>平台运营概述/及待办事项</h5>
      </div>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <div class="info-panel">
    <dl class="member">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_member'];?>"><span><em id="statistics_member"></em></span></sub></div>
        <h3><?php echo $lang['im_member'];?></h3>
        <h5><?php echo $lang['dashboard_wel_member_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w50pre normal"><a href="<?php echo urlAdminshop('member','member');?>"><?php echo $lang['dashboard_wel_new_add'];?><sub><em id="statistics_week_add_member"></em></sub></a></li>
          <li class="w50pre none"><a href="<?php echo urlAdminshop('predeposit','pd_cash_list');?>"><?php echo $lang['dashboard_wel_predeposit_get'];?><sub><em id="statistics_cashlist">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="shop">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_count_store_add'];?>"><span><em id="statistics_store"></em></span></sub></div>
        <h3><?php echo $lang['im_store'];?></h3>
        <h5><?php echo $lang['dashboard_wel_store_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w20pre none"><a href="<?php echo urlAdminshop('store','store_joinin');?>">开店审核<sub><em id="statistics_store_joinin">0</em></sub></a></li>
          <li class="w20pre none"><a href="<?php echo urlAdminshop('store','store_bind_class_applay_list',array('state'=>0));?>">类目申请<sub><em id="statistics_store_bind_class_applay">0</em></sub></a></li>
          <li class="w20pre none"><a href="<?php echo urlAdminshop('store','reopen_list',array('re_state'=>1));?>">续签申请<sub><em id="statistics_store_reopen_applay">0</em></sub></a></li>
          <li class="w20pre none"><a href="<?php echo urlAdminshop('store','store',array('store_type'=>'expired'));?>"><?php echo $lang['dashboard_wel_expired'];?><sub><em id="statistics_store_expired">0</em></sub></a></li>
          <li class="w20pre none"><a href="<?php echo urlAdminshop('store','store',array('store_type'=>'expire'));?>"><?php echo $lang['dashboard_wel_expire'];?><sub><em id="statistics_store_expire">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="goods">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_goods'];?>"><span><em id="statistics_goods"></em></span></sub></div>
        <h3><?php echo $lang['im_goods'];?></h3>
        <h5><?php echo $lang['dashboard_wel_goods_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w25pre normal"><a href="<?php echo urlAdminshop('goods','goods');?>"><?php echo $lang['dashboard_wel_new_add'];?><sub title="<?php echo $lang['dashboard_wel_count_goods'];?>"><em id="statistics_week_add_product"></em></sub></a></li>
          <li class="w25pre none"><a href="<?php echo urlAdminshop('goods','waitverify_list');?>">商品审核<sub><em id="statistics_product_verify">0</em></sub></a></li>
          <li class="w25pre none"><a href="<?php echo urlAdminshop('inform','inform_list');?>"><?php echo $lang['dashboard_wel_inform'];?><sub><em id="statistics_inform_list">0</em></sub></a></li>
          <li class="w25pre none"><a href="<?php echo urlAdminshop('brand','brand_apply');?>"><?php echo $lang['dashboard_wel_brnad_applay'];?><sub><em id="statistics_brand_apply">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="trade">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_total_order'];?>"><span><em id="statistics_order"></em></span></sub></div>
        <h3><?php echo $lang['im_trade'];?></h3>
        <h5><?php echo $lang['dashboard_wel_trade_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w18pre none"><a href="<?php echo urlAdminshop('refund','index');?>">退款<sub><em id="statistics_refund"></em></sub></a></li>
          <li class="w18pre none"><a href="<?php echo urlAdminshop('return','index');?>">退货<sub><em id="statistics_return"></em></sub></a></li>
          <li class="w25pre none"><a href="<?php echo urlAdminshop('vr_refund','index');?>">虚拟订单退款<sub><em id="statistics_vr_refund"></em></sub></a></li>
          <li class="w18pre none"><a href="<?php echo urlAdminshop('complain','complain_new_list');?>"><?php echo $lang['dashboard_wel_complain'];?><sub><em id="statistics_complain_new_list">0</em></sub></a></li>
          <li class="w20pre none"><a href="<?php echo urlAdminshop('complain','complain_handle_list');?>"><?php echo $lang['dashboard_wel_complain_handle'];?><sub><em id="statistics_complain_handle_list">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <dl class="operation">
      <dt>
        <div class="ico"><i></i></div>
        <h3><?php echo $lang['im_operation'];?></h3>
        <h5><?php echo $lang['dashboard_wel_stat_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w15pre none"><a href="<?php echo urlAdminshop('groupbuy','groupbuy_verify_list');?>"><?php echo $lang['dashboard_wel_groupbuy'];?><sub><em id="statistics_groupbuy_verify_list">0</em></sub></a></li>
          <li class="w17pre none"><a href="<?php echo urlAdminshop('pointprod','pointorder_list');?>"><?php echo $lang['dashboard_wel_point_order'];?><sub><em id="statistics_points_order">0</em></sub></a></li>
          <li class="w17pre none"><a href="<?php echo urlAdminshop('bill','show_statis');?>"><?php echo $lang['dashboard_wel_check_billno'];?><sub><em id="statistics_check_billno">0</em></sub></a></li>
          <li class="w17pre none"><a href="<?php echo urlAdminshop('bill','show_statis');?>"><?php echo $lang['dashboard_wel_pay_billno'];?><sub><em id="statistics_pay_billno">0</em></sub></a></li>
          <li class="w17pre none"><a href="<?php echo urlAdminshop('mall_consult', 'index');?>">平台客服<sub><em id="statistics_mall_consult">0</em></sub></a></li>
          <li class="w17pre none"><a href="<?php echo urlAdminshop('delivery', 'index', array('sign' => 'verify'));?>">服务站<sub><em id="statistics_delivery_point">0</em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <?php if (OPEN_STORE_EXTENSION_STATE==10){?>
    <dl class="extension">
      <dt>
        <div class="ico"><i></i><sub title="<?php echo $lang['dashboard_wel_extension_total'];?>"><span><em id="statistics_extension_total"></em></span></sub></div>
        <h3>推广体系</h3>
        <h5><?php echo $lang['dashboard_wel_extension_des'];?></h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="<?php echo urlAdminExtension('extension_promotion','index');?>"><?php echo $lang['dashboard_wel_extension_new'];?><sub><em id="statistics_week_active_extension"></em></sub></a></li>          <li class="w33pre none"><a href="<?php echo urlAdminExtension('extension_promotion','apply_list');?>"><?php echo $lang['dashboard_wel_extension_apply'];?><sub><em id="statistics_extension_apply">0</em></sub></a></li>
          <li class="w34pre none"><a href="<?php echo urlAdminExtension('extension_commisputout','apply_list');?>"><?php echo $lang['dashboard_wel_commisputout_apply'];?><sub><em id="statistics_commisputout_apply"></em></sub></a></li>
        </ul>
      </dd>
    </dl>
    <?php }?>
    <?php if (C('cms_isuse') != null) {?>
    <dl class="cms">
      <dt>
        <div class="ico"><i></i></div>
        <h3>CMS</h3>
        <h5>资讯文章/图片画报/会员评论</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="<?php echo urlAdminCms('cms_article', 'cms_article_list_verify');?>">文章审核<sub><em id="statistics_cms_article_verify">0</em></sub></a></li>
          <li class="w33pre none"><a href="<?php echo urlAdminCms('cms_picture', 'cms_picture_list_verify');?>">画报审核<sub><em id="statistics_cms_picture_verify">0</em></sub></a></li>
          <li class="w34pre none"><a href="<?php echo urlAdminCms('cms_comment', 'comment_manage');?>">评论<sub></sub></a></li>
        </ul>
      </dd>
    </dl>
    <?php }?>
    <?php if (C('circle_isuse') != null) {?>
    <dl class="circle">
      <dt>
        <div class="ico"><i></i></div>
        <h3>圈子</h3>
        <h5>申请开通/圈内话题及举报</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="<?php echo urlAdminCircle('circle_manage', 'circle_verify');?>">圈子申请<sub><em id="statistics_circle_verify">0</em></sub></a></li>
          <li class="w33pre none"><a href="<?php echo urlAdminCircle('circle_theme', 'theme_list');?>">话题</a></li>
          <li class="w34pre none"><a href="<?php echo urlAdminCircle('circle_inform', 'inform_list');?>">举报</a></li>
        </ul>
      </dd>
    </dl>
    <?php }?>
    <?php if (C('microshop_isuse') != null){?>
    <dl class="microshop">
      <dt>
        <div class="ico"><i></i></div>
        <h3>微商城</h3>
        <h5>随心看/个人秀/店铺街</h5>
      </dt>
      <dd>
        <ul>
          <li class="w33pre none"><a href="<?php echo urlAdminMicroshop('goods', 'index');?>">随心看</a></li>
          <li class="w33pre none"><a href="<?php echo urlAdminMicroshop('personal', 'index');?>">个人秀</a></li>
          <li class="w34pre none"><a href="<?php echo urlAdminMicroshop('store', 'index');?>">店铺街</a></li>
        </ul>
      </dd>
    </dl>
    <?php }?>    
    <div class=" clear"></div>
    <div class="system-info"></div>
  </div>
</div>
<script type="text/javascript">
var normal = ['week_add_member','week_add_product','ccard_total','extension_total'];
var work = ['store_joinin','store_bind_class_applay','store_reopen_applay','store_expired','store_expire','brand_apply','cashlist','groupbuy_verify_list','points_order','complain_new_list','complain_handle_list', 'product_verify','inform_list','refund','return','vr_refund','cms_article_verify','cms_picture_verify','circle_verify','check_billno','pay_billno','mall_consult','delivery_point','offline','week_active_ccard','ccard_apply','week_active_extension','extension_apply','commisputout_apply'];
$(document).ready(function(){
	$.getJSON("index.php?act=welcome&op=statistics", function(data){
	  $.each(data, function(k,v){
		  $("#statistics_"+k).html(v);
		  if (v!= 0 && $.inArray(k,work) !== -1){
			$("#statistics_"+k).parent().parent().parent().removeClass('none').addClass('high');
		  }else if (v == 0 && $.inArray(k,normal) !== -1){
			$("#statistics_"+k).parent().parent().parent().removeClass('normal').addClass('none');
		  }
	  });
	});
	//自定义滚定条
	$('#system-info').perfectScrollbar();
});
</script>