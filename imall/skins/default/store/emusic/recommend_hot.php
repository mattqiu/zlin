<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="title_list">
  <div class="title_txt"> <img src="<?php echo SHOP_SKINS_URL;?>/store/<?php echo $output['store_theme'];?>/images/title_txt02.jpg" border="0"> </div>
</div> 
<?php if (!empty($output['hot_sales']) && is_array($output['hot_sales'])){$i=0;?>
<div class="title_body ">
  <dl class="hot_list01">
    <?php foreach ($output['hot_sales'] as $val) {$i++; if ($i>3){break;}?>
    <dd >
      <div class="hot_img"> 
        <span class="hot"></span>
        <div class="pic"><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>"><img src="<?php echo thumb($val, 240);?>" alt="<?php echo $val['goods_name']?>" class="goodsimg" /></a></div>
        <div class="text">
          <p class="name"><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>" title="<?php echo $val['goods_name']?>"><?php echo $val['goods_name']?></a></p>
          <p class="brife"><?php echo str_replace("\n", "<br>", $val['goods_jingle']);?></p>
          <p class="price" >
            <font class="aa">
			<?php echo $lang['currency'];?>
            <?php 
			if (!empty($val['goods_promotion_price']) && $val['goods_promotion_price']>0){
              echo $val['goods_promotion_price'];
			}else{
              echo $val['goods_price'];
			}
			?>        
            </font> 
            <font class="bb"><?php echo $val['goods_marketprice'];?></font> 
          </p>
        </div>
      </div>
      <div class="hot_btn"> <a href="javascript:addToCartShowDiv(<?php echo $val['goods_id'];?>,1,'hot')"></a> </div>
      <div class="hot_line" > </div>
    </dd>
    <?php }?>
  </dl>
</div>
<?php }?>
<div class="title_body ">
  <dl class="hot_list02">
    <?php if (!empty($output['recommended_goods_list']) && is_array($output['recommended_goods_list'])){$i=0;?>
    <dt>
      <div class="hot_img"> 
        <span class="hot"></span> 
        <?php foreach ($output['recommended_goods_list'] as $val) {$i++; if ($i>1){break;}?>
        <div class="text">
          <p class="name"><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>" title="<?php echo $val['goods_name']?>"><?php echo $val['goods_name']?></a></p>
          <p class="brife"><?php echo str_replace("\n", "<br>", $val['goods_jingle']);?></p>
          <p class="price" > 
            <font class="aa">
            <?php echo $lang['currency'];?>
            <?php 
			if (!empty($val['goods_promotion_price']) && $val['goods_promotion_price']>0){
              echo $val['goods_promotion_price'];
			}else{
              echo $val['goods_price'];
			}
			?>        
            </font> <font class="bb"><?php echo $val['goods_marketprice'];?></font> 
          </p>
        </div>
        <div class="pic"> <a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>"><img src="<?php echo thumb($val, 240);?>" border="0" alt="<?php echo $val['goods_name']?>"/></a></div>    
        <div class="hot_btn"><a href="javascript:addToCartShowDiv(<?php echo $val['goods_id'];?>,1,'promotion')"  ></a> </div>
        <div class="hot_line" > </div>
        <?php }?> 
      </div>
    </dt> 
    <?php }?>
    
    <?php if (!empty($output['new_goods_list']) && is_array($output['new_goods_list'])){$i=0;?>
    <?php foreach ($output['new_goods_list'] as $val) {$i++; if ($i>2){break;}?>
    <dd >
      <div class="hot_img"> 
        <span class="hot"></span>
        <div class="text">
          <p class="name"><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>" title="<?php echo $val['goods_name']?>"><?php echo $val['goods_name']?></a></p>
          <p class="brife"><?php echo str_replace("\n", "<br>", $val['goods_jingle']);?></p>
          <p class="price" > 
            <font class="aa">
            <?php echo $lang['currency'];?>
            <?php 
			if (!empty($val['goods_promotion_price']) && $val['goods_promotion_price']>0){
              echo $val['goods_promotion_price'];
			}else{
              echo $val['goods_price'];
			}
			?>        
            </font> <font class="bb"><?php echo $val['goods_marketprice'];?></font> 
          </p>
        </div>
        <div class="pic"><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>"><img src="<?php echo thumb($val, 240);?>" alt="<?php echo $val['goods_name']?>" class="goodsimg" /></a></div>
      </div>
      <div class="hot_btn"> <a href="javascript:addToCartShowDiv(<?php echo $val['goods_id'];?>,1,'new')"  ></a> </div>
      <div class="hot_line" > </div>
    </dd>
    <?php }?>
    <?php }?>
    
    <?php if (!empty($output['hot_collect']) && is_array($output['hot_collect'])){$i=0;?>
    <?php foreach ($output['hot_collect'] as $val) {$i++; if ($i>2){break;}?>
    <dd class="hot_dd02" >
      <div class="hot_img"> 
        <span class="hot1"></span>    
        <div class="pic"><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>"><img src="<?php echo thumb($val, 240);?>" alt="<?php echo $val['goods_name']?>" class="goodsimg" /></a></div>
        <div class="text">
          <p class="name"><a href="<?php echo urlShop('goods', 'index',array('goods_id'=>$val['goods_id']));?>" title="<?php echo $val['goods_name']?>"><?php echo $val['goods_name']?></a></p>
          <p class="brife"><?php echo str_replace("\n", "<br>", $val['goods_jingle']);?></p>          
          <p class="price" > 
            <font class="aa">
            <?php echo $lang['currency'];?>
            <?php 
			if (!empty($val['goods_promotion_price']) && $val['goods_promotion_price']>0){
              echo $val['goods_promotion_price'];
			}else{
              echo $val['goods_price'];
			}
			?>        
            </font> <font class="bb"><?php echo $val['goods_marketprice'];?></font> 
          </p>
        </div>
      </div>
      <div class="hot_btn"><a href="javascript:addToCartShowDiv(<?php echo $val['goods_id'];?>,1,'best')"  ></a> </div>
      <div class="hot_line" > </div>
    </dd>
    <?php }?>
    <?php }?>
  </dl>
</div>