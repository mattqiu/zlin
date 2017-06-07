<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="extension_config_form" name="extension_config_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_config', 'commisclass_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <?php if ($output['commis_info']['commis_id']!=0) { ?>
    <input type="hidden" name="commis_id" value="<?php echo $output['commis_info']['commis_id']; ?>" />
    <?php } ?>
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="sg_name">模板名称:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input class="text w200" type="text" name="commis_name" id="commis_name" value="<?php echo $output['commis_info']['commis_name']; ?>" /></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="sg_name">佣金类型:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <label><input type="radio" name="commis_class" imtype="commis_class" value="0" <?php if ($output['commis_info']['commis_class']==0) echo 'checked="checked"'; ?> />店铺自定</label>
            <label><input type="radio" name="commis_class" imtype="commis_class" value="1" <?php if ($output['commis_info']['commis_class']==1) echo 'checked="checked"'; ?> />固定佣金</label>
            <label><input type="radio" name="commis_class" imtype="commis_class" value="2" <?php if ($_GET['commis_id']=='' || $output['commis_info']['commis_class']==2) echo 'checked="checked"'; ?> />售价的百分比</label>
            <label><input type="radio" name="commis_class" imtype="commis_class" value="3" <?php if ($output['commis_info']['commis_class']==3) echo 'checked="checked"'; ?> />利润的百分比</label>
          </td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label class="validation" for="sg_name">佣金比率:</label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <input class="text w60" type="text" name="commis_rate" id="commis_rate" value="<?php echo intval($output['commis_info']['commis_rate']); ?>"  />
            <label id="commis_rate_title">
            <?php 
		      switch ($output['commis_info']['commis_class']){
			      case 0:
			        echo "";
				    break;
			      case 1:
			        echo " 元";
				    break;
			      case 2:
			        echo " %";
				    break;
			      case 3:
			        echo " %";
				    break;
			      default:
			        echo "";
				    break;
		      }
		    ?>
            </label>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15"><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><span><?php echo $lang['im_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
<script type="text/javascript">
$(function(){
    $('#extension_config_form').validate({
    	errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
            commis_name : {
                required : true
            },
            commis_rate : {
				required : true,
                number   : true
            }
        },
        messages : {
            commis_name : {
                required : '名称不能为空'

            },
            commis_rate  : {
				required : '比率不能为空',
                number   : '比率必须是数字'
            }
        }
    });
});
$('[imtype="commis_class"]').change(function() { 
    var selectedvalue = $('[imtype="commis_class"]:checked').val();
    if (selectedvalue=='0'){
	    $('#commis_rate').val(0);
		$('#commis_rate_title').hide();
	}else if (selectedvalue=='1'){
		$('#commis_rate_title').show();
		$('#commis_rate_title').html(" 积分");
	}else if (selectedvalue=='2'){
		$('#commis_rate_title').show();
		$('#commis_rate_title').html(" %");
	}else if (selectedvalue=='3'){
		$('#commis_rate_title').show();
		$('#commis_rate_title').html(" %");
	}
});
//按钮先执行验证再提交表单
$(function(){
	$("#submitBtn").click(function(){
        if($("#extension_config_form").valid()){
            ajaxpost('extension_config_form', '', '', 'onerror') 
	    }
	});
});
</script> 
