<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <div class="alert alert-success">
    <h4>操作提示：</h4>
    <ul>
      <li>1. 提佣申请操作需“绑定邮箱”或“绑定手机”以便接收验证码，如果未收到验证码，请检查绑定的手机或邮箱是否正确。</li>
      <li>2. 收到安全验证码后，请在30分钟内完成验证。</li>
      <li>3. 提佣申请提交后，所在店铺确认同意，则将提交申请时间以前的佣金收益打入申请者帐户余额中。</li>
      <li>4. 提佣申请一经提交，申请者不可撤消，如需撤消，请通知店家撤消。</li>
    </ul>
  </div>
  <div class="imm-default-form">
    <form id="auth_form" method="post" target="_parent" action="<?php echo urlShop('member_extension', 'apply_commission_save');?>">
      <input name="imhash" type="hidden" value="<?php echo getIMhash();?>" />
      <dl>
        <dt><i class="required">*</i>选择身份认证方式：</dt>
        <dd>
          <p>
          <select name="auth_type" id="auth_type">
            <?php if ($output['member_info']['member_mobile']) {?>
            <option value="mobile">手机 [<?php echo encryptShow($output['member_info']['member_mobile'],4,4);?>]</option>
            <?php } ?>
            <?php if ($output['member_info']['member_email']) {?>
            <option value="email">邮箱 [<?php echo encryptShow($output['member_info']['member_email'],4,4);?>]</option>
            <?php } ?>
          </select>
          <a href="javascript:void(0);" id="send_auth_code" class="imm-btn ml5"><span id="sending" style="display:none">正在</span><span class="send_success_tips"><strong id="show_times" class="red mr5"></strong>秒后再次</span>获取安全验证码</a>
          </p>
          <p class="send_success_tips hint mt10">“安全验证码”已发出，请注意查收，请在<strong>“30分种”</strong>内完成验证。</p>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>请输入安全验证码：</dt>
        <dd>
          <input type="text" class="text"  maxlength="6" value="" name="auth_code" size="10" id="auth_code" autocomplete="off" />
          <label for="email" generated="true" class="error"></label>
        </dd>
      </dl>
      <dl>
        <dt><i class="required">*</i>图形验证码：</dt>
        <dd>
          <input type="text" name="captcha" class="text" id="captcha" maxlength="4" size="10" autocomplete="off" />
         <img src="<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>" name="codeimage" border="0" id="codeimage" class="ml5 vm">
         <a href="javascript:void(0)" class="ml5 blue" onclick="javascript:document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();">看不清？换张图</a>
          <label for="captcha" generated="true" class="error"></label>
        </dd>
      </dl>
      <dl>
        <dt>留言：</dt>
        <dd>
          <textarea class="textarea" cols="50" name="describe" rows="10">这家伙很懒，什么都不肯留下!</textarea>
        </dd>
      </dl>
      <dl class="bottom">
        <dt>&nbsp;</dt>
        <dd>
          <label class="submit-border">
            <input type="button" class="submit" value="确认，进入下一步" />
          </label>
        </dd>
      </dl>
    </form>
  </div>
</div>
<script type="text/javascript">
$('.send_success_tips').hide();
var ALLOW_SEND = true;
$(function(){
	$('.submit').on('click',function(){
		if (!$('#auth_form').valid()){
			document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();
		} else {
			ajaxpost('auth_form', '', '', 'onerror') 
		}
	});
	function StepTimes() {
		$num = parseInt($('#show_times').html());
		$num = $num - 1;
		$('#show_times').html($num);
		if ($num <= 0) {
			ALLOW_SEND = !ALLOW_SEND;
			$('.send_success_tips').hide();
		} else {
			setTimeout(StepTimes,1000);
		}
	}
	$('#send_auth_code').on('click',function(){
		if (!ALLOW_SEND) return;
		ALLOW_SEND = !ALLOW_SEND;
		$('#sending').show();
		$.getJSON('index.php?act=member_security&op=send_auth_code',{type:$('#auth_type').val()},function(data){
			if (data.state == 'true') {
				$('#sending').hide();
				$('#show_times').html(60);
			    $('.send_success_tips').show();
			    setTimeout(StepTimes,1000);
			} else {
				ALLOW_SEND = !ALLOW_SEND;
				$('#sending').hide();
				showDialog('发送失败', 'error','','','','','','','','',2);
			}
		});
	});

    $('#auth_form').validate({
        rules : {
        	auth_code : {
                required : true,
                maxlength : 6,
                minlength : 6,
                digits : true
            },
            captcha : {
                required : true,
                minlength: 4,
                remote   : {
                    url : '<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=check&imhash=<?php echo getIMhash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    }
                }
            }
        },
        messages : {
        	auth_code : {
                required : '<i class="fa fa-exclamation-circle"></i>请正确输入验证码',
                maxlength : '<i class="fa fa-exclamation-circle"></i>请正确输入验证码',
				minlength : '<i class="fa fa-exclamation-circle"></i>请正确输入验证码',
				digits : '<i class="fa fa-exclamation-circle"></i>请正确输入验证码'
            },
            captcha : {
                required : '<i class="fa fa-exclamation-circle"></i>请正确输入图形验证码',
                minlength: '<i class="fa fa-exclamation-circle"></i>请正确输入图形验证码',
				remote	 : '<i class="fa fa-exclamation-circle"></i>请正确输入图形验证码'
            }
        }
    });
});
</script> 
