<?php defined('InIMall') or exit('Access Invalid!');?>

<style>
  .category {display: block !important;}
</style>
<!-- HomeFocusLayout Begin-->
<div class="home-focus-layout">
  <?php echo $output['web_html']['brand'];?>
  <?php if (!empty($output['brand_r']) && is_array($output['brand_r'])) {$i=0;$j=0;?>
  <div class="jfocus-trigeminy">
    <ul>
      <?php foreach ($output['brand_r'] as $key => $val) {$i++;?>
      <?php if ($i%6==1){?>
      <li>
      <?php }?>
        <a href="<?php echo urlShop('brand', 'list', array('brand'=>$val['brand_id']));?>"><img src="<?php echo brandImage($val['brand_pic']);?>" alt="<?php echo $val['brand_name'];?>"/></a>
      <?php if ($i%6==0){?>
      </li>
      <?php $j++;if ($j>=3){break;}}?>
      <?php }?> 
      <?php if ($i%6!=0){?>
      </li>
      <?php }?>     
    </ul>
  </div>
  <?php }?>
</div>

<div class="home-brand-layout" style="background: #000 url(<?php echo UPLOAD_SITE_URL.'/shop/common/untitled.png';?>) no-repeat center top;">
  <div class="home-special-layout">
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
              <p class="box-logo">
                <img alt="<?php echo $special['brand_name'];?>" src="<?php echo brandImage($special['brand_pic']);?>" width="90" height="45">                                                    
              </p>
              <div class="box-info">
                <h4 class="box-title"><?php echo $special['special_title'];?></h4>
                <p class="box-desc"><?php echo $special['special_desc'];?></p>
              </div>
              <div class="box-info-shadow"></div>
              <b class="ui-brand-btn j_CollectBrand" dataparam="<?php echo $special['special_brand'];?>"><i></i><span>关注</span><b></b></b>
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
      <strong>品牌专题</strong>
      <b class="left"></b>
      <b class="right"></b>
    </h3>
    <p class="nav-tag clearfix" id="J_NavTag" data-spm="20131101"> 
      <a <?php if (empty($_GET['class'])){?>class="selected"<?php }?> target="_self" href="<?php echo urlShop('brand','index');?>"><span>全部</span></a> 
      <?php if (!empty($output['special_class']) && is_array($output['special_class'])){?>
      <?php foreach ($output['special_class'] as $key=>$class) {?>
      <a <?php if ($_GET['class']==$class['class_id']){?>class="selected"<?php }?> target="_self" href="<?php echo urlShop('brand','index',array('class'=>$class['class_id']));?>"><span><?php echo $class['class_name'];?></span><i></i></a>
      <?php } ?>
      <?php } ?>      
    </p>

    <!--第1列-->
    <?php if (!empty($output['special_list1']) && is_array($output['special_list1'])){?>
    <div class="firstboxs">
      <div class="box-item"  data-spm="a2224yp">
        <img src="<?php echo UPLOAD_SITE_URL.'/shop/common/brand_first.png';?>" alt="的是的" width="280" height="210" />
      </div>
      <?php foreach ($output['special_list1'] as $key=>$special) {?>
      <div class="box-item">
        <a href="<?php echo urlShop('special','special_detail',array('special_id'=>$special['special_id']));?>" target="_blank">
        <img  src="<?php echo getCMSSpecialImageUrl($special['special_image']);?>" title="<?php echo $special['special_title'];?>" width="280" height="365" alt="<?php echo $special['special_desc'];?>" />
        <p class="box-logo">
          <img alt="<?php echo $special['brand_name'];?>" src="<?php echo brandImage($special['brand_pic']);?>" width="90" height="45">
        </p>
        <h4 class="box-title"><?php echo $special['special_title'];?></h4>
        <p class="box-desc"><?php echo $special['special_desc'];?></p>
        <b class="ui-brand-btn j_CollectBrand" dataparam="<?php echo $special['special_brand'];?>"><i></i><span>关注</span><b></b></b>
        <p class="box-num"><em class="j_SubjectCountNum"><?php echo $special['brand_follows'];?></em>人捧场</p>
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
        <p class="box-logo">
          <img alt="<?php echo $special['brand_name'];?>" src="<?php echo brandImage($special['brand_pic']);?>" width="90" height="45">
        </p>
        <h4 class="box-title"><?php echo $special['special_title'];?></h4>
        <p class="box-desc"><?php echo $special['special_desc'];?></p>
        <b class="ui-brand-btn j_CollectBrand" dataparam="<?php echo $special['special_brand'];?>"><i></i><span>关注</span><b></b></b>
        <p class="box-num"><em class="j_SubjectCountNum"><?php echo $special['brand_follows'];?></em>人捧场</p>
        </a>
      </div>
      <?php }?>
    </div>
    <?php }?>
    <!--第3列-->
    <?php if (!empty($output['special_list3']) && is_array($output['special_list3'])){?>
    <div class="firstboxs">
      <div class="box-item"  data-spm="a2224yq">
        <img src="<?php echo UPLOAD_SITE_URL.'/shop/common/brand_three.png';?>" alt="" width="280" height="210" />
      </div>
      <?php foreach ($output['special_list3'] as $key=>$special) {?>
      <div class="box-item">
        <a href="<?php echo urlShop('special','special_detail',array('special_id'=>$special['special_id']));?>" target="_blank">
        <img  src="<?php echo getCMSSpecialImageUrl($special['special_image']);?>" title="<?php echo $special['special_title'];?>" width="280" height="365" alt="<?php echo $special['special_desc'];?>" />
        <p class="box-logo">
          <img alt="<?php echo $special['brand_name'];?>" src="<?php echo brandImage($special['brand_pic']);?>" width="90" height="45">
        </p>
        <h4 class="box-title"><?php echo $special['special_title'];?></h4>
        <p class="box-desc"><?php echo $special['special_desc'];?></p>
        <b class="ui-brand-btn j_CollectBrand" dataparam="<?php echo $special['special_brand'];?>"><i></i><span>关注</span><b></b></b>
        <p class="box-num"><em class="j_SubjectCountNum"><?php echo $special['brand_follows'];?></em>人捧场</p>
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
        <p class="box-logo">
          <img alt="<?php echo $special['brand_name'];?>" src="<?php echo brandImage($special['brand_pic']);?>" width="90" height="45">
        </p>
        <h4 class="box-title"><?php echo $special['special_title'];?></h4>
        <p class="box-desc"><?php echo $special['special_desc'];?></p>  
        <b class="ui-brand-btn j_CollectBrand" dataparam="<?php echo $special['special_brand'];?>"><i></i><span>关注</span><b></b></b>      
        <p class="box-num"><em class="j_SubjectCountNum"><?php echo $special['brand_follows'];?></em>人捧场</p>
        </a>        
      </div>
      <?php }?>
    </div>
    <?php }?>
    <div class="clear"></div>
    <div class="tr mt10">
      <div class="pagination tc"><?php echo $output['showpage'];?></div>
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
	$('.home-special-layout > .firstboxs').jfade({
		start_opacity: "1",
		high_opacity: "1",
		low_opacity: ".5",
		timing: "500"
	});
	$('.box-item').hover(
		function(){
			$(this).find('.ui-brand-btn').css('display','block');
			$(this).find('.box-logo').css('opacity','0');
		},
		function(){
			$(this).find('.ui-brand-btn').css('display','none');
			$(this).find('.box-logo').css('opacity','100');
		}
	);
	$('.j_CollectBrand').click(function(){
		var key=$(this).attr('dataparam');		
		var ok=follow_brand(key,'count',this);

		return false;
	});
	
	
})
</script>