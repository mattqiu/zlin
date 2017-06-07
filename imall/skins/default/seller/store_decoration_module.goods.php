<?php defined('InIMall') or exit('Access Invalid!');?>

<?php 
if(empty($output['goods_list'])) {	
    $block_content = empty($block_content) ? $output['block_content'] : $block_content; 
    $data = unserialize($block_content);
	$goods_list  = $data['goods'];
	$goods_title = $data['title'];
	$goods_slide = $data['slide'];
	$goods_brand = $data['brand'];
	
	$block = empty($block) ? $output['block'] : $block;
}
?>
<?php if(!empty($goods_title) && is_array($goods_title)){?>
<div class="title_list">
  <div class="title_txt">
    <?php if ($goods_title['type']=='img'){?>
    <div class="pic-type"><img src="<?php echo $goods_title['url'];?>"/></div>      
    <?php }else { ?>
    <?php 
	  $title_arr = explode('|',$goods_title['text']);
	  if (count($title_arr)>1){
		$title_floor = $title_arr[0];
		$title_caption = $title_arr[1];
	  }else{
		$title_floor = '';
		$title_caption = $goods_title['text'];
	  }
	?>
    <div class="txt-type">
      <?php if ($title_floor!=''){?><span><?php echo $title_floor;?></span><?php }?>
      <h2 title="<?php echo $goods_title['text'];?>"><?php echo $title_caption;?></h2>
    </div>      
    <?php } ?>
  </div>
  <div class="title_key">
    <ul>
      <?php if(!empty($goods_title['adv']) && is_array($goods_title['adv'])){?>
      <?php foreach($goods_title['adv'] as $key=>$adv){?>
      <li>
        <a href="<?php echo $adv['adv_url'];?>" target="_blank" title="<?php echo $adv['name'];?>"><?php echo $adv['adv_name'];?></a>
      </li>
      <?php }?>
      <?php }?>   
    </ul>
  </div>
</div>
<?php }?>  
<div class="title_body">
  <?php if(!empty($goods_slide) && is_array($goods_slide)){$slide_show=1;?>
  <div id="slideBox1" class="slideBox1">    
    <div class="hd">
      <ul>        
        <?php foreach($goods_slide as $key=>$slide){?>
        <li></li>
        <?php }?>           
      </ul>
    </div>
    <div class="bd">
      <ul>
        <?php foreach($goods_slide as $key=>$slide){?>
        <?php $image_url = getStoreDecorationImageUrl($slide['image_name']);?>
        <li imtype="slide_item">
          <a href="<?php echo $slide['image_link'];?>" target="_blank"><img src="<?php echo $image_url;?>" alt="" class="goodsimg" /></a>
        </li>
        <?php }?>                           	
      </ul>
    </div>
    <script type="text/javascript">
	  $(".slideBox1").slide({mainCell:".bd ul",autoPlay:true});
    </script>
  </div>
  <?php }?>
  <?php if(!empty($goods_list) && is_array($goods_list)){?>
  <ul class="goods_list <?php if ($slide_show==1){?>w930<?php }else{?>w1200<?php }?>"> 
    <?php $i=0; foreach($goods_list as $key=>$val){$i++; if ($i>10 && $slide_show==1) break;?>
    <?php $goods_url = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>
    <li imtype="goods_item" class="goods_item">
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
          <font class="marker_s"><?php echo $val['goods_marketprice'];?></font> 
        </p>
      </div >
    </li>
    <?php }?>  
  </ul>
  <?php }?>
  <?php if(!empty($goods_brand) && is_array($goods_brand)){?>
  <div class="brand_logo">
    <ul>       
      <?php $i=0; foreach($goods_brand as $key=>$brand){$i++; if ($i>10){break;}?>    
      <li> 
        <a href="<?php echo $brand['brand_url'];?>" target="_blank" title="<?php echo $brand['brand_name'];?>"> 
          <img width="90" height="30" border="0" alt="<?php echo $brand['brand_name'];?>" src="<?php echo $brand['brand_img'];?>">
        </a> 
      </li>     
      <?php }?>      
    </ul>
  </div>
  <?php }?>
</div>
<div class="blank" style="height:0"></div>