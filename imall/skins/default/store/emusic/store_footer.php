<?php defined('InIMall') or exit('Access Invalid!');?>

<?php if(is_array($output['article_list']) && !empty($output['article_list'])){$i=0;?>
<div class="footTop "> 
  <?php foreach ($output['article_list'] as $k=> $article_class){$i++;?>
  <ul>
    <li  class="foot_help foot_help_<?php echo $i;?> "><a href='' title="<?php echo $article_class['class']['ac_name'];?>"><?php if(is_array($article_class['class'])) echo $article_class['class']['ac_name'];?></a></li>
    <?php if(is_array($article_class['list']) && !empty($article_class['list'])){ ?>
    <?php foreach ($article_class['list'] as $article){ ?>
    <li>
      <a href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo urlShop('article', 'show',array('article_id'=> $article['article_id']));?>" title="<?php echo $article['article_title']; ?>"><?php echo $article['article_title']; ?></a>
    </li>
    <?php }?>
    <?php }?>
  </ul>
  <?php }?>
</div>
<?php }?>
<div class="footBottom_line">
  <?php if($output['store_info']['store_shiti']){?>
  <div class="footLine_list"> <img src="<?php echo SHOP_SKINS_URL;?>/images/shop/foot_01.gif" border="0"> </div>
  <?php }?>
  <?php if($output['store_info']['store_qtian']){?>
  <div class="footLine_list"> <img src="<?php echo SHOP_SKINS_URL;?>/images/shop/foot_02.gif" border="0"> </div>
  <?php }?>
  <?php if($output['store_info']['store_zhping']){?>
  <div class="footLine_list"> <img src="<?php echo SHOP_SKINS_URL;?>/images/shop/foot_03.gif" border="0"> </div>
  <?php }?>
  <?php if($output['store_info']['store_erxiaoshi']){?>
  <div class="footLine_list"> <img src="<?php echo SHOP_SKINS_URL;?>/images/shop/foot_04.gif" border="0"> </div>
  <?php }?>
  <?php if($output['store_info']['store_tuihuo']){?>
  <div class="footLine_list"> <img src="<?php echo SHOP_SKINS_URL;?>/images/shop/foot_05.gif" border="0"> </div>
  <?php }?>
  <?php if($output['store_info']['store_shiyong']){?>
  <div class="footLine_list"> <img src="<?php echo SHOP_SKINS_URL;?>/images/shop/foot_06.gif" border="0"> </div>
  <?php }?>
  <?php if($output['store_info']['store_xiaoxie']){?>
  <div class="footLine_list"> <img src="<?php echo SHOP_SKINS_URL;?>/images/shop/foot_07.gif" border="0"> </div>
  <?php }?>
  <?php if($output['store_info']['store_huodaofk']){?>
  <div class="footLine_list"> <img src="<?php echo SHOP_SKINS_URL;?>/images/shop/foot_08.gif" border="0"> </div>
  <?php }?>
</div>
<div class="footer_txt">
  <p>
    <?php echo html_entity_decode($output['store_info']['store_copyright']);?>
  </p>
</div>
<div class="footBottom_list">
  <ul>
    <li>
      <div class="two"><a href="#" rel="nofollow" target="_blank"></a></div>
    </li>
    <li>
      <div class="three"><a href="#" target="_blank"></a></div>
    </li>
    <li>
      <div class="four"><a href="#"></a></div>
    </li>
    <li>
      <div class="five"><a href="#" rel="nofollow" target="_blank"></a></div>
    </li>
    <li>
      <div class="six"><a href="#" rel="nofollow" target="_blank"></a></div>
    </li>
  </ul>
</div>

<?php echo getChat($layout);?>

<link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<!-- 对比 -->
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/compare.js"></script>