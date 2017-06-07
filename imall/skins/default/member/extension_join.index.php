<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="eject_con">
  <div class="adds">
    <div id="warning"></div>
    <form method="post" action="index.php?act=extension_join&op=apply_extension_save" id="my_extension_join" name="my_extension_join" target="_parent"> 
      <input type="hidden" name="form_submit" value="ok" />  
      <dl>
        <dt>姓名：</dt>
        <dd>
          <input type="text" id="truename" name="truename" class="text tip" value="<?php echo $output['apply_info']['member_truename'];?>" autofocus />
        </dd>
      </dl>
      <dl>
        <dt>手机：</dt>
        <dd>
          <input type="text" id="mobile" name="mobile" class="text tip" value="<?php echo $output['apply_info']['member_mobile'];?>" />
        </dd>
      </dl>
      <dl>
        <dt>Email：</dt>
        <dd>
          <input type="text" id="email" name="email" class="text tip" value="<?php echo $output['apply_info']['member_email'];?>" />
        </dd>
      </dl>      
      <dl>
        <dt>QQ：</dt>
        <dd>
          <input type="text" id="qq" name="qq" class="text tip" value="<?php echo $output['apply_info']['member_qq'];?>" />
        </dd>
      </dl>
      <dl>
        <dt>所在地：</dt>
        <dd>
          <input type="text" id="areainfo" name="areainfo" class="text tip" value="<?php echo preg_replace("/\s/","",$output['apply_info']['member_areainfo']);?>" />
        </dd>
      </dl>
      <dl>
        <dt>留言：</dt>
        <dd>
          <textarea class="textarea" cols="50" name="describe" rows="10">这家伙很懒，什么都不肯留下!</textarea>
        </dd>
      </dl>
      <div class="bottom">
        <label class="submit-border">
          <input type="submit" class="submit" value="提交推广申请" title="提交推广申请" />
        </label>
      </div>
    </form>
  </div>  
</div>
<script type="text/javascript">
$(function(){
	jQuery.validator.addMethod("phones", function(value, element) {
		return this.optional(element) || /^[1][3-8]+\d{9}/i.test(value);
	}, "phone number please"); 

    $('#my_extension_join').validate({
    	submitHandler:function(form){
    		ajaxpost('my_extension_join', '', '', 'onerror') 
    	},
		errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
           var errors = validator.numberOfInvalids();
           if(errors)
           {
               $('#warning').show();
           }
           else
           {
               $('#warning').hide();
           }
        },
        rules : {
            truename : {
                required : true
            },
			mobile : {
				required : true,
				phones   : true
            }
        },
        messages : {
			truename : {
                required : '真实姓名不能为空'
            },
			mobile : {
				required : '手机号码不能为空',
				phones   : '手机号码不正确'
            }
        }
    });
});
</script>