<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="imcss-login-layout">
  <div class="left-pic"><img src="<?php echo SHOP_SKINS_URL;?>/images/login_qq.jpg" /></div>
  <div class="imcss-login">
    <div class="imcss-qq-mode">
      <ul class="tabs-nav">
        <li><a href="#register">创建新帐号<i></i></a></li>    
        <li><a href="#binding">绑定已有帐号<i></i></a></li>    
      </ul>
      <div id="tabs_container" class="tabs-container">
        <div id="register" class="tabs-content">
          <form name="register_form" id="register_form" class="imcss-login-form" method="post" action="index.php?act=connect&op=register">
            <input type="hidden" value="ok" name="form_submit">
            <dl>
              <dt>用户名：</dt>
              <dd>
                <input type="text" autocomplete="off" value="<?php echo $output['user_name'];?>" id="reg_user_name" name="reg_user_name" class="text" tipMsg="<?php echo $lang['login_register_username_to_login'];?>" />
               </dd>
            </dl>
            <dl>
              <dt>密码：</dt>
              <dd>
                <input type="text" autocomplete="off" value="<?php echo $output['user_passwd'];?>" id="reg_password" name="reg_password" class="text" tipMsg="<?php echo $lang['login_register_password_to_login'];?>" />
              </dd>
            </dl>
            <dl>
              <dt>邮箱：</dt>
              <dd>
                <input type="text" autocomplete="off" id="reg_email" name="reg_email" class="text" tipMsg="<?php echo $lang['login_register_input_valid_email'];?>"/>
              </dd>
            </dl>
            <div class="submit-div">
              <input type="submit" class="submit" value="确认提交">
            </div>
          </form>
        </div>
        
        <div id="binding" class="tabs-content">
          <form id="binding_form" class="imcss-login-form" method="post" action="index.php?act=connect&op=binding">
            <input type="hidden" name="form_submit" value="ok" />
            <dl>
              <dt><?php echo $lang['login_index_username'];?>：</dt>
              <dd>
                <input type="text" class="text" id="user_name" name="user_name" tipMsg="已注册的会员名<?php if(C('sms_open')==1){echo '/手机号';}?>" >
              </dd>
            </dl>
            <dl>
              <dt><?php echo $lang['login_index_password'];?>：</dt>
              <dd>
                <input type="password" class="text" id="password" name="password" autocomplete="off" tipMsg="6-20个大小写英文字母、符号或数字" >
              </dd>
            </dl>            
            <div class="submit-div">
              <input type="submit" class="submit" value="绑&nbsp;&nbsp;&nbsp;定">
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="clear"></div>
</div>
<script type="text/javascript">
$(function() {
    //初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password,reg_password'});

	//登录方式切换
	$('.imcss-qq-mode').tabulous({
	    effect: 'slideLeft'//动画反转效果
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

    //注册表单验证
    $('#register_form').validate({
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
        rules: {
			reg_user_name : {
                required : true,
                lettersmin : true,
                lettersmax : true,
                lettersonly : true,
                remote   : {
                    url :'index.php?act=login&op=check_member&column=ok',
                    type:'get',
                    data:{
                        user_name : function(){
                            return $('#reg_user_name').val();
                        }
                    }
                }
            },
            reg_password: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            reg_email: {
                required: true,
                email: true,
                remote: {
                    url: 'index.php?act=login&op=check_email',
                    type: 'get',
                    data: {
                        email: function() {
                            return $('#reg_email').val();
                        }
                    }
                }
            }
        },
        messages : {
			reg_user_name : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_input_username'];?>'></i>",
                lettersmin : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_username_range'];?>'></i>",
                lettersmax : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_username_range'];?>'></i>",
				lettersonly: "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_username_lettersonly'];?>'></i>",
				remote	 : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_username_exists'];?>'></i>"
            },
            reg_password  : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_input_password'];?>'></i>",
                minlength: "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_password_range'];?>'></i>",
				maxlength: "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_password_range'];?>'></i>"
            },
            reg_email : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_input_email'];?>'></i>",
                email    : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_invalid_email'];?>'></i>",
				remote	 : "<i class='fa fa-question-circle' title='<?php echo $lang['login_register_email_exists'];?>'></i>"
            }
        }
    });
	
	//绑定已有帐号
	$("#binding_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('binding_form', '', '', 'onerror');
    	},
		rules: {
			user_name: "required",
			password: "required"
		},
		messages: {
			user_name: "<i class='fa fa-question-circle' title='<?php echo $lang['login_index_input_username'];?>'></i>",
			password: "<i class='fa fa-question-circle' title='<?php echo $lang['login_index_input_password'];?>'></i>"
		}
	});
});
</script>