<?php defined('InIMall') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_SKINS_URL;?>/css/home_activity.css" rel="stylesheet" type="text/css">

<!-- 下期预告背景 -->
<div class="content-miaosha-next-bg"> </div>
   </div>
<!-- 主容器 -->
<div class="container">
  <!-- 内容 -->
  <div class="content">
    <?php if (!empty($output['be_xianshi_list']) && is_array($output['be_xianshi_list'])){?>
    <!-- 横幅-->
    <div class="content-miaosha-countdown-processing-region"><?php echo loadadv(12);?></div>
    <!-- 即将开始列表上半部 -->
    <div class="content-miaosha-countdown-top">
      <!-- 下期抢先看 -->
      <div class="content-miaosha-countdown-top-next">
        <div class="content-miaosha-countdown-top-next-bg"></div>
        <div class="content-miaosha-countdown-top-next-content"></div>
      </div>
      <div class="content-miaosha-countdown-top-list-region">
        <ul>        
          <!-- 等待秒杀加next样式，立即秒杀加process，秒杀结束加end -->          
	      <?php foreach ($output['be_xianshi_list'] as $k => $v){?>
          <li class="content-miaosha-countdown-top-list-item next">
            <div class="content-miaosha-countdown-top-img-region" title="<?php echo $v['goods_name'];?>">
              <a href="<?php echo $v['goods_url'];?>" target="_blank">
                <img src="<?php echo $v['image_url'];?>" alt="<?php echo $v['xianshi_name']; ?>" />
              </a>
            </div>
            <div class="content-miaosha-countdown-top-name" title="<?php echo $v['xianshi_explain']; ?>">
              <a href="<?php echo $v['goods_url'];?>" target="_blank"><span style="color:#F00"><?php echo $v['xianshi_title']; ?></span>:<?php echo $v['goods_name'];?></a>
            </div>
            <div class="content-miaosha-countdown-top-price-region">
              <span class="content-miaosha-countdown-top-price-title">惊爆价：</span>
              <span class="content-miaosha-countdown-top-price-current"><?php echo imPriceFormatForList($v['xianshi_price']); ?></span>
              <span class="content-miaosha-countdown-bottom-price-discount"><?php echo imPriceFormatForList($v['goods_price']);?></span>
            </div>
            <!-- 填充文字：立即秒杀，秒杀结束 -->
            <!-- 填充文字：即将开始 -->
            <div class="content-miaosha-countdown-top-btn unclickable">
              <a href="<?php echo $v['goods_url'];?>" target="_blank" ><span class="content-miaosha-countdown-top-click">查看详情</span></a>
              <span class="content-miaosha-countdown-top-txt">开始：<?php echo date("Y-m-d",$v['start_time']);?></span>
            </div>
            <!-- 折扣信息 -->
            <div class="content-seckill-dazhe-region"><?php echo $v['xianshi_discount']; ?></div>
          </li>
          <?php }?>                  
        </ul>
      </div>
      <br style="clear:both;" />
    </div>
    <?php }?>     
    <!-- 正在进行列表 -->
    <!-- 头部-->
    <?php if (!empty($output['xianshi_list']) && is_array($output['xianshi_list'])){?>
    <div class="content-miaosha-countdown-middle-region"><?php echo loadadv(11);?></div>    
    <div class="content-youhui-middle">
      <div class="content-miaosha-countdown-middle-over">
        <div class="content-miaosha-countdown-middle-over-bg"></div>
        <div class="content-miaosha-countdown-middle-over-content"></div>
      </div>
      <div class="content-miaosha-countdown-middle-list-region">       
	    <?php foreach ($output['xianshi_list'] as $k => $v){?>
          <li class="content-miaosha-countdown-middle-list-item next">
            <div class="content-miaosha-countdown-middle-img-region" title="<?php echo $v['goods_name'];?>">
              <a href="<?php echo $v['goods_url'];?>" target="_blank" >
                <img src="<?php echo $v['image_url'];?>" />
              </a>
            </div>
            <div class="content-miaosha-countdown-middle-name" title="<?php echo $v['xianshi_explain']; ?>">
              <a href="<?php echo $v['goods_url'];?>" target="_blank"><span style="color:#F00"><?php echo $v['xianshi_title']; ?></span>:<?php echo $v['goods_name'];?></a>
            </div>
            <div class="content-miaosha-countdown-middle-price-region">
              <span class="content-miaosha-countdown-middle-price-title">惊爆价：</span>
              <span class="content-miaosha-countdown-middle-price-current"><?php echo imPriceFormatForList($v['xianshi_price']); ?></span>
              <span class="content-miaosha-countdown-bottom-price-discount"><?php echo imPriceFormatForList($v['goods_price']);?></span>
            </div>
            <!-- 填充文字：立即秒杀，秒杀结束 -->
            <!-- 填充文字：即将开始 -->
            <div class="content-miaosha-countdown-middle-btn unclickable">
              <a href="<?php echo $v['goods_url'];?>" target="_blank" ><span class="content-miaosha-countdown-middle-click">立即团购</span></a>
              <span class="content-miaosha-countdown-middle-txt">结束：<?php echo date("Y-m-d",$v['end_time']);?></span>
            </div>
            <!-- 折扣信息 -->
            <div class="content-seckill-dazhe-region"><?php echo $v['xianshi_discount']; ?></div>
          </li>
          <?php }?>  
      </div>
      <div class="tc mt10">
        <div class="pagination tc"><?php echo $output['show_page'];?></div>
      </div>
      <br style="clear:both;" />
    </div>
    <?php }?>
    <!-- 头部-->
    <?php if (!empty($output['ov_xianshi_list']) && is_array($output['ov_xianshi_list'])){?>
    <div class="content-miaosha-countdown-bottom-region"><?php echo loadadv(10);?></div>
    <!-- 秒杀列表下半部 -->
    <div class="content-miaosha-countdown-bottom">
      <!-- 刚团完 -->
      <div class="content-miaosha-countdown-bottom-over">
        <div class="content-miaosha-countdown-bottom-over-bg"></div>
        <div class="content-miaosha-countdown-bottom-over-content"></div>
      </div>
      <div class="content-miaosha-countdown-bottom-list-region">
        <ul>        
          <!-- 即将开始加next样式，秒杀结束加end -->          
	      <?php foreach ($output['ov_xianshi_list'] as $k => $v){?>
          <li class="content-miaosha-countdown-bottom-list-item end">
            <div class="content-miaosha-countdown-bottom-img-region" title="<?php echo $v['goods_name'];?>">
              <a href="<?php echo $v['goods_url'];?>"  target="_blank">
                <img src="<?php echo $v['image_url'];?>" />
              </a>
            </div>
            <div class="content-miaosha-countdown-bottom-name"  title="<?php echo $v['xianshi_explain']; ?>">
              <a href="<?php echo $v['goods_url'];?>" target="_blank"><span style="color:#F00"><?php echo $v['xianshi_title']; ?></span>:<?php echo $v['goods_name']; ?></a>
            </div>
            <div class="content-miaosha-countdown-bottom-price-region">
              <span class="content-miaosha-countdown-bottom-price-title">惊爆价：</span>
              <span class="content-miaosha-countdown-bottom-price-current"><?php echo imPriceFormatForList($v['xianshi_price']); ?></span>
              <span class="content-miaosha-countdown-bottom-price-discount"><?php echo imPriceFormatForList($v['goods_price']);?></span>
            </div>
            <!-- 填充文字：秒杀结束，即将开始 -->
            <div class="content-miaosha-countdown-bottom-btn">
              <span class="content-miaosha-countdown-bottom-btn-click">活动结束</span>
              <span class="content-miaosha-countdown-bottom-btn-txt">结束：<?php echo date("Y-m-d",$v['end_time']);?></span>
            </div>
            <!-- 折扣信息 -->
            <div class="content-seckill-dazhe-region">结束</div>
          </li>
          <?php }?>          
        </ul>
      </div>
      <br style="clear:both;" />
    </div>
    <?php }?> 
  </div>
</div>

<script>
$(function(){
	$('.content-youhui-bottom-praise').click(function(){
		var key=$(this).attr('dataparam');		
		var ok=follow_activity(key,'count',this);

		return false;
	});	
})
</script>