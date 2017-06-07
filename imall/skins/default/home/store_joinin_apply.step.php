<?php defined('InIMall') or exit('Access Invalid!');?>

<link href="<?php echo SHOP_SKINS_URL;?>/css/store_join_select.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.lazyload.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/store_join_select.js"></script>

<div class="store-joinin-apply"> 
        <DIV id="pocket" class="pocket">
          <ARTICLE id="home">
            <SECTION id="slideshow">
              <NAV id="slideshow-nav">
                <A style="height: 230px; line-height: 230px;" class="nav hide btn-blue" href="index.php?act=store_joinin&op=step1" data-slideto="0">
                  <SPAN class="v-middle">
                    <H1 class="title">个人网店入驻</H1>
                    <P class="subtitle">适合于没有实体店的个人申请入驻</P>
                  </SPAN>
                  <SPAN class="icon sprite sprite-home shirt_of_the_day"></SPAN>
                  <DIV class="progress"></DIV>
                </A>
                <A style="height: 230px; line-height: 230px;" class="nav hide" href="index.php?act=store_joinin&op=step1" data-slideto="990">
                  <SPAN class="v-middle">
                    <H1 class="title">企业网店入驻</H1>
                    <P class="subtitle">适合于拥有实体店的商家申请入驻</P>
                  </SPAN>
                  <SPAN class="icon sprite sprite-home sale"></SPAN>
                  <DIV class="progress"></DIV>
                </A>
              </NAV>
              <UL id="slideshow-slides" class="listings dbh-slider dbh-slider-banner dbh-slider-parent lazy-parent">
                <LI class="listing">
                  <A href="index.php?act=store_joinin&op=step1">
                    <IMG alt="Ancient Ink<br>by hghmn" src="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/1.jpg" width="990" height="460">
                  </A>
                  <P class="slide_content">
                    <A class="slide_text fleft" href="index.php?act=store_joinin&op=step1">适合于没有实体店的个人申请入驻</A>                     
                  </P>
                </LI>
                <LI class="listing">
                  <A href="index.php?act=store_joinin&op=step1">
                    <IMG class="lazy-child" alt="Submitted by Cjmarsh" src="" width="990" height="460" data-original="<?php echo SHOP_SKINS_URL;?>/images/store_joinin/2.jpg">
                  </A>
                  <P class="slide_content">
                    <A class="slide_text fleft" href="index.php?act=store_joinin&op=step1">适合于拥有实体店的商家申请入驻</A> 
                  </P>
                </LI>
              </UL>
            </SECTION>
          </ARTICLE>
        </DIV>
</div>
