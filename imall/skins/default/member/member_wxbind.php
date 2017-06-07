<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu');?>
  </div>
  <?php if ($output['setting_config']['wx_isuse'] == 1){?>
  <div class="imm-bind">
    <?php if (!empty($output['member_info']['member_wxopenid'])){?>
    <div class="alert">
      <h4>提示信息：</h4>
      <ul>
        <li><?php echo $lang['member_qqconnect_binding_tip_1'];?><em>“<?php echo $_SESSION['member_name'];?>”</em>与微信帐号<em>“<?php echo $output['member_info']['member_wxinfoarr']['nickname'];?>”</em><?php echo $lang['member_qqconnect_binding_tip_3'];?></li>
      </ul>
    </div>
    <input type="hidden" name="form_submit" value="ok"  />
    <div class="relieve">
      <form method="post" id="editbind_form" name="editbind_form" action="index.php?act=member_connect&op=wxunbind">
        <input type='hidden' id="is_editpw" name="is_editpw" value='no'/>
        <div class="ico-wx"></div>
        <p><?php echo $lang['member_qqconnect_unbind_click']; ?></p>
        <div class="bottom">
          <label class="submit-border">
            <input class="submit" type="submit" value="<?php echo $lang['member_qqconnect_unbind_submit'];?>" />
          </label>
        </div>
      </form>
    </div>
    <div class="revise imm-default-form ">
      <form method="post" id="editpw_form" name="editpw_form" action="index.php?act=member_connect&op=wxunbind">
        <input type='hidden' id="is_editpw" name="is_editpw" value='yes'/>
        <dl>
          <dt><?php echo $lang['member_qqconnect_modpw_newpw'].$lang['im_colon']; ?></dt>
          <dd>
            <input type="password"  name="new_password" id="new_password"/>
            <label for="new_password" generated="true" class="error"></label>
          </dd>
        </dl>
        <dl>
          <dt><?php echo $lang['member_qqconnect_modpw_two_password'].$lang['im_colon']; ?></dt>
          <dd>
            <input type="password"  name="confirm_password" id="confirm_password" />
            <label for="confirm_password" generated="true" class="error"></label>
          </dd>
        </dl>
        <dl class="bottom">
          <dt></dt>
          <dd>
            <label class="submit-border">
              <input class="submit" type="submit" value="<?php echo $lang['member_qqconnect_unbind_updatepw_submit'];?>" />
            </label>
          </dd>
        </dl>
      </form>
    </div>
    <?php } else {?>
    <div class="relieve pt50">
      <p class="ico">
        <a href="javascript:void(0);" onclick="ajax_form('weixin_form', '微信账号登录', 'http://192.168.1.104/imall/api.php?act=towx', 360);">
          <img src="<?php echo SHOP_SKINS_URL;?>/images/wx_bind_small.gif">
        </a>
      </p>
      <p class="hint">点击按钮，立刻绑定微信账号</p>
    </div>
    <div class="revise pt50">
      <p class="qq">使用微信账号绑定本站，您可以...</p>
      <p>用微信账号轻松登录</p>
      <p class="hint">无需记住本站的账号和密码，随时使用微信账号密码轻松登录</p>
    </div>
    <?php }?>
  </div>
  <?php } else {?>
  <div class="warning-option"><i>&nbsp;</i><span>系统未开启微信互联功能</span></div>
  <?php }?>
</div>
<script type="text/javascript">
$(function(){
	$("#unbind").hide();

    $('#editpw_form').validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('td').next('td');
            error_td.find('.field_notice').hide();
            error_td.append(error);
        },
        rules : {
            new_password : {
                required   : true,
                minlength  : 6,
                maxlength  : 20
            },
            confirm_password : {
                required   : true,
                equalTo    : '#new_password'
            }
        },
        messages : {
            new_password  : {
                required   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_qqconnect_new_password_null'];?>',
                minlength  : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_qqconnect_password_range'];?>'
            },
            confirm_password : {
                required   : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_qqconnect_ensure_password_null'];?>',
                equalTo    : '<i class="fa fa-exclamation-circle"></i><?php echo $lang['member_qqconnect_input_two_password_again'];?>'
            }
        }
    });
});
function showunbind(){
	$("#unbind").show();
}
function showpw(){
	$("#is_editpw").val('yes');
	$("#editbinddiv").hide();
	$("#editpwul").show();
}
</script>
