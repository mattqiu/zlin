<?php defined('InIMall') or exit('Access Invalid!');?>
<?php 
//自动手机版转跳
$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
$uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap'))
{
	global $config;
	if (!empty($_GET['extension'])){
		$extension_info = 'extension='.$_GET['extension'];
	}else{
		$extension_info = '';
	}
    if(!empty($config['wap_site_url'])){
        $url = $config['wap_site_url'];
        if($_GET['act'] == 'goods') {
            $url .= '/tmpl/product_detail.html?goods_id=' . $_GET['goods_id'];
			if (!empty($extension_info)){
				$url .= '&'.$extension_info;
			}
        }else{
			if (!empty($extension_info)){
				$url .= '/index.html?'.$extension_info;
			}
		}
    } else {
        $url = $config['site_url'];
    }
    header('Location:' . $url);
    exit();
}
?>
<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="iMall">
<meta name="copyright" content="<?php echo C('site_name'); ?> Inc. All Rights Reserved">
<?php echo html_entity_decode($output['setting_config']['qq_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($output['setting_config']['sina_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($output['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($output['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<style type="text/css">
body {
  _behavior: url(<?php echo SHOP_SKINS_URL;?>/css/csshover.htc);
}
</style>
<link rel="shortcut icon" href="<?php echo SHOP_SKINS_URL;?>/images/favicon.ico" />
<link rel="icon" href="<?php echo SHOP_SKINS_URL;?>/images/animated_favicon.gif" type="image/gif" />
<link href="<?php echo SHOP_SKINS_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL;?>/css/home_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL;?>/css/home_login.css" rel="stylesheet" type="text/css">
<link href="<?php echo RESOURCE_SITE_URL;?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo SHOP_RESOURCE_SITE_URL;?>/font/font-awesome/css/font-awesome-ie7.min.css">
<![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
  <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]>
  <script src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_PNG.js"></script>
<script>
  DD_belatedPNG.fix('.pngFix');
</script>
<script>
// <![CDATA[
if((window.navigator.appName.toUpperCase().indexOf("MICROSOFT")>=0)&&(document.execCommand))
  try{
    document.execCommand("BackgroundImageCache", false, true);
  }
  catch(e){}
// ]]>
</script>
<![endif]-->
<script>
var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';
var _CHARSET = '<?php echo strtolower(CHARSET);?>';
var SITEURL = '<?php echo SHOP_SITE_URL;?>';
var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
var SHOP_SKINS_URL = '<?php echo SHOP_SKINS_URL;?>';
</script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.masonry.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript">
var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';
$(function(){
	//首页左侧分类菜单
	$(".category ul.menu").find("li").each(
		function() {
			$(this).hover(
				function() {
				    var cat_id = $(this).attr("cat_id");
					var menu = $(this).find("div[cat_menu_id='"+cat_id+"']");
					menu.show();
					$(this).addClass("hover");
					if(menu.attr("hover")>0) return;
					menu.masonry({itemSelector: 'dl'});
					var menu_height = menu.height();
					if (menu_height < 60) menu.height(80);
					menu_height = menu.height();
					var li_top = $(this).position().top;
					if ((li_top > 60) && (menu_height >= li_top)) $(menu).css("top",-li_top+50);
					if ((li_top > 150) && (menu_height >= li_top)) $(menu).css("top",-li_top+90);
					if ((li_top > 240) && (li_top > menu_height)) $(menu).css("top",menu_height-li_top+90);
					if (li_top > 300 && (li_top > menu_height)) $(menu).css("top",60-menu_height);
					if ((li_top > 40) && (menu_height <= 120)) $(menu).css("top",-5);
					menu.attr("hover",1);
				},
				function() {
					$(this).removeClass("hover");
				    var cat_id = $(this).attr("cat_id");
					$(this).find("div[cat_menu_id='"+cat_id+"']").hide();
				}
			);
		}
	);
    //购物车、我的商城
	$(".head-user-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
	$('.head-user-menu .my-mall').mouseover(function(){// 最近浏览的商品
		load_history_information();
		$(this).unbind('mouseover');
	});
	$('.head-user-menu .my-cart').mouseover(function(){// 运行加载购物车
		load_cart_information();
		$(this).unbind('mouseover');
	});
	//搜索框
	$('#button').click(function(){
	    if ($('#keyword').val() == '') {
		    return false;
	    }
	});
    <?php if (C('fullindexer.open')) { ?>
	$('#keyword').focus(function(){
		if ($(this).val() == $(this).attr('title')) {
			$(this).val('').removeClass('tips');
		}
	}).blur(function(){
		if ($(this).val() == '' || $(this).val() == $(this).attr('title')) {
			$(this).addClass('tips').val($(this).attr('title'));
		}
	}).blur().autocomplete({
        source: function (request, response) {
            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                 $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
                }
            });
       },
		select: function(ev,ui) {
			$('#keyword').val(ui.item.label);
			$('#top_search_form').submit();
		}
	});	
	
	<?php } ?>
	var act = "<?php echo $_GET['act']?>";
	/*if (act == "store_list"){
		$("#search").children('ul').children('li:eq(1)').addClass("current");
		$("#search").children('ul').children('li:eq(0)').removeClass("current");
		
		$('#search_act').attr("value",$("#search").children('ul').children('li:eq(1)').attr("act"));
		$('#keyword').attr("placeholder",$("#search").children('ul').children('li:eq(1)').attr("title"));
	}
	$("#search").children('ul').children('li').click(function(){
		$(this).parent().children('li').removeClass("current");
		$(this).addClass("current");
		$('#search_act').attr("value",$(this).attr("act"));
		$('#keyword').attr("placeholder",$(this).attr("title"));
	});	*/
	
	$("#search-table").hover(function() {
		$(this).children('.list').show();
	},
	function() {
		$(this).children('.list').hide();
	});
	
	$("#search-table div ul li a").click(function(){		
		$('#search_act').attr("value",$(this).attr("act"));
		$('#keyword').attr("placeholder",$(this).attr("title"));
		$(this).parent().parent().parent().hide();
		$(this).parent().parent().parent().prev('span').html($(this).html());
	});	
	
	$("#keyword").blur();
	//----------------	
	$("#top-banner").slideDown(800);
	$("#top-banner .close").click(function(){
		$("#top-banner").hide();
	});	

});
</script>
</head>
<body>
<!-- PublicTopLayout Begin -->
<?php require_once template('layout/layout_top');?>
<!-- PublicHeadLayout Begin -->
<div class="header-wrap">
  <header class="public-head-layout wrapper">
    <h1 class="site-logo"><a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h1>
    
    <div class="head-app">
    <?php if (C('mobile_isuse') && C('mobile_app')){?>
      <span class="pic"></span>
      <div class="download-app">
        <div class="qrcode"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('mobile_app');?>" ></div>
        <div class="hint">
          <h4>扫描二维码</h4>下载手机客户端
        </div>          
        <div class="addurl">
          <a href="<?php echo C('main_site_url');?>" target="_blank"><i class="fa fa-android"></i>Android</a>
          <a href="<?php echo C('main_site_url');?>" target="_blank"><i class="fa fa-apple"></i>iPhone</a>
        </div>         
        <!-- <div class="addurl">
        <?php if (C('mobile_apk')){?>
          <a href="<?php echo C('mobile_apk');?>" target="_blank"><i class="fa fa-android"></i>Android</a>
        <?php } ?>
        <?php if (C('mobile_ios')){?>
          <a href="<?php echo C('mobile_ios');?>" target="_blank"><i class="fa fa-apple"></i>iPhone</a>
        <?php } ?>
        </div>-->
      </div>
      <?php } ?>
    </div>
    
    
    <div class="ser_n">
      <form class="searchBox" action="<?php echo SHOP_SITE_URL;?>" method="get" >
      <div class="search-table" id="search-table">
        <span class="cur" data-type="1">宝贝</span>
        <div class="list">
          <ul>
            <li><a title="请输入您要搜索的商品关键字" act="search" data-type="1">宝贝</a></li>	
            <li><a title="请输入您要搜索的店铺关键字" act="store_list" data-type="2">商家</a></li>		
          </ul>					
        </div>					
        <em class="arrow"></em>				
      </div>				
      <span class="ipt1">
        <em class="i_search"></em>
        <input class="searchKey" name="keyword" type="text" autocomplete="off" value="<?php echo $_GET['keyword'];?>" placeholder="7.17夏出清好货史上最低价，马上抢购!" data-placeholder="7.17夏出清好货史上最低价，马上抢购!" />
      </span>				
      <input class="search_type" type="hidden" value="1" name="searchType"/>
      <input class="filter" type="hidden" value="search" name="act" id="search_act"/>
      <input class="search_frm" type="hidden" value="searchsuggestion" name="frm">
      <input class="suggest_frm" type="hidden" value="" name="suggest_frm">
      <span class="ipt2"><input type="submit" class="fm_hd_btm_shbx_bttn" value="搜 索"/></span>			
      </form>			
      <div class="clear_f"></div>
      <ul class="searchType none_f"></ul>
      <div class="keyword"><?php echo $lang['hot_search'].$lang['nc_colon'];?>
        <ul>
          <?php if(is_array($output['hot_search']) && !empty($output['hot_search'])) { foreach($output['hot_search'] as $val) { ?>
          <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $val));?>"><?php echo $val; ?></a></li>
          <?php } }?>
        </ul>
      </div>
    </div> 
    
    <div class="head-user-menu">
      <dl class="my-mall">
        <dt><span class="ico"></span>我的商城<i class="arrow"></i></dt>
        <dd>
          <div class="sub-title">
            <h4><?php echo $_SESSION['member_name'];?>
            <?php if ($output['member_info']['level_name']){ ?>
            <div class="imcss-grade-mini" style="cursor:pointer;" onClick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?></div>
            <?php } ?>            
            </h4>
            <a href="<?php echo urlShop('member', 'home');?>" class="arrow">我的用户中心<i></i></a></div>
          <div class="user-centent-menu">
            <ul>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_message&op=message">站内消息(<span><?php echo $output['message_num']>0 ? $output['message_num']:'0';?></span>)</a></li>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order" class="arrow">我的订单<i></i></a></li>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_consult&op=my_consult">咨询回复(<span id="member_consult">0</span>)</a></li>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fglist" class="arrow">我的收藏<i></i></a></li>
              <?php if (C('voucher_allow') == 1){?>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_voucher">代金券(<span id="member_voucher">0</span>)</a></li>
              <?php } ?>
              <?php if (C('points_isuse') == 1){ ?>
              <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_points" class="arrow">我的积分<i></i></a></li>
              <?php } ?>
            </ul>
          </div>
          <div class="browse-history">
            <div class="part-title">
              <h4>最近浏览的商品</h4>
              <span style="float:right;"><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_goodsbrowse&op=list">全部浏览历史</a></span>
            </div>
            <ul>
              <li class="no-goods"><img class="loading" src="<?php echo SHOP_SKINS_URL;?>/images/loading.gif" /></li>
            </ul>
          </div>
        </dd>
      </dl>
      <dl class="my-cart">
        <?php if ($output['cart_goods_num'] > 0) { ?>
        <div class="addcart-goods-num"><?php echo $output['cart_goods_num'];?></div>
        <?php } ?>
        <dt><span class="ico"></span>购物车结算<i class="arrow"></i></dt>
        <dd>
          <div class="sub-title">
            <h4>最新加入的商品</h4>
          </div>
          <div class="incart-goods-box">
            <div class="incart-goods"> <img class="loading" src="<?php echo SHOP_SKINS_URL;?>/images/loading.gif" /> </div>
          </div>
          <div class="checkout"> <span class="total-price">共<i><?php echo $output['cart_goods_num'];?></i><?php echo $lang['im_kindof_goods'];?></span><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=cart" class="btn-cart">结算购物车中的商品</a> </div>
        </dd>
      </dl>
    </div>
  </header>
</div>
<!-- PublicHeadLayout End -->