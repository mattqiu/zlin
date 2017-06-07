<?php defined('InIMall') or exit('Access Invalid!');?>
<div class="quick-login">
  <div class="register">
    <h4>登录<?php echo C('site_name'); ?>,享受会员特权
      <a href="<?php echo SHOP_SITE_URL?>/index.php?act=login&op=register" class="forget">立即注册</a>
    </h4>
  </div>
  <form id="login_form" action="<?php echo SHOP_SITE_URL;?>/index.php?act=login" method="post" class="bg" >
    <?php Security::getToken();?>
    <input type="hidden" name="form_submit" value="ok" />
    <input name="imhash" type="hidden" value="<?php echo getIMhash();?>" />
    <dl>
      <dt><?php echo $lang['login_index_username'];?>：</dt>
      <dd>
        <input type="text" class="text" autocomplete="off"  name="user_name" id="user_name" autofocus>
      </dd>
    </dl>
    <dl>
      <dt><?php echo $lang['login_index_password'];?>：</dt>
      <dd>
        <input type="password" class="text" name="password" autocomplete="off"  id="password">
      </dd>
    </dl>
    <?php if(C('captcha_status_login') == '1') { ?>
    <dl>
      <dt><?php echo $lang['login_index_checkcode'];?>：</dt>
      <dd>
        <input type="text" name="captcha" class="text fl w60" id="captcha" maxlength="4" size="10" />
        <img class="fl ml10" src="<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>" title="<?php echo $lang['login_index_change_checkcode'];?>" name="codeimage" border="0" id="codeimage" onclick="this.src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random()"><span></span></dd>
    </dl>
    <?php } ?>
    <div class="enter">
      <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
      <input type="submit" class="submit" value="登&nbsp;&nbsp;录" name="Submit">
    </div>      
  </form>
  <?php if ($output['setting_config']['qq_isuse'] == 1 || $output['setting_config']['sina_isuse'] == 1 || $output['setting_config']['wx_isuse'] == 1){?>
  <div class="other" id="demo-form-site">
    <h4>使用合作帐号登录
      <a href="<?php echo SHOP_SITE_URL?>/index.php?act=login&op=forget_password" class="forget">忘记密码?</a>
    </h4>
    <?php if ($output['setting_config']['qq_isuse'] == 1){?>
    <a href="<?php echo SHOP_SITE_URL;?>/api.php?act=toqq" title="QQ账号登录" class="qq"><i></i>QQ</a>
    <?php } ?>
    <?php if ($output['setting_config']['sina_isuse'] == 1){?>
    <a href="<?php echo SHOP_SITE_URL;?>/api.php?act=tosina" title="<?php echo $lang['im_otherlogintip_sina']; ?>" class="sina"><i></i>新浪微博</a>
    <?php } ?>
    <?php if ($output['setting_config']['wx_isuse'] == 1){?>
    <a href="javascript:void(0);" onclick="ajax_form('weixin_form', '微信账号登录', '<?php echo SHOP_SITE_URL;?>/api.php?act=towx', 360);" title="微信账号登录" class="wx"><i></i>微信</a>
    <?php } ?>
  </div>
  <?php } ?>  
</div>
<script>
$(document).ready(function(){
	$("#login_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
        },
        onkeyup: false,
    	submitHandler:function(form){
    		ajaxpost('login_form', '', '', 'error');
    	},
		rules: {
			user_name: "required",
			password: "required"
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : true,
                remote   : {
                    url : '<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=check&imhash=<?php echo getIMhash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                        	document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();
                        }
                    }
                }
            }
			<?php } ?>
		},
		messages: {
			user_name: "",
			password: ""
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : '',
				remote	 : '验证码错误'
            }
			<?php } ?>
		}
	});
});
</script>