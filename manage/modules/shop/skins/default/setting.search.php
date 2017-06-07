<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['im_mall_set'];?></h3>
        <h5><?php echo $lang['im_mall_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?> </div>
  </div>
  <form id="form" method="post" enctype="multipart/form-data" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit">
          <label for="hot_search"><?php echo $lang['hot_search'];?></label>
        </dt>
        <dd class="opt">
          <input id="hot_search" name="hot_search" value="<?php echo $output['list_setting']['hot_search'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic"><?php echo $lang['field_notice'];?></p>
      </dl>
      
      <dl class="row">
        <dt class="tit">
          <label for="adv_search">搜索框广告</label>
        </dt>
        <dd class="opt">
          <input id="adv_search" name="adv_search" value="<?php echo $output['list_setting']['adv_search'];?>" class="input-txt" type="text">
          <span class="err"></span>
          <p class="notic">搜索框文字广告将显示在首页顶部商品搜索框中</p>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
