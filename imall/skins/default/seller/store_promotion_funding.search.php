<?php defined('InIMall') or exit('Access Invalid!');?>
<?php if(!empty($output['goods_list']) && is_array($output['goods_list'])){?>
<ul class="goods-list" style="width:760px;">
  <?php foreach($output['goods_list'] as $key=>$val){?>
  <li>
    <div class="goods-thumb"><img src="<?php echo thumb($val, 240);?>"/></div>
    <dl class="goods-info">
      <dt><?php echo $val['goods_name'];?></dt>
      <dd>销售价：<?php echo $lang['currency'].$val['goods_price'];?>
    </dl>
    <a imtype="btn_add_funding_goods" data-goods-commonid="<?php echo $val['goods_commonid'];?>" href="javascript:void(0);" class="imsc-btn-mini imsc-btn-green"><i class="fa fa-check-circle-o "></i>选择为众筹商品</a> </li>
  <?php } ?>
</ul>
<div class="pagination"><?php echo $output['show_page']; ?></diWWWv>
<?php } else { ?>
<div><?php echo $lang['no_record'];?></div>
<?php } ?>