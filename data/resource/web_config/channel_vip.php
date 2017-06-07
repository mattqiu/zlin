<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="vip-standard-layout-fullscreen nav-Sidebar-layout" <?php if (!empty($output['code_tit']['code_info']['pic'])){?> style="background:url(<?php echo UPLOAD_SITE_URL.'/'.$output['code_tit']['code_info']['pic'];?>) repeat-x"<?php }?>>
  <div class="vip-standard-layout wrapper style-<?php echo $output['style_name'];?>">
    <?php if (is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) {$i=0; ?>
    <div class="top-side-focus">
      <ul>        
        <?php foreach ($output['code_adv']['code_info'] as $key => $val) {$i++; ?>
        <?php if (is_array($val) && !empty($val)) { ?>
        <li <?php if ($i==3){?>class="right"<?php }?>>
          <a href="<?php echo $val['pic_url'];?>" title="<?php echo $val['pic_name'];?>" target="_blank">
            <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>" alt="<?php echo $val['pic_name'];?>"/>
          </a>
        </li>
        <?php } ?>
        <?php } ?>        
      </ul>
    </div>
    <?php } ?>
    <?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {$i = 0;?>
    <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) {$i++;?>
    <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
    <div class="middle-goods-list">
      <?php foreach($val['goods_list'] as $k => $v){ ?>
      <div class="goods_info">
        <div class="goods_pic">
          <img src="<?php echo strpos($v['goods_advpic'],'http')===0 ? $v['goods_advpic']:UPLOAD_SITE_URL."/".$v['goods_advpic'];?>" />
        </div>
        <div class="goods_detail">
          <dl>
            <dt class="goods_name"><?php echo $v['goods_name']; ?></dt>
            <dd class="goods_jingle"><?php echo $v['goods_jingle']; ?></dd>
            <dd>
              <div class="goods_price">
                <div class="goods_price_left">
                  <div class="goods_price_tag">¥</div>
                  <div class="goods_price_amount"><?php echo $v['goods_price'];?></div>
                  <div class="goods_price_market"><?php echo imPriceFormatForList($v['market_price']); ?></div>
                  <div class="goods_price_unit">/件</div>
                </div>
                <div class="goods_price_right">
                  <div class="goods_pay">
                    <a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>" title="<?php echo $v['goods_name']; ?>">立即抢购</a>                  
                  </div>                    
                </div>
              </div>
            </dd>
          </dl>
        </div>            
      </div>
      <?php } ?> 
    </div>
    <?php } elseif (!empty($val['pic_list']) && is_array($val['pic_list'])) { ?>
    <div class="middle-banner">
      <ul>  
        <?php foreach($val['pic_list'] as $k => $v){ ?>
        <li>
          <a href="<?php echo $v['pic_url'];?>" title="<?php echo $v['pic_name'];?>" target="_blank">
            <img src="<?php echo UPLOAD_SITE_URL.'/'.$v['pic_img'];?>" alt="<?php echo $v['pic_name'];?>"/>
          </a>
        </li>
        <?php } ?>
      </ul>
    </div>
    <?php } ?> 
    <?php } ?>  
    <?php } ?> 
  </div>
</div>