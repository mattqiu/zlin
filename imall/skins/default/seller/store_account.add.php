<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="imsc-form-default">
  <form id="add_form" action="<?php echo urlShop('store_account', 'account_save');?>" method="post">
    <dl>
      <dt><i class="required">*</i>前台用户名或手机号<?php echo $lang['im_colon'];?></dt>
      <dd><input class="w120 text" name="member_name" type="text" id="member_name" value="" />
          <span></span>
        <p class="hint"></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>用户密码<?php echo $lang['im_colon'];?></dt>
      <dd><input class="w120 text" name="password" type="password" id="password" value="" />
          <span></span>
        <p class="hint"></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>登录账号<?php echo $lang['im_colon'];?></dt>
      <dd><input class="w120 text" name="seller_name" type="text" id="seller_name" value="" />
          <span></span>
        <p class="hint">新账号登录商家中心的用户名，密码与该账号前台密码相同</p>
      </dd>
    </dl>
    <dl>
      <dt>导购昵称<?php echo $lang['im_colon'];?></dt>
      <dd><input class="w120 text" name="nick_name" type="text" id="nick_name" value="" />
          <span></span>
        <p class="hint">方便会员记录该导购</p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>是否为店长<?php echo $lang['im_colon'];?></dt>
      <dd>
      	<ul class="imsc-form-radio-list">
            <li>
              <input type="radio" name="is_owner" value="0" id="is_owner_0" checked>
              <label for="is_owner_0">员工</label>
            </li>
            <li>
              <input type="radio" name="is_owner" value="1" id="is_owner_1">
              <label for="is_owner_1">店长</label>
            </li>
            <li>
              <input type="radio" name="is_owner" value="2" id="is_owner_2">
              <label for="is_owner_2">导购员</label>
            </li>
          </ul>
        <p class="hint">如果是店长则需要设置其管辖的导购</p>
      </dd>
    </dl>
    <dl imtype="im_is_owner" style="display:none;">
      <dt>管辖导购</dt>
    	<dd>
    	<!-- 
    	<div class="imsc-account-all">
          <input id="btn_select_all" name="btn_select_all" class="checkbox" type="checkbox" />
          <label for="btn_select_all">全选</label>
          <span></span>
        </div>
        <div class="imsc-account-container">          
        -->
        <div>
          <?php if(!empty($output['salemen_list']) && is_array($output['salemen_list'])) {?>
          <ul class="imsc-account-container-list">
            <?php foreach($output['salemen_list'] as $seller) {?>
            <li>
              <input id="<?php echo $seller['seller_id'];?>" type="checkbox" class="checkbox" name="saleman_id[]" value="<?php echo $seller['seller_id'];?>" type="checkbox" />
              <label for="<?php echo $seller['seller_id'];?>"><?php echo $seller['seller_name'];?></label>
            </li>
            <?php } ?>
          </ul>
          <?php } else {?>
          	<p class="hint">暂无其他导购员</p>
          <?php } ?>
        </div>
        <p class="hint"></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>账号组<?php echo $lang['im_colon'];?></dt>
      <dd><select name="group_id">
            <?php foreach($output['seller_group_list'] as $value) { ?>
            <option value="<?php echo $value['group_id'];?>"><?php echo $value['group_name'];?></option>
            <?php } ?>
          </select>
          <span></span>
        <p class="hint"></p>
      </dd>
    </dl>
    <dl>
      <dt><i class="required">*</i>是否默认登录此店铺<?php echo $lang['im_colon'];?></dt>
      <dd>
      	<ul class="imsc-form-radio-list">
            <li>
              <input type="radio" name="is_default" value="0" id="is_default_0" <?php if(empty($output['seller_info'])||$output['seller_info']['is_default'] == 0) {?>checked<?php }?>>
              <label for="is_owner_0">否</label>
            </li>
            <li>
              <input type="radio" name="is_default" value="1" id="is_default_1" <?php if($output['seller_info']['is_default'] == 1) {?>checked<?php }?>>
              <label for="is_owner_1">是</label>
            </li>
          </ul>
        <p class="hint">一个员工可能管理多家店铺，此处为了让员工登录后台的时候默认登录的店铺</p>
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border">
        <input type="submit" class="submit" value="<?php echo $lang['im_submit'];?>">
      </label>
    </div>
  </form>
</div>
<script>
$(document).ready(function(){
	$('#btn_select_all').on('click', function() {
        if($(this).prop('checked')) {
            $(this).parents('dd').find('input:checkbox').prop('checked', true);
        } else {
            $(this).parents('dd').find('input:checkbox').prop('checked', false);
        }
    });
	$('[name="is_owner"]').change(function() {
		if ($('[name="is_owner"]:checked').val() == '0'){
			$('[imtype="im_is_owner"]').hide();
		}else{
			$('[imtype="im_is_owner"]').show();
		}
	});
    jQuery.validator.addMethod("seller_name_exist", function(value, element, params) { 
        var result = true;
        $.ajax({  
            type:"GET",  
            url:'<?php echo urlShop('store_account', 'check_seller_name_exist');?>',  
            async:false,  
            data:{seller_name: $('#seller_name').val()},  
            success: function(data){  
                if(data == 'true') {
                    $.validator.messages.seller_name_exist = "卖家账号已存在";
                    result = false;
                }
            }  
        });  
        return result;
    }, '');

    jQuery.validator.addMethod("check_member_password", function(value, element, params) { 
        var result = true;
        $.ajax({  
            type:"GET",  
            url:'<?php echo urlShop('store_account', 'check_seller_member');?>',  
            async:false,  
            data:{member_name: $('#member_name').val(), password: $('#password').val()},  
            success: function(data){  
                if(data != 'true') {
                    $.validator.messages.check_member_password = "前台用户验证失败";
                    result = false;
                }
            }  
        });  
        return result;
    }, '');

    $('#add_form').validate({
        onkeyup: false,
        errorPlacement: function(error, element){
            element.nextAll('span').first().after(error);
        },
    	submitHandler:function(form){
    		ajaxpost('add_form', '', '', 'onerror');
    	},
        rules: {
            member_name: {
                required: true
            },
            password: {
                required: true,
                check_member_password: true
            },
            seller_name: {
                required: true,
                maxlength: 50, 
                seller_name_exist: true
            },
            group_id: {
                required: true
            }
        },
        messages: {
            member_name: {
                required: '<i class="fa fa-exclamation-circle"></i>前台用户名不能为空'
            },
            password: {
                required: '<i class="fa fa-exclamation-circle"></i>用户密码不能为空',
                remote: '<i class="fa fa-exclamation-circle"></i>用户名密码错误'
            },
            seller_name: {
                required: '<i class="fa fa-exclamation-circle"></i>卖家账号不能为空',
                maxlength: '<i class="fa fa-exclamation-circle"></i>卖家账号最多50个字'
            },
            group_id: {
                required: '<i class="fa fa-exclamation-circle"></i>请选择账号组'
            }
        }
    });
});
</script> 
