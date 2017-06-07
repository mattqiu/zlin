<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="imcr-main">
  <div class="imcr-title">
    <h3><?php echo $lang['cart_index_buy_finish'];?></h3>
    <h5>订单已支付完成，祝您购物愉快。</h5>
  </div>
  <div class="imcr-receipt-info mb30">
  <div class="imcr-finish-a"><i></i>订单支付成功！您已成功支付订单金额<em>￥<?php echo $_GET['pay_amount'];?></em>。</div>
  <div class="imcr-finish-b">可通过用户中心<a href="<?php echo SHOP_SITE_URL?>/index.php?act=member_order">已买到的商品</a>查看订单状态。</div>
  <div class="imcr-finish-c mb30"><a href="<?php echo SHOP_SITE_URL?>" class="imcr-btn-mini imcr-btn-green mr15"><i class="fa fa-shopping-cart"></i>继续购物</a><a href="<?php echo SHOP_SITE_URL?>/index.php?act=member_order" class="imcr-btn-mini imcr-btn-acidblue"><i class="fa fa-file-text-o"></i>查看订单</a></div>
  </div>
</div>