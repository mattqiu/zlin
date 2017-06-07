<?php defined('InIMall') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_SKINS_URL;?>/css/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.SuperSlide.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.flexslider-min.js" charset="utf-8"></script>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ie6.js" charset="utf-8"></script>
<![endif]-->

<div class="clear"></div>

<!-- HomeFocusLayout Begin-->
<div class="home-focus-layout">  
  <?php echo $output['web_html']['index_pic'];?>
</div>
<!--HomeFocusLayout End-->  
  
<div class="home-sale-layout wrapper">
  <?php echo $output['web_html']['index_sale'];?>
  <script type="text/javascript">
      $(function () {		  
		  $('.mt_hotSlider').flexslider({
              directionNav: true,
              pauseOnAction: false
          });
		
          $(".hot_list").hover(function () {
              $(".spxq_slider .flex-direction-nav").show();
          }, function () {
              $(".spxq_slider .flex-direction-nav").hide();
          });
          $(".spxq_slider .flex-direction-nav").hover(function () {
              $(".spxq_slider .flex-direction-nav").show();
          }, function () {
              $(".spxq_slider .flex-direction-nav").hide();
          });
		  
		  $('.mt_hotTab li').each(function (e) {
              $(this).hover(function () {
                  $(".mt_hotTab li").removeClass("on");
                  $(this).addClass("on");
                  $(".mt_hotList").each(function (i) {
                      if (e == i) {
                          $(".mt_hotList").hide();
                          $(this).show();
                      }
                      else {
                          $(this).hide();
                      }
                  });
              });
          });
      });
  </script>    
</div>

<div class="wrapper">
  <div class="mt10">
    <?php echo loadadv(1049);?>
  </div>
</div>

<!--StandardLayout Begin-->
  <?php echo $output['web_html']['index'];?>
<!--StandardLayout End-->
<div class="wrapper">
  <div class="mt10"><?php echo loadadv(9,'html');?></div>
  <div class="mt10">
  <?php echo loadadv(1057,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1058,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1059,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1060,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1061,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1062,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1063,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1064,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1065,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1066,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1067,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1068,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1069,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1070,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1071,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1072,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1073,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1074,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1075,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1076,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1077,'html');?>
  </div>
  <div class="mt10">
  <?php echo loadadv(1078,'html');?>
  </div>
</div>

<div class="nav_Sidebar">
    <a class="nav_Sidebar_1" href="javascript:;" ></a>
    <a class="nav_Sidebar_2" href="javascript:;" ></a>
    <a class="nav_Sidebar_3" href="javascript:;" ></a>
    <a class="nav_Sidebar_4" href="javascript:;" ></a>
    <a class="nav_Sidebar_5" href="javascript:;" ></a>
    <a class="nav_Sidebar_6" href="javascript:;" ></a> 
    <a class="nav_Sidebar_7" href="javascript:;" ></a>
    <a class="nav_Sidebar_8" href="javascript:;" ></a>
</div>