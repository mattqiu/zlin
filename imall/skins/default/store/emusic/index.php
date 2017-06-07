<?php defined('InIMall') or exit('Access Invalid!');?>

<?php if($output['page'] == 'index'){?>
<style type="text/css">
  .classNav .left_nav { display: block !important;}
</style>
<?php }?>

<div class="contentBody">
  <?php if(!$output['store_decoration_only']) {?>
  <div class="mainBody"> 
    <!--商品分类-->
    <div class="right_banner"> 
      <div id="slideBox" class="slideBox">
        <div class="hd">
          <ul>
          </ul>
        </div>
        <div class="bd">
          <ul>
            <?php if(!empty($output['store_slide']) && is_array($output['store_slide'])){?>
            <?php for($i=0;$i<5;$i++){?>
            <?php if($output['store_slide'][$i] != ''){?>
            <li><A href="<?php echo $output['store_slide_url'][$i];?>" target=_blank><IMG height=265 alt="" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS.$output['store_slide'][$i];?>" width=725></A> </li>
            <?php }?>
            <?php }?>
            <?php }else{?>
            <li><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS;?>f01.jpg"></li>
            <li><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS;?>f02.jpg"></li>
            <li><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_SLIDE.DS;?>f03.jpg"></li>
            <?php }?>     
          </ul>
        </div>
        <!-- 下面是前/后按钮代码-->  
        <div class="banner_btn_left" > <a class="prev" href="javascript:void(0)"></a> </div>
        <div class="banner_btn_right"> <a class="next" href="javascript:void(0)"></a> </div>
      </div>
      <script type="text/javascript">
		$(".slideBox").slide({mainCell:".bd ul",titCell:".hd ul",autoPage:"true",effect:"left",trigger:"click",autoPlay:"true"});
      </script> 
      <div class="banner_ico" style="background:url(<?php echo $output['store_exp']['pic']?>) 0 0 no-repeat;">
        <a href="<?php echo $output['store_exp']['url1']?>" target="_blank"></a>
        <a href="<?php echo $output['store_exp']['url2']?>" target="_blank"></a>
        <a href="<?php echo $output['store_exp']['url3']?>" target="_blank"></a>
        <a href="<?php echo $output['store_exp']['url4']?>" target="_blank"></a>
      </div>
    </div>
    <div class="right_banner">
      <?php if(!empty($output['store_adv']) && is_array($output['store_adv'])){$i=0;?>
      <?php foreach($output['store_adv'] as $key=>$adv){$i++;?>
      <div class="banner_pic">
        <div class="big_logo" id="banner_0<?php echo $i;?>"> 
          <a href="<?php echo $adv['url'];?>" target="_blank"><img src="<?php echo $adv['pic'];?>" border="0"></a>
          <div class="big_txt"> <a href="<?php echo $adv['url'];?>" target="_blank"><img src="<?php echo $adv['text'];?>" border="0"></a> </div>
        </div>
      </div>
      <?php }?>
      <?php }?>
    </div>
  </div>
  <div class="blank"></div>
  <?php } ?>  
  <?php 
    //加载店铺装修静态页
    if(isset($output['decoration_file'])) { 
      require($output['decoration_file']);
    } 
  ?>
  <?php if(!$output['store_decoration_only']) {?>
  <div class="mainBody"> 
    <?php require_once template('/store/'.$output['store_theme'].'/group_buy');?>
    <?php require_once template('/store/'.$output['store_theme'].'/recommend_hot');?>        
  </div>          
  <!-- 热销排行榜" -->
  <?php require_once template('/store/'.$output['store_theme'].'/goods_top10');?>      
  <!-- 常用操作" -->
  <?php require_once template('/store/'.$output['store_theme'].'/common_op');?> 
  <!-- 浏览记录 -->
  <?php require_once template('/store/'.$output['store_theme'].'/goods_history');?>
  <?php } ?>          
</div>
  
