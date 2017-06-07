<?php defined('InIMall') or exit('Access Invalid!');?>

<script src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" type="text/javascript" charset="utf-8"></script>

<!-- PublicTopLayout Begin -->
<div class="public-top-layout">
  <div class="topbar wrapper">
    <div class="user-entry">
      <?php if($_SESSION['is_login'] == '1'){?>
      <?php echo $lang['im_hello'];?> 
      <span>
        <a href="<?php echo urlShop('member','home');?>"><?php echo $_SESSION['member_name'];?></a>
        <?php if ($output['member_info']['level_name']){ ?>
        <div class="imcss-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?></div>
        <?php } ?>
      </span> 
	  <?php echo $lang['im_comma'],$lang['welcome_to_site'];?> 
      <a href="<?php echo SHOP_SITE_URL;?>"  title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><span><?php echo $output['setting_config']['site_name']; ?></span></a> 
      <span>[<a href="<?php echo urlShop('login','logout');?>"><?php echo $lang['im_logout'];?></a>] </span>
      <?php }else{?>
      <?php echo $lang['im_hello'].$lang['im_comma'].$lang['welcome_to_site'];?> 
      <a href="<?php echo SHOP_SITE_URL;?>" title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><?php echo $output['setting_config']['site_name']; ?></a> 
      <span>[<a href="<?php echo urlShop('login');?>"><?php echo $lang['im_login'];?></a>]</span> 
      <span>[<a href="<?php echo urlShop('login','register');?>"><?php echo $lang['im_register'];?></a>]</span>
      <?php }?>
    </div>     
    <div class="quick-menu">
      <!-- 
      <dl class="menuitem mobile">
        <dt class="title"><a href="<?php echo MAIN_SITE_URL;?>/wap/index.html">手机APP</a></dt>
        <dd class="submenu">
          <div class="detail">
            <h4>扫一扫,进入手机商城</h4>
            <img src="<?php echo UPLOAD_SITE_URL;?>/app/public/images/app_down.png" >
          </div>
        </dd>
      </dl>
       -->   
      <dl class="menuitem">
        <dt class="title"><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=show_joinin&op=index" title="招商代理">招商代理</a><i class="point"></i></dt>
        <dd  class="submenu">
          <ul class="ul">
		    <li><a href="<?php echo urlShop('show_joinin','index');?>" title="商家入驻">商家入驻</a></li>
            <li><a href="<?php echo urlShop('seller_login','show_login');?>" title="登录商家管理中心">商家登录</a></li>
          </ul>
        </dd>
      </dl>
      <dl class="menuitem">
        <dt class="title"><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order">我的订单</a><i class="point"></i></dt>
        <dd class="submenu">
          <ul class="ul">
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order&state_type=state_new">待付款订单</a></li>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order&state_type=state_send">待确认收货</a></li>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order&state_type=state_noeval">待评价交易</a></li>
          </ul>
        </dd>
      </dl>
      <dl class="menuitem">
        <dt class="title"><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fglist"><?php echo $lang['im_favorites'];?></a><i class="point"></i></dt>
        <dd class="submenu">
          <ul  class="ul">
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fglist">商品收藏</a></li>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fslist">店铺收藏</a></li>
          </ul>
        </dd>
      </dl>
      <!-- 
      <dl class="menuitem">
        <dt class="title">客户服务<i class="point"></i></dt>
        <dd class="submenu">
          <ul  class="ul">
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 2));?>">帮助中心</a></li>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 5));?>">售后服务</a></li>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 6));?>">客服中心</a></li>
          </ul>
        </dd>
      </dl>
      <dl class="menuitem weixin">
        <dt class="title"><i class="fa fa-weixin"></i>关注我们</dt>
        <dd class="submenu">
          <div class="detail">
            <h4>扫描二维码<br/>关注商城微信号</h4>
            <img src="<?php echo SHOP_SKINS_URL;?>/images/weixin_img.jpg" >
          </div> 
        </dd>
      </dl>
       -->
      <?php
      if(!empty($output['nav_list']) && is_array($output['nav_list'])){
	    foreach($output['nav_list'] as $nav){
	      if($nav['nav_location']<1){
	      	$output['nav_list_top'][] = $nav;
	      }
	    }
      }
	  ?>
      <?php if(!empty($output['nav_list_top']) && is_array($output['nav_list_top'])){?>
      <dl class="menuitem">
        <dt class="title">站点导航<i></i></dt>
        <dd class="submenu"> 
          <ul>
            <?php foreach($output['nav_list_top'] as $nav){?>
            <li><a
            <?php
              if($nav['nav_new_open']) {
                echo ' target="_blank"';
              }
              echo ' href="';
              switch($nav['nav_type']) {
        	    case '0':echo $nav['nav_url'];break;
        	    case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
        	    case '2':echo urlShop('article', 'article', array('ac_id'=>$nav['item_id']));break;
        	    case '3':echo urlShop('activity', 'index', array('activity_id'=>$nav['item_id']));break;
              }
              echo '"';
            ?>><?php echo $nav['nav_title'];?></a>
            </li>
            <?php }?>
          </ul>
        </dd>
      </dl>
      <?php }?>
    </div>
  </div>
</div>
<script type="text/javascript">
//弹出子菜单
$(function() {
	$(".quick-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
});
</script>
<!-- PublicTopLayout End -->