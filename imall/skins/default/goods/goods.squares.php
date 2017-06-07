<?php defined('InIMall') or exit('Access Invalid!');?>
<style type="text/css">
#box {
	background: #FFF;
	width: 238px;
	height: 410px;
	margin: -390px 0 0 0;
	display: block;
	border: solid 4px #D93600;
	position: absolute;
	z-index: 999;
	opacity: .5
}
.shopMenu {
	position: fixed;
	z-index: 1;
	right: 25%;
	top: 0;
}
</style>
<div class="squares" im_type="current_display_mode">
  <input type="hidden" id="lockcompare" value="unlock" />
  <?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
  <ul class="list_pic">
    <?php foreach($output['goods_list'] as $value){?>
    <li class="item">
      <div class="goods-content" imtype_goods=" <?php echo $value['goods_id'];?>" imtype_store="<?php echo $value['store_id'];?>">
        <div class="goods-pic">
          <a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_id']));?>" target="_blank" title="<?php echo $value['goods_name'];?>">
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
            <a href="<?php echo urlShop('goods','index',array('goods_id'=>$value['goods_id']));?>" target="_blank" title="<?php echo $value['goods_jingle'];?>"><?php echo $value['goods_name_highlight'];?>
              <em><?php echo $value['goods_jingle'];?></em>
            </a>
          </div>
          <div class="goods-price"> 
            <div class="p_price">
              <em title="<?php echo $lang['goods_class_index_store_goods_price'].$lang['im_colon'].$lang['currency'].$value['goods_promotion_price'];?>"><b><?php echo L('currency');?></b><?php echo floatval($value['goods_promotion_price']);?></em>
            </div>
            <div class="m_price">
              <span title="市场价：<?php echo $lang['currency'].$value['goods_marketprice'];?>">
                <i><?php echo imPriceFormatForList($value['goods_marketprice']);?></i>
                <?php if ($value['goods_discount']>0){?>
                <b>|</b><u><?php echo $value['goods_discount'];?>折</u><strong></strong>
                <?php }?>
              </span>              
              <div class='icon_bydj'>
                <?php if ($value['goods_freight']==0){?>
                <div class='icon_by' title='包邮'></div>
                <?php }?>
              </div>              
            </div>
            <div class="s_price">              
              <div class='dtype'>
                <?php if($value['store_type']==2){?>
                <em class='ownshop'></em><span>实体店</span>
                <?php }else if($value['store_type']==1){?>
                <em class='ownshop'></em><span>加盟店</span>
                <?php }else {?>
                <em class='ownshop'></em><span>商城自营</span>
                <?php }?>                
              </div>              
            </div>           
          </div>
          <div class="goods-sub">
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
                <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $value['goods_id']));?>#imGoodsRate" target="_blank" class="status"><?php echo $value['goods_salenum'];?></a>
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
          <div class="add-cart">
            <?php if ($value['goods_storage'] == 0) {?>
              <?php if ($value['is_appoint'] == 1) {?>
              <a href="javascript:void(0);" onclick="<?php if ($_SESSION['is_login'] !== '1'){?>login_dialog();<?php }else{?>ajax_form('arrival_notice', '立即预约', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $value['goods_id'], 'type' => 2));?>', 350);<?php }?>"><i class="fa fa-bullhorn"></i>立即预约</a>
              <?php } else {?>
              <a href="javascript:void(0);" onclick="<?php if ($_SESSION['is_login'] !== '1'){?>login_dialog();<?php }else{?>ajax_form('arrival_notice', '到货通知', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $value['goods_id'], 'type' => 2));?>', 350);<?php }?>"><i class="fa fa-bullhorn"></i>到货通知</a>
              <?php }?>
            <?php } else {?>
              <?php if ($value['is_virtual'] == 1 || $value['is_fcode'] == 1 || $value['is_presell'] == 1) {?>
              <a href="javascript:void(0);" imtype="buy_now" data-param="{goods_id:<?php echo $value['goods_id'];?>}"><i class="fa fa-shopping-cart"></i>
              <?php if ($value['is_fcode'] == 1) {echo 'F码购买';}else if($value['is_presell'] == 1) {echo '预售购买';}else{echo '立即购买';}?>
              </a>
              <?php } else {?>
              <a href="javascript:void(0);" imtype="add_cart" data-param="{goods_id:<?php echo $value['goods_id'];?>}"><i class="fa fa-shopping-cart"></i>加入购物车</a>
              <?php }?>
            <?php }?>
          </div>
        </div>
      </div>
    </li>
    <?php }?>
    <div class="clear"></div>
  </ul>
  <?php }else{?>
  <div id="no_results" class="no-results"><i></i><?php echo $lang['index_no_record'];?></div>
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
