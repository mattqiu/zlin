<?php defined('InIMall') or exit('Access Invalid!');?>
<!doctype html>
<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7 charset=<?php echo CHARSET;?>">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
<title><?php if ($output['goods_title']){ echo $output['goods_title'].' - '.$GLOBALS['setting_config']['flea_site_title'];}else{echo $GLOBALS['setting_config']['flea_site_title'];}?></title>
<meta name="keywords" content="<?php if ($output['seo_keywords']){ echo $output['seo_keywords'].',';}echo $GLOBALS['setting_config']['flea_site_keywords']; ?>" />
<meta name="description" content="<?php if ($output['seo_description']){ echo $output['seo_description'].',';}echo $GLOBALS['setting_config']['flea_site_description']; ?>" />
<?php echo html_entity_decode($GLOBALS['setting_config']['qq_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($GLOBALS['setting_config']['sina_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($GLOBALS['setting_config']['share_qqzone_appcode'],ENT_QUOTES); ?>
<?php echo html_entity_decode($GLOBALS['setting_config']['share_sinaweibo_appcode'],ENT_QUOTES); ?>
<link href="<?php echo SHOP_SKINS_URL;?>/css/flea.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/flea/common.js" charset="utf-8"></script>
<!--弹出层-->
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/flea/member.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/flea/jquery.flea_area.js" charset="utf-8"></script>
<script type="text/javascript">
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

$(function(){
	$("#close").click(function(){
	     $("#j_headercitylist").css("display","none");
	});
	if ($.cookie('flea_area') != null && $.cookie('flea_area') != ''){
		$('#cityname').html($.cookie('flea_area'));
		$('#show_area').html('<?php echo $lang['flea_index_area']; ?>');
	}else{
		$('#show_area').html('<?php echo $lang['flea_all_country']?>');
	}
});
</script>
<style type="text/css">
<!--
body { font-size:12px;}
ul,li { list-style:none; padding:0; margin:0;}
-->
</style>
</head>
<body>
<?php require_once template('layout/layout_top');?>
<div id="topHeader" class="wrapper">
  <div class="site-logo"><a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.C('site_logo'); ?>"></a>  </div>
  <!--地区-->
  <span id="cityname"></span>
  <div id="cityblock">
    <div id="show_area"></div>
    <ul id="j_headercitylist">
      <li onclick="areaGo(0,'')"><?php echo $lang['flea_all_country']?></li>
      <?php if($output['area_one_level']){?>
	  <?php foreach($output['area_one_level'] as $val){?>
       <li id="<?php echo $val['flea_area_id'];?>">
	    <?php echo $val['flea_area_name'];?>
	  </li>
	  <?php };?>
      <?php };?>
      <a id="close" href="javascript:void(0)">X</a>
     </ul>
    <ul id="citylist"></ul>
  </div>
  <!--地区-->
  <div id="search" class="search">
    <div class="details" id="details">
      <div id="a1" class="form">
        <form action="index.php" onSubmit="return searchInput();" method="get">
          <input name="act" id="search_act" value="flea_class" type="hidden">
          <div class="formstyle">
            <input id="keyword" name="key_input" type="text" class="textinput" value="<?php echo $lang['im_searchdefault']; ?>" onFocus="searchFocus(this)" onBlur="searchBlur(this)" maxlength="200"/>
            <input name="" type="submit" class="search-button" value="">
          </div>
        </form>
      </div>
      <div class="tag">
        <?php if(is_array($output['flea_hot_search']) and !empty($output['flea_hot_search'])) { foreach($output['flea_hot_search'] as $val) { ?>
        <a href="index.php?act=flea_class&key_input=<?php echo urlencode($val);?>"><?php echo $val; ?></a>
        <?php } }?>
      </div>
    </div>
  </div>
</div>
<div id="navBar" class="mb10">
  <ul>
    <li class="fn-left none" <?php echo $output['index_sign']=='index' && $output['index_sign'] != '0'?'class="current"':'class="link"'; ?> >
      <a href="<?php echo SHOP_SITE_URL;?>"><span><?php echo $lang['im_index'];?></span><span class="line"></span></a>
    </li>
    <?php if(OPEN_MODULE_FLEA_STATE ==1){?>
    <li class="fn-left link" <?php echo $output['index_sign'] == 'flea' && $output['index_sign'] != '0'?'class="current"':'class="link"'; ?> >
      <a href="<?php echo urlShop('flea','index');?>"><span>闲置市场</span><span class="line"></span></a>
    </li>
    <?php }?>     
    <?php if (intval(C('groupbuy_allow')) === 1){ ?>
    <li class="fn-left" <?php echo $output['index_sign'] == 'groupbuy' && $output['index_sign'] != '0'?'class="current"':'class="link"'; ?> >
      <a href="<?php echo urlShop('show_groupbuy','index');?>"><span><?php echo $lang['im_groupbuy'];?></span><span class="line"></span></a>
    </li>
    <?php }?>  
    <li class="fn-left" <?php echo $output['index_sign'] == 'brand' && $output['index_sign'] != '0'?'class="current"':'class="link"'; ?>>
      <a href="<?php echo urlShop('brand','index');?>"><span><?php echo $lang['im_brand'];?></span><span class="line"></span></a>
    </li>
    <li class="fn-left" <?php echo $output['index_sign'] == 'store_list' && $output['index_sign'] != '0'?'class="current"':'class="link"'; ?>>
      <a href="<?php echo urlShop('store_list','index');?>"><span>店铺</span><span class="line"></span></a>
    </li>
    <?php if (C('points_isuse') && C('pointshop_isuse')){ ?>
    <li class="fn-left" <?php echo $output['index_sign'] == 'pointshop' && $output['index_sign'] != '0'?'class="current"':'class="link"'; ?> >
      <a href="<?php echo urlShop('pointshop','index');?>"><span><?php echo $lang['im_pointprod'];?></span><span class="line"></span></a>
    </li>
    <?php } ?>
    <li class="fn-left" <?php echo $output['index_sign'] == 'special' && $output['index_sign'] != '0'?'class="current"':'class="link"'; ?>>
      <a href="<?php echo urlShop('special','index');?>"><span>专题</span><span class="line"></span></a>
    </li>
    <!-- 功能模块 -->
    <?php if (C('cms_isuse')){ ?>
    <li class="fn-left"><a href="<?php echo urlCMS('index', 'index');?>"> <?php echo $lang['im_cms'];?></a></li>
    <?php } ?>
    <?php if (C('circle_isuse')){ ?>
    <li class="fn-left"><a href="<?php echo urlCircle('index', 'index');?>"> <?php echo $lang['im_circle'];?></a></li>
    <?php } ?>
    <?php if (C('microshop_isuse')){ ?>
    <li class="fn-left"><a href="<?php echo urlMicroshop('index', 'index');?>"> <?php echo $lang['im_microshop'];?></a></li>
    <?php } ?>
    <?php if (OPEN_MODULE_WEIXIN_STATE == 1){ ?>
    <li class="fn-left"><a href="<?php echo urlWeiXin('index', 'index');?>"> <?php echo $lang['im_weixin'];?></a></li>
    <?php } ?>
    
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '1'){?>
    <li class="fn-left"><a
      <?php
        if($nav['nav_new_open']) {
            echo ' target="_blank"';
        }
        switch($nav['nav_type']) {
            case '0':
                echo ' href="' . $nav['nav_url'] . '"';
                break;
            case '1':
                echo ' href="' . urlShop('search', 'index',array('cate_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['cate_id']) && $_GET['cate_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
            case '2':
                echo ' href="' . urlShop('article', 'article',array('ac_id'=>$nav['item_id'])) . '"';
                if (isset($_GET['ac_id']) && $_GET['ac_id'] == $nav['item_id']) {
                    echo ' class="current"';
                }
                break;
            case '3':
                echo ' href="' . urlShop('activity', 'index', array('activity_id'=>$nav['item_id'])) . '"';
                if ((isset($_GET['activity_id']) && $_GET['activity_id'] == $nav['item_id']) || ($nav['item_id']==0 && $_GET['act']=='activity')) {
                    echo ' class="current"';
                }
                break;
        }
      ?>>
	  <?php echo $nav['nav_title'];?></a>
    </li>
    <?php }?>
    <?php }?>
    <?php }?>
  </ul>
  <!--地区-->
  <?php if($output['area_two_level']){?>
	<?php foreach($output['area_two_level'] as $val){?>
	  <div style="display:none;" id="hidden_<?php echo $val['id'];?>"><?php echo $val['content'];?></div>
	<?php };?>
  <?php };?>
  <!--地区-->
</div>
<?php require_once($tpl_file);?>
<?php include template('layout/sidebar_right');?>
<?php require_once template('layout/layout_footer');?>