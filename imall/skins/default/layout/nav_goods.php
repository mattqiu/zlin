<?php defined('InIMall') or exit('Access Invalid!');?>

<!-- publicNavLayout Begin -->
<nav class="public-nav-layout channel-<?php echo $output['channel_style'];?>">
  <div class="wrapper">
    <div class="all-category">
      <div class="title">
        <h3><a href="<?php echo urlShop('category');?>"><?php echo $lang['im_all_goods_class'];?></a></h3>
        <i class="arrow"></i>
      </div>
      <div class="category">
        <ul class="menu">          
          <?php if (!empty($output['show_goods_class']) && is_array($output['show_goods_class'])) {?>           
          <?php $show_class_num=8;?>
          <!--精选市场-->         
          <?php if (!empty($output['show_goods_class']['recommend']) && is_array($output['show_goods_class']['recommend'])) {?>
          <?php $i = 0;?>
          <?php $show_class_num=7;?>
          <li cat_id="goods_class_recommend" class="recommend odd">
            <div class="class">
              <span class="ico"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/jingxuan.png';?>"></span>
              <div class="caption">
                <h4><a href="">精选市场</a></h4>
                <span class="recommend-class">商城推荐商品......</span>
                <span class="arrow"></span> 
              </div>
            </div>
            <div class="sub-class" cat_menu_id="goods_class_recommend">
              <dl>
              <?php foreach ($output['show_goods_class']['recommend'] as $key => $val) {?>
              <?php $i++; if ($i>12){break;}?> 
                <?php if(!empty($val['channel_id'])) {?>
                <dd><a href="<?php echo urlShop('channel','index',array('id'=> $val['channel_id']));?>"><?php echo $val['gc_name'];?></a></dd>
              	<?php } else {?>
              	<dd><a href="<?php echo urlShop('search','index',array('cate_id'=> $val['gc_id']));?>"><?php echo $val['gc_name'];?></a></dd>
              	<?php }?>                
              <?php }?>
              </dl>
            </div>
          </li>            
          <?php }?>   
          <?php $i = 0;?>
          <?php foreach ($output['show_goods_class'] as $key => $val) {?>
          <?php if ($key=='recommend'){continue;}?>
          <?php $i++;?>
          <li cat_id="<?php echo $val['gc_id'];?>" class="<?php echo $i%2==1 ? 'odd':'even';?>" <?php if($i>$show_class_num){?>style="display:none;"<?php }?>>
            <div class="class">
              <?php if(!empty($val['pic'])) { ?>
              <span class="ico"><img src="<?php echo $val['pic'];?>"></span>
              <?php } ?>
              <?php if(!empty($val['channel_id'])) {?>
              <h4><a href="<?php echo urlShop('channel','index',array('id'=> $val['channel_id']));?>"><?php echo $val['gc_name'];?></a></h4>
              <?php } else {?>
              <h4><a href="<?php echo urlShop('search', 'index', array('cate_id'=>$val['gc_id']));?>"><?php echo $val['gc_name'];?></a></h4>
              <?php }?>
              <span class="recommend-class">
              <?php if (!empty($val['class3']) && is_array($val['class3'])) { ?>
              <?php foreach ($val['class3'] as $k => $v) { ?>
              <a href="<?php echo urlShop('search','index',array('cate_id'=> $v['gc_id']));?>" title="<?php echo $v['gc_name']; ?>"><?php echo $v['gc_name'];?></a>
              <?php } ?>
              <?php } ?>
              </span>
              <span class="arrow"></span> 
            </div>
            <div class="sub-class" cat_menu_id="<?php echo $val['gc_id'];?>" style="background: rgba(255,255,255,0.9)<?php if(!empty($val['image'])) { ?> url(<?php echo $val['image'];?>) right bottom no-repeat<?php }?>">
              <?php if (!empty($val['class2']) && is_array($val['class2'])) { ?>
              <?php foreach ($val['class2'] as $k => $v) { ?>
              <dl>
                <dt>
                  <?php if(!empty($v['channel_id'])) {?>
                  <h3><a href="<?php echo urlShop('channel','index',array('id'=> $v['channel_id']));?>"><?php echo $v['gc_name'];?></a></h3>
                  <?php } else {?>
                  <h3><a href="<?php echo urlShop('search', 'index', array('cate_id'=>$v['gc_id']));?>"><?php echo $v['gc_name'];?></a></h3>
                  <?php }?>
                </dt>
                <dd class="goods-class">
                  <?php if (!empty($v['class3']) && is_array($v['class3'])) { ?>
                  <?php foreach ($v['class3'] as $k3 => $v3) { ?>
                  <a href="<?php echo urlShop('search','index',array('cate_id'=> $v3['gc_id']));?>"><?php echo $v3['gc_name'];?></a>
                  <?php } ?>
                  <?php } ?>
                </dd>
                <?php if (!empty($v['brands']) && is_array($v['brands'])) { $n = 0; ?>
                <dd class="brands-class">
                  <h5><?php echo $lang['im_brand'].$lang['im_colon'];?></h5>
                  <?php foreach ($v['brands'] as $k3 => $v3) {
                    if ($n++ < 10) {
                    ?>
                    <a href="<?php echo urlShop('brand','list',array('brand'=> $v3['brand_id'])); ?>"><?php echo $v3['brand_name'];?></a>
                  <?php } ?>
                  <?php } ?>
                </dd>
                <?php } ?>
              </dl>
              <?php } ?>
              <?php } ?>
            </div>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>     
    </div>   
    <ul class="site-menu">      
      <li><a href="<?php echo SHOP_SITE_URL;?>" <?php if($output['index_sign'] == 'index' && $output['index_sign'] != '0') {echo 'class="current"';} ?>><?php echo $lang['im_index'];?></a></li>
            
      <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
      <?php foreach($output['nav_list'] as $nav){?>
      <?php if($nav['nav_location'] == '1'){?>
      <li><a
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
        ?>><?php echo $nav['nav_title'];?></a></li>
      <?php }?>
      <?php }?>
      <?php }?>
    </ul>    
  </div>
</nav>
<script type="text/javascript">
$(function(){
	<?php if (OPEN_CONSUMER_CARD_STATE == 1){?>
	<?php if ($_SESSION['is_login']!=1){?>
	$("#site-signin-now").click(function(){
		login_dialog();
	})
	$("#site-signin-now").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
	<?php }else{?>
	$("#site-signin-ok").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
	<?php }?>
	<?php }?>
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
});
</script>
<!-- publicNavLayout End -->