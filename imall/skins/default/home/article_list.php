<?php defined('InIMall') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_SKINS_URL;?>/css/layout.css" rel="stylesheet" type="text/css">
<div class="imcc-container wrapper">
  <div class="left">
    <div class="imcc-module imcc-module-style01">
      <div class="title">
        <h3><?php echo $lang['article_article_article_class'];?></h3>
      </div>
      <div class="content">
        <ul class="imcc-sidebar-article-class">
          <?php foreach ($output['sub_class_list'] as $k=>$v){?>
          <li><a href="<?php echo urlShop('article', 'article', array('ac_id'=>$v['ac_id']));?>"><?php echo $v['ac_name']?></a></li>
          <?php }?>
        </ul>
      </div>
    </div>
    <div class="imcc-module imcc-module-style03">
      <div class="title">
        <h3><?php echo $lang['article_article_new_article'];?></h3>
      </div>
      <div class="content">
        <ul class="imcc-sidebar-article-list">
          <?php if(is_array($output['new_article_list']) and !empty($output['new_article_list'])){?>
          <?php foreach ($output['new_article_list'] as $k=>$v){?>
          <li><i></i><a <?php if($v['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($v['article_url']!='')echo $v['article_url'];else echo urlShop('article', 'show', array('article_id'=>$v['article_id']));?>"><?php echo $v['article_title']?></a></li>
          <?php }?>
          <?php }else{?>
          <li><?php echo $lang['article_article_no_new_article'];?></li>
          <?php }?>
        </ul>
      </div>
    </div>
  </div>
  <div class="right">
    <div class="imcc-article-con">
      <div class="title-bar">
        <h3><?php echo $output['class_name'];?></h3>
      </div>
      <?php if(!empty($output['article']) and is_array($output['article'])){?>
      <ul class="imcc-article-list">
        <?php foreach ($output['article'] as $article) {?>
        <li><i></i><a <?php if($article['article_url']!=''){?>target="_blank"<?php }?> href="<?php if($article['article_url']!='')echo $article['article_url'];else echo urlShop('article', 'show', array('article_id'=>$article['article_id']));?>"><?php echo $article['article_title'];?></a><time><?php echo date('Y-m-d H:i',$article['article_time']);?></time></li>
        <?php }?>
      </ul>
      <?php }else{?>
      <div><?php echo $lang['article_article_not_found'];?></div>
      <?php }?>
     
    </div> <div class="tc mb20">  <div class="pagination"> <?php echo $output['show_page'];?> </div></div>
  
  </div>
</div>