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
<div class="wrap middle">
	<div class="all-sort-list">
		<?php if (!empty($output['show_goods_class']) && is_array($output['show_goods_class'])) { $i = 0; ?>
		<?php foreach ($output['show_goods_class'] as $key => $val) { $i++; ?>
    	<div class="item bo">
            <h3><span></span><ae gc_id="<?php echo $val['gc_id'];?>"><?php echo $val['gc_name'];?></ae><span2>&gt;</span2></h3>
            <div class="item-list clearfix" style="top: 0px; display: none;">
	            <div class="subitem">
	            	<?php if (!empty($val['class2']) && is_array($val['class2'])) { ?>
					<?php foreach ($val['class2'] as $k => $v) { ?>
		            <dl class="fore0">
			            <dt>
				            <ae gc_id="<?php echo $v['gc_id'];?>"><?php echo $v['gc_name'];?></ae>
				        </dt>
				        <dd class="goods-class">
				        	<?php if (!empty($v['class3']) && is_array($v['class3'])) { ?>
							<?php foreach ($v['class3'] as $k3 => $v3) { ?>
								<em><ae gc_id="<?php echo $v3['gc_id'];?>"><?php echo $v3['gc_name'];?></ae></em>
							<?php }} ?>
						</dd>
				   	</dl>
		            <?php }} ?>
	            </div>
            </div>
      	</div>
        <script>
        	$().ready(function(){	
	     		$('ae').on('click',function(){
	             	location.href='<?php echo SHOP_SITE_URL;?>/index.php?act=fx_market&op=search&cate_id='+$(this).attr('gc_id');
	        	});
      		});
      	</script>
		<script type="text/javascript">
			$('.all-sort-list > .item').hover(function(){
				var eq=$('.all-sort-list > .item').index(this),h=$('.all-sort-list').offset().top,s=$(window).scrollTop(),i=$(this).offset().top,item=$(this).children('.item-list').height(),sort=$('.all-sort-list').height();
				if(item<sort){
					if(eq==0){
						$(this).children('.item-list').css('top',(i-h))
					}else{
						$(this).children('.item-list').css('top',(i-h)+1)
					}
				}else{
					if(s>h){
						if(i-s>0){
							$(this).children('.item-list').css('top',(s-h)+2)
						}else{
							$(this).children('.item-list').css('top',(s-h)-(-(i-s))+2)
						}
					}else{
						$(this).children('.item-list').css('top',0)
					}
				}
				$(this).addClass('hover');
				$(this).children('.item-list').css('display','block')
			},function(){
				$(this).removeClass('hover');
				$(this).children('.item-list').css('display','none')});
			$('.item > .item-list > .close').click(function(){
				$(this).parent().parent().removeClass('hover');
				$(this).parent().hide()
			});
		</script>
		<?php }} ?>
	</div>
	<script type="text/javascript">$(function(){$('#banner').flexslider({animation: "slide",direction:"horizontal",easing:"swing",animationSpeed:"900",keyboardNav:"true"});});</script>
	<div id="banner" class="flexslider clear">
        <div class="flex-viewport" style="overflow: hidden; position: relative;">
	        <ul class="slides" style="width: 1000%; transition-duration: 0s; transform: translate3d(-800px, 0px, 0px);">
		        <li class="clone" style="width: 800px; float: left; display: block;"><a href="http://www.shopnc.net/saas-cloud.html" target="_blank"><img src="http://saas.shopnctest.com/data/upload/shop/editor/web-1101-1101-2.jpg?284" width="100%" height="100%" alt=""></a></li>
		        <li class="flex-active-slide" style="width: 800px; float: left; display: block;"><a href="http://www.shopnc.net/saas-cloud.html" target="_blank"><img src="http://saas.shopnctest.com/data/upload/shop/editor/web-1101-1101-1.jpg?450" width="100%" height="100%" alt=""></a></li>
		        <li style="width: 800px; float: left; display: block;" class=""><a href="http://www.shopnc.net/saas-cloud.html" target="_blank"><img src="http://saas.shopnctest.com/data/upload/shop/editor/web-1101-1101-3.jpg?877" width="100%" height="100%" alt=""></a></li>
		        <li style="width: 800px; float: left; display: block;" class=""><a href="http://www.shopnc.net/saas-cloud.html" target="_blank"><img src="http://saas.shopnctest.com/data/upload/shop/editor/web-1101-1101-2.jpg?284" width="100%" height="100%" alt=""></a></li>
		        <li class="clone" style="width: 800px; float: left; display: block;"><a href="http://www.shopnc.net/saas-cloud.html" target="_blank"><img src="http://saas.shopnctest.com/data/upload/shop/editor/web-1101-1101-1.jpg?450" width="100%" height="100%" alt=""></a></li>
	        </ul>
        </div>
        <ol class="flex-control-nav flex-control-paging">
	        <li><a class="flex-active">1</a></li>
	        <li><a class="">2</a></li>
	        <li><a class="">3</a></li>
        </ol>
        <ul class="flex-direction-nav">
	        <li><a class="flex-prev" href="#">Previous</a></li>
	        <li><a class="flex-next" href="#">Next</a></li>
        </ul>
	</div>
</div>
<div class="clear"></div>
<div class="home-sale-layout middle">
	<div class="tabs-panel2 sale-goods-list">
		<ul>
		            <li>
            <dl>
            	<dd class="ad-thumb"><a href="http://www.shopnc.net/saas-cloud.html" target="_blank" title=""><img src="http://saas.shopnctest.com/data/upload/shop/editor/web-1111-1111-1.jpg?305" alt=""></a></dd>
            </dl>
          </li>
                    <li>
            <dl>
            	<dd class="ad-thumb"><a href="http://www.shopnc.net/saas-cloud.html" target="_blank" title=""><img src="http://saas.shopnctest.com/data/upload/shop/editor/web-1111-1111-2.jpg?419" alt=""></a></dd>
            </dl>
          </li>
                    <li>
            <dl>
            	<dd class="ad-thumb"><a href="http://www.shopnc.net/saas-cloud.html" target="_blank" title="111"><img src="http://saas.shopnctest.com/data/upload/shop/editor/web-1111-1111-0.jpg?362" alt="111"></a></dd>
            </dl>
          </li>
          		</ul>
	</div>
</div>