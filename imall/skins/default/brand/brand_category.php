<?php defined('InIMall') or exit('Access Invalid!');?>

<!--HomeFocusLayout End-->
<div class="imcc-container wrapper">
  <?php if(!empty($output['brand_r']) && is_array($output['brand_r'])){?>
  <div class="imcc-recommend-borand">
    <div class="title" title="<?php echo $lang['brand_index_recommend_brand'];?>"></div>
    <div class="jfocus-barnd-list">
      <ul>
        <?php array_splice($output['brand_r'], 36);?>
        <?php foreach($output['brand_r'] as $key=>$brand_r){?>
        <li>
          <dl>
            <dt><a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand_r['brand_id']));?>"><img src="<?php echo brandImage($brand_r['brand_pic']);?>" alt="<?php echo $brand_r['brand_name'];?>" /></a></dt>
            <dd><a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand_r['brand_id']));?>"><?php echo $brand_r['brand_name'];?></a></dd>
          </dl>
        </li>
        <?php }?>
      </ul>
    </div>
  </div>
  <?php }?>    
  <!--分类品牌-->
  <?php if (!empty($output['brand_l']) && is_array($output['brand_l'])) {?>  
  <div class="imcc-brand-class">
    <div class="imcc-brand-class-tab">      
      <ul class="tabs-nav">
        <li class="<?php if (empty($_GET['cate_id'])){echo 'tabs-selected';}?>"><a href="<?php echo urlShop('brand','category');?>">全　　部</a></li>
        <?php foreach ($output['brand_l'] as $key => $val) {?>
        <li class="<?php if ($_GET['cate_id'] == $val['gc_id'] || !empty($val['child'][$_GET['cate_id']])){echo 'tabs-selected';} ?>"><a href="<?php echo urlShop('brand','category',array('cate_id'=>$val['gc_id']));?>"><?php echo $val['gc_name'];?></a></li>
        <?php }?>
      </ul>
    </div>   
    <?php if ($output['parent_id']>0 && !empty($output['brand_l'][$output['parent_id']])){?>  
      <?php if (!empty($output['brand_l'][$output['parent_id']]['child']) && is_array($output['brand_l'][$output['parent_id']]['child'])){?>
      <p class="nav-tag clearfix"> 
      <?php foreach ($output['brand_l'][$output['parent_id']]['child'] as $key=>$val) {?>
      <a <?php if ($_GET['cate_id']==$val['gc_id']){?>class="selected"<?php }?> target="_self" href="<?php echo urlShop('brand','category',array('cate_id'=>$val['gc_id']));?>"><span><?php echo $val['gc_name'];?></span><i></i></a>
      <?php } ?>           
      </p>
      <?php }?>
    <?php } ?>
    <?php $curr_class_r=($output['parent_id']==$_GET['cate_id'])?$output['brand_l'][$_GET['cate_id']]['recommend']:$output['brand_l'][$output['parent_id']]['child'][$_GET['cate_id']]['recommend'];?>
    <?php if(!empty($curr_class_r)) {?>    
    <div class="imcc-barnd-list tabs-panel">
      <ul>
        <?php foreach($curr_class_r as $key=>$brand){?>
        <li>
          <dl>
            <dt><a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand['brand_id']));?>"><img src="<?php echo brandImage($brand['brand_pic']);?>" alt="<?php echo $brand['brand_name'];?>"/></a></dt>
            <dd><a href="<?php echo urlShop('brand', 'list', array('brand'=>$brand['brand_id']));?>"><?php echo $brand['brand_name'];?></a></dd>
          </dl>
        </li>
        <?php }?>
      </ul>
    </div>    
    <?php }?>
  </div>
  <?php }?>	
  <?php if(!empty($output['brand_lists'])) {?>  
  <div class="">    
    <h2 class="brandTitle"><?php echo $output['brand_name'];?></h2>	
    <?php foreach($output['brand_lists'] as $key=>$brand){?>  				
	<div class="brandItem">
      <div class="brandItem-info">
        <div class="bIi-brand  j_BrandItemInfo ">
          <p class="bIi-brand-logo">
            <a target="_blank" href="<?php echo urlShop('brand', 'list', array('brand'=>$brand['brand_id']));?>">
			  <img   src="<?php echo brandImage($brand['brand_pic']);?>" height="50" width="150" alt="<?php echo $brand['brand_name'];?>" />
		    </a>
		    <strong><?php echo $brand['brand_name'];?></strong>
		  </p>
          <dl class="bIi-style clearfix">
            <dt></dt>
            <dd></dd>
          </dl>
          <p></p>
		  <p class="bIi-intro j_BrandItemIntro"><?php echo $brand['brand_name'];?></p>
        </div>
        <b class="ui-brand-btn j_CollectBrand" dataparam="<?php echo $brand['brand_id'];?>"><i></i><span>关注</span><b></b></b>
      </div>
      <div class="brandItem-con j_BrandItemSlide">
        <div class="bIc-title">
          <p class="bIc-title-notice">
		    <a target="_blank" class="bIc-title-notice-shop" href=""></a>
		  </p>                	    		
          <a target="_blank" class="bIc-title-more ui-more-nbg" href="<?php echo urlShop('brand', 'list', array('brand'=>$brand['brand_id']));?>">更多商品...<i class="ui-more-nbg-arrow"></i></a>
        </div>
        <a href="javascript:;" target="_self" class="bIc-slide-prev ks-switchable-disable-btn">&lt;</a>
        <?php if(!empty($brand['goods'])) {?> 
        <div class="bIc-slide">
          <ul class="bIc-slideList">
            <?php foreach($brand['goods'] as $k=>$goods){?>
            <li class="j_BrandItemList">
              <p class="bIc-slideList-img">
                <a target="_blank" href="<?php echo urlShop('goods', 'index', array('goods_id'=>$goods['goods_id']));?>">
                <img  src="<?php echo thumb($goods, 240);?>"  title="<?php echo $goods['goods_name'];?>" alt="<?php echo $goods['goods_name'];?>" />
                </a>
              </p>
              <p class="bIc-slideList-sell">
                <span class="bIc-slideList-sell-price ui-price"><span class="ui-price-icon"></span><?php echo imPriceFormatForList($goods['goods_price']);?></span>
              	<span class="bIc-slideList-sell-num">月销量：<em><?php echo $goods['goods_salenum'];?></em></span>
			  </p>
              <p class="bIc-slideList-title">
                <a target="_blank" href="<?php echo urlShop('goods', 'index', array('goods_id'=>$goods['goods_id']));?>"><?php echo $goods['goods_name'];?></a>
              </p>
            </li>
            <?php }?> 
          </ul>
        </div>
        <?php }?> 
        <a href="javascript:;" target="_self" class="bIc-slide-next">&gt;</a>
      </div>
    </div>   
    <?php }?>  
  </div>
  <div class="tc mt10 mb10">
    <div class="pagination"><?php echo $output['showpage'];?></div>
  </div>
  <?php }?>
</div>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<script>
$(function(){
	$(".jfocus-barnd-list").jfocusV({time:8000,num:12});
	
	$('.j_CollectBrand').click(function(){
		var key=$(this).attr('dataparam');		
		var ok=follow_brand(key,'count',this);

		return false;
	});
})
</script>