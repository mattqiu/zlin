<?php defined('InIMall') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_SKINS_URL;?>/css/home_activity.css" rel="stylesheet" type="text/css">

<!-- 下期预告背景 -->
<div class="content-miaosha-next-bg"> </div>
   </div>
<!-- 主容器 -->
<div class="container">
  <!-- 内容 -->
  <div class="content">
    <?php if (!empty($output['be_activity_list']) && is_array($output['be_activity_list'])){?>
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
	      <?php foreach ($output['be_activity_list'] as $k => $v){?>
          <li class="content-miaosha-countdown-top-list-item next">
            <div class="content-miaosha-countdown-top-img-region">
              <a href="<?php echo urlShop('activity','index',array('activity_id'=>$v['activity_id']));?>" target="_blank">
                <img src="<?php if(is_file(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY.DS.$v['activity_image'])){echo UPLOAD_SITE_URL."/".ATTACH_ACTIVITY."/".$v['activity_image'];}else{echo SHOP_SKINS_URL."/images/sale_banner.jpg";}?>" />
              </a>
            </div>
            <div class="content-miaosha-countdown-top-name" title="<?php echo $v['activity_title'];?>">
              <a href="<?php echo urlShop('activity','index',array('activity_id'=>$v['activity_id']));?>" target="_blank"><?php echo $v['activity_title'];?></a>
            </div>
            <div class="content-miaosha-countdown-top-price-region">
              <span class="content-miaosha-countdown-top-price-title">开始时间：</span>
              <span class="content-miaosha-countdown-top-price-current"><?php echo date("Y-m-d",$v['activity_start_date']);?></span>
              <span class="content-miaosha-countdown-bottom-price-discount"></span>
            </div>
            <!-- 填充文字：立即秒杀，秒杀结束 -->
            <!-- 填充文字：即将开始 -->
            <div class="content-miaosha-countdown-top-btn unclickable">
              <a href="<?php echo urlShop('activity','index',array('activity_id'=>$v['activity_id']));?>" target="_blank" ><span class="content-miaosha-countdown-top-click">查看详情</span></a>
              <span class="content-miaosha-countdown-top-txt">摩拳擦掌</span>
            </div>
            <!-- 折扣信息 -->
            <div class="content-seckill-dazhe-region">等</div>
          </li>
          <?php }?>                  
        </ul>
      </div>
      <br style="clear:both;" />
    </div>
    <?php }?>     
    <!-- 正在进行列表 -->
    <!-- 头部-->
    <?php if (!empty($output['activity_list']) && is_array($output['activity_list'])){?>
    <div class="content-miaosha-countdown-middle-region"><?php echo loadadv(11);?></div>    
    <div class="content-youhui-middle">
      <div class="content-miaosha-countdown-middle-over">
        <div class="content-miaosha-countdown-middle-over-bg"></div>
        <div class="content-miaosha-countdown-middle-over-content"></div>
      </div>
      <ul id="content-youhui-middle-ul">        
	    <?php foreach ($output['activity_list'] as $k => $v){?>
        <li class="content-youhui-list-item" id="3027" entry="1">
          <div class="content-youhui-list-top">
            <span class="content-youhui-top-txt">活动时间：<?php echo date("Y-m-d",$v['activity_start_date']);?>-<?php echo date("Y-m-d",$v['activity_end_date']);?></span>
            <span class="content-youhui-top-bottom-list-bottom-right-date-region"></span>
          </div>
          <div class="content-youhui-list-middle">
            <span class="content-youhui-img-region">
              <span class="content-youhui-left-img">
                <a href="<?php echo urlShop('activity','index',array('activity_id'=>$v['activity_id']));?>" target="_blank" clstag="xuan|keycount|homepage|imge">
                  <img src="<?php if(is_file(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY.DS.$v['activity_image'])){echo UPLOAD_SITE_URL."/".ATTACH_ACTIVITY."/".$v['activity_image'];}else{echo SHOP_SKINS_URL."/images/sale_banner.jpg";}?>" />
                </a>
              </span>              
              <span class="content-youhui-right-img">
                <?php if (!empty($v['goods']) && is_array($v['goods'])){?>
	            <?php foreach ($v['goods'] as $key => $good){?>
                <a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$good['goods_id']));?>" target="_blank" clstag="xuan|keycount|homepage|subgoods">
                  <div class="content-youhui-right-top-img" title="<?php echo $good['goods_name'];?>">
                    <img src="<?php echo thumb($good, 160);?>" />
                    <div class="content-youhui-right-top-price">
                      <div class="content-youhui-right-top-price-name"><?php echo mb_substr($good['goods_name'],0,10,'utf-8');?>...</div>
                      <div class="content-youhui-right-top-price-txt"><?php echo $lang['currency'].$good['goods_price'];?></div>
                    </div>
                    <div class="content-youhui-right-top-price-mask"></div>
                  </div>
                </a>
                <?php }?> 
                <?php }?>               
              </span>
            </span>
            <span class="content-youhui-content-region">
              <div class="content-youhui-content-title">
                <div class="content-youhui-main-title">
                  <a href="<?php echo urlShop('activity','index',array('activity_id'=>$v['activity_id']));?>" target="_blank">
				  <?php echo $v['activity_title'];?>&nbsp;<font style="color:#cc0000;font-size:20px;margin-bottom:5px;"></font>
                  </a>
                </div>
              </div>
              <div class="content-youhui-description">
                <div class="content-youhui-description-region">
                  <?php echo $v['activity_desc'];?>...
                  <a href="<?php echo urlShop('activity','index',array('activity_id'=>$v['activity_id']));?>" target="_blank" >阅读全文</a>
                </div>
              </div>
              <div class="content-youhui-bottom-region">
                <!-- 跳转按钮 -->
                <a href="<?php echo urlShop('activity','index',array('activity_id'=>$v['activity_id']));?>" target="_blank">
                  <span class="content-youhui-bottom-goto-btn _zhidaolianjie_home_page_zhidao">直达链接</span>
                  <span class="content-youhui-bottom-goto-btn _woxiangyao_home_page_zhidao">我想要</span>
                </a>
                <!-- 赞 -->
                <span class="content-youhui-bottom-praise" dataparam="<?php echo $v['activity_id'];?>">赞（<em class="followCountNum"><?php echo $v['activity_follows'];?></em>）</span>
                <!-- 分享到 -->
                <a href="<?php echo urlShop('activity','index',array('activity_id'=>$v['activity_id']));?>" target="_blank">
                <span class="content-youhui-bottom-share" id="share-0">
                  <s class="content-youhui-bottom-share-icon"></s>
                  <span class="content-youhui-bottom-share-txt">查看（<?php echo $v['activity_views'];?>）</span>
                </span>
                </a>
              </div>
            </span>
          </div>
        </li>        
        <?php }?>       
      </ul>
      <div class="tc mt10">
        <div class="pagination tc"><?php echo $output['show_page'];?></div>
      </div>
    </div>
    <?php }?>
    <!-- 头部-->
    <?php if (!empty($output['close_activity_list']) && is_array($output['close_activity_list'])){?>
    <div class="content-miaosha-countdown-bottom-region"><?php echo loadadv(10);?></div>
    <!-- 秒杀列表下半部 -->
    <div class="content-miaosha-countdown-bottom">
      <!-- 刚抢完 -->
      <div class="content-miaosha-countdown-bottom-over">
        <div class="content-miaosha-countdown-bottom-over-bg"></div>
        <div class="content-miaosha-countdown-bottom-over-content"></div>
      </div>
      <div class="content-miaosha-countdown-bottom-list-region">
        <ul>        
          <!-- 即将开始加next样式，秒杀结束加end -->          
	      <?php foreach ($output['close_activity_list'] as $k => $v){?>
          <li class="content-miaosha-countdown-bottom-list-item end">
            <div class="content-miaosha-countdown-bottom-img-region">
              <a href="<?php echo urlShop('activity','index',array('activity_id'=>$v['activity_id']));?>"  target="_blank">
                <img src="<?php if(is_file(BASE_UPLOAD_PATH.DS.ATTACH_ACTIVITY.DS.$v['activity_image'])){echo UPLOAD_SITE_URL."/".ATTACH_ACTIVITY."/".$v['activity_image'];}else{echo SHOP_SKINS_URL."/images/sale_banner.jpg";}?>" />
              </a>
            </div>
            <div class="content-miaosha-countdown-bottom-name">
              <a href="<?php echo urlShop('activity','index',array('activity_id'=>$v['activity_id']));?>"  title="<?php echo $v['activity_title'];?>" target="_blank"><?php echo $v['activity_title'];?></a>
            </div>
            <div class="content-miaosha-countdown-bottom-price-region">
              <span class="content-miaosha-countdown-bottom-price-title">结束时间：</span>
              <span class="content-miaosha-countdown-bottom-price-current"><?php echo date("Y-m-d",$v['activity_end_date']);?></span>
              <span class="content-miaosha-countdown-bottom-price-discount"></span>
            </div>
            <!-- 填充文字：秒杀结束，即将开始 -->
            <div class="content-miaosha-countdown-bottom-btn">
              <span class="content-miaosha-countdown-bottom-btn-click">活动结束</span>
              <span class="content-miaosha-countdown-bottom-btn-txt">活动结束</span>
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