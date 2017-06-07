<?php defined('InIMall') or exit('Access Invalid!');?>

<style type="text/css">
.home-activity-background {
	width: 100%; 
	padding: 0; 
	margin: 0;
	background-color: <?php echo $output['activity']['activity_background_color'];?>; 
	background-image: url(<?php echo UPLOAD_SITE_URL."/".ATTACH_ACTIVITY."/".$output['activity']['activity_background'];?>); 
	background-repeat: <?php echo $output['activity']['activity_repeat'];?>;
	background-position: top center;
	overflow: hidden;
}
.home-activity-layout {
	width: 100%; 
	padding: 0; 
	margin: 0; 	
	background-image: url(<?php echo UPLOAD_SITE_URL."/".ATTACH_ACTIVITY."/".$output['activity']['activity_banner'];?>);
	background-repeat:no-repeat;
	background-position: top center;
	overflow: hidden;
}

.imcc-activity {
	width: 1200px; 
	overflow: hidden; 
	background:#FFF; 
	margin:auto; 
	margin-top: <?php echo $output['activity']['activity_margin_top']?>px;
	margin-bottom:20px;
	position:relative;
}
</style>
<link href="<?php echo SHOP_SKINS_URL;?>/css/home_activity.css" rel="stylesheet" type="text/css">

<script type="text/javascript" >
	$(document).ready(function(){
		$('#sale').children('ul').children('li').bind('mouseenter',function(){
			$('#sale').children('ul').children('li').attr('class','c1');
			$(this).attr('class','c2');
		});
	
		$('#sale').children('ul').children('li').bind('mouseleave',function(){
			$('#sale').children('ul').children('li').attr('class','c1');
		});
})
</script>
<div class="home-activity-background">
  <div class="home-activity-layout">
    <div class="imcc-activity">
      <div class="imcss-activity_desc">
        <span class="title"><?php echo $output['activity']['activity_title'];?></span>
        <p class="description"><?php echo $output['activity']['activity_desc'];?></p>
      </div>
      <?php if (!($output['activity']['activity_start_date']>time() || $output['activity']['activity_end_date']<time())){?>	
      <div class="sale" id="sale">      
        <ul class="list_pic">
          <?php if(is_array($output['list']) and !empty($output['list'])){?>
          <?php foreach ($output['list'] as $v) {?>
          <li class="c1">
            <dl>
              <dt class="goodspic"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$v['goods_id']));?>" target="_blank"><img src="<?php echo thumb($v, 240);?>"/></a></dt>
              <dd class="goodsname"><a href="<?php echo urlShop('goods', 'index', array('goods_id'=>$v['goods_id']));?>" target="_blank" title="<?php echo $v['goods_name'];?>"><?php echo $v['goods_name'];?></a></dd>
              <dd class="price">
                <h4><?php echo imPriceFormatForList($v['goods_price']);?></h4>
              </dd>
            </dl>
          </li>
          <?php }?>
          <?php }?>
        </ul>
        <div class="tc mt10">
          <div class="pagination tc"><?php echo $output['show_page'];?></div>
        </div>
      </div>
      <?php }?>
      <br style="clear:both;" />
    </div>
  </div>
</div>
