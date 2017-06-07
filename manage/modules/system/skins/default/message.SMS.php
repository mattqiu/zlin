<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <div class="subject">
        <h3><?php echo $lang['im_SMS_set'];?></h3>
        <h5><?php echo $lang['im_SMS_set_subhead'];?></h5>
      </div>
      <?php echo $output['top_link'];?>
    </div>
  </div>
  <!-- 操作说明 -->
  <div class="explanation" id="explanation">
    <div class="title" id="checkZoom"><i class="fa fa-lightbulb-o"></i>
      <h4 title="<?php echo $lang['im_prompts_title'];?>"><?php echo $lang['im_prompts'];?></h4>
      <span id="explanationZoom" title="<?php echo $lang['im_prompts_span'];?>"></span> </div>
    <ul>
      <li>填写短信接口服务器相关参数，并点击“测试”按钮进行效验，保存后生效。</li>
      <li>如使用第三方提供的短信发送服务器，请认真阅读服务商提供的相关帮助文档。</li>
    </ul>
  </div>
  <form method="post" id="form_SMS" name="settingForm">
    <input type="hidden" name="form_submit" value="ok" />
    <div class="imap-form-default">
      <dl class="row">
        <dt class="tit"><?php echo $lang['setting_sms_open'];?></dt>
        <dd class="opt">
          <div class="onoff">
            <label for="sms_open_1" class="cb-enable <?php if($output['list_setting']['sms_open'] == '1'){ ?>selected<?php } ?>" title="<?php echo $lang['open'];?>"><span><?php echo $lang['open'];?></span></label>
            <label for="sms_open_0" class="cb-disable <?php if($output['list_setting']['sms_open'] == '0'){ ?>selected<?php } ?>" title="<?php echo $lang['close'];?>"><span><?php echo $lang['close'];?></span></label>
            <input type="radio" <?php if($output['list_setting']['sms_open'] == '1'){ ?>checked="checked"<?php } ?> value="1" name="sms_open" id="sms_open_1" />
            <input type="radio" <?php if($output['list_setting']['sms_open'] == '0'){ ?>checked="checked"<?php } ?> value="0" name="sms_open" id="sms_open_0" />
          </div>
          <p class="notic"></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['setting_sms_gwUrl'];?></dt>
        <dd class="opt">
          <input type="text" name="sms_gwUrl" id="sms_gwUrl" class="input-txt" value="<?php echo $output['list_setting']['sms_gwUrl'];?>">
          <p class="notic"><?php echo $lang['setting_sms_gwUrl_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['setting_sms_username'];?></dt>
        <dd class="opt">
          <input type="text" name="sms_username" id="sms_username" class="input-txt" value="<?php echo $output['list_setting']['sms_username'];?>">
          <p class="notic"><?php echo $lang['setting_sms_username_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['setting_sms_pwd'];?></dt>
        <dd class="opt">
          <input type="text" name="sms_pwd" id="sms_pwd" class="input-txt" value="<?php echo $output['list_setting']['sms_pwd'];?>">
          <p class="notic"><?php echo $lang['setting_sms_pwd_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['setting_sms_key'];?></dt>
        <dd class="opt">
          <input type="text" name="sms_key" id="sms_key" class="input-txt" value="<?php echo $output['list_setting']['sms_key'];?>">
          <p class="notic"><?php echo $lang['setting_sms_key_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['setting_sms_price'];?></dt>
        <dd class="opt">
          <input type="text" name="sms_price" id="sms_price" class="input-txt" value="<?php echo $output['list_setting']['sms_price'];?>">
          <p class="notic"><?php echo $lang['setting_sms_price_tips'];?></p>
        </dd>
      </dl>
      <dl class="row">
        <dt class="tit"><?php echo $lang['setting_sms_test_mobile'];?></dt>
        <dd class="opt">
          <input type="text" value="" name="SMS_test" id="SMS_test" class="input-txt">
          <input type="button" value="<?php echo $lang['test'];?>" name="send_test_sms" class="input-btn" id="send_test_sms">
        </dd>
      </dl>
      <div class="bot"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" onclick="document.settingForm.submit()"><?php echo $lang['im_submit'];?></a></div>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#send_test_sms').click(function(){
		$.ajax({
			type:'POST',
			url:'index.php',
			data:'act=message&op=SMS_testing&SMS_host='+$('#sms_gwUrl').val()+'&SMS_key='+$('#sms_key').val()+'&SMS_test='+$('#SMS_test').val(),
			error:function(){
					alert('<?php echo $lang['test_SMS_send_fail'];?>');
				},
			success:function(html){
				alert(html.msg);
			},
			dataType:'json'
		});
	});
});
</script>