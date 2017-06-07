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
      <a href="<?php echo urlShop('show_store','', array('store_id'=>$output['store_info']['store_id']),$output['store_info']['store_domain']);?>"  title="<?php echo C('site_name').'-'.$output['store_info']['store_name'];?>" alt="<?php echo C('site_name').'-'.$output['store_info']['store_name'];?>"><span><?php echo $output['store_info']['store_name']; ?></span></a> 
      <span>[<a href="<?php echo urlShop('login','logout');?>"><?php echo $lang['im_logout'];?></a>] </span>
      <?php }else{?>
      <?php echo $lang['im_hello'].$lang['im_comma'].$lang['welcome_to_site'];?> 
      <a href="<?php echo urlShop('show_store','', array('store_id'=>$output['store_info']['store_id']),$output['store_info']['store_domain']);?>" title="<?php echo C('site_name').'-'.$output['store_info']['store_name'];?>" alt="<?php echo C('site_name').'-'.$output['store_info']['store_name'];?>"><?php echo $output['store_info']['store_name']; ?></a> 
      <span>[<a href="<?php echo urlShop('login');?>"><?php echo $lang['im_login'];?></a>]</span> 
      <span>[<a href="<?php echo urlShop('login','register');?>"><?php echo $lang['im_register'];?></a>]</span>
      <?php }?>
    </div>
    <div class="quick-menu">
      <dl class="menuitem mobile">
        <dt class="title"><a href="<?php echo WAP_SITE_URL . '/tmpl/store.html?store_id='.$output['store_info']['store_id'];?>">手机触屏版</a></dt>
        <dd class="submenu">
          <div class="detail">
            <h4>扫描二维码，进入手机商城</h4>
            <img src="<?php echo GetStoreQRCode($output['store_info']['store_id']);?>" >
          </div> 
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
          <ul class="ul">
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fglist">商品收藏</a></li>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fslist">店铺收藏</a></li>
          </ul>
        </dd>
      </dl>
      <dl class="menuitem">
        <dt class="title"><i class="fa fa-weixin"></i>关注我们</dt>
        <dd class="submenu">
          <div class="storeinfo">
            <div class="store-logo">
              <img src="<?php echo getStoreLogo($output['store_info']['store_label'],'store_logo');?>" alt="<?php echo $output['store_info']['store_name'];?>" title="<?php echo $output['store_info']['store_name'];?>" />
            </div>
            <?php include template('/store/'.$output['store_theme'].'/info');?>
            <?php if (!empty($output['store_info']['store_weixin'])){?>
            <div class="imcs-store-code">
              <p><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_STORE.DS.'LOGO' .DS.$output['store_info']['store_weixin'];?>"  title="店铺网址：<?php echo urlShop('show_store', 'index', array('store_id'=>$output['store_info']['store_id']));?>"></p>
              <span class="imcs-store-code-note"><i></i>扫描二维码<br/>关注本店微信号</span> 
            </div>
            <?php }?>
          </div>
        </dd>
      </dl>
      <dl class="menuitem">
        <dt class="title">客户服务<i></i></dt>
        <dd class="submenu">
          <div class="services">
            <?php include template('/store/'.$output['store_theme'].'/callcenter');?>            
          </div>
        </dd>
      </dl>      
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