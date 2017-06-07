<?php defined('InIMall') or exit('Access Invalid!');?>

<link href="<?php echo SHOP_SKINS_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_SKINS_URL;?>/css/home_special.css" rel="stylesheet" type="text/css">

<!-- HomeFocusLayout Begin-->
<div class="home-focus-layout">
  <?php echo $output['web_html']['special'];?>
</div>
<!--HomeFocusLayout End-->

<div class="home-special-layout" style="background: #000 url(<?php echo UPLOAD_SITE_URL.'/shop/common/untitled.png';?>) no-repeat center top;">
  <div class="home-special-list">
    <?php if (!empty($output['special_r']) && is_array($output['special_r'])) {$i=0;$j=0;?>
    <h3 class="nav-title">
      <strong>推荐专题</strong>
      <b class="left"></b>
      <b class="right"></b>
    </h3>    
    <div class="jfocus-newspecial">
      <ul>
        <?php foreach ($output['special_r'] as $key => $special) {$i++;?>
        <?php if ($i%4==1){?>
        <li>
        <?php }?>
          <div class="box-item">
            <a href="<?php echo urlShop('special','special_detail',array('special_id'=>$special['special_id']));?>" target="_blank">
              <img src="<?php echo getCMSSpecialImageUrl($special['special_image']);?>" title="<?php echo $special['special_title'];?>" width="280" height="280" alt="" />
              <div class="box-info">
                <h4 class="box-title"><?php echo $special['special_title'];?></h4>
                <p class="box-desc"><?php echo $special['special_desc'];?></p>
              </div>
              <div class="box-info-shadow"></div>
            </a>
          </div>
        <?php if ($i%4==0){?>
        </li>
        <?php $j++;if ($j>=3){break;}}?>
        <?php }?> 
        <?php if ($i%4!=0){?>
        </li>
        <?php }?>     
      </ul>
    </div>
    <?php }?>

    <h3 class="nav-title">
      <strong>专题列表</strong>
      <b class="left"></b>
      <b class="right"></b>
    </h3>
    <p class="nav-tag clearfix" id="J_NavTag" data-spm="20131101"> 
      <a <?php if (empty($_GET['class'])){?>class="selected"<?php }?> target="_self" href="<?php echo urlShop('special','index');?>"><span>全部</span></a> 
      <?php if (!empty($output['special_class']) && is_array($output['special_class'])){?>
      <?php foreach ($output['special_class'] as $key=>$class) {?>
      <a <?php if ($_GET['class']==$class['class_id']){?>class="selected"<?php }?> target="_self" href="<?php echo urlShop('special','index',array('class'=>$class['class_id']));?>"><span><?php echo $class['class_name'];?></span><i></i></a>
      <?php } ?>
      <?php } ?>      
    </p>

    <!--第1列-->
    <?php if (!empty($output['special_list1']) && is_array($output['special_list1'])){?>
    <div class="firstboxs">
      <?php foreach ($output['special_list1'] as $key=>$special) {?>
      <div class="box-item">
        <a href="<?php echo urlShop('special','special_detail',array('special_id'=>$special['special_id']));?>" target="_blank">
        <img  src="<?php echo getCMSSpecialImageUrl($special['special_image']);?>" title="<?php echo $special['special_title'];?>" width="280" height="365" alt="<?php echo $special['special_desc'];?>" />
        <h4 class="box-title"><?php echo $special['special_title'];?></h4>
        <p class="box-desc"><?php echo $special['special_desc'];?></p>
        </a>
      </div>
      <?php }?>
    </div>
    <?php }?>
    <!--第2列-->
    <?php if (!empty($output['special_list2']) && is_array($output['special_list2'])){?>
    <div class="firstboxs">
      <?php foreach ($output['special_list2'] as $key=>$special) {?>
      <div class="box-item">
        <a href="<?php echo urlShop('special','special_detail',array('special_id'=>$special['special_id']));?>" target="_blank">
        <img  src="<?php echo getCMSSpecialImageUrl($special['special_image']);?>" title="<?php echo $special['special_title'];?>" width="280" height="365" alt="<?php echo $special['special_desc'];?>" />
        <h4 class="box-title"><?php echo $special['special_title'];?></h4>
        <p class="box-desc"><?php echo $special['special_desc'];?></p>
        </a>
      </div>
      <?php }?>
    </div>
    <?php }?>
    <!--第3列-->
    <?php if (!empty($output['special_list3']) && is_array($output['special_list3'])){?>
    <div class="firstboxs">
      <?php foreach ($output['special_list3'] as $key=>$special) {?>
      <div class="box-item">
        <a href="<?php echo urlShop('special','special_detail',array('special_id'=>$special['special_id']));?>" target="_blank">
        <img  src="<?php echo getCMSSpecialImageUrl($special['special_image']);?>" title="<?php echo $special['special_title'];?>" width="280" height="365" alt="<?php echo $special['special_desc'];?>" />
        <h4 class="box-title"><?php echo $special['special_title'];?></h4>
        <p class="box-desc"><?php echo $special['special_desc'];?></p>
        </a>
      </div>
      <?php }?>
    </div>
    <?php }?>
    <!--第4列-->
    <?php if (!empty($output['special_list4']) && is_array($output['special_list4'])){?>
    <div class="firstboxs">
      <?php foreach ($output['special_list4'] as $key=>$special) {?>
      <div class="box-item">
        <a href="<?php echo urlShop('special','special_detail',array('special_id'=>$special['special_id']));?>" target="_blank">
        <img  src="<?php echo getCMSSpecialImageUrl($special['special_image']);?>" title="<?php echo $special['special_title'];?>" width="280" height="365" alt="<?php echo $special['special_desc'];?>" />
        <h4 class="box-title"><?php echo $special['special_title'];?></h4>
        <p class="box-desc"><?php echo $special['special_desc'];?></p>  
        </a>        
      </div>
      <?php }?>
    </div>
    <?php }?>
    <div class="clear"></div>
    <div class="tc mt10">
      <div class="pagination tc"><?php echo $output['show_page'];?></div>
    </div>
  </div>
</div>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<script>
$(function(){
	$(".jfocus-newspecial").jfocus();
	
	$('.firstboxs > .box-item').jfade({
		start_opacity: "1",
		high_opacity: "1",
		low_opacity: ".5",
		timing: "500"
	});
	$('.home-special-list > .firstboxs').jfade({
		start_opacity: "1",
		high_opacity: "1",
		low_opacity: ".5",
		timing: "500"
	});
})
</script>