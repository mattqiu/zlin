<?php defined('InIMall') or exit('Access Invalid!');?>

<style type="text/css">
#box { 
	background: #FFF; width: 238px; height: 410px; margin: -390px 0 0 0; 
	display: block; border: solid 4px #D93600; position: absolute; z-index: 999; opacity: .5 
}
.shopMenu { position: fixed; z-index: 1; right: 25%; top: 0; }
</style>

<div class="squares" im_type="current_display_mode">
  <input type="hidden" id="lockcompare" value="unlock" />
  <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
  <ul class="list_pic">
    <?php foreach($output['goods_list'] as $value){?>
    <li class="item">
      <div class="goods-content" nctype_store="<?php echo $value['goods_id'];?>" nctype_store="<?php echo $value['store_id'];?>">
        <div class="goods-pic">
          <a href="<?php echo urlShop('fx_market','goods_detail',array('goods_id'=>$value['goods_id']));?>" title="<?php echo $value['goods_name'];?>">
            <img src="<?php echo thumb($value, 240);?>" title="<?php echo $value['goods_name'];?>" alt="<?php echo $value['goods_name'];?>" />
          </a>
        </div>
        <?php if (C('groupbuy_allow') && $value['goods_promotion_type'] == 1) {?>
        <div class="goods-promotion"><span>抢购商品</span></div>
        <?php } elseif (C('promotion_allow') && $value['goods_promotion_type'] == 2)  {?>
        <div class="goods-promotion"><span>限时折扣</span></div>
        <?php }?>
        <div class="goods-info">
          <div class="goods-pic-scroll-show">
            <ul>
            <?php if(!empty($value['image'])) {?>
              <?php $i=0;foreach ($value['image'] as $val) {$i++?>
              <li<?php if($i==1) {?> class="selected"<?php }?>><a href="javascript:void(0);"><img src="<?php echo thumb($val, 60);?>"/></a></li>
              <?php }?>
            <?php } else {?>
              <li class="selected"><a href="javascript:void(0);"><img src="<?php echo thumb($value, 60);?>" /></a></li>
            <?php }?>
            </ul>
          </div>
          <div class="goods-name">
            <a href="<?php echo urlShop('fx_market','goods_detail',array('goods_id'=>$value['goods_id']));?>" target="_blank" title="<?php echo $value['goods_jingle'];?>"><?php echo $value['goods_name_highlight'];?>
              <em><?php echo $value['goods_jingle'];?></em>
            </a>
          </div>
          <div class="goods-price"> 
            <div class="fxp_price">
            	分销利润：
              <em title="提成利润：<?php echo $lang['currency'];
 				if ($value['goods_tradeprice']==0){ 
              		echo floatval($value['goods_promotion_price']*$value['baifen']);
              	} elseif (!empty($value['promotion_price'])){
	          		echo floatval($value['promotion_price']-$value['goods_tradeprice']-($value['promotion_price']*$value['commis_rate']*0.01));
          		} else {
              		echo floatval($value['goods_promotion_price']-$value['goods_tradeprice']-($value['goods_promotion_price']*$value['commis_rate']*0.01));
              	}?>">
              <b><?php echo L('currency');?></b>
              <?php if ($value['goods_tradeprice']==0){ 
              		echo floatval($value['goods_promotion_price']*$value['baifen']);
              	}elseif (!empty($value['promotion_price'])){
	          		echo floatval($value['promotion_price']-$value['goods_tradeprice']-($value['promotion_price']*$value['commis_rate']*0.01));
          		}else {
              		echo floatval($value['goods_promotion_price']-$value['goods_tradeprice']-($value['goods_promotion_price']*$value['commis_rate']*0.01));
              	}?>
              </em>
            </div>
            <div class="fxm_price">
            	<?php echo $lang['goods_class_index_store_goods_price'].$lang['im_colon'];?>
            	<b><?php echo L('currency');?></b>
              	<span title="该会员价可以调整，利润也会发生变化。<?php echo $lang['goods_class_index_store_goods_price'].$lang['im_colon'].$lang['currency'].$value['goods_promotion_price'];?>">
                <i><?php echo floatval($value['goods_promotion_price']);?></i>
              	</span>             
            </div>
                     
          </div>
          <div class="goods-sub">
          	<div class="fxs_price">              
              <div class='dtype'>
                <?php if($value['store_type']==2){?>
                <em class='ownshop'></em><i>实体店</i>
                <?php }else if($value['store_type']==1){?>
                <em class='ownshop'></em><i>加盟店</i>
                <?php }else {?>
                <em class='ownshop'></em><i>商城自营</i>
                <?php }?>                
              </div>              
            </div>  
            <?php if ($value['is_virtual'] == 1) {?>
            <span class="virtual" title="虚拟兑换商品">虚拟兑换</span>
            <?php }?>
            <?php if ($value['is_fcode'] == 1) {?>
            <span class="fcode" title="F码优先购买商品">F码优先</span>
            <?php }?>
            <?php if ($value['is_presell'] == 1) {?>
            <span class="presell" title="预售购买商品">预售</span>
            <?php }?>
            <?php if ($value['have_gift'] == 1) {?>
            <span class="gift" title="捆绑赠品">赠品</span>
            <?php }?>
            <span class="goods-compare" im_type="compare_<?php echo $value['goods_id'];?>" data-param='{"gid":"<?php echo $value['goods_id'];?>"}'><i></i>加入对比</span> 
          </div>
          <div class="sell-stat">
            <ul>
              <li>
                <a href="<?php echo urlShop('fx_market','goods_detail', array('goods_id' => $value['goods_id']));?>#imGoodsRate" target="_blank" class="status"><?php echo $value['goods_salenum'];?></a>
                <p>商品销量</p>
              </li>
              <li>
                <a href="<?php echo urlShop('goods', 'comments_list', array('goods_id' => $value['goods_id']));?>" target="_blank"><?php echo $value['evaluation_count'];?></a>
                <p>用户评论</p>
              </li>
              <li><em member_id="<?php echo $value['member_id'];?>">&nbsp;</em></li>
            </ul>
            <div class="ratybox">
              <span class="raty" data-score="<?php echo $value['evaluation_good_star'];?>"></span>
              <p>店铺评价</p>
            </div>
          </div>
          <div class="store">
            <a href="<?php echo urlShop('show_store','index',array('store_id'=>$value['store_id']), $value['store_domain']);?>" title="<?php echo $value['store_name'];?>" class="name"><?php echo $value['store_name'];?></a>
          </div>
          <?php if(!empty($_SESSION['store_id'])&&$value['store_id']!=$_SESSION['store_id']&&$value['is_market']==1){ ?>
          <div class="add-cart">
		  <a href="<?php echo urlShop('goods', 'goodsadd', array('goods_id' => $value['goods_id']));?>" imtype="add_cart" data-param="{goods_id:<?php echo $value['goods_id'];?>}"><i class="fa fa-shopping-cart"></i>选入店铺</a>
          </div>
          <?php }?>
        </div>
      </div>
    </li>
    <?php }?>
    <div class="clear"></div>
  </ul>
  <?php }else{?>
  <div id="no_results" class="no-results"><i></i>没有找到符合条件的商品</div>
  <?php }?>
</div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php" target="_blank">
  <input id="act" name="act" type="hidden" value="buy" />
  <input id="op" name="op" type="hidden" value="buy_step1" />
  <input id="goods_id" name="cart_id[]" type="hidden"/>
</form>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script> 
<script type="text/javascript">
    $(document).ready(function(){
        $('.raty').raty({
            path: "<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/img",
            readOnly: true,
            width: 80,
            score: function() {
              return $(this).attr('data-score');
            }
        });
      	//初始化对比按钮
    	initCompare();
    });
</script> 
