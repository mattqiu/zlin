<?php defined('InIMall') or exit('Access Invalid!');?>

<!-- PublicTopBanner Begin -->
<div class="header-wrap" id="top-banner"> 
  <div class="banner" id="banner_box">
  <?php echo loadadv(1048);?>
  <a href="javascript:void(0);" class="closebtn" title="关闭"></a>
  </div>
</div>
<script type="text/javascript">
$(function(){
	var top_banner_h = $("#top-banner a img").height();
	$("#top-banner").css("height",top_banner_h);
	$("#banner_box").css("height",top_banner_h);
	
	$("#banner_box").css("left",($(window).width()-$("#top-banner a img").width())/2);
	$("#top-banner").slideDown(800);
	$("#top-banner .closebtn").click(function(){
		$("#top-banner").slideUp(800);
		//$("#top-banner").hide();
	});	
});
</script>
<!-- PublicTopBanner End -->