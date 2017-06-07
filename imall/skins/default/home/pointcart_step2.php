<link href="<?php echo SHOP_SKINS_URL;?>/css/home_cart.css" rel="stylesheet" type="text/css">
<style type="text/css">
.head-search-bar, .head-user-menu, .public-nav-layout, .head-app {
	display: none !important;
}
</style>

<div class="wrapper pr">
  <ul class="imcr-flow imcr-point-flow">
    <li class=""><i class="step1"></i>
      <p><?php echo $lang['pointcart_ensure_order'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
    <li class=""><i class="step2"></i>
      <p><?php echo $lang['pointcart_ensure_info'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
    <li class="current"><i class="step4"></i>
      <p><?php echo $lang['pointcart_exchange_finish'];?></p>
      <sub></sub>
      <div class="hr"></div>
    </li>
  </ul>
  <div class="imcr-main">
    <div class="imcr-title">
      <h3><?php echo $lang['pointcart_exchange_finish'];?></h3>
      <h5>兑换订单已提交完成，祝您购物愉快</h5>
    </div>
      <div class="imcr-receipt-info mb30">
        <div class="imcr-finish-a"><i></i><?php echo $lang['pointcart_step2_order_created'];?>
          <span class="all-points"><?php echo $lang['pointcart_step2_order_allpoints'].$lang['im_colon'];?><em><?php echo $output['order_info']['point_allpoint']; ?></em></span> </div>
        <div class="imcr-finish-b">可通过用户中心<a href="<?php echo SHOP_SITE_URL?>/index.php?act=member_pointorder&op=orderlist">积分兑换记录</a>查看兑换单状态。

        </div>
        <div class="imcr-finish-c mb30">
        <a class="imcr-btn-mini imcr-btn-green mr15" href="<?php echo SHOP_SITE_URL?>"><i class="fa fa-shopping-cart"></i>继续购物</a>
        <a class="imcr-btn-mini imcr-btn-acidblue" href="index.php?act=member_pointorder&op=order_info&order_id=<?php echo $output['order_info']['point_orderid'];?>"><i class="fa fa-file-text-o"></i><?php echo $lang['pointcart_step2_view_order'];?></a></div>
      </div>
  </div>
</div>