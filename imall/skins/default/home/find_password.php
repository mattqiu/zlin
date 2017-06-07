<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="imcss-login-layout">
  <div class="left-pic"> <img src="<?php echo $output['lpic'];?>"  border="0"> </div>
  <div class="imcss-login">
    <div class="imcss-password-mode">
      <ul class="tabs-nav">
        <li><a href="#default">邮箱找回密码<i></i></a></li> 
        <?php if (C('sms_open')==1){?>      
        <li><a href="#mobile" title="手机找回密码" class="sms_find">手机找回密码<i></i></a></li>
        <?php }?>
      </ul>
      <div id="tabs_container" class="tabs-container">
        <div id="default" class="tabs-content">
          <form class="imcss-login-form" action="index.php?act=login&op=find_password" method="POST" id="find_password_form">
            <input type="hidden" name="form_submit" value="ok" />
            <?php Security::getToken();?>            
            <input name="imhash" type="hidden" value="<?php echo getIMhash();?>" />
            <dl>
              <dt><?php echo $lang['login_password_you_account'];?>：</dt>
              <dd>
                <input type="text" class="text" name="username" tipMsg="输入您已注册的用户名"/>
              </dd>
            </dl>
            <dl>
              <dt><?php echo $lang['login_password_you_email'];?>：</dt>
              <dd>
                <input type="text" class="text" name="email" tipMsg="输入您已注册的邮箱"/>
              </dd>
            </dl>
            <?php if(C('captcha_status_login') == '1') { ?>
            <div class="code-div mt15">
              <dl>
                <dt><?php echo $lang['login_register_code'];?>：</dt>
                <dd>
                  <input type="text" name="captcha" autocomplete="off" class="text w100" tipMsg="输入验证码" id="captcha" size="10" />                  
                </dd>
              </dl>
              <span>
                <img src="<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>" name="codeimage" id="codeimage"> 
                <a class="makecode" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();"><?php echo $lang['login_password_change_code']; ?></a>
              </span>
            </div>
            <?php } ?>
            <div class="submit-div">
              <input type="hidden" value="<?php echo $output['ref_url']?>" name="ref_url">
              <input type="button" class="submit" value="重置密码" name="Submit" id="Submit">              
            </div>
          </form>
        </div>
        <?php if (C('sms_open')==1){?>
        <div id="mobile" class="tabs-content">
          <form id="mobile_form" method="post" class="imcss-login-form" action="index.php?act=connect_sms&op=find_password">
            <?php Security::getToken();?>
            <input type="hidden" name="form_submit" value="ok" />
            <input name="imhash" type="hidden" value="<?php echo getIMhash();?>" />
            <dl>
              <dt>手机号：</dt>
              <dd>
                <input type="text" class="text" autocomplete="off" value="" name="phone" id="phone" tipMsg="输入您已注册的手机号" >
              </dd>
            </dl>
            <?php if(C('captcha_status_register') == '1') { ?>
            <div class="code-div mt15">
              <dl>
                <dt>验证码：</dt>
                <dd>
                  <input type="text" name="captcha" class="text w100" id="mobile_captcha" size="10" tipMsg="输入验证码" />
                </dd>
              </dl>
              <span>
                <img src="" title="" name="codeimage" id="sms_codeimage"> 
                <a class="makecode" href="javascript:void(0);" class="ml5" onclick="javascript:document.getElementById('sms_codeimage').src='index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();"><?php echo $lang['login_password_change_code']; ?></a></span>
              </dd>
            </div>
            <?php } ?>
            <div class="tiptext" id="sms_text">
              <p id="sending_tips">正确输入上方验证码后，点击“获取手机校验码”，将收到系统发送的“6位手机校验码”输入到下方验证后登录。</p>
              <p id="send_success_tips" style="display:none">校验码已发出，请注意查收短信，如果没有收到，您可以在<i style="color: red" id="show_times">60</i>秒后要求系统重新发送，收到后，请在30分种内完成验证。</p>
            </div>
            <div class="verify_div">
              <dl>
                <dt>校验码：</dt>
                <dd>
                  <input type="text" name="sms_captcha" class="text w120" tipMsg="输入6位手机校验码" id="sms_captcha" size="10" />                              
                </dd>
              </dl>
              <span>               
                <a href="javascript:void(0);" onclick="get_sms_captcha('3')" id="sending_btn"><i class="fa fa-mobile"></i>获取手机校验码</a>
              </span> 
            </div>
            <dl>
              <dt>新密码：</dt>
              <dd>
                <input type="text" name="password" id="password" class="text"  tipMsg="输入您修改的密码" />
              </dd>
            </dl>
            <div class="submit-div">
              <input type="button" id="submitBtn" class="submit" value="确认重置">
            </div>
          </form>
        </div>
        <?php } ?> 
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<script type="text/javascript">
$(function(){
	//初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password'});
	//找回密码方式切换
	$('.imcss-password-mode').tabulous({
		 effect: 'flip'//动画反转效果
	});	
	var div_form = '#default';
	$(".imcss-password-mode .tabs-nav li a").click(function(){
        if($(this).attr("href") !== div_form){
            div_form = $(this).attr('href');
            $(""+div_form).find(".makecode").trigger("click");
    	}
	});

    $('#Submit').click(function(){
        if($("#find_password_form").valid()){
			ajaxpost('find_password_form', '', '', 'onerror');
			//$("#find_password_form").submit();
        } else{
        	document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();
        }
    });
    $('#find_password_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
		success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
        rules : {
            username : {
                required : true
            },
            email : {
                required : true,
                email : true
            },
            captcha : {
                required : true,
                minlength: 4,
                remote   : {
                    url : 'index.php?act=seccode&op=check&imhash=<?php echo getIMhash();?>',
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
            username : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_usersave_login_usersave_username_isnull'];?>'></i>"
            },
            email  : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_password_input_email'];?>'></i>",
                email : "<i class='fa fa-question-circle' title='<?php echo $lang['login_password_wrong_email'];?>'></i>"
            },
            captcha : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_usersave_code_isnull'];?>'></i>",
                minlength : "<i class='fa fa-question-circle' title='<?php echo $lang['login_usersave_wrong_code'];?>'></i>",
                remote   : "<i class='fa fa-question-circle' title='<?php echo $lang['login_usersave_wrong_code'];?>'></i>"
            }
        }
    });
});
</script> 
<?php if (C('sms_open')==1){?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/connect_sms.js" charset="utf-8"></script> 
<script>
$(function(){
	$("#submitBtn").click(function(){
        if($("#mobile_form").valid()){
            ajaxpost('mobile_form', '', '', 'onerror');
    	}
	});
	
	$("#mobile_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
        onkeyup: false,
		rules: {
			phone: {
                required : true,
                mobile : true
            },
			captcha : {
                required : true,
                minlength: 4,
                remote   : {
                    url : 'index.php?act=seccode&op=check&imhash=<?php echo getIMhash();?>',
                    type: 'get',
                    data:{
                        captcha : function(){
                            return $('#mobile_captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                        	document.getElementById('sms_codeimage').src='index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();
                        }
                    }
                }
            },
			sms_captcha: {
                required : function(element) {
                    return $("#captcha").val().length == 4;
                },
                minlength: 6,
				maxlength: 6
            },
            password : {
                required : function(element) {
                    return $("#sms_captcha").val().length == 6;
                },
                minlength: 6,
				maxlength: 20
            }
		},
		messages: {
			phone: {
                required : "<i class='fa fa-question-circle' title='请输入正确的手机号'></i>",
                mobile : "<i class='fa fa-question-circle' title='请输入正确的手机号'></i>"
            },
			captcha : {
                required : "<i class='fa fa-question-circle' title='请输入正确的验证码'></i>",
                minlength: "<i class='fa fa-question-circle' title='请输入正确的验证码'></i>",
				remote	 : "<i class='fa fa-question-circle' title='请输入正确的验证码'></i>"
            },
			sms_captcha: {
                required : "<i class='fa fa-question-circle' title='请输入六位短信动态码'></i>",
                minlength: "<i class='fa fa-question-circle' title='请输入六位短信动态码'></i>",
				maxlength: "<i class='fa fa-question-circle' title='请输入六位短信动态码'></i>"
            },
            password  : {
                required : "<i class='fa fa-question-circle' title='密码不能为空'></i>",
                minlength: "<i class='fa fa-question-circle' title='密码长度应在6-20个字符之间'></i>",
				maxlength: "<i class='fa fa-question-circle' title='密码长度应在6-20个字符之间'></i>"
            }
		}
	});
});
</script>
<?php } ?> 