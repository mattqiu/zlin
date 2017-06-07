<?php defined('InIMall') or exit('Access Invalid!');?>

<div class="eject_con">
  <div id="warning" class="alert alert-error"></div>
  <form id="customadd_form" method="post" target="_parent" action="index.php?act=store_decoration&op=custom_add_save">  
    <input type="hidden" name="form_submit" value="ok" />
    <input id="decoration_id" name="decoration_id" type="hidden" value="<?php echo $output['decoration_info']['decoration_id']; ?>">
    <dl>
      <dt><i class="required">*</i>展厅名称：</dt>
      <dd>
        <input class="text w200" type="text" name="decoration_name" id="decoration_name" value="<?php echo $output['decoration_info']['decoration_name']; ?>" />
      </dd>
    </dl>
    <div class="bottom">
      <label class="submit-border"><input type="submit" class="submit" value="<?php echo $lang['im_submit'];?>" /></label>
    </div>
  </form>
</div>
<script type="text/javascript">
$(function(){
    $('#customadd_form').validate({
        errorLabelContainer: $('#warning'),
        invalidHandler: function(form, validator) {
               $('#warning').show();
        },
    	submitHandler:function(form){
    		ajaxpost('customadd_form', '', '', 'onerror') 
    	},
        rules : {
            decoration_name : {
                required : true
            }
        },
        messages : {
            decoration_name : {
                required : '<i class="fa fa-exclamation-circle"></i>展厅名称不能为空'
            }
        }
    });
});
</script> 