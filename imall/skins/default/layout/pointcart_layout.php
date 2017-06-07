<?php defined('InIMall') or exit('Access Invalid!');?>
<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
<meta name="description" content="<?php echo $output['seo_description']; ?>" />
<meta name="author" content="zhenyang">
<meta name="copyright" content="<?php echo C('site_name'); ?> Inc. All Rights Reserved">
<meta property="qc:admins" content="" />
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

var connect_qq_isuse = '<?php echo C('qq_isuse');?>';
var connect_sn_isuse = '<?php echo C('sina_isuse');?>';
var connect_wx_isuse = '<?php echo C('wx_isuse');?>';
</script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.masonry.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript">
    var PRICE_FORMAT = '<?php echo $lang['currency'];?>%s';
</script>
</head>
<body>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>
<?php include template('layout/layout_top');?>
<!-- PublicHeadLayout Begin -->
<div class="header-wrap">
  <header class="public-head-layout wrapper">
    <h1 class="site-logo"><a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h1> 
  </header>
</div>
<div class="separate_line_header"></div>
<!-- PublicHeadLayout End -->
<?php require_once($tpl_file);?>
<div class="separate_line_bottom"></div>
<?php include template('layout/sidebar_right');?>
<?php require_once template('layout/footer_copyright');?>