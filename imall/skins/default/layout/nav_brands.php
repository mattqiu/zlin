<?php defined('InIMall') or exit('Access Invalid!');?>
<!-- publicNavLayout Begin -->
<nav class="public-nav-layout">
  <div class="wrapper">
    <div class="all-category">
      <div class="title">
        <h3><a href="<?php echo urlShop('brand');?>">大牌街</a></h3>
        <i class="arrow"></i>
      </div>
      <div class="category">
        <ul class="menu">
          <?php $show_class_num=8;?>
          <!--精选市场-->         
          <?php if ($_GET['op']=='index' && !empty($output['brand_r']) && is_array($output['brand_r'])) {?>
          <?php $i = 0;?>
          <?php $show_class_num=7;?>
          <li cat_id="goods_class_recommend" class="recommend">
            <div class="class">
              <span class="ico"><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.'/dapai.png';?>"></span>
              <div class="caption">
                <h4><a href="<?php echo urlShop('brand');?>">大牌街</a></h4>
                <span class="recommend-class">汇聚国际高端品牌</span>
                <span class="arrow"></span>
              </div>
            </div>
          </li>            
          <?php }?> 
          <?php if (!empty($output['brand_l']) && is_array($output['brand_l'])) {?>  
          <?php $i = 0;?>
          <?php foreach ($output['brand_l'] as $key => $val) {?>
          <?php $i++;?>
          <li cat_id="<?php echo $val['gc_id'];?>" class="<?php echo $i%2==1 ? 'odd':'even';?>" <?php if($i>$show_class_num){?>style="display:none;"<?php }?>>
            <div class="class">
              <?php if(!empty($val['pic'])) { ?>
              <span class="ico"><img src="<?php echo $val['pic'];?>"></span>
              <?php } ?>
              <h4><a href="<?php echo urlShop('brand','category',array('cate_id'=>$val['gc_id']));?>"><?php echo $val['gc_name'];?></a></h4>
              <span class="recommend-class">
              <?php if (!empty($val['recommend']) && is_array($val['recommend'])) { ?>
              <?php foreach ($val['recommend'] as $k => $v) { ?>
              <a href="<?php echo urlShop('brand','list',array('brand'=> $v['brand_id']));?>" title="<?php echo $v['brand_name']; ?>"><?php echo $v['brand_name'];?></a>
              <?php } ?>
              <?php } ?>
              </span>
              <span class="arrow"></span> 
            </div>
            <div class="sub-class" cat_menu_id="<?php echo $val['gc_id'];?>" style="background: rgba(255,255,255,0.9)<?php if(!empty($val['image'])) { ?> url(<?php echo $val['image'];?>) right bottom no-repeat<?php }?>">
              <!--本类品牌-->
              <?php if (!empty($val['brand']) && is_array($val['brand'])) { ?>              
              <dl>
                <dt>
                  <h3><a href="<?php echo urlShop('brand','category',array('cate_id'=>$val['gc_id']));?>">品牌列表</a></h3>
                </dt>
                <dd class="goods-class">
                  <?php foreach ($val['brand'] as $k => $v) { ?>
                  <a href="<?php echo urlShop('brand','list',array('brand'=> $v['brand_id']));?>"><?php echo $v['brand_name'];?></a>
                  <?php } ?>
                </dd>                
              </dl>
              <?php } ?>
              <!--分类品牌-->
              <?php if (!empty($val['child']) && is_array($val['child'])) { ?>
              <?php foreach ($val['child'] as $k => $v) { ?>
              <dl>
                <dt>
                  <h3><a href="<?php echo urlShop('brand','category',array('cate_id'=>$v['gc_id']));?>"><?php echo $v['gc_name'];?></a></h3>
                </dt>
                <dd class="goods-class">
                  <?php if (!empty($v['child']) && is_array($v['child'])) { ?>
                  <?php foreach ($v['child'] as $k3 => $v3) { ?>
                  <a href="<?php echo urlShop('brand','list',array('brand'=> $v3['brand_id']));?>"><?php echo $v3['brand_name'];?></a>
                  <?php } ?>
                  <?php } ?>
                </dd>                
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
      <?php if (OPEN_GROUPBUY_STATE ==1 && C('groupbuy_allow')){ ?>
      <li><a href="<?php echo urlShop('show_groupbuy', 'index');?>" <?php if($output['index_sign'] == 'groupbuy' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['im_groupbuy'];?></a></li>
      <?php } ?>
      <li><a href="<?php echo urlShop('brand', 'index');?>" <?php if($output['index_sign'] == 'brand' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['im_brand'];?></a></li>
      <li><a href="<?php echo urlShop('store_list', 'index');?>" <?php if($output['index_sign'] == 'store_list' && $output['index_sign'] != '0') {echo 'class="current"';} ?>>商铺</a></li>
      <?php if (OPEN_MODULE_FLEA_STATE ==1){ ?>
      <li><a href="<?php echo urlShop('flea', 'index');?>" <?php if($output['index_sign'] == 'flea' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> 二手市场</a></li>
      <?php } ?>
	  <?php if (C('points_isuse') && C('pointshop_isuse')){ ?>
      <li><a href="<?php echo urlShop('pointshop', 'index');?>" <?php if($output['index_sign'] == 'pointshop' && $output['index_sign'] != '0') {echo 'class="current"';} ?>> <?php echo $lang['im_pointprod'];?></a></li>
      <?php } ?>
      <li><a href="<?php echo urlShop('special', 'index');?>" <?php if($output['index_sign'] == 'special' && $output['index_sign'] != '0') {echo 'class="current"';} ?>>专题</a></li>
      <!-- 功能模块 -->
      <?php if (OPEN_CMS_STATE == 1 && C('cms_isuse')){ ?>
      <li><a href="<?php echo urlCMS('index', 'index');?>"> <?php echo $lang['im_cms'];?></a></li>
      <?php } ?>
      <?php if (OPEN_CIRCLE_STATE == 1 && C('circle_isuse')){ ?>
      <li><a href="<?php echo urlCircle('index', 'index');?>"> <?php echo $lang['im_circle'];?></a></li>
      <?php } ?>
      <?php if (OPEN_MICROSHOP_STATE == 1 && C('microshop_isuse')){ ?>
      <li><a href="<?php echo urlMicroshop('index', 'index');?>"> <?php echo $lang['im_microshop'];?></a></li>
      <?php } ?>
      <?php if (OPEN_MODULE_WEIXIN_STATE == 1){ ?>
      <li><a href="<?php echo urlWeiXin('index', 'index');?>"> <?php echo $lang['im_weixin'];?></a></li>
      <?php } ?>
      
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