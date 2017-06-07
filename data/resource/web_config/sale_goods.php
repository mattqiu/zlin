<?php defined('InIMall') or exit('Access Invalid!');?>

<?php if (!empty($output['code_sale_list']['code_info']) && is_array($output['code_sale_list']['code_info'])) {?>
<div class="mt_hotcontent">
  <div class="mt_hotTab">
    <ul>
      <?php if (!empty($output['code_sale_list']['code_info']) && is_array($output['code_sale_list']['code_info'])) {$i = 0;?>
      <?php foreach ($output['code_sale_list']['code_info'] as $key => $val) {$i++;?>
      <li class="<?php echo $i==1 ? 'on':'';?>"><?php echo $val['recommend']['name'];?></li>
      <?php } ?>
      <?php } ?>
    </ul>
  </div>
  <?php if (!empty($output['code_sale_list']['code_info']) && is_array($output['code_sale_list']['code_info'])) {$i = 0;?>
  <?php foreach ($output['code_sale_list']['code_info'] as $key => $val) {$i++;?>
  <div class="mt_hotList" style="<?php echo $i==1?'display:block;':'';?>">
    <div class="mt_hotSlider">
      <ul class="slides">
        <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
        <li class="hot_list">          
          <ul>
            <?php foreach($val['goods_list'] as $k => $v){ ?>
            <li>
              <div class="hot_info">
                <a title="<?php echo $v['goods_name']; ?>" target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=>$v['goods_id'])); ?>">
                  <img src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>" />
                  <p class="goods_name"><?php echo $v['goods_name']; ?></p>
                  <p class="goods_jingle"><?php echo $v['goods_jingle']; ?></p>
                  <hr/>
                  <p class="goods_price">
                    <span class="left"><?php echo imPriceFormatForList($v['goods_price']); ?><del><?php echo imPriceFormatForList($v['market_price']); ?></del></span>
                    <span class="right">已售<?php echo $v['goods_salenum']; ?></span>
                  </p>
                </a>
              </div>
            </li>
            <?php } ?>
          </ul>          
        </li>
        <?php } ?>        
      </ul>
    </div>
  </div>
  <?php } ?>
  <?php } ?>
</div>
<?php } ?>