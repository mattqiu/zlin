<div class="eject_con">
  <form id="promotion_form" method="post" target="_parent" action="<?php echo urlShop('seller_promotion', 'promotion_save');?>">
    <dl>
      <dt>推广帐号：</dt>
      <dd>
        <input class="text w200" type="text" name="member_name" id="member_name" value="" />
      </dd>
    </dl>
    <dl>
      <dt>登录密码：</dt>
      <dd>
        <input class="text w200" type="password" name="password" id="password" value="" />
      </dd>
    </dl>
    <dl>
      <dt>确认密码：</dt>
      <dd>
        <input class="text w200" type="password" name="password_confirm" id="password_confirm" value="" />
      </dd>
    </dl>
    <dl>
      <dt>隶属上级：</dt>
      <dd>
        <select name="parent_id">
          <option value="0" <?php if ($output['parent_id'] == 0) {?>selected="selected"<?php }?>>股东</option>
          <?php if(!empty($output['promotion_list']) && is_array($output['promotion_list'])){ ?>
          <?php   foreach($output['promotion_list'] as $k => $val){ ?>
          <option value="<?php echo $val['member_id']?>" <?php if ($output['parent_id'] == $val['member_id']) {?>selected="selected"<?php }?>><?php echo $val['member_name'];?></option>
          <?php }?>
          <?php }?>
        </select>
      </dd>
    </dl>
    <dl>
      <dt>真实姓名：</dt>
      <dd>
        <input class="text w200" type="text" name="member_truename" id="member_truename" value="" />
      </dd>
    </dl>
    <dl>
      <dt>手机：</dt>
      <dd>
        <input class="text w200" type="text" name="member_mobile" id="member_mobile" value="" />
      </dd>
    </dl>
    <dl>
      <dt>邮箱：</dt>
      <dd>
        <input class="text w200" type="text" name="member_email" id="member_email" value="" />
      </dd>
    </dl>
    <dl>
      <dt>性别：</dt>
      <dd>
        <label for="member_sex0"><input type="radio" value="0" name="member_sex" id="member_sex0" checked="checked">保密</label>            
        <label for="member_sex1"><input type="radio" value="1" name="member_sex" id="member_sex1">男</label>            
        <label for="member_sex2"><input type="radio" value="2" name="member_sex" id="member_sex2">女</label>
      </dd>
    </dl>    
    <dl>
      <dt>QQ：</dt>
      <dd>
        <input class="text w200" type="text" name="member_qq" id="member_qq" value="" />
      </dd>
    </dl>    
    <div class="bottom">
        <label class="submit-border"><input type="submit" class="submit" value="保存" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#promotion_form').validate({
    	submitHandler:function(form){
    		ajaxpost('promotion_form', '', '', 'onerror') 
    	},		
        rules : {
		    password : {
                required : true
            },
			password_confirm : {
                required : true
            },
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
		    password : {
                required : '密码不能为空'
            },
			password_confirm : {
                required : '确认密码不能为空'
            },
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
                required : '邮箱不能为空'
            }
        }
    });
});
</script> 
