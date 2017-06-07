<?php defined('InIMall') or exit('Access Invalid!');?>

<?php if (!empty($output['hot_sales']) && is_array($output['hot_sales'])){$i=0;?>
<div class="slideTxtBox1">
  <div class="hd">
    <div class="title_txt"> <img src="<?php echo SHOP_SKINS_URL;?>/store/<?php echo $output['store_theme'];?>/images/title_txt08.jpg" border="0"> </div>
  </div>
  <div class="title_body">
    <div class="bd">
      <ul>
        <?php foreach ($output['hot_sales'] as $val) {$i++;?>
        <li> 
          <div class="topimg"> 
            <a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>"><img src="<?php echo thumb($val, 240);?>" alt="<?php echo $val['goods_name']?>" style="width:220px; height:220px;" /></a>
            <div class="top_num01"> <img src="<?php echo SHOP_SKINS_URL;?>/store/<?php echo $output['store_theme'];?>/images/top_<?php echo $i;?>.gif" class="iteration"  /> </div>          
          </div> 
          <div class="top_txt"> 
			<b><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>" title="<?php echo $val['goods_name']?>"><?php echo $val['goods_name']?></a></b>
            <p>本店价：<font class="f1"><?php echo $lang['currency'];?><?php echo $val['goods_price'];?></font></p>
            <p><?php echo $val['goods_salenum'];?>人已购买</p>
          </div>
        </li>
        <?php }?>
      </ul> 
    </div>
  </div>
</div>
<div class="blank5"></div>
<script type="text/javascript">
  jQuery(".slideTxtBox1").slide({trigger:"click"});
</script>
<?php }?>