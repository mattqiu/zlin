<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['im_member_pointsmanage']?></h3>
        <h5><?php echo $lang['im_member_pointsmanage_subhead']?></h5>
      </div>
      <ul class="tab-base im-row">
        <li><a href="index.php?act=points&op=pointslog"><?php echo $lang['admin_points_log_title']?></a></li>
        <li><a href="JavaScript:void(0);" class="current">规则设置</a></li>
        <li><a href="index.php?act=points&op=addpoints">积分增减</a></li>
      </ul>
    </div>
  </div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <div class="title">
        <h3>会员日常获取积分设定</h3>
      </div>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_reg']; ?></dt>
        <dd class="opt">
          <input id="points_reg" name="points_reg" value="<?php echo $output['list_setting']['points_reg'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_login'];?></dt>
        <dd class="opt">
          <input id="points_login" name="points_login" value="<?php echo $output['list_setting']['points_login'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">连续签到1天</dt>
        <dd class="opt">
          <input id="points_sign_1" name="points_sign_1" value="<?php echo $output['list_setting']['points_sign'][1];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">连续签到2天</dt>
        <dd class="opt">
          <input id="points_sign_2" name="points_sign_2" value="<?php echo $output['list_setting']['points_sign'][2];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">连续签到3天</dt>
        <dd class="opt">
          <input id="points_sign_3" name="points_sign_3" value="<?php echo $output['list_setting']['points_sign'][3];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">连续签到4天</dt>
        <dd class="opt">
          <input id="points_sign_4" name="points_sign_4" value="<?php echo $output['list_setting']['points_sign'][4];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">连续签到5天</dt>
        <dd class="opt">
          <input id="points_sign_5" name="points_sign_5" value="<?php echo $output['list_setting']['points_sign'][5];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">连续签到6天</dt>
        <dd class="opt">
          <input id="points_sign_6" name="points_sign_6" value="<?php echo $output['list_setting']['points_sign'][6];?>" class="input-txt" type="text">
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit">连续签到大于或等于7天</dt>
        <dd class="opt">
          <input id="points_sign_7" name="points_sign_7" value="<?php echo $output['list_setting']['points_sign'][7];?>" class="input-txt" type="text">
        </dd>
      </dl>      
      <div class="title">
        <h3>会员<?php echo $lang['points_number_order']; ?>时积分获取设定</h3>
      </div>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_orderrate'];?></dt>
        <dd class="opt">
          <input id="points_orderrate" name="points_orderrate" value="<?php echo $output['list_setting']['points_orderrate'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['points_number_orderrate_tip']; ?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_ordermax']; ?></dt>
        <dd class="opt">
          <input id="points_ordermax" name="points_ordermax" value="<?php echo $output['list_setting']['points_ordermax'];?>" class="input-txt" type="text">
          <p class="notic"><?php echo $lang['points_number_ordermax_tip'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_number_comments']; ?></dt>
        <dd class="opt">
          <input id="points_comments" name="points_comments" value="<?php echo $output['list_setting']['points_comments'];?>" class="input-txt" type="text">
        </dd>
      </dl>
      
      <div class="title">
        <h3>会员<?php echo $lang['points_number_order']; ?>时积分抵换现金比例</h3>
      </div>
      <dl class="row">
        <dt class="tit">1积分抵换多少现金(元)</dt>
        <dd class="opt">
          <input id="points_trade" name="points_trade" value="<?php echo $output['list_setting']['points_trade'];?>" class="input-txt" type="text">
          <p class="notic">下单时，1积分可抵换多少元现金</p>
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
    </div>    
  </form>
</div>
<script>
$(function(){
    $("#submitBtn").click(function(){
        if($("#settingForm").valid()){
            $("#settingForm").submit();
        }
    });
});
</script> 
