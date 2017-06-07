<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="imcss-login-layout">
  <div class="imcss-login-left">
    <h3><?php echo $lang['login_register_after_regist'];?></h3>
    <ol>
      <li class="ico05"><i></i><?php echo $lang['login_register_buy_info'];?></li>
      <li class="ico01"><i></i><?php echo $lang['login_register_openstore_info'];?></li>
      <li class="ico03"><i></i><?php echo $lang['login_register_sns_info'];?></li>
      <li class="ico02"><i></i><?php echo $lang['login_register_collect_info'];?></li>
      <li class="ico06"><i></i><?php echo $lang['login_register_talk_info'];?></li>
      <li class="ico04"><i></i><?php echo $lang['login_register_honest_info'];?></li>
      <div class="clear"></div>
    </ol>
    <h3 class="mt20"><?php echo $lang['login_register_already_have_account'];?></h3>
    <div class="imcss-login-now mt10">
      <span class="ml20">
	    <?php echo $lang['login_register_login_now_1'];?>
        <a href="index.php?act=login&ref_url=<?php echo urlencode($output['ref_url']); ?>" title="<?php echo $lang['login_register_login_now'];?>" class="register"><?php echo $lang['login_register_login_now_2'];?></a>
      </span>
      <span><?php echo $lang['login_register_login_now_3'];?><a class="forget" href="index.php?act=login&op=forget_password"><?php echo $lang['login_register_login_forget'];?></a></span>
    </div>
  </div>  
  <div class="imcss-login">
    <div class="imcss-register-mode">
      <ul class="tabs-nav">
        <li><a href="#default">邮箱注册<i></i></a></li>
        <?php if (C('sms_open')==1){?>
        <li><a href="#mobile">手机注册<i></i></a></li>
        <?php }?>
      </ul>
      <div id="tabs_container" class="tabs-container">
        <div id="default" class="tabs-content">
        <form id="register_form" method="post" class="imcss-login-form" action="<?php echo SHOP_SITE_URL;?>/index.php?act=login&op=usersave">
          <input type="hidden" name="form_submit" value="ok" />
          <?php Security::getToken();?>  
          <input name="imhash" type="hidden" value="<?php echo getIMhash();?>" />
          <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">                       
          <dl>
            <dt><?php echo $lang['login_register_username'];?>：</dt>
            <dd>
              <input type="text" id="user_name" name="user_name" class="text" tipMsg="<?php echo $lang['login_register_username_to_login'];?>"/>
            </dd>
          </dl>
          <dl>
            <dt><?php echo $lang['login_register_pwd'];?>：</dt>
            <dd>
              <input type="password" id="password" name="password" class="text" tipMsg="<?php echo $lang['login_register_password_to_login'];?>"/>
            </dd>
          </dl>
          <dl>
            <dt><?php echo $lang['login_register_ensure_password'];?>：</dt>
            <dd>
              <input type="password" id="password_confirm" name="password_confirm" class="text" tipMsg="<?php echo $lang['login_register_input_password_again'];?>"/>
            </dd>
          </dl>
          <dl class="mt15">
            <dt><?php echo $lang['login_register_email'];?>：</dt>
            <dd>
              <input type="text" id="email" name="email" class="text" tipMsg="<?php echo $lang['login_register_input_valid_email'];?>"/>
            </dd>
          </dl>
          <?php if(C('invite_open') == '1') { ?>
          <dl class="mt15">
            <dt><?php echo $lang['login_register_invite_code'];?>：</dt>
            <dd>
              <input type="text" id="invite_code" name="invite_code" class="text" value="<?php echo $output['invite_code'];?>" tipMsg="<?php echo $lang['login_register_input_invite_code'];?>"/>
            </dd>
          </dl>
          <?php } ?>
          <?php if(C('captcha_status_register') == '1') { ?>
          <div class="code-div mt15">
            <dl>
              <dt><?php echo $lang['login_register_code'];?>：</dt>
              <dd>
                <input type="text" id="captcha" name="captcha" class="text w120" size="10" tipMsg="<?php echo $lang['login_register_input_code'];?>" />
              </dd>
            </dl>
            <span>
              <img src="index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>" name="codeimage" id="codeimage"/> 
              <a class="makecode" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();"><?php echo $lang['login_register_click_to_change_code'];?></a>
            </span>
          </div>
          <?php } ?>
          <dl class="clause-div">
            <dd>
              <input name="agree" type="checkbox" class="checkbox" id="clause" value="1" checked="checked" /><?php echo $lang['login_register_agreed'];?>
              <a href="<?php echo urlShop('document', 'index',array('code'=>'agreement'));?>" target="_blank" class="agreement" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a>
            </dd>
          </dl>
          <div class="submit-div">
            <input type="submit" id="Submit" value="立即注册" class="submit"/>
          </div>
        </form>
        </div>
        <?php if (C('sms_open')==1){?>
        <div id="mobile" class="tabs-content">
        <form id="mobile_form" method="post" class="imcss-login-form">
          <input type="hidden" name="form_submit" value="ok" />
          <?php Security::getToken();?>           
          <input name="imhash" type="hidden" value="<?php echo getIMhash();?>" />
          <dl>
            <dt>手机号：</dt>
            <dd>
              <input type="text" class="text" tipMsg="请输入手机号码" autocomplete="off" value="" name="phone" id="phone"  >
            </dd>
          </dl>
          <?php if(C('captcha_status_login') == '1') { ?>
          <div class="code-div">
            <dl>
              <dt>验证码：</dt>
              <dd>
                <input type="text" name="mobile_captcha" class="text w100" id="mobile_captcha" size="10" tipMsg="输入验证码" />
              </dd>
            </dl>
            <span>
              <img src="" title="" name="codeimage" id="sms_codeimage">
              <a class="makecode" href="javascript:void(0);" onclick="javascript:document.getElementById('sms_codeimage').src='index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();">看不清，换一张</a>
            </span> 
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
              <a href="javascript:void(0);" onclick="get_sms_captcha('1')" id="sending_btn"><i class="fa fa-mobile"></i>获取手机校验码</a>
            </span> 
          </div>
          <div class="submit-div">
            <input type="button" id="submitBtn" class="submit" value="下一步">
          </div>
        </form>
        
        <form style="display: none;" id="register_sms_form" class="imcss-login-form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php?act=connect_sms&op=usersave">
          <input type="hidden" name="form_submit" value="ok" />
          <input type="hidden" name="register_captcha" id="register_sms_captcha" value="" />
          <input type="hidden" name="register_phone" id="register_phone" value="" />
          <dl>
            <dt>用户名：</dt>
            <dd>
              <input type="text" id="member_name" name="member_name" class="text w150" value=""/>
            </dd>
            <span class="note">系统生成随机用户名，可选择默认或自行修改一次。</span>
          </dl>
          <dl>
            <dt>设置密码：</dt>
            <dd>
              <input type="text" id="sms_password" name="password" class="text w150" value=""/>
            </dd>
            <span class="note">系统生成随机密码，请牢记或修改为自设密码。</span>
          </dl>
          <dl class="mt15">
            <dt>邮箱：</dt>
            <dd>
              <input type="text" id="sms_email" name="email" class="text" value="" tipMsg="输入常用邮箱作为验证及找回密码使用" />
            </dd>
          </dl>
          <dl class="clause-div">
            <dd>
              <input name="agree" type="checkbox" class="checkbox" id="sms_clause" value="1" checked="checked" />阅读并同意
              <a href="<?php echo urlShop('document', 'index',array('code'=>'agreement'));?>" target="_blank" class="agreement" title="<?php echo $lang['login_register_agreed'];?>"><?php echo $lang['login_register_agreement'];?></a>
            </dd>
          </dl>
          <div class="submit-div">
            <input type="submit" value="提交注册" class="submit" title="提交注册" />
          </div>
        </form>
        </div>
        <?php } ?>        
      </div>
    </div>
    <?php if ($output['setting_config']['qq_isuse'] == 1 || $output['setting_config']['sina_isuse'] == 1){?>
    <div class="imcss-login-api" id="demo-form-site">
      <h4><?php echo $lang['im_otherlogintip'];?></h4>
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
  <div class="clear"></div>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script> 
<script>
//注册表单验证
$(function(){	
    //初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password,password_confirm,active_pwd'});
	//注册方式切换
	$('.imcss-register-mode').tabulous({
		 effect: 'slideLeft'//动画左侧滑入效果
	});
	
	var div_form = '#default';
	$(".imcss-register-mode .tabs-nav li a").click(function(){
        if($(this).attr("href") !== div_form){
            div_form = $(this).attr('href');
            $(""+div_form).find(".makecode").trigger("click");
    	}
	});
	
	jQuery.validator.addMethod("lettersonly", function(value, element) {
		return this.optional(element) || /^[^:%,'\*\"\s\<\>\&]+$/i.test(value);
	}, "Letters only please"); 
	jQuery.validator.addMethod("lettersmin", function(value, element) {
		return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length>=3);
	}, "Letters min please"); 
	jQuery.validator.addMethod("lettersmax", function(value, element) {
		return this.optional(element) || ($.trim(value.replace(/[^\u0000-\u00ff]/g,"aa")).length<=15);
	}, "Letters max please");	

    $("#register_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
		success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('register_form', '', '', 'onerror');
    	},
        onkeyup: false,
        rules : {
            user_name : {
                required : true,
                lettersmin : true,
                lettersmax : true,
                lettersonly : true,
                remote   : {
                    url :'index.php?act=login&op=check_member&column=ok',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#user_name').val();
                        }
                    }
                }
            },
            password : {
                required : true,
                minlength: 6,
				maxlength: 20
            },
            password_confirm : {
                required : true,
                equalTo  : '#password'
            },
            email : {
                required : true,
                email    : true,
                remote   : {
                    url : 'index.php?act=login&op=check_email',
                    type: 'get',
                    data:{
                        email : function(){
                            return $('#email').val();
                        }
                    }
                }
            },
            <?php if(C('invite_open') == '1') { ?>
            invite_code : {
                required : true,
                invite_code    : true,
                remote   : {
                    url : 'index.php?act=login&op=check_inviteCode',
                    type: 'get',
                    data:{
                    	invite_code : function(){
                            return $('#invite_code').val();
                        }
                    }
                }
            },
            <?php } ?>
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : true,
                remote   : {
                    url : 'index.php?act=seccode&op=check&imhash=<?php echo getIMhash();?>',
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
            },
			<?php } ?>
            agree : {
                required : true
            }
        },
        messages : {
            user_name : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_input_username'];?>'></i>",
                lettersmin : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_username_range'];?>'></i>",
                lettersmax : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_username_range'];?>'></i>",
				lettersonly: "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_username_lettersonly'];?>'></i>",
				remote	 : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_username_exists'];?>'></i>"
            },
            password  : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_input_password'];?>'></i>",
                minlength: "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_password_range'];?>'></i>",
				maxlength: "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_password_range'];?>'></i>"
            },
            password_confirm : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_input_password_again'];?>'></i>",
                equalTo  : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_password_not_same'];?>'></i>"
            },
            email : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_input_email'];?>'></i>",
                email    : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_invalid_email'];?>'></i>",
				remote	 : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_email_exists'];?>'></i>"
            },
            <?php if(C('invite_open') == '1') { ?>
            invite_code : {
                required 	: "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_input_invite_code'];?>'></i>",
                invite_code : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_invite_code'];?>'></i>",
				remote	 	: "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_invite_code_exists'];?>'></i>"
            },
            <?php } ?>
			<?php if(C('captcha_status_register') == '1') { ?>
            captcha : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_input_text_in_image'];?>'></i>",
				remote	 : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_code_wrong'];?>'></i>"
            },
			<?php } ?>
            agree : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_must_agree'];?>'></i>"
            }
        }
    });
});
</script>
<?php if (C('sms_open')==1){?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/connect_sms.js" charset="utf-8"></script> 
<script>
$(function(){
	jQuery.validator.addMethod("mobile", function(value, element) {
		return this.optional(element) || /^[1][3-8]+\d{9}/i.test(value);
	}, "phone number please");
	
	$("#submitBtn").click(function(){
        if($("#mobile_form").valid()){
            check_captcha();
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
			mobile_captcha : {
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
                    return $("#mobile_captcha").val().length == 4;
                },
                minlength: 6,
				maxlength: 6
            }
		},
		messages: {
			phone: {
                required : "<i class='fa fa-question-circle' title='输入正确的手机号'></i>",
                mobile : "<i class='fa fa-question-circle' title='输入正确的手机号'></i>"
            },
			mobile_captcha : {
                required : "<i class='fa fa-question-circle' title='请输入验证码'></i>",
                minlength: "<i class='fa fa-question-circle' title='请输入验证码'></i>",
				remote	 : "<i class='fa fa-question-circle' title='验证码不正确'></i>"
            },
			sms_captcha: {
                required : "<i class='fa fa-question-circle' title='请输入六位短信动态码'></i>",
                minlength: "<i class='fa fa-question-circle' title='请输入六位短信动态码'></i>",
				maxlength: "<i class='fa fa-question-circle' title='请输入六位短信动态码'></i>"
            }
		}
	});
	
    $('#register_sms_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('register_sms_form', '', '', 'onerror');
    	},
        rules : {
            member_name : {
                required : true,
                lettersmin : true,
                lettersmax : true,
                letters_name : true,
                remote   : {
                    url :'index.php?act=login&op=check_member&column=ok',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#member_name').val();
                        }
                    }
                }
            },
            password : {
                required   : true,
                minlength: 6,
				maxlength: 20
            },
            email : {
                email    : true,
                remote   : {
                    url : 'index.php?act=login&op=check_email',
                    type: 'get',
                    data:{
                        email : function(){
                            return $('#sms_email').val();
                        }
                    }
                }
            },
            agree : {
                required : true
            }
        },
        messages : {
            member_name : {
                required : "<i class='fa fa-question-circle' title='用户名不能为空'></i>",
                lettersmin : "<i class='fa fa-question-circle' title='用户名必须在3-15个字符之间'></i>",
                lettersmax : "<i class='fa fa-question-circle' title='用户名必须在3-15个字符之间'></i>",
				letters_name: "<i class='fa fa-question-circle' title='可包含“_”、“-”，不能是纯数字'></i>",
				remote	 : "<i class='fa fa-question-circle' title='该用户名已经存在'></i>"
            },
            password  : {
                required : "<i class='fa fa-question-circle' title='密码不能为空'></i>",
                minlength: "<i class='fa fa-question-circle' title='密码长度应在6-20个字符之间'></i>",
				maxlength: "<i class='fa fa-question-circle' title='密码长度应在6-20个字符之间'></i>"
            },
            email : {
                email    : "<i class='fa fa-question-circle' title='这不是一个有效的电子邮箱'></i>",
				remote	 : "<i class='fa fa-question-circle' title='该电子邮箱已经存在'></i>"
            },
            agree : {
                required : "<i class='fa fa-question-circle' title='请勾选服务协议'></i>"
            }
        }
    });
});
</script>
<?php } ?>