<?php defined('InIMall') or exit('Access Invalid!');?>
<?php 
  $goods_list = $output['goods_list'];
?>
<?php if(!empty($goods_list) && is_array($goods_list)){?>

  <?php foreach($goods_list as $key=>$val){?>
  <?php $goods_url = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>
  <li imtype="goods_item" class="goods_item" data-goods-id="<?php echo $val['goods_id'];?>" data-goods-name="<?php echo $val['goods_name'];?>" data-goods-price="<?php echo $val['goods_price'];?>" data-goods-promotion-price="    <?php echo $val['goods_promotion_price'];?>" data-goods-marketprice="<?php echo $val['goods_marketprice'];?>" data-goods-image="<?php echo $val['goods_image'];?>">
    <div class="goods-thumb"><a href="<?php echo $goods_url;?>"><img src="<?php echo cthumb($val['goods_image'], 240);?>" alt="<?php echo $val['goods_name'];?>" /></a></div>
    <div class="goods-info"> 
      <b><a href="<?php echo $goods_url;?>" title="<?php echo $val['goods_name'];?>"><?php echo $val['goods_name'];?></a></b>
      <p> 
        <?php echo $lang['currency'];?>
        <?php if (!empty($val['goods_promotion_price']) && $val['goods_promotion_price']>0){?>
        <font class="shop_s"><?php echo $val['goods_promotion_price'];?></font> 
        <?php }else{?>
        <font class="shop_s"><?php echo $val['goods_price'];?></font> 
        <?php }?>   
      </p>
    </div >
    <a imtype="btn_module_goods_operate" class="imsc-btn-mini" href="javascript:;"><i class="fa fa-plus"></i>选择添加</a>
  </li>
  <?php }?>  

<div class="pagination"><?php echo $output['show_page']; ?></div>
<?php } else { ?>
<li class="goods_item">暂无商品</li>
<?php } ?>
