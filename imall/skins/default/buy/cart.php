<?php defined('InIMall') or exit('Access Invalid!');?>
<style>
.imcr-table-style tbody tr.item_disabled td {
	background: none repeat scroll 0 0 #F9F9F9;
	height: 30px;
	padding: 10px 0;
	text-align: center;
}
</style>
<div class="imcr-main">
  <div class="imcr-title">
    <h3><?php echo $lang['cart_index_ensure_order'];?></h3>
    <h5>查看购物车商品清单，增加减少商品数量，并勾选想要的商品进入下一步操作。</h5>
  </div>
  <form action="<?php echo urlShop('buy','buy_step1');?>" method="POST" id="form_buy" name="form_buy">
    <input type="hidden" value="1" name="ifcart">
    <table class="imcr-table-style" im_type="table_cart">
      <thead>
        <tr>
          <th class="w50"><label>
              <input type="checkbox" checked value="1" id="selectAll">
              全选</label></th>
          <th></th>
          <th><?php echo $lang['cart_index_store_goods'];?></th>
          <th class="w120"><?php echo $lang['cart_index_price'].'('.$lang['currency_zh'].')';?></th>
          <th class="w120"><?php echo $lang['cart_index_amount'];?></th>
          <th class="w120"><?php echo $lang['cart_index_sum'].'('.$lang['currency_zh'].')';?></th>
          <th class="w80"><?php echo $lang['cart_index_handle'];?></th>
        </tr>
      </thead>
      <?php foreach($output['store_cart_list'] as $store_id => $cart_list) {?>
      <tbody>
        <tr>
          <th colspan="20"><strong>店铺：<a href="<?php echo urlShop('show_store','index',array('store_id'=>$store_id), $output['store_list'][$store_id]['store_domain']);?>"><?php echo $cart_list[0]['store_name']; ?></a></strong> <span member_id="<?php echo $output['store_list'][$store_id]['member_id'];?>"></span>
            <?php if (!empty($output['free_freight_list'][$store_id])) {?>
            <div class="store-sale"><em><i class="fa fa-gift"></i>免运费</em><?php echo $output['free_freight_list'][$store_id];?>&emsp;</div>
            <?php } ?>
          </th>
        </tr>
        <!-- S one store list -->
        <?php foreach($cart_list as $cart_info) {?>
        <tr id="cart_item_<?php echo $cart_info['cart_id'];?>" im_group="<?php echo $cart_info['cart_id'];?>" class="shop-list <?php echo $cart_info['state'] ? '' : 'item_disabled';?>">
          <td>
            <input type="checkbox" <?php echo $cart_info['state'] ? 'checked' : 'disabled';?> im_type="eachGoodsCheckBox" value="<?php echo $cart_info['cart_id'].'|'.$cart_info['goods_num'];?>" id="cart_id<?php echo $cart_info['cart_id'];?>" name="cart_id[]">
          </td>
          <?php if ($cart_info['bl_id'] == '0') {?>
          <td class="w60">
            <a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank" class="imcr-goods-thumb">
              <img src="<?php echo thumb($cart_info,60);?>" alt="<?php echo $cart_info['goods_name']; ?>" />
            </a>
          </td>
          <?php } ?>
          <td class="tl" <?php if ($cart_info['bl_id'] != '0') {?>colspan="2"<?php }?>>
            <dl class="imcr-goods-info">
              <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$cart_info['goods_id']));?>" target="_blank"><?php echo $cart_info['goods_name']; ?></a></dt>
              <?php if (!empty($cart_info['xianshi_info'])) {?>
              <dd> <span class="xianshi">满<strong><?php echo $cart_info['xianshi_info']['lower_limit'];?></strong>件，单价直降<em>￥<?php echo $cart_info['xianshi_info']['down_price']; ?></em></span> </dd>
              <?php }?>
              <?php if ($cart_info['ifgroupbuy']) {?>
              <dd> <span class="groupbuy">抢购<?php if ($cart_info['upper_limit']) {?>，最多限购<strong><?php echo $cart_info['upper_limit']; ?></strong>件<?php } ?></span></dd>
              <?php }?>
              <?php if ($cart_info['bl_id'] != '0') {?>
              <dd><span class="buldling">优惠套装，单套直降<em>￥<?php echo $cart_info['down_price']; ?></em></span></dd>
              <?php }?>              

              <!-- S gift list -->
              <?php if (!empty($cart_info['gift_list'])) {?>
              <dd><span class="imcr-goods-gift">赠</span>
                <ul class="imcr-goods-gift-list">
                  <?php foreach ($cart_info['gift_list'] as $goods_info) { ?>
                  <li im_group="<?php echo $cart_info['cart_id'];?>"><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['gift_goodsid']));?>" target="_blank" class="thumb" title="赠品：<?php echo $goods_info['gift_goodsname']; ?> * <?php echo $goods_info['gift_amount'] * $cart_info['goods_num']; ?>"><img src="<?php echo cthumb($goods_info['gift_goodsimage'],60,$store_id);?>" alt="<?php echo $goods_info['gift_goodsname']; ?>" /></a>
                    <?php } ?>
                  </li>
                </ul>
              </dd>
              <?php  } ?>
              <!-- E gift list -->
            </dl>
          </td>
          <td class="w120"><em id="item<?php echo $cart_info['cart_id']; ?>_price"><?php echo $cart_info['goods_price']; ?></em></td>
          <?php if ($cart_info['state']) {?>
          <td class="w120 ws0"><a href="JavaScript:void(0);" onclick="decrease_quantity(<?php echo $cart_info['cart_id']; ?>);" title="<?php echo $lang['cart_index_reduse'];?>" class="add-substract-key tip">-</a>
            <input id="input_item_<?php echo $cart_info['cart_id']; ?>" value="<?php echo $cart_info['goods_num']; ?>" orig="<?php echo $cart_info['goods_num']; ?>" changed="<?php echo $cart_info['goods_num']; ?>" onkeyup="change_quantity(<?php echo $cart_info['cart_id']; ?>, this);" type="text" class="text w20"/>
            <a href="JavaScript:void(0);" onclick="add_quantity(<?php echo $cart_info['cart_id']; ?>);" title="<?php echo $lang['cart_index_increase'];?>" class="add-substract-key tip" >+</a></td>
          <?php } else {?>
          <td class="w120">无效
            <input type="hidden" value="<?php echo $cart_info['cart_id']; ?>" name="invalid_cart[]"></td>
          <?php }?>
          <td class="w120"><?php if ($cart_info['state']) {?>
            <em id="item<?php echo $cart_info['cart_id']; ?>_subtotal" im_type="eachGoodsTotal"><?php echo $cart_info['goods_total']; ?></em>
            <?php }?></td>
          <td class="w80"><?php if ($cart_info['bl_id'] == '0') {?>
            <a href="javascript:void(0)" onclick="collect_goods('<?php echo $cart_info['goods_id']; ?>');"><?php echo $lang['cart_index_favorite'];?></a><br/>
            <?php } ?>
            <a href="javascript:void(0)" onclick="drop_cart_item(<?php echo $cart_info['cart_id']; ?>);"><?php echo $lang['cart_index_del'];?></a></td>
        </tr>

        <!-- S bundling goods list -->
        <?php if (is_array($cart_info['bl_goods_list'])) {?>
        <?php foreach ($cart_info['bl_goods_list'] as $goods_info) { ?>
        <tr class="shop-list <?php echo $cart_info['state'] ? '' : 'item_disabled';?>" im_group="<?php echo $cart_info['cart_id'];?>">
          <td></td>
          <td class="w60">
            <a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank" class="imcr-goods-thumb">
              <img src="<?php echo cthumb($goods_info['goods_image'],60,$store_id);?>" alt="<?php echo $goods_info['goods_name']; ?>" />
            </a>
          </td>
          <td class="tl">
            <dl class="imcr-goods-info">
              <dt><a href="<?php echo urlShop('goods','index',array('goods_id'=>$goods_info['goods_id']));?>" target="_blank"><?php echo $goods_info['goods_name']; ?></a> </dt>
              <?php if ($goods_info['goods_spec']) { ?>
              <dd class="goods-spec"><?php echo $goods_info['goods_spec'];?></dd>
              <?php } ?>
            </dl>
          </td>
          <td><em><?php echo $goods_info['bl_goods_price'];?></em></td>
          <td><?php echo $cart_info['state'] ? '' : '无效';?></td>
          <td></td>
          <td><a href="javascript:void(0)" onclick="collect_goods('<?php echo $goods_info['goods_id']; ?>');"><?php echo $lang['cart_index_favorite'];?></a><br/></td>
        </tr>
        <?php } ?>
        <?php  } ?>
        <!-- E bundling goods list -->

        <?php } ?>
        <!-- E one store list -->

        <!-- S mansong list -->
        <?php if (!empty($output['mansong_rule_list'][$store_id]) && is_array($output['mansong_rule_list'][$store_id])) {?>
        <tr im_group="<?php echo $cart_info['cart_id'];?>">
          <td></td>
          <td class="tl" colspan="10"><div class="store-sale"><em> <i class="fa fa-gift"></i> 满即送 </em><?php echo implode('<br/>', $output['mansong_rule_list'][$store_id]);?></div></td>
        </tr>
        <?php }?>
        <!-- E mansong list -->

        <tr>
          <td class="tr" colspan="20"><div class="imcr-store-account">
              <dl>
                <dt>店铺合计：</dt>
                <dd><em im_type="eachStoreTotal"></em><?php echo $lang['currency_zh'];?></dd>
              </dl>
            </div></td>
        </tr>
        <?php }?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="20"><div class="imcr-all-account"><?php echo $lang['cart_index_goods_sumary'];?><em id="cartTotal"><?php echo $output['cart_totals']; ?></em><?php echo $lang['currency_zh'];?></div></td>
        </tr>
      </tfoot>
    </table>
  </form>
  <div class="imcr-bottom"><a id="next_submit" href="javascript:void(0)" class="imcr-btn imcr-btn-acidblue fr"><i class="fa fa-pencil"></i><?php echo $lang['cart_index_input_next'].$lang['cart_index_ensure_info'];?></a></div>

  <!-- 猜你喜欢 -->
  <div id="guesslike_div"></div>
</div>
<script type="text/javascript">
$(function(){
	//猜你喜欢
	$('#guesslike_div').load('<?php echo urlShop('search', 'get_guesslike', array()); ?>', function(){
        $(this).show();
    });
});
</script>