<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="joininOK">
  <i></i><span>恭喜你，开店成功！</span><br />
  <ul class="header_menu">    
    <li class=""><a href="<?php echo urlShop('seller_center','index');?>" class="seller"><i></i>管理店铺</a></li>
    <li class=""><a href="<?php echo urlShop('show_store','index',array('store_id'=>empty($output['store_id'])?DEFAULT_PLATFORM_STORE_ID:$output['store_id']));?>" class="shop"><i></i>查看店铺</a></li>
    <li class=""><a href="<?php echo urlShop('show_help', 'index');?>" class="help"><i></i>开店指南</a></li>
  </ul>
</div>