<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3>商城运营配置</h3>
        <h5>商城运营设定开关</h5>
      </div>
    </div>
  </div>
  <form method="post" name="settingForm" id="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo $lang['payment_method'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="payment_method_1" class="cb-enable <?php if($output['list_setting']['payment_method'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="payment_method_0" class="cb-disable <?php if($output['list_setting']['payment_method'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="payment_method_1" name="payment_method" <?php if($output['list_setting']['payment_method'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="payment_method_0" name="payment_method" <?php if($output['list_setting']['payment_method'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['payment_method_notice'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['open_pointshop_isuse'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="pointshop_isuse_1" class="cb-enable <?php if($output['list_setting']['pointshop_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['im_open'];?>"><span><?php echo $lang['im_open'];?></span></label>
            <label for="pointshop_isuse_0" class="cb-disable <?php if($output['list_setting']['pointshop_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['im_close'];?>"><span><?php echo $lang['im_close'];?></span></label>
            <input id="pointshop_isuse_1" name="pointshop_isuse" <?php if($output['list_setting']['pointshop_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="pointshop_isuse_0" name="pointshop_isuse" <?php if($output['list_setting']['pointshop_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo sprintf($lang['open_pointshop_isuse_notice'],"index.php?act=setting&op=pointshop_setting");?></p>
        </dd>
      </dl>   
      <dl class="row">
        <dt class="tit"><?php echo $lang['points_isuse'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="points_isuse_1" class="cb-enable <?php if($output['list_setting']['points_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="points_isuse_0" class="cb-disable <?php if($output['list_setting']['points_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="points_isuse_1" name="points_isuse" <?php if($output['list_setting']['points_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="points_isuse_0" name="points_isuse" <?php if($output['list_setting']['points_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['points_isuse_notice'];?></p>
        </dd>
      </dl> 
      <dl class="row">
        <dt class="tit"><?php echo $lang['open_pointprod_isuse'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="pointprod_isuse_1" class="cb-enable <?php if($output['list_setting']['pointprod_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="pointprod_isuse_0" class="cb-disable <?php if($output['list_setting']['pointprod_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="pointprod_isuse_1" name="pointprod_isuse" <?php if($output['list_setting']['pointprod_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="pointprod_isuse_0" name="pointprod_isuse" <?php if($output['list_setting']['pointprod_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic"><?php echo $lang['open_pointprod_isuse_notice'];?></p>
        </dd>
      </dl>                   
      <dl class="row">
        <dt class="tit">经验值</dt>
        <dd class="opt">
          <div class="onoff">
            <label for="experience_isuse_1" class="cb-enable <?php if($output['list_setting']['experience_isuse'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><?php echo $lang['open'];?></label>
            <label for="experience_isuse_0" class="cb-disable <?php if($output['list_setting']['experience_isuse'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><?php echo $lang['close'];?></label>
            <input id="experience_isuse_1" name="experience_isuse" <?php if($output['list_setting']['experience_isuse'] == '1'){ ?>checked="checked"<?php } ?> value="1" type="radio">
            <input id="experience_isuse_0" name="experience_isuse" <?php if($output['list_setting']['experience_isuse'] == '0'){ ?>checked="checked"<?php } ?> value="0" type="radio">
          </div>
          <p class="notic">会员经验值系统启用后，可设置会员的注册、登录、购买商品加一定的经验值</p>
        </dd>
      </dl>      
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(function(){$("#submitBtn").click(function(){
    if($("#settingForm").valid()){
     $("#settingForm").submit();
	}
	});
});
</script>
