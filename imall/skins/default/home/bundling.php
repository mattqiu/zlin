<?php defined('InIMall') or exit('Access Invalid!');?>
<style type="text/css">
@charset "utf-8";
.clearfix{display:block;*zoom:1;}
.common-wrap{width:1200px;margin:0 auto;}
/*特惠头部*/
.package-title{
	position:relative;
	padding-top:100px;
}
.package-title .red-bg{
	position: absolute;
	left:0;
	bottom:0;
	width: 50%;
	height: 36px;
	margin-left: -194px;
	background-color: #e22d77
}
.package-title .common-wrap{
	position: relative;
	z-index: 2;
	height: 36px;
}
.package-title h2{
	float: left;
	font-size: 19px;
	color: #fff;
	line-height: 36px;
}
.package-title .title-content{
	float: right;
	width: 790px;
	height: 36px;
	font-size: 13px;
	color: #d60060;
	line-height: 18px;
}
.package-title .sub-tit{
	position: absolute;
	left: 112px;
	bottom: 4px;
	width: 292px;
	height: 90px;
}
/*特惠列表*/
.package-list{padding-top:32px;}
.package-item{
	border:1px solid #f1f1f1;
	border-top:2px solid #c41818;
	overflow: hidden;
	margin-bottom: 20px;
}
.package-tit{
	padding-left: 12px;
	font-size: 12px;
	font-weight: normal;
	color: #777;
	line-height: 23px;
	border-bottom:1px solid #f1f1f1;
}
.package-item li{
	float: left;
	width: 236px;
	border-left: 1px solid #f1f1f1;
	margin-left: -1px;
}
.pitem-box{
	padding: 7px 17px 0;
	display:block;
	color: #000;
}
.pitem-box:hover{
	color: #000;
}
.pitem-box .pic{
	width: 202px;
	height:202px;
}
.pitem-box .pic img{
	display: block;
	width: 100%;
}
.pitem-box .name{
	height: 30px;
	line-height: 15px;
}
.pitem-box .meta{
	height: 20px;
	overflow: hidden;
}
.package-price{
	float: left;
	text-decoration: line-through;
}
.sale-price{
	float: right;
	font-weight: bold;
	color: #c41818;
}
.package-info{
	position: relative;
	padding-left: 28px;
	height: 31px;
	background: url('<?php echo SHOP_SKINS_URL;?>/images/special/arrow.png') no-repeat left top;
	border-top:1px solid #f1f1f1;
	line-height: 31px;
}
.package-info .info-cont{
	font-size: 14px;
	color: #b1004f;
}
.package-info .package-btn{
	position: absolute;
	top:4px;
	right:14px;
	width: 86px;
	height: 23px;
	background: url('<?php echo SHOP_SKINS_URL;?>/images/special/btn-bg.png') no-repeat left top;
	font-size: 14px;
	line-height: 23px;
	text-align: center;
	color: #fff;
}
.package-info .package-btn:hover{
	color: #fff;
}

.item-col2{
	width: 473px;
	float: left
}

.item-col3{
	width: 712px;
	float: right
}

.item-col5{
	width: 100%;
	clear:both;
}
.item-col5 li{
	width: 240px;
}
</style>
<div class="package-title">
	<div class="red-bg"></div>
	<div class="common-wrap">
		<h2>开业聚惠</h2>
		<img src="<?php echo SHOP_SKINS_URL;?>/images/special/music.png" alt="emusic" class="sub-tit">
		<div class="title-content">凡购买满300元套餐即可获得3个月的推广员资格；满500元套餐可获得6个月的推广员资格；满1000元套餐的可获得1年的推广员资格；<br>购买满1800元套餐的可获得2年的推广员资格；满2800元套餐的可获得3年的推广员资格；购买满5000元套餐的可获得终生的推广员资格</div>
	</div>
</div>
<div class="package-list common-wrap clearfix">
	<?php foreach($output['bundlingList'] as $key => $bundling) {?>
	<div class="package-item item-col<?php echo $bundling['bg_classid'];?>">
		<h3 class="package-tit">套餐组合一</h3>
		<ul class="clearfix">
			<?php foreach($bundling['bundlingGoodsList'] as $bgoods) {?>
			<li>
				<a class="pitem-box" href="<?php echo urlShop('goods','index',array('goods_id'=>$bgoods['goods_id']));?>"  target="_blank">
					<div class="pic"><img src="<?php echo thumb($bgoods, 240);?>" alt="<?php echo $bgoods['goods_name'];?>"></div>
					<div class="name"><?php echo $bgoods['goods_name'];?></div>
					<div class="meta">
						<span class="package-price"><?php echo $bgoods['goods_price'];?></span>
						<span class="sale-price">套餐价<?php echo $bgoods['bl_goods_price'];?></span>
					</div>
				</a>
			</li>
			<?php }?>
		</ul>
		<div class="package-info">
			<span class="info-cont"><?php echo $bundling['bl_name'];?></span>
			<a class="package-btn" bl_id="<?php echo $bundling['bl_id']?>" imtype="addblcart_submit">立即购买</a>
		</div>
	</div>
	<?php }?>
</div>
<script>
	$(function(){
		$('a[imtype="addblcart_submit"]').click(function(){
                    addblcart($(this).attr('bl_id'));
		});	
	});
            
	/* add one bundling to cart */ 
	function addblcart(bl_id)
	{
            	<?php if ($_SESSION['is_login'] !== '1'){?>
            	   login_dialog();
                <?php } else {?>
                    var url = 'index.php?act=cart&op=add';
                    $.getJSON(url, {'bl_id':bl_id}, function(data){
                    	if(data != null){
                    		if (data.state)
                            {
                                $('#bold_num').html(data.num);
                                $('#bold_mly').html(price_format(data.amount));
                                $('.imcs-cart-popup').fadeIn('fast');
                                // 头部加载购物车信息
                                load_cart_information();
								$("#rtoolbar_cartlist").load('index.php?act=cart&op=ajax_load&type=html');
                            }
                            else
                            {
                                showDialog(data.msg, 'error','','','','','','','','',2);
                            }
                    	}
                    });
                <?php } ?>
	}
</script>