<?php defined('InIMall') or exit('Access Invalid!');?>

<!-- PublicHeadLayout Begin -->
<div class="header-wrap">
  <header class="public-head-layout wrapper">
    <h1 class="site-logo"><a href="<?php echo SHOP_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>" class="pngFix"></a></h1>
    <div class="head-app">
    </div>    
    
    <div class="search-bar">
      <div class="searchBox channel-selectBox-<?php echo $output['channel_style'];?>">
        <div class="selectBox">
          <span class="selectitem" id="selectBoxInput">宝贝</span>
          <div  class="selectList channel-selectList-<?php echo $output['channel_style'];?>">
            <ul>
              <li><a href="javascript:void(0);" <?php if($_GET['act']=='search'){?> cur='true'<?php }?> title="请输入您要搜索的商品关键字" act="search">宝贝</a></li>
              <li><a href="javascript:void(0);" <?php if($_GET['act']=='store_list'){?> cur='true'<?php }?> title="请输入您要搜索的店铺关键字" act="store_list">商家</a></li>
            </ul>
          </div>
        </div>      
        <form id="search_form"  method="get" action="<?php echo SHOP_SITE_URL;?>"> 
	  <input type="hidden" value="search" name="act" id="search_act"/>       
          <input type="text" class="text" name="keyword" value="<?php echo $_GET['keyword'];?>" placeholder="<?php echo C('adv_search');?>" data-placeholder="<?php echo C('adv_search');?>" />
          <input type="submit" class="submit channel-submit-<?php echo $output['channel_style'];?>" value="搜索" />
        </form>
      </div>
      <div class="keyword"><?php echo $lang['hot_search'].$lang['im_colon'];?>
        <ul>
          <?php if(is_array($output['hot_search']) && !empty($output['hot_search'])) { foreach($output['hot_search'] as $val) { ?>
          <li><a href="<?php echo urlShop('search', 'index', array('keyword' => $val));?>"><?php echo $val; ?></a></li>
          <?php } }?>
        </ul>
      </div>
    </div>
 
    <div class="head-user-menu">
      <div class="item">
        <dl class="my-mall">
          <dt><span class="ico"></span>我的商城<i class="arrow"></i></dt>
          <dd>
            <div class="sub-title">
              <h4><?php echo $_SESSION['member_name'];?>
              <?php if ($output['member_info']['level_name']){ ?>
              <div class="imcss-grade-mini" style="cursor:pointer;" onClick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?></div>
              <?php } ?>            
              </h4>
              <a href="<?php echo urlShop('member', 'home');?>" class="arrow">我的用户中心<i></i></a></div>
            <div class="user-centent-menu">
              <ul>
                <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_message&op=message">站内消息(<span><?php echo $output['message_num']>0 ? $output['message_num']:'0';?></span>)</a></li>
                <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_order" class="arrow">我的订单<i></i></a></li>
                <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_consult&op=my_consult">咨询回复(<span id="member_consult">0</span>)</a></li>
                <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_favorites&op=fglist" class="arrow">我的收藏<i></i></a></li>
                <?php if (C('voucher_allow') == 1){?>
                <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_voucher">代金券(<span id="member_voucher">0</span>)</a></li>
                <?php } ?>
                <?php if (C('points_isuse') == 1){ ?>
                <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_points" class="arrow">我的积分<i></i></a></li>
                <?php } ?>
              </ul>
            </div>
            <div class="browse-history">
              <div class="part-title">
                <h4>最近浏览的商品</h4>
                <span style="float:right;"><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=member_goodsbrowse&op=list">全部浏览历史</a></span>
              </div>
              <ul>
                <li class="no-goods"><img class="loading" src="<?php echo SHOP_SKINS_URL;?>/images/loading.gif" /></li>
              </ul>
            </div>
          </dd>
        </dl>
        <dl class="my-cart">
          <?php if ($output['cart_goods_num'] > 0) { ?>
          <div class="addcart-goods-num"><?php echo $output['cart_goods_num'];?></div>
          <?php } ?>
          <dt><span class="ico"></span>购物车结算<i class="arrow"></i></dt>
          <dd>
            <div class="sub-title">
              <h4>最新加入的商品</h4>
            </div>
            <div class="incart-goods-box">
              <div class="incart-goods"> <img class="loading" src="<?php echo SHOP_SKINS_URL;?>/images/loading.gif" /> </div>
            </div>
            <div class="checkout"> 
              <span class="total-price">共<i><?php echo $output['cart_goods_num'];?></i><?php echo $lang['im_kindof_goods'];?></span>
              <a href="<?php echo SHOP_SITE_URL;?>/index.php?act=cart" class="btn-cart">结算购物车中的商品</a> 
            </div>
          </dd>
        </dl>        
      </div>
      <div class="header-svip">
        <a href="javascript:void(0);">
          <img src="<?php echo SHOP_SKINS_URL;?>/images/member/svip1.png" height="25" />
        </a>
        <a href="javascript:void(0);">
          <img src="<?php echo SHOP_SKINS_URL;?>/images/member/svip2.png" height="25" />
        </a>
        <a href="javascript:void(0);">
          <img src="<?php echo SHOP_SKINS_URL;?>/images/member/svip3.png" height="" />
        </a>
      </div>
    </div>
  </header>
</div>
<script type="text/javascript">
$(function(){
    //购物车、我的商城
	$(".head-user-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});
	$('.head-user-menu .my-mall').mouseover(function(){// 最近浏览的商品
		load_history_information();
		$(this).unbind('mouseover');
	});
	$('.head-user-menu .my-cart').mouseover(function(){// 运行加载购物车
		load_cart_information();
		$(this).unbind('mouseover');
	});
	//搜索框
	var act = "<?php echo $_GET['act']?>";
	$('#selectBoxInput').on("mouseleave", function () {
        $('.selectList').stop().hide();
    }).on("mouseenter", function(){
        $('.selectList').stop().slideDown();
    });
    $('.selectList').on("mouseleave", function(){
        $(this).stop().hide();
    }).on("mouseenter", function(){
        $(this).stop().show();
    });
	$(".selectList li a").click(function () {
        $('#search_act').attr("value",$(this).attr("act"));
		$('#keyword').attr("placeholder",$(this).attr("title"));
        $("#selectBoxInput").html($(this).html());
        $('.selectList').hide();
    });
    $(".selectList a").each(function(){
        if($(this).attr("cur")){
            $('#search_act').attr("value",$(this).attr("act"));
			$('#keyword').attr("placeholder",$(this).attr("title"));
            $("#selectBoxInput").html($(this).html());                                
        }
    })				
      
    <?php if (C('fullindexer.open')) { ?>
	$('#keyword').focus(function(){
		if ($(this).val() == $(this).attr('title')) {
			$(this).val('').removeClass('tips');
		}
	}).blur(function(){
		if ($(this).val() == '' || $(this).val() == $(this).attr('title')) {
			$(this).addClass('tips').val($(this).attr('title'));
		}
	}).blur().autocomplete({
        source: function (request, response) {
            $.getJSON('<?php echo SHOP_SITE_URL;?>/index.php?act=search&op=auto_complete', request, function (data, status, xhr) {
                $('#top_search_box > ul').unwrap();
                response(data);
                if (status == 'success') {
                 $('body > ul:last').wrap("<div id='top_search_box'></div>").css({'zIndex':'1000','width':'362px'});
                }
            });
       },
		select: function(ev,ui) {
			$('#keyword').val(ui.item.label);
			$('#top_search_form').submit();
		}
	});		
	<?php } ?>	
});
</script>
<!-- PublicHeadLayout End -->