<?php defined('InIMall') or exit('Access Invalid!');?>

<!--底部版权-->
<div class="public-footer-layout">
  <div id="footer" class="footer_wrapper">
    <p>
      <a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['im_index'];?></a>
      <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
      <?php foreach($output['nav_list'] as $nav){?>
      <?php if($nav['nav_location'] == '2'){?>
      | <a <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	  case '0':echo $nav['nav_url'];break;
    	  case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
    	  case '2':echo urlShop('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	  case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
      }?>"><?php echo $nav['nav_title'];?></a>
      <?php }?>
      <?php }?>
      <?php }?>
    </p>Copyright 2014-2015 <?php echo C('site_name'); ?> Inc.,All rights reserved.
    <br />Powered by <?php echo $GLOBALS['setting_config']['imall_version'];?> <?php echo $GLOBALS['setting_config']['icp_number']; ?>
    <br /><?php echo html_entity_decode($output['setting_config']['statistics_code'],ENT_QUOTES); ?> 
  </div>
</div>
<?php echo getChat($layout);?>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<!-- 对比 -->
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/compare.js"></script>

<script language="javascript">
$(function(){
	// Membership card
	$('[imtype="mcard"]').membershipCard({type:'shop'});
});
</script>
</body>
</html>
