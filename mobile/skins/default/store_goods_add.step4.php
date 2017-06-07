<link href="<?php echo MOBILE_SKINS_URL;?>/css/promotion.css" rel="stylesheet" type="text/css">
<style>
    .header_title{width:90%;}
</style>
<div class="warp">
    <div class="header navbar-fixed-top">
        <div class="return fl">
            <a href="<?php echo WAP_SITE_URL;?>/tmpl/seller/seller.html"><img width="15" height="25" src="<?php echo MOBILE_SKINS_URL;?>/css/images/return_img.jpg"/></a>
        </div>
        <div class="header_title hh">完成发布</div>
        <div class="yulan">

        </div>
        <div class="clear"></div>
    </div>
<div class="alert alert-block hr32">
  <h2><i class="icon-ok-circle mr10"></i><?php echo $lang['store_goods_step3_goods_release_success'];?>&nbsp;&nbsp;<?php if (C('goods_verify')) {?>等待管理员审核商品！<?php }?></h2>
  <div class="hr16"></div>
  <ul class="ml30">
    <li>1. <?php echo $lang['store_goods_step3_continue'];?> &quot; <a href="<?php echo urlMobile('store_goods_add', 'index');?>"><?php echo $lang['store_goods_step3_release_new_goods'];?></a>&quot;</li>
      <li>2. 返回 &quot; <a href="<?php echo urlMobile('goods', 'index');?>">商品列表</a>&quot;</li>
      <li>3. 返回 &quot; <a href="../wap/tmpl/seller/seller.html">卖家中心</a>&quot;</li>
  </ul>
</div>
</div>