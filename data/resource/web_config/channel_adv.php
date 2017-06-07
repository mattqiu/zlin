<?php defined('InIMall') or exit('Access Invalid!');?>

<?php if ($output['web_style']==0){?>
<div class="adv-standard-layout nav-Sidebar-layout wrapper style-<?php echo $output['style_name'];?>">
  <div class="left-sidebar">
    <div class="title">
      <?php if ($output['code_tit']['code_info']['type'] == 'txt') { ?>
      <div class="txt-type">
        <?php if(!empty($output['code_tit']['code_info']['floor'])) { ?><span><?php echo $output['code_tit']['code_info']['floor'];?></span><?php } ?>
        <h2 title="<?php echo $output['code_tit']['code_info']['title'];?>"><?php echo $output['code_tit']['code_info']['title'];?></h2>
      </div>
      <?php }else { ?>
      <div class="pic-type"><img src="<?php echo UPLOAD_SITE_URL.'/'.$output['code_tit']['code_info']['pic'];?>"/></div>
      <?php } ?>
    </div>
    <div class="right-side-focus">
      <ul>
        <?php if (is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) { ?>
        <?php foreach ($output['code_adv']['code_info'] as $key => $val) { ?>
        <?php if (is_array($val) && !empty($val)) { ?>
        <li>
          <a href="<?php echo $val['pic_url'];?>" title="<?php echo $val['pic_name'];?>" target="_blank">
          <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_img'];?>" alt="<?php echo $val['pic_name'];?>"/></a>
        </li>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="middle-layout">
    <ul class="tabs-nav">
      <?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {$i = 0;?>
      <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) {$i++;?>
      <li class="<?php echo $i==1 ? 'tabs-selected':'';?>"><i class="arrow"></i><h3><?php echo $val['recommend']['name'];?></h3></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {$i = 0;?>
    <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) {$i++;?>
    <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
    <div class="tabs-panel middle-goods-list <?php echo $i==1 ? '':'tabs-hide';?>">
      <ul>
      <?php foreach($val['goods_list'] as $k => $v){ ?>
        <li>
          <dl>
            <dt class="goods-name">
              <a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>" title="<?php echo $v['goods_name']; ?>"><?php echo $v['goods_name']; ?></a>
            </dt>
            <dd class="goods-thumb">
              <a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>">
              <img src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>" alt="<?php echo $v['goods_name']; ?>" />
              </a>
            </dd>
            <dd class="goods-price">
              <em><?php echo imPriceFormatForList($v['goods_price']); ?></em>              
              <span class="original"><?php echo imPriceFormatForList($v['market_price']); ?></span>
            </dd>            
          </dl>
        </li>
      <?php } ?>
      </ul>
    </div>
    <?php } elseif (!empty($val['pic_list']) && is_array($val['pic_list'])) { ?>
    <div class="tabs-panel middle-banner-style01 fade-img <?php echo $i==1 ? '':'tabs-hide';?>">
      <a href="<?php echo $val['pic_list']['11']['pic_url'];?>" title="<?php echo $val['pic_list']['11']['pic_name'];?>" class="a1" target="_blank">
      <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['11']['pic_img'];?>" alt="<?php echo $val['pic_list']['11']['pic_name'];?>">
      </a>
      <a href="<?php echo $val['pic_list']['12']['pic_url'];?>" title="<?php echo $val['pic_list']['12']['pic_name'];?>" class="a2" target="_blank">
      <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['12']['pic_img'];?>" alt="<?php echo $val['pic_list']['12']['pic_name'];?>">
      </a>
      <a href="<?php echo $val['pic_list']['14']['pic_url'];?>" title="<?php echo $val['pic_list']['14']['pic_name'];?>" class="b1" target="_blank">
      <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['14']['pic_img'];?>" alt="<?php echo $val['pic_list']['14']['pic_name'];?>">
      </a>
      <a href="<?php echo $val['pic_list']['21']['pic_url'];?>" title="<?php echo $val['pic_list']['21']['pic_name'];?>" class="c1" target="_blank">
      <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['21']['pic_img'];?>" alt="<?php echo $val['pic_list']['21']['pic_name'];?>">
      </a>
      <a href="<?php echo $val['pic_list']['24']['pic_url'];?>" title="<?php echo $val['pic_list']['24']['pic_name'];?>" class="c2" target="_blank">
      <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['24']['pic_img'];?>" alt="<?php echo $val['pic_list']['24']['pic_name'];?>">
      </a>
      <a href="<?php echo $val['pic_list']['31']['pic_url'];?>" title="<?php echo $val['pic_list']['31']['pic_name'];?>" class="d1" target="_blank">
      <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['31']['pic_img'];?>" alt="<?php echo $val['pic_list']['31']['pic_name'];?>">
      </a>
      <a href="<?php echo $val['pic_list']['32']['pic_url'];?>" title="<?php echo $val['pic_list']['32']['pic_name'];?>" class="d2" target="_blank">
      <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['32']['pic_img'];?>" alt="<?php echo $val['pic_list']['32']['pic_name'];?>">
      </a>
      <a href="<?php echo $val['pic_list']['33']['pic_url'];?>" title="<?php echo $val['pic_list']['33']['pic_name'];?>" class="d3" target="_blank">
      <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['33']['pic_img'];?>" alt="<?php echo $val['pic_list']['33']['pic_name'];?>">
      </a>
      <a href="<?php echo $val['pic_list']['34']['pic_url'];?>" title="<?php echo $val['pic_list']['34']['pic_name'];?>" class="d4" target="_blank">
      <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['34']['pic_img'];?>" alt="<?php echo $val['pic_list']['34']['pic_name'];?>">
      </a>
    </div>
    <?php } ?>
    <?php } ?>
    <?php } ?>
  </div>
</div>
<?php }else{?>
<div class="adv-standard-layout nav-Sidebar-layout wrapper style-<?php echo $output['style_name'];?>">
  <!--导航栏-->
  <div class="top-navbar">
    <div class="left-sidebar">
      <div class="title">
        <?php if ($output['code_tit']['code_info']['type'] == 'txt') { ?>
        <div class="txt-type">
          <?php if(!empty($output['code_tit']['code_info']['floor'])) { ?><span><?php echo $output['code_tit']['code_info']['floor'];?></span><?php } ?>
          <h2 title="<?php echo $output['code_tit']['code_info']['title'];?>"><?php echo $output['code_tit']['code_info']['title'];?></h2>
        </div>
        <?php }else { ?>
        <div class="pic-type"><img src="<?php echo UPLOAD_SITE_URL.'/'.$output['code_tit']['code_info']['pic'];?>"/></div>
        <?php } ?>
      </div>
    </div>
    <div class="middle-layout">
      <ul class="tabs-nav">
        <?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {
            $i = 0;
        ?>
        <?php foreach ($output['code_recommend_list']['code_info'] as $key => $val) {
            $i++;
        ?>
        <li class="<?php echo $i==1 ? 'tabs-selected':'';?>"><i class="arrow"></i><h3><?php echo $val['recommend']['name'];?></h3></li>
        <?php } ?>
        <?php } ?>
      </ul>
    </div>
  </div>
  <!--内容栏-->
  <?php if (!empty($output['code_recommend_list']['code_info']) && is_array($output['code_recommend_list']['code_info'])) {
        $i = 0;
        foreach ($output['code_recommend_list']['code_info'] as $key => $val) {
          $i++;
  ?>
  <?php if ($i==1){?>
  <div class="panel_box home-base-layout">
    <div class="left-sidebar">
      <div class="right-side-focus">
        <ul>
          <?php if (is_array($output['code_adv']['code_info']) && !empty($output['code_adv']['code_info'])) { ?>
          <?php foreach ($output['code_adv']['code_info'] as $key => $adv) { ?>
          <?php if (is_array($adv) && !empty($adv)) { ?>
          <li>
            <a href="<?php echo $adv['pic_url'];?>" title="<?php echo $adv['pic_name'];?>" target="_blank">
            <img src="<?php echo UPLOAD_SITE_URL.'/'.$adv['pic_img'];?>" alt="<?php echo $adv['pic_name'];?>"/></a>
          </li>
          <?php } ?>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div class="middle-layout">
      <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) { ?>
      <div class="tabs-panel middle-goods-list">
        <ul>
          <?php foreach($val['goods_list'] as $k => $v){ ?>
          <li>
            <dl>
              <dt class="goods-name">
                <a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>" title="<?php echo $v['goods_name']; ?>"><?php echo $v['goods_name']; ?></a>
              </dt>
              <dd class="goods-thumb">
                <a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>">
                <img src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>" alt="<?php echo $v['goods_name']; ?>" />
                </a>
              </dd>
              <dd class="goods-price">
                <em><?php echo imPriceFormatForList($v['goods_price']); ?></em>
                <span class="original"><?php echo imPriceFormatForList($v['market_price']); ?></span>
              </dd>              
            </dl>
          </li>
        <?php } ?>
        </ul>
      </div>
      <?php } elseif (!empty($val['pic_list']) && is_array($val['pic_list'])) { ?>
      <div class="tabs-panel middle-banner-style01 fade-img">
        <a href="<?php echo $val['pic_list']['11']['pic_url'];?>" title="<?php echo $val['pic_list']['11']['pic_name'];?>" class="a1" target="_blank">
        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['11']['pic_img'];?>" alt="<?php echo $val['pic_list']['11']['pic_name'];?>">
        </a>
        <a href="<?php echo $val['pic_list']['12']['pic_url'];?>" title="<?php echo $val['pic_list']['12']['pic_name'];?>" class="a2" target="_blank">
        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['12']['pic_img'];?>" alt="<?php echo $val['pic_list']['12']['pic_name'];?>">
        </a>
        <a href="<?php echo $val['pic_list']['14']['pic_url'];?>" title="<?php echo $val['pic_list']['14']['pic_name'];?>" class="b1" target="_blank">
        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['14']['pic_img'];?>" alt="<?php echo $val['pic_list']['14']['pic_name'];?>">
        </a>
        <a href="<?php echo $val['pic_list']['21']['pic_url'];?>" title="<?php echo $val['pic_list']['21']['pic_name'];?>" class="c1" target="_blank">
        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['21']['pic_img'];?>" alt="<?php echo $val['pic_list']['21']['pic_name'];?>">
        </a>
        <a href="<?php echo $val['pic_list']['24']['pic_url'];?>" title="<?php echo $val['pic_list']['24']['pic_name'];?>" class="c2" target="_blank">
        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['24']['pic_img'];?>" alt="<?php echo $val['pic_list']['24']['pic_name'];?>">
        </a>
        <a href="<?php echo $val['pic_list']['31']['pic_url'];?>" title="<?php echo $val['pic_list']['31']['pic_name'];?>" class="d1" target="_blank">
        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['31']['pic_img'];?>" alt="<?php echo $val['pic_list']['31']['pic_name'];?>">
        </a>
        <a href="<?php echo $val['pic_list']['32']['pic_url'];?>" title="<?php echo $val['pic_list']['32']['pic_name'];?>" class="d2" target="_blank">
        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['32']['pic_img'];?>" alt="<?php echo $val['pic_list']['32']['pic_name'];?>">
        </a>
        <a href="<?php echo $val['pic_list']['33']['pic_url'];?>" title="<?php echo $val['pic_list']['33']['pic_name'];?>" class="d3" target="_blank">
        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['33']['pic_img'];?>" alt="<?php echo $val['pic_list']['33']['pic_name'];?>">
        </a>
        <a href="<?php echo $val['pic_list']['34']['pic_url'];?>" title="<?php echo $val['pic_list']['34']['pic_name'];?>" class="d4" target="_blank">
        <img src="<?php echo UPLOAD_SITE_URL.'/'.$val['pic_list']['34']['pic_img'];?>" alt="<?php echo $val['pic_list']['34']['pic_name'];?>">
        </a>
      </div>
      <?php } ?>
    </div>
  </div>
  <?php }else{?>
  <div class="panel_box home-expand-layout tabs-hide" style="background:<?php echo empty($val['recommend']['bgcolor'])?'#FFF':$val['recommend']['bgcolor'];?> url(<?php echo empty($val['recommend']['bgimg'])?'':getAttachEditorImageUrl($val['recommend']['bgimg']);?>) no-repeat;" >
    <?php if(!empty($val['goods_list']) && is_array($val['goods_list'])) {$m=1;?>
    <div class="goods-list">
      <ul>
        <?php foreach($val['goods_list'] as $k => $v){?>
        <li class="<?php if (($m % 2)==1){echo 'fleft';}else{echo 'fright';}?>">
          <dl>
            <dt class="goods-name">
              <a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>" title="<?php echo $v['goods_name']; ?>"><?php echo $v['goods_name']; ?></a>
            </dt>
            <dd class="goods-thumb">
              <a target="_blank" href="<?php echo urlShop('goods','index',array('goods_id'=> $v['goods_id'])); ?>">
              <img src="<?php echo strpos($v['goods_pic'],'http')===0 ? $v['goods_pic']:UPLOAD_SITE_URL."/".$v['goods_pic'];?>" alt="<?php echo $v['goods_name']; ?>" />
              </a>
            </dd>
            <dd class="goods-price"><em><?php echo imPriceFormatForList($v['goods_price']); ?></em>
              <span class="original"><?php echo imPriceFormatForList($v['market_price']); ?></span>
            </dd>
          </dl>
        </li>
      <?php $m++;} ?>
      </ul>
    </div>
    <?php } elseif (!empty($val['pic_list']) && is_array($val['pic_list'])) { ?>
    <div class="banner-list fade-img">
      <ul>
        <?php foreach($val['pic_list'] as $k => $v){?>
        <li class="<?php if (($m % 2)==1){echo 'fleft';}else{echo 'fright';}?>">
          <a href="<?php echo $v['pic_url'];?>" title="<?php echo $v['pic_name'];?>" target="_blank">
            <img src="<?php echo UPLOAD_SITE_URL.'/'.$v['pic_img'];?>" alt="<?php echo $v['pic_name'];?>">
          </a>          
        </li>
      <?php $m++;} ?>
      </ul>      
    </div>
    <?php } ?>
  </div>
  <?php }?>
  <?php }?>
  <?php }?>
</div>
<?php }?>