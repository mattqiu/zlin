<?php defined('InIMall') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php echo $output['html_title'];?></title>
<meta name="keywords" content="<?php echo $GLOBALS['setting_config']['site_keywords']; ?>" />
<meta name="description" content="<?php echo $GLOBALS['setting_config']['site_description']; ?>" />
<meta name="author" content="IMall">
<meta name="copyright" content="<?php echo C('site_name'); ?> Inc. All Rights Reserved">
<link href="<?php echo SHOP_SKINS_URL;?>/css/base.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL;?>/css/home_header.css" rel="stylesheet" type="text/css">
<link href="<?php echo RESOURCE_SITE_URL;?>/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/html5shiv.js"></script>
      <script src="<?php echo RESOURCE_SITE_URL;?>/js/respond.min.js"></script>
<![endif]-->
<style type="text/css">
body, .header-wrap { background-color: #FAFAFA;}
.wrapper { width: 1200px;}
#faq { display: none;}
.msg { font: 100 36px/48px arial,"microsoft yahei"; color: #555; background-color: #FFF; text-align: center; width: 100%; border: solid 1px #E6E6E6; margin-bottom: 10px; padding: 120px 0;}
.msg i { font-size: 48px; vertical-align: middle; margin-right: 10px;}
</style>
<script>var COOKIE_PRE = '<?php echo COOKIE_PRE;?>';var _CHARSET = '<?php echo strtolower(CHARSET);?>';var SITEURL = '<?php echo SHOP_SITE_URL;?>';var SHOP_RESOURCE_SITE_URL = '<?php echo SHOP_RESOURCE_SITE_URL;?>';var RESOURCE_SITE_URL = '<?php echo RESOURCE_SITE_URL;?>';var SHOP_SKINS_URL = '<?php echo SHOP_SKINS_URL;?>';</script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common.js"></script>
<script type="text/javascript">
$(function(){
	$("#details").children('ul').children('li').click(function(){
		$(this).parent().children('li').removeClass("current");
		$(this).addClass("current");
		$('#search_act').attr("value",$(this).attr("act"));
	});
	var search_act = $("#details").find("li[class='current']").attr("act");
	$('#search_act').attr("value",search_act);
	$("#keyword").blur();
});
</script>
</head>
<body>
<?php require_once template('layout/layout_top');?>
<div class="header-wrap"><header class="public-head-layout wrapper">
  <h1 class="site-logo"><a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h1></header></div>
<div class="msg">
      <?php if($output['msg_type'] == 'error'){ ?>
      <i class="fa fa-info-circle" style="color: #39C;"></i>
        <?php }else { ?>
      <i class="fa fa-check-circle" style=" color: #099;"></i>
        <?php } ?>
        <?php require_once($tpl_file);?>
</div>
<script type="text/javascript">
<?php if (!empty($output['url'])){
?>
	window.setTimeout("javascript:location.href='<?php echo $output['url'];?>'", <?php echo $time;?>);
<?php
}else{
?>
	window.setTimeout("javascript:history.back()", <?php echo $time;?>);
<?php
}?>
</script>
<div class="separate_line_bottom"></div>
<?php require_once template('layout/footer_copyright');?>
</body>
</html>