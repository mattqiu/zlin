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
<div class="top">
  <dl>
    <dt>
      <h4>标题图片</h4>
      <a imtype="btn_edit_goods_module" data-module-type="title" data-block-id="<?php echo $block['block_id'];?>" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
    </dt>
    <dd>       
      <div class="title_list">
        <div class="title_txt" imtype="title_item" data-title-type="<?php echo $goods_title['type'];?>" data-title-url="<?php echo $goods_title['url'];?>" data-title-text="<?php echo $goods_title['text'];?>" data-title-img="<?php echo $goods_title['img'];?>">
          <?php if ($goods_title['type']=='img'){?>
          <div class="pic-type"><img src="<?php echo $goods_title['url'];?>"/></div>      
          <?php }else {?>
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
            <li imtype="adv_item" data-adv-name="<?php echo $adv['adv_name'];?>" data-adv-url="<?php echo $adv['adv_url'];?>">
              <a href="<?php echo $adv['adv_url'];?>" target="_blank" title="<?php echo $adv['adv_name'];?>"><?php echo $adv['adv_name'];?></a>
            </li>
            <?php }?>
            <?php }?>   
          </ul>
        </div>
      </div>
    </dd>
  </dl>
</div>

<div class="middle">  
  <dl class="left">
    <dt>
      <h4>切换广告图片</h4>
      <a imtype="btn_edit_goods_module" data-module-type="slide" data-block-id="<?php echo $block['block_id'];?>" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
    </dt>
    <dd>
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
            <li imtype="slide_item" data-image-url="<?php echo $image_url;?>" data-image-name="<?php echo $slide['image_name'];?>" data-image-link="<?php echo $slide['image_link'];?>">
              <a href="<?php echo $slide['image_link'];?>" target="_blank"><img src="<?php echo $image_url;?>" alt="" class="goodsimg" /></a>
            </li>
            <?php }?>                           	
          </ul>
        </div>
        <script type="text/javascript">
	      $(".slideBox1").slide({mainCell:".bd ul",autoPlay:true});
        </script>
      </div>
    </dd>
  </dl>
  <dl class="right" imtype="store_decoration_goods_list">
    <dt>
      <h4>商品列表</h4>
      <a imtype="btn_edit_goods_module" data-module-type="goods" data-block-id="<?php echo $block['block_id'];?>" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
    </dt>
    <dd>
      <ul class="goods_list w930"> 
        <?php $i=0; foreach($goods_list as $key=>$val){$i++; if ($i>10) break;?>
        <?php $goods_url = urlShop('goods', 'index', array('goods_id' => $val['goods_id']));?>
        <li imtype="goods_item" class="goods_item" data-goods-id="<?php echo $val['goods_id'];?>" data-goods-name="<?php echo $val['goods_name'];?>" data-goods-price="<?php echo $val['goods_price'];?>" data-goods-promotion-price="<?php echo $val['goods_promotion_price'];?>" data-goods-marketprice="<?php echo $val['goods_marketprice'];?>" data-goods-image="<?php echo $val['goods_image'];?>">
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
    </dd>
  </dl>
</div>

<div class="bottom">
  <dl>
    <dt>
      <h4>推荐品牌</h4>
      <a imtype="btn_edit_goods_module" data-module-type="brand" data-block-id="<?php echo $block['block_id'];?>" href="javascript:;"><i class="fa fa-pencil-square-o"></i>编辑</a>
    </dt>
    <dd> 
      <div class="brand_logo">
        <ul>       
          <?php foreach($goods_brand as $key=>$brand){?>    
          <li imtype="brand_item" class="brand_item" data-brand-name="<?php echo $brand['brand_name'];?>" data-brand-img="<?php echo $brand['brand_img'];?>" data-brand-url="<?php echo $brand['brand_url'];?>"> 
            <a href="<?php echo $brand['brand_url'];?>" target="_blank" title="<?php echo $brand['brand_name'];?>"> 
              <img width="90" height="30" border="0" alt="<?php echo $brand['brand_name'];?>" src="<?php echo $brand['brand_img'];?>">
            </a> 
          </li>     
          <?php }?>    
        </ul>
      </div>
    </dd>
  </dl>
</div>
<div class="blank" style="height:0"></div>