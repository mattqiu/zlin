<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['account_syn'];?></h3>
        <h5><?php echo $lang['account_syn_subhead'];?></h5>
      </div>
	  <?php echo $output['top_link'];?>
    </div>
  </div>
  <form method="post" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit">
          <label><?php echo $lang['wx_isuse'];?></label>
        </dt>
        <dd class="opt">
          <div class="onoff">
            <label for="wx_isuse_1" class="cb-enable <?php if($output['list_setting']['wx_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['wx_isuse_open'];?>"><span><?php echo $lang['wx_isuse_open'];?></span></label>
            <label for="wx_isuse_0" class="cb-disable <?php if($output['list_setting']['wx_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['wx_isuse_close'];?>"><span><?php echo $lang['wx_isuse_close'];?></span></label>
            <input type="radio" id="wx_isuse_1" name="wx_isuse" value="1" <?php echo $output['list_setting']['wx_isuse']==1?'checked=checked':''; ?>>
            <input type="radio" id="wx_isuse_0" name="wx_isuse" value="0" <?php echo $output['list_setting']['wx_isuse']==0?'checked=checked':''; ?>>
          </div>
          <p class="notic"><?php echo $lang['wxSettings_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="wx_appid"><em>*</em><?php echo $lang['wx_appid'];?></label>
        </dt>
        <dd class="opt">
          <input id="wx_appid" name="wx_appid" value="<?php echo $output['list_setting']['wx_appid'];?>" class="input-txt" type="text">
          <p class="notic"><a class="imap-btn" target="_blank" href="http://open.weixin.qq.com"><?php echo $lang['wx_apply_link']; ?></a></p>
        </dd>
        <p class="notic"></p>
      </dl>
      <dl class="row">
        <dt class="tit">
          <label for="wx_appkey"><em>*</em><?php echo $lang['wx_appkey'];?></label>
        </dt>
        <dd class="opt">
          <input id="wx_appkey" name="wx_appkey" value="<?php echo $output['list_setting']['wx_appkey'];?>" class="input-txt" type="text">
        </dd>
        <p class="notic"></p>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
