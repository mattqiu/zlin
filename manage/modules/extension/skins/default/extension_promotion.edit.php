<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="promotion_form" name="promotion_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_promotion', 'promotion_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="member_id" value="<?php echo $output['promotion_info']['member_id'];?>" />    
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="member_name">代理帐号：</label></td>        
          <td class="vatop rowform"><input class="text w200" type="text" name="member_name" id="member_name" value="<?php echo $output['promotion_info']['member_name']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="password">登录密码：</label></td>
          <td class="vatop rowform"><input class="text w200" type="password" name="password" id="password" value="" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="member_truename">真实姓名：</label></td>
          <td class="vatop rowform"><input class="text w200" type="text" name="member_truename" id="member_truename" value="<?php echo $output['promotion_info']['member_truename']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr">性别：</td>
          <td class="vatop rowform">
            <label for="member_sex0"><input type="radio" <?php if($output['promotion_info']['member_sex'] == 0){ ?>checked="checked"<?php } ?> value="0" name="member_sex" id="member_sex0">保密</label>            
            <label for="member_sex1"><input type="radio" <?php if($output['promotion_info']['member_sex'] == 1){ ?>checked="checked"<?php } ?> value="1" name="member_sex" id="member_sex1">男</label>            
            <label for="member_sex2"><input type="radio" <?php if($output['promotion_info']['member_sex'] == 2){ ?>checked="checked"<?php } ?> value="2" name="member_sex" id="member_sex2">女</label>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="member_mobile">手机：</label></td>
          <td class="vatop rowform"><input class="text w200" type="text" name="member_mobile" id="member_mobile" value="<?php echo $output['promotion_info']['member_mobile']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr">QQ：</td>
          <td class="vatop rowform"><input class="text w200" type="text" name="member_qq" id="member_qq" value="<?php echo $output['promotion_info']['member_qq']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="member_email">邮箱：</label></td>
          <td class="vatop rowform"><input class="text w200" type="text" name="member_email" id="member_email" value="<?php echo $output['promotion_info']['member_email']; ?>" /></td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td class="required tr"></td>
          <td colspan="15"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><span>保存</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>


<script type="text/javascript">
$(function(){
	$("#submitBtn").click(function(){
        if($("#promotion_form").valid()){
            ajaxpost('promotion_form', '', '', 'onerror') 
	    }
	});
	
    $('#promotion_form').validate({
        rules : {
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
