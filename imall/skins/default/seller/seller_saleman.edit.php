<div class="eject_con">
  <form id="saleman_form" method="post" target="_parent" action="<?php echo urlShop('seller_saleman', 'saleman_save');?>">
    <?php if (isset($output['saleman_info']['member_id'])) { ?>
    <input type="hidden" name="member_id" value="<?php echo $output['saleman_info']['member_id'];?>" />
    <?php } ?>
    <dl>
      <dt>导购帐号：</dt>
      <dd>
        <input class="text w200" type="text" name="member_name" id="member_name" value="<?php echo $output['saleman_info']['member_name']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>登录密码：</dt>
      <dd>
        <input class="text w200" type="password" name="password" id="password" value="" />
      </dd>
    </dl>
    <?php if (!isset($output['saleman_info']['member_id'])) { ?>
    <dl>
      <dt>确认密码：</dt>
      <dd>
        <input class="text w200" type="password" name="password_confirm" id="password_confirm" value="" />
      </dd>
    </dl>
    <?php }?>
    <dl>
      <dt>真实姓名：</dt>
      <dd>
        <input class="text w200" type="text" name="member_truename" id="member_truename" value="<?php echo $output['saleman_info']['member_truename']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>手机：</dt>
      <dd>
        <input class="text w200" type="text" name="member_mobile" id="member_mobile" value="<?php echo $output['saleman_info']['member_mobile']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>邮箱：</dt>
      <dd>
        <input class="text w200" type="text" name="member_email" id="member_email" value="<?php echo $output['saleman_info']['member_email']; ?>" />
      </dd>
    </dl>
    <dl>
      <dt>性别：</dt>
      <dd>
        <label for="member_sex0"><input type="radio" <?php if($output['saleman_info']['member_sex'] == 0){ ?>checked="checked"<?php } ?> value="0" name="member_sex" id="member_sex0">保密</label>            
        <label for="member_sex1"><input type="radio" <?php if($output['saleman_info']['member_sex'] == 1){ ?>checked="checked"<?php } ?> value="1" name="member_sex" id="member_sex1">男</label>            
        <label for="member_sex2"><input type="radio" <?php if($output['saleman_info']['member_sex'] == 2){ ?>checked="checked"<?php } ?> value="2" name="member_sex" id="member_sex2">女</label>
      </dd>
    </dl>    
    <dl>
      <dt>QQ：</dt>
      <dd>
        <input class="text w200" type="text" name="member_qq" id="member_qq" value="<?php echo $output['saleman_info']['member_qq']; ?>" />
      </dd>
    </dl>
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="保存" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#saleman_form').validate({
    	submitHandler:function(form){
    		ajaxpost('saleman_form', '', '', 'onerror') 
    	},		
        rules : {
			<?php if (!isset($output['saleman_info']['member_id'])) { ?>
		    password : {
                required : true
            },
			password_confirm : {
                required : true
            },
		    <?php } ?>
            member_name : {
                required : true
            },
			member_truename : {
                required : true
            },
			member_mobile : {
                required : true
            },
			member_email : {
                required : true
            }
        },
        messages : {
			<?php if (!isset($output['saleman_info']['member_id'])) { ?>
		    password : {
                required : '密码不能为空'
            },
			password_confirm : {
                required : '确认密码不能为空'
            },
		    <?php } ?>
            member_name : {
                required : '用户名不能为空'
            },
			member_truename : {
                required : '真实姓名不能为空'
            },
			member_mobile : {
                required : '手机不能为空'
            },
			member_email : {
                required : '电子邮箱不能为空'
            }
        }
    });
});
</script> 
