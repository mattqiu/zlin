<?php defined('InIMall') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_SKINS_URL;?>/css/market_layout.css" rel="stylesheet" type="text/css">
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
<script>
	var goods_class = <?php echo $output['goods_class_array'];?>;
</script>
<div class="imsc-path">
    <i gc_level="0" style="cursor:pointer">
    <a href="<?php echo SHOP_SITE_URL?>/index.php?act=fx_market&op=search">分销市场</a>
    </i>
    <i class="icon-angle-right" style="display: none"></i>
        <div class="classlist" gc_id="" gc_level="1">
        <span><span style="visibility:hidden">.</span></span>
        <div class="box"></div>        
    </div><i class="icon-angle-right" style="visibility:hidden"></i>
    <div class="classlist" gc_id="" gc_level="2">
        <span><span style="visibility:hidden">.</span></span>
        <div class="box">
        </div>
    </div><i class="icon-angle-right" style="visibility:hidden"></i>    
    <div class="classlist red" gc_id="" gc_level="3">
        <span><span style="visibility:hidden">.</span></span>
        <div class="box">
        </div>
        <div style="position: absolute; top: 0px; left: -160px; z-index: 18; width: 300px; height: 1000px; display: none;" id="cover"></div>
    </div>    
</div>
<div class="clear"></div>
<div class="wrapper_search">
  <div> 
    <div class="shop_con_list" id="main-nav-holder">
      <nav class="sort-bar" id="main-nav">
        <div class="nch-sortbar-array"> 排序方式：
          <ul>
            <li class="selected"><a href="<?php echo SHOP_SITE_URL?>/index.php?act=fx_market&op=search&type=0&keyword=<?php echo $output['show_keyword'];?>&order=0&key=0" title="默认排序">默认</a></li>
            <li><a href="<?php echo SHOP_SITE_URL?>/index.php?act=fx_market&op=search&type=0&keyword=<?php echo $output['show_keyword'];?>&key=1&order=2" title="点击按销量从高到低排序">销量<i></i></a></li>
            <li><a href="<?php echo SHOP_SITE_URL?>/index.php?act=fx_market&op=search&type=0&keyword=<?php echo $output['show_keyword'];?>&key=2&order=2" title="点击按人气从高到低排序">人气<i></i></a></li>
            <li><a href="<?php echo SHOP_SITE_URL?>/index.php?act=fx_market&op=search&type=0&keyword=<?php echo $output['show_keyword'];?>&key=3&order=2" title="点击按价格从高到低排序">价格<i></i></a></li>
          </ul>
        </div>
        <div class="nch-sortbar-filter" im_type="more-filter">
        <span class="arrow"></span>
          <ul>
            <!-- 消费者保障服务 -->
            <li><a href="<?php echo SHOP_SITE_URL?>/index.php?act=fx_market&op=search&type=0&keyword=<?php echo $output['show_keyword'];?>&ci=1"><i></i>7天退货</a></li>
            <li><a href="<?php echo SHOP_SITE_URL?>/index.php?act=fx_market&op=search&type=0&keyword=<?php echo $output['show_keyword'];?>&ci=2"><i></i>品质承诺</a></li>
            <li><a href="<?php echo SHOP_SITE_URL?>/index.php?act=fx_market&op=search&type=0&keyword=<?php echo $output['show_keyword'];?>&ci=3"><i></i>破损补寄</a></li>
            <li><a href="<?php echo SHOP_SITE_URL?>/index.php?act=fx_market&op=search&type=0&keyword=<?php echo $output['show_keyword'];?>&ci=4"><i></i>急速物流</a></li>
          </ul>
        </div>
        
        <div class="nch-sortbar-location">商品地域：
          <div class="select-layer">
            <div class="holder"><em im_type="area_name"><?php echo $output['area_name']; ?><!-- 所在地 --></em></div>
            <div class="selected"><a im_type="area_name"><?php echo $output['area_name']; ?><!-- 所在地 --></a></div>
            <i class="direction"></i>
            <ul class="options">
              <?php require(BASE_TPL_PATH.'/goods/fxgoods_class_area.php');?>
            </ul>
          </div>
        </div>
      </nav>
      <!-- 商品列表循环  -->
      <div>
      	<?php require_once (BASE_TPL_PATH.'/goods/goods.fx_market.php');?>
      </div>
      <div class="tc mt20 mb20">
        <div class="pagination"> <?php echo $output['show_page']; ?> </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/search_category_menu.js"></script>
<!--[if lt IE 10]>
<script type="text/javascript" src="<?php echo BASE_RESOURCE_PATH;?>/js/fly/requestAnimationFrame.js" charset="utf-8"></script>
<![endif]-->
<script type="text/javascript">
var defaultSmallGoodsImage = '<?php echo defaultGoodsImage(240);?>';
var defaultTinyGoodsImage = '<?php echo defaultGoodsImage(60);?>';

$(function(){
    $('#files').tree({
        expanded: 'li:lt(2)'
    });

    //浮动导航  waypoints.js
    $('#main-nav-holder').waypoint(function(event, direction) {
        $(this).parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });
	// 单行显示更多
	$('span[im_type="show"]').click(function(){
		s = $(this).parents('dd').prev().find('li[im_type="none"]');
		if(s.css('display') == 'none'){
			s.show();
			$(this).html('<i class="fa fa-angle-up"></i><?php echo $lang['goods_class_index_retract'];?>');
		}else{
			s.hide();
			$(this).html('<i class="fa fa-angle-down"></i><?php echo $lang['goods_class_index_more'];?>');
		}
	});
	
	// 显示更多筛选条件
	$('#J_selectorMore').click(function(){		
		s = $('dl[im_type="hide"]').first();
		if (typeof(s) != "undefined") {
		    if(s.css('display') == 'none'){
			    $('dl[im_type="hide"]').show();
			    $('span[im_type="sm-wrap"]').addClass('opened');
			    $('span[im_type="sm-wrap"]').html('收起' + '<i></i>');
		    }else{
			    $('dl[im_type="hide"]').hide();
			    $('span[im_type="sm-wrap"]').removeClass('opened');
			    $('span[im_type="sm-wrap"]').html('更多选项（' + $('span[im_type="sm-wrap"]').attr('data-more') + ')<i></i>');
		    }
		}
	});

	<?php if(isset($_GET['cate_id']) && intval($_GET['cate_id']) > 0){?>
	// 推荐商品异步显示
    $('div[imtype="booth_goods"]').load('<?php echo urlShop('search', 'get_booth_goods', array('cate_id' => $_GET['cate_id']))?>', function(){
        $(this).show();
    });
	<?php }?>
	//浏览历史处滚条
	$('#imhSidebarViewed').perfectScrollbar();

	//猜你喜欢
	$('#guesslike_div').load('<?php echo urlShop('search', 'get_guesslike', array()); ?>', function(){
        $(this).show();
    });
});
</script>