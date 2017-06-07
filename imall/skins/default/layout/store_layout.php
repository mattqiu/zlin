<?php defined('InIMall') or exit('Access Invalid!');?>
<?php 
//自动手机版转跳
if (userAgent()=='mobile'){
	global $config;
	if (!empty($_GET['extension'])){
		$extension_info = '&extension='.$_GET['extension'];
	}else{
		$extension_info = '';
	}
    if(!empty($config['wap_site_url'])){
        $url = $config['wap_site_url'];
        if($_GET['act'] == 'goods') {
            $url .= '/tmpl/product_detail.html?goods_id=' . $_GET['goods_id'];
        }else{
			$url .= '/tmpl/store.html?store_id=' . $_GET['store_id'];
		}
    } else {
		$stote_id = $_GET['store_id'];
        $url = $config['site_url'].'/tmpl/store.html?store_id='.$store_id;
    }
	$url .= $extension_info;
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
<meta name="author" content="IMall">
<meta name="copyright" content="<?php echo C('site_name'); ?> Inc. All Rights Reserved">
<link rel="shortcut icon" href="<?php echo SHOP_SKINS_URL;?>/images/favicon.ico" />
<link rel="icon" href="<?php echo SHOP_SKINS_URL;?>/images/animated_favicon.gif" type="image/gif" />
<link href="<?php echo RESOURCE_SITE_URL;?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo SHOP_SKINS_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL;?>/css/home_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL;?>/css/home_login.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL;?>/css/shop.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL;?>/css/shop_custom.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL;?>/store/<?php echo $output['store_theme'];?>/css/style.css" rel="stylesheet" type="text/css">
<!--[if IE 6]><style type="text/css">
body {_behavior: url(< ?php echo SHOP_SKINS_URL;?>/css/csshover.htc);}
</style>
<![endif]-->
<script>
  var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';
  var _CHARSET = '<?php echo strtolower(CHARSET);?>';
  var SITEURL = '<?php echo SHOP_SITE_URL;?>'; //MAIN_SITE_URL
  var SHOP_SITE_URL = '<?php echo SHOP_SITE_URL;?>';
  var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';
  var SHOP_SKINS_URL = '<?php echo SHOP_SKINS_URL;?>';
  
  var connect_qq_isuse = '<?php echo C('qq_isuse');?>';
　var connect_sn_isuse = '<?php echo C('sina_isuse');?>';
　var connect_wx_isuse = '<?php echo C('wx_isuse');?>';
</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.SuperSlide.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/shop.js" charset="utf-8"></script>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<!--[if IE 6]>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_PNG.js"></script>
<script>
DD_belatedPNG.fix('.pngFix,.pngFix:hover');
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
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/IE6_MAXMIX.js"></script>
<![endif]-->
</head>
<body>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php 
  if (!$output['show_own_copyright']){
    include template('layout/layout_top');
	include template('layout/layout_header');
  }else{
	include template('layout/store_top');
	include template('layout/store_header');
  }
?>
<?php require_once template('/store/'.$output['store_theme'].'/store_nav');?>

<div id="web_body_content" class="public-body-layout" <?php if ($output['decoration_background_style']){?>style="<?php echo $output['decoration_background_style'];?>"<?php }?>>  
  <?php require_once($tpl_file);?>
</div>
<?php include template('layout/sidebar_right');?>
<div id="web_footer_content" class="public-footer-layout"> 
  <?php 
    if (!$output['show_own_copyright'] || $output['store_info']['is_own_shop']){
      include template('layout/layout_footer');
    }else{
  ?>
  <div class="blank5"></div>
  <div class="footer">
    <div class="footerBody">
      <Div class="block">
        <?php require_once template('/store/'.$output['store_theme'].'/store_footer');?>
      </Div>
    </div>
  </div>
  <?php }?>
</div>
<?php if (OPEN_STORE_EXTENSION_STATE == 1){?>
<?php if ($output['store_info']['extension_op']==1 && ($output['store_info']['promotion_apply']==1 || $output['store_info']['saleman_apply']==1) && !empty($output['store_info']['extension_adv']) ){?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/extension_ad.js"></script>
<script type="text/javascript" >
    //使用参数：1.标题，2.链接地址，3.内容简介
	 window.onload=function(){
		var pop=new Pop('','','');
	}
</script>
<div id="adv_pop" style="display:none;">
  <div id="adv_popHead">
	<a id="adv_popClose" title="关闭">关闭</a>
    <h2>温馨提示</h2>
  </div>
  <div id="adv_popContent">
	<dl>
	  <dt id="adv_popTitle"></dt>
	  <dd id="adv_popIntro"><?php echo htmlspecialchars_decode($output['store_info']['extension_adv']);?></dd>
	</dl>
	<p id="adv_popMore"></p>
  </div>
</div>
<?php }?>
<?php }?>
</body>
</html>