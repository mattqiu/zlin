<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="promotion_form" name="promotion_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_promotion', 'promotion_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="parent_id" value="<?php echo $output['parent_id']; ?>" />
    <table class="table tb-type3 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="member_name">代理帐号：</label></td>        
          <td class="vatop rowform"><input class="text w200" type="text" name="member_name" id="member_name" value="" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="password">登录密码：</label></td>
          <td class="vatop rowform"><input class="text w200" type="password" name="password" id="password" value="" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="password_confirm">确认密码：</label></td>
          <td class="vatop rowform"><input class="text w200" type="password" name="password_confirm" id="password_confirm" value="" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="mc_id">代理类型：</label></td>
          <td class="vatop rowform">
            <select name="mc_id">
              <?php if (!empty($output['mc_list']) && is_array($output['mc_list'])) {?>
              <?php foreach($output['mc_list'] as $v) {?>
              <option value="<?php echo $v[0];?>"><?php echo $v[1];?></option>
              <?php }?>
              <?php }?>            
            </select>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="member_truename">真实姓名：</label></td>
          <td class="vatop rowform"><input class="text w200" type="text" name="member_truename" id="member_truename" value="" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="member_mobile">手机：</label></td>
          <td class="vatop rowform"><input class="text w200" type="text" name="member_mobile" id="member_mobile" value="" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="member_email">邮箱：</label></td>
          <td class="vatop rowform"><input class="text w200" type="text" name="member_email" id="member_email" value="" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr">性别：</td>
          <td class="vatop rowform">
            <label for="member_sex0"><input type="radio" value="0" name="member_sex" id="member_sex0" checked="checked">保密</label>            
            <label for="member_sex1"><input type="radio" value="1" name="member_sex" id="member_sex1">男</label>            
            <label for="member_sex2"><input type="radio" value="2" name="member_sex" id="member_sex2">女</label>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr">QQ：</td>
          <td class="vatop rowform"><input class="text w200" type="text" name="member_qq" id="member_qq" value="" /></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td class="required tr"></td>
          <td colspan="15"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><span>确认添加</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
 
<script type="text/javascript">
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
        if($("#promotion_form").valid()){
            ajaxpost('promotion_form', '', '', 'onerror') 
	    }
	});

    $('#promotion_form').validate({
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
