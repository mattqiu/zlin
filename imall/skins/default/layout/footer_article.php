<?php defined('InIMall') or exit('Access Invalid!');?>

<?php if(is_array($output['article_list']) && !empty($output['article_list'])){ ?>
<div id="faq" class="footer_container">
  <div class="footer_separate_line"></div>
  <div class="footer_wrapper">        
    <div class="faq">      
      <ul>
        <?php $i = 0; foreach ($output['article_list'] as $k=> $article_class){ $i++;?>
        <?php if(!empty($article_class)){ ?>
        <li> 
          <dl>
            <dt>
		      <i class="icon<?php echo $i;?>"></i><?php if(is_array($article_class['class'])) echo $article_class['class']['ac_name'];?>
            </dt>
            <?php if(is_array($article_class['list']) && !empty($article_class['list'])){ ?>
            <?php foreach ($article_class['list'] as $article){ ?>
            <dd>
              <i></i><a href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo urlShop('article', 'show',array('article_id'=> $article['article_id']));?>" title="<?php echo $article['article_title']; ?>"> <?php echo $article['article_title'];?> </a>
            </dd>
            <?php }?>
            <?php }?>
          </dl>
        </li>
        <?php }?>
        <?php }?>
      </ul> 
    </div>
    <div class="weixin">
      <ul class="linksf">
        <li class="links">扫码关注公众号</li>
        <li><img src="<?php echo SHOP_SKINS_URL;?>/images/weixin_img.jpg" height="110" width="110"></li>
      </ul>
    </div>
    <div class="clear"></div>
  </div>
</div>
<?php }?>