<?php defined('InIMall') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_SKINS_URL;?>/css/market_goods.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/search_goods.js"></script>
<link href="<?php echo SHOP_SKINS_URL;?>/css/distribution.css" rel="stylesheet" type="text/css">
<style>
	.ncsc-layout-right .main-content {padding:0px;margin-left:-1px}
	.ncsc-layout-left .sidebar .column-title span{ background-position:-128px -100px !important}
</style>
<div class="distribution-top middle">
    <form action="" method="get" class="block right" style="margin-right: 16px;">
        <select name="type">
            <option value="0">商品</option>
            <option value="1">供货商</option>
        </select>
        <input name="act" value="fx_market" type="hidden">
        <input name="op" value="search" type="hidden">
    	<input name="keyword" type="text" placeholder="请输入关键字" class="search" value="">
        <input value="搜索" type="submit" class="submit">
    </form>
</div>
<div class="clear"></div>
<script>var fx = true;</script>
<style type="text/css">
.ncs-goods-picture .levelB,
.ncs-goods-picture .levelC { cursor: url(<?php echo SHOP_SKINS_URL;?>/images/shop/zoom.cur), pointer;
}
.ncs-goods-picture .levelD { cursor: url(<?php echo SHOP_SKINS_URL;?>/images/shop/hand.cur), move\9;
}
.nch-sidebar-all-viewed { display: block; height: 20px; text-align: center; padding: 9px 0; }
.ncs-handle{ width:322px !important}
</style>
<div class="wrapper_search">
  <input type="hidden" id="lockcompare" value="unlock">
  <div class="ncs-detail"> 
    <!-- S 商品图片 -->
    <div id="ncs-goods-picture" class="ncs-goods-picture image_zoom" style="width: 300px;">
    	
    </div>
    <!-- S 商品基本信息 -->
    <div class="ncs-goods-summary">
      <div class="name">
        <h1><?php echo $output['goods']['goods_name']; ?></h1>
        <strong><?php echo str_replace("\n", "<br>", $output['goods']['goods_jingle']);?></strong> 
      </div>
      <div class="ncs-meta">
        <!-- S 商品参考价格 -->
        <dl>
          <dt>市&nbsp;&nbsp;场&nbsp;&nbsp;价：</dt>
          <dd class="cost-price"><strong><?php echo $lang['currency'].$output['goods']['goods_marketprice'];?></strong></dd>
        </dl>
        <!-- E 商品参考价格 --> 
        <!-- S 商品发布价格 -->
        <dl>
          <dt>商&nbsp;&nbsp;城&nbsp;&nbsp;价：</dt>
          <dd class="price">
            <?php if (isset($output['goods']['title']) && $output['goods']['title'] != '') {?>
            <span class="tag"><?php echo $output['goods']['title'];?></span>
            <?php }?>
            <?php if (isset($output['goods']['promotion_price']) && !empty($output['goods']['promotion_price'])) {?>
            <strong><?php echo $lang['currency'].$output['goods']['promotion_price'];?></strong>
            <em>(原售价<?php echo $lang['im_colon'];?><?php echo $lang['currency'].$output['goods']['goods_price'];?>)</em>
            <?php } else {?>
            <strong><?php echo $lang['currency'].$output['goods']['goods_price'];?></strong>
            <?php }?>
          </dd>
        </dl>
        <!-- E 商品发布价格 -->
        <dl>
          <dt>分润比例：</dt>
          <dd><span>5%</span></dd>
        </dl>
        <div class="ncs-goods-code">
           <a href="<?php echo SHOP_SITE_URL;?>/index.php?act=fx_market&op=store_detail&store_id=<?php echo $output['store_info']['store_id']; ?>">
           <img src="<?php echo UPLOAD_SITE_URL;?>/shop/store/<?php echo $output['store_info']['store_label']; ?>" width="100px" height="100px"> 
           <span style="word-wrap:break-word;"><?php echo $output['store_info']['store_name']; ?></span>
           </a>
        </div>
      </div>      

      <div class="ncs-key"> 
        <!-- S 商品规格值-->
        <?php if (is_array($output['goods']['spec_name'])) { ?>
        <?php foreach ($output['goods']['spec_name'] as $key => $val) {?>
        <dl imtype="nc-spec">
          <dt><?php echo $val;?><?php echo $lang['im_colon'];?></dt>
          <dd>
            <?php if (is_array($output['goods']['spec_value'][$key]) and !empty($output['goods']['spec_value'][$key])) {?>
            <ul nctyle="ul_sign">
              <?php foreach($output['goods']['spec_value'][$key] as $k => $v) {?>
              <?php if( $key == 1 ){?>
              <!-- 图片类型规格-->
              <li class="sp-img"><a href="javascript:void(0);" class="<?php if (isset($output['goods']['goods_spec'][$k])) {echo 'hovered';}?>" data-param="{valid:<?php echo $k;?>}" title="<?php echo $v;?>"><img src="<?php echo $output['spec_image'][$k];?>"/><?php echo $v;?><i></i></a></li>
              <?php }else{?>
              <!-- 文字类型规格-->
              <li class="sp-txt"><a href="javascript:void(0)" class="<?php if (isset($output['goods']['goods_spec'][$k])) { echo 'hovered';} ?>" data-param="{valid:<?php echo $k;?>}"><?php echo $v;?><i></i></a></li>
              <?php }?>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <?php }?>
        <!-- E 商品规格值-->
      </div>
      <!-- S 购买数量及库存 -->
      <div class="ncs-buy">
        <!-- S 购买按钮 -->
        <div class="ncs-btn">
          <!-- 分销商品--> 
          <a href="javascript:void(0);" nctype="fxbtn_submit" class="fxbtn" title="分销商品">上架到店铺</a>
          <!-- S 加入购物车弹出提示框 -->
          <div class="ncs-cart-popup">
            <dl>
	            <dt><?php echo $lang['goods_index_cart_success'];?><a title="<?php echo $lang['goods_index_close'];?>" onClick="$('.imcs-cart-popup').css({'display':'none'});">X</a></dt>
	            <dd><?php echo $lang['goods_index_cart_have'];?> <strong id="bold_num"></strong> <?php echo $lang['goods_index_number_of_goods'];?> <?php echo $lang['goods_index_total_price'];?><?php echo $lang['im_colon'];?><em id="bold_mly" class="saleP"></em></dd>
	            <dd class="btns"><a href="javascript:void(0);" class="imcs-btn-mini imcs-btn-green" onClick="location.href='<?php echo SHOP_SITE_URL.DS?>index.php?act=cart'"><?php echo $lang['goods_index_view_cart'];?></a> <a href="javascript:void(0);" class="imcs-btn-mini" value="" onClick="$('.imcs-cart-popup').css({'display':'none'});"><?php echo $lang['goods_index_continue_shopping'];?></a></dd>
	          </dl>
          </div>
          <!-- E 加入购物车弹出提示框 --> 
        </div>
        <!-- E 购买按钮 --> 
      </div>
      <!-- E 购买数量及库存 --> 
      <!-- S消费者保障服务 -->
      <!-- E消费者保障服务 --> 
      <!--E 商品信息 --> 
    </div>

    
  </div>
  <div class="clear"></div>
</div>

<!-- S 优惠套装 -->
<div class="ncs-promotion" id="nc-bundling" style="display:none;"></div>
<!-- E 优惠套装 -->
<div id="content" class="ncs-goods-layout expanded">
  <div class="ncs-goods-main" id="main-nav-holder">
    <div class="ncs-intro">
      <div class="content bd" id="ncGoodsIntro">
      	<?php if(is_array($output['goods']['goods_attr']) || isset($output['goods']['brand_name'])){?>
		<ul class="nc-goods-sort">
          <?php if(!empty($output['goods']['goods_serial'])){?>
            <li>商家货号：<?php echo $output['goods']['goods_serial'];?></li>
            <?php }?>
            <?php if(!empty($output['goods']['brand_name'])){echo '<li>'.$lang['goods_index_brand'].$lang['im_colon'].$output['goods']['brand_name'].'</li>';}?>
            <?php if(is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr'])){?>
            <?php 
			      foreach ($output['goods']['goods_attr'] as $val){ 
			        $val= array_values($val);
					if (!empty($val[1]) && $val[1]!='不限'){
					  echo '<li>'.$val[0].$lang['im_colon'].$val[1].'</li>';
					}
				  }
		    ?>
            <?php }?>
        </ul>
       	<?php }?>
        <div class="ncs-goods-info-content">
        	<?php if (isset($output['plate_top'])) {?>
            <div class="top-template"><?php echo $output['plate_top']['plate_content']?></div>
            <?php }?>
            <div class="default"><?php echo $output['goods']['goods_body']; ?></div>
            <?php if (isset($output['plate_bottom'])) {?>
            <div class="bottom-template"><?php echo $output['plate_bottom']['plate_content']?></div>
            <?php }?>
         </div>
      </div>
    </div>
      </div>
  <div class="ncs-sidebar">
  </div>
</div>
<form id="fxbtn_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php">
  <input id="act" name="act" type="hidden" value="fx_market">
  <input id="op" name="op" type="hidden" value="fx_add">
  <input id="fx_id" name="fx_id" type="hidden" value="">
</form>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />

<script type="text/javascript">
/** 辅助浏览 **/
jQuery(function($){	
	//产品图片
	$.getScript('<?php echo SHOP_RESOURCE_SITE_URL?>/js/ImageZoom.js', function(){
		var
		zoomController,
		zoomControllerUl,
		zoomControllerUlLeft = 0,
		shell = $('#ncs-goods-picture'),
		shellPanel = shell.parent(),
		heightNcsDetail = $('div[class="ncs-detail"]').height();
		heightOffset = 60,
		minGallerySize = [360, 360],
		imageZoom = new ImageZoom({
			shell: shell,
			basePath: '',
			levelASize: [60, 60],
			levelBSize: [320, 320],
			gallerySize: minGallerySize,
			onBeforeZoom: function(index, level){
				if(!zoomController){
					zoomController = shell.find('div.controller');
				}

				var
				self = this,
				duration = 320,
				width = minGallerySize[0],
				height = minGallerySize[1],
				zoomFx = function(){
					self.ops.gallerySize = [width, height];
					self.galleryPanel.stop().animate({width:width, height:height}, duration);
					shellPanel.stop().animate({height:height + heightOffset}, duration).css('overflow', 'visible');
					zoomController.animate({width:width-22}, duration);
					shell.stop().animate({width:width}, duration);
				};
				if(level !== this.level && this.level !== 0){
					if(this.level === 1 && level > 1){
						height = Math.max(480, shellPanel.height());
						width = shellPanel.width();
						zoomFx();
					}
					else if(level === 1){
						zoomFx();
						shellPanel.stop().animate({height:heightNcsDetail}, duration);
					}
				}
			},
			onZoom: function(index, level, prevIndex){
				shell.find('a.prev,a.next')[level<3 ? 'removeClass' : 'addClass']('hide');
				shell.find('a.close').css('display', [level>1 ? 'block' : 'none']);
			},
			items: [
	                <?php if (!empty($output['goods_image'])) {?>
	                <?php echo implode(',', $output['goods_image']);?>
	                <?php }?>
					]
		});
		shell.data('imageZoom', imageZoom);
	});

});
$(function(){
	//赠品处滚条
	$('#ncsGoodsGift').perfectScrollbar({suppressScrollX:true});
    
    $('a[nctype="fxbtn_submit"]').click(function(){
    	if (typeof(allow_buy) != 'undefined' && allow_buy === false) return ;
        fxbtn(<?php echo $output['goods']['goods_id'];?>);
    });
});

function checkSpec() {
    var spec_param = <?php echo $output['spec_list'];?>;
    var spec = new Array();
    $('ul[nctyle="ul_sign"]').find('.hovered').each(function(){
        var data_str = ''; eval('data_str =' + $(this).attr('data-param'));
        spec.push(data_str.valid);
    });
    spec1 = spec.sort(function(a,b){
        return a-b;
    });
    var spec_sign = spec1.join('|');
    $.each(spec_param, function(i, n){
        if (n.sign == spec_sign) {
            window.location.href = n.url;
        }
    });
}

//分销js
function fxbtn(goods_id){
	
    <?php if ($_SESSION['store_id'] == $output['goods']['store_id']) { ?>
    	alert('不能购买自己店铺的商品');return;
    <?php } ?>
    $("#fx_id").val(goods_id);
    $("#fxbtn_form").submit();
}

$(function(){
    
/** goods.php **/
	// 商品内容介绍Tab样式切换控制
	$('#categorymenu').find("li").click(function(){
		$('#categorymenu').find("li").removeClass('current');
		$(this).addClass('current');
	});
	// 商品详情默认情况下显示全部
	$('#tabGoodsIntro').click(function(){
		$('.bd').css('display','');
		$('.hd').css('display','');
	});
	// 点击地图隐藏其他以及其标题栏
	$('#tabStoreMap').click(function(){
		$('.bd').css('display','none');
		$('#ncStoreMap').css('display','');
		$('.hd').css('display','none');
	});
	// 点击评价隐藏其他以及其标题栏
	$('#tabGoodsRate').click(function(){
		$('.bd').css('display','none');
		$('#ncGoodsRate').css('display','');
		$('.hd').css('display','none');
	});
	// 点击成交隐藏其他以及其标题
	$('#tabGoodsTraded').click(function(){
		$('.bd').css('display','none');
		$('#ncGoodsTraded').css('display','');
		$('.hd').css('display','none');
	});
	// 点击咨询隐藏其他以及其标题
	$('#tabGuestbook').click(function(){
		$('.bd').css('display','none');
		$('#ncGuestbook').css('display','');
		$('.hd').css('display','none');
	});

//触及显示缩略图
	$('.goods-pic > .thumb').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);
    load_goodseval('all');
    function load_goodseval(type) {
    	var url = '<?php echo urlShop('goods', 'comments', array('goods_id' => $output['goods']['goods_id']));?>';
        url += '&type=' + type;
        $("#goodseval").load(url, function(){
        });
    }

    
    // 满即送、加价购显示隐藏
    $('[nctype="show-rule"]').click(function(){
        $(this).parent().find('[nctype="rule-content"]').show();
    });
    $('[nctype="hide-rule"]').click(function(){
        $(this).parents('[nctype="rule-content"]:first').hide()
    });

    $('.ncs-buy').bind({
        mouseover:function(){$(".ncs-point").show();},
        mouseout:function(){$(".ncs-point").hide();}
    });
    
});

var $cur_area_list,$cur_tab,next_tab_id = 0,cur_select_area = [],calc_area_id = '',calced_area = [],cur_select_area_ids = [];
$(document).ready(function(){

	$('ul[class="area-list"]').on('click','a',function(){
		$('#ncs-freight-selector').unbind('mouseleave');
		var tab_id = parseInt($(this).parents('div[data-widget="tab-content"]:first').attr('data-area'));
		if (tab_id == 0) {cur_select_area = [];cur_select_area_ids = []};
		if (tab_id == 1 && cur_select_area.length > 1) {
			cur_select_area.pop();
			cur_select_area_ids.pop();
			if (cur_select_area.length > 1) {
				cur_select_area.pop();
				cur_select_area_ids.pop();
			}
		}
		next_tab_id = tab_id + 1;
		var area_id = $(this).attr('data-value');
		$cur_tab = $('#ncs-stock').find('li[data-index="'+tab_id+'"]');
		$cur_tab.find('em').html($(this).html());
		$cur_tab.find('i').html(' ∨');
		if (tab_id < 2) {
			calc_area_id = area_id;
			cur_select_area.push($(this).html());
			cur_select_area_ids.push(area_id);
			$cur_tab.find('a').removeClass('hover');
			$cur_tab.nextAll().remove();
			if (typeof nc_a === "undefined") {
    	 		$.getJSON(SITEURL + "/index.php?act=index&op=json_area&callback=?", function(data) {
    	 			nc_a = data;
    	 			_loadArea(area_id);
    	 		});
			} else {
				_loadArea(area_id);
			}
		} else {
			//点击第三级，不需要显示子分类
			if (cur_select_area.length == 3) {
				cur_select_area.pop();
				cur_select_area_ids.pop();
			}
			cur_select_area.push($(this).html());
			cur_select_area_ids.push(area_id);
			$('#ncs-freight-selector > div[class="text"] > div').html(cur_select_area.join(''));
			$('#ncs-freight-selector').removeClass("hover");
			_calc();
		}
		$('#ncs-stock').find('li[data-widget="tab-item"]').on('click','a',function(){
			var tab_id = parseInt($(this).parent().attr('data-index'));
			if (tab_id < 2) {
				$(this).parent().nextAll().remove();
				$(this).addClass('hover');
				$('#ncs-stock').find('div[data-widget="tab-content"]').each(function(){
					if ($(this).attr("data-area") == tab_id) {
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			}
		});
	});
	function _loadArea(area_id){
		if (nc_a[area_id] && nc_a[area_id].length > 0) {
			$('#ncs-stock').find('div[data-widget="tab-content"]').each(function(){
				if ($(this).attr("data-area") == next_tab_id) {
					$(this).show();
					$cur_area_list = $(this).find('ul');
					$cur_area_list.html('');
				} else {
					$(this).hide();
				}
			});
			var areas = [];
			areas = nc_a[area_id];
			for (i = 0; i < areas.length; i++) {
				if (areas[i][1].length > 8) {
					$cur_area_list.append("<li class='longer-area'><a data-value='" + areas[i][0] + "' href='#none'>" + areas[i][1] + "</a></li>");
				} else {
				    $cur_area_list.append("<li><a data-value='" + areas[i][0] + "' href='#none'>" + areas[i][1] + "</a></li>");
				}
			}
			if (area_id > 0){
				$cur_tab.after('<li data-index="' + (next_tab_id) + '" data-widget="tab-item"><a class="hover" href="#none" ><em>请选择</em><i> ∨</i></a></li>');
			}
		} else {
			//点击第一二级时，已经到了最后一级
			$cur_tab.find('a').addClass('hover');
			$('#ncs-freight-selector > div[class="text"] > div').html(cur_select_area);
			$('#ncs-freight-selector').removeClass("hover");
			_calc();
		}
	}

});
</script>

