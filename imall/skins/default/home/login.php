<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="imcss-login-layout">
  <div class="left-pic"><img src="<?php echo $output['lpic'];?>"  border="0"></div>
  <div class="imcss-login">
    <div class="imcss-login-mode">
      <ul class="tabs-nav">
        <li><a href="#default">会员登录<i></i></a></li>
        <?php if (C('sms_open')==1){?>      
        <li><a href="#mobile">手机免密码登录<i></i></a></li>
        <?php }?>        
      </ul>
      <div id="tabs_container" class="tabs-container">
        <div id="default" class="tabs-content">
          <form id="login_form" class="imcss-login-form" method="post" action="index.php?act=login&op=login">
            <input type="hidden" name="form_submit" value="ok" />
            <?php Security::getToken();?>           
            <input name="imhash" type="hidden" value="<?php echo getIMhash();?>" />
            <dl>
              <dt><?php echo $lang['login_index_username'];?>：</dt>
              <dd>
                <input type="text" class="text" autocomplete="off"  name="user_name" tipMsg="已注册的会员名<?php if(C('sms_open')==1){echo '/手机号';}?>" id="user_name" >
              </dd>
            </dl>
            <dl>
              <dt><?php echo $lang['login_index_password'];?>：</dt>
              <dd>
                <input type="password" class="text" name="password" autocomplete="off" tipMsg="6-20个大小写英文字母、符号或数字" id="password">
              </dd>
            </dl>
            <?php if(C('captcha_status_login') == '1') { ?>
            <div class="code-div mt15">
              <dl>
                <dt><?php echo $lang['login_index_checkcode'];?>：</dt>
                <dd>
                  <input type="text" name="captcha" autocomplete="off" class="text w100" tipMsg="输入验证码" id="captcha" size="10" />                  
                </dd>
              </dl>
              <span>
                <img src="<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>" name="codeimage" id="codeimage"> 
                <a class="makecode" href="javascript:void(0)" onclick="javascript:document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();"><?php echo $lang['login_index_change_checkcode'];?></a>
              </span>
            </div>
            <?php } ?>
            <div class="handle-div">
              <span class="auto">
			    <?php echo $lang['login_index_regist_now_1'];?>
                <a title="" href="index.php?act=login&op=register&ref_url=<?php echo urlencode($output['ref_url']);?>" class="register"><?php echo $lang['login_index_regist_now_2'];?></a>
              </span>
              <a class="forget" href="index.php?act=login&op=forget_password">忘记密码？</a>
            </div>
            <div class="submit-div">
              <input type="submit" class="submit" value="登&nbsp;&nbsp;&nbsp;录">
              <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
            </div>
          </form>
        </div>
        <?php if (C('sms_open')==1){?>
        <div id="mobile" class="tabs-content">
          <form id="mobile_form" method="post" class="imcss-login-form" action="index.php?act=connect_sms&op=login">
            <?php Security::getToken();?>            
            <input type="hidden" name="form_submit" value="ok" />
            <input name="imhash" type="hidden" value="<?php echo getIMhash();?>" />
            <dl>
              <dt>手机号：</dt>
              <dd>
                <input name="phone" type="text" class="text" id="phone" tipMsg="已注册的手机号" autocomplete="off" value="" >              
              </dd>
            </dl>
            <?php if(C('captcha_status_login') == '1') { ?>
            <div class="code-div">
              <dl>
                <dt>验证码：</dt>
                <dd>
                  <input type="text" name="mobile_captcha" class="text w100" tipMsg="输入验证码" id="mobile_captcha" size="10" />                 
                </dd>
              </dl>
              <span>
                <img src="" title="<?php echo $lang['login_index_change_checkcode'];?>" name="codeimage" id="sms_codeimage"> 
                <a class="makecode" href="javascript:void(0);" onclick="javascript:document.getElementById('sms_codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&imhash=<?php echo getIMhash();?>&t=' + Math.random();"><?php echo $lang['login_index_change_checkcode'];?></a>
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
                <a href="javascript:void(0);" onclick="get_sms_captcha('2')" id="sending_btn"><i class="fa fa-mobile"></i>获取手机校验码</a>
              </span> 
            </div>
            <div class="submit-div">
              <input type="submit" id="submit" class="submit" value="登&nbsp;&nbsp;&nbsp;录">
              <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
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
<script>
$(function(){
	//初始化Input的灰色提示信息  
	$('input[tipMsg]').inputTipText({pwd:'password'});

	//登录方式切换
	$('.imcss-login-mode').tabulous({
	    effect: 'slideLeft'//动画反转效果
	});	
	
	var div_form = '#default';
	$(".imcss-login-mode .tabs-nav li a").click(function(){
        if($(this).attr("href") !== div_form){
            div_form = $(this).attr('href');
            $(""+div_form).find(".makecode").trigger("click");
    	}
	});
    //帐号登录方式
	$("#login_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('login_form', '', '', 'onerror');
    	},
        onkeyup: false,
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
			user_name: "<i class='fa fa-question-circle' title='<?php echo $lang['login_index_input_username'];?>'></i>",
			password: "<i class='fa fa-question-circle' title='<?php echo $lang['login_index_input_password'];?>'></i>"
			<?php if(C('captcha_status_login') == '1') { ?>
            ,captcha : {
                required : "<i class='fa fa-question-circle' title='<?php echo $lang['login_index_input_checkcode'];?>'></i>",
				remote	 : "<i class='fa fa-question-circle' title='<?php echo $lang['login_index_wrong_checkcode'];?>'></i>"
            }
			<?php } ?>
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
	
	$("#mobile_form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.append(error);
            element.parents('dl:first').addClass('error');
        },
        success: function(label) {
            label.parents('dl:first').removeClass('error').find('label').remove();
        },
    	submitHandler:function(form){
    	    ajaxpost('mobile_form', '', '', 'onerror');
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
                required : "<i class='fa fa-question-circle' title='请输入正确的手机号'>",
                mobile : "<i class='fa fa-question-circle' title='请输入正确的手机号'>"
            },
			mobile_captcha : {
                required : "<i class='fa fa-question-circle' title='请输入正确的验证码'></i>",
                minlength: "<i class='fa fa-question-circle' title='请输入正确的验证码'></i>",
				remote	 : "<i class='fa fa-question-circle' title='请输入正确的验证码'></i>"
            },
			sms_captcha: {
                required : "<i class='fa fa-question-circle' title='请输入六位短信动态码'><",
                minlength: "<i class='fa fa-question-circle' title='请输入六位短信动态码'></i>",
				maxlength: "<i class='fa fa-question-circle' title='请输入六位短信动态码'></i>"
            }
		}
	});
});
</script>
<?php } ?>
