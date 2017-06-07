<?php defined('InIMall') or exit('Access Invalid!');?>

  <form id="extension_config_form" name="extension_config_form" enctype="multipart/form-data" method="post" action="<?php echo urlAdminExtension('extension_config', 'perfor_save');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <?php if ($output['perfor_info']['ep_id']!=0) { ?>
    <input type="hidden" name="ep_id" value="<?php echo $output['perfor_info']['ep_id']; ?>" />
    <?php } ?>
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="award_name">补贴名称：</td>
          <td>
            <input class="text w200" type="text" name="award_name" id="award_name" value="<?php echo $output['perfor_info']['award_name']; ?>" />
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="mc_id">门店类型：</td>
          <td>
            <label><input type="radio" name="mc_id" value="2" <?php if ($output['perfor_info']['mc_id']==2) echo 'checked="checked"'; ?> />经理</label>
            <label><input type="radio" name="mc_id" value="3" <?php if ($output['perfor_info']['mc_id']==3) echo 'checked="checked"'; ?> />协理</label>
            <label><input type="radio" name="mc_id" value="4" <?php if ($output['perfor_info']['mc_id']==4) echo 'checked="checked"'; ?> />首席</label>
            <label><input type="radio" name="mc_id" value="5" <?php if (empty($output['perfor_info']['mc_id']) || $output['perfor_info']['mc_id']==5) echo 'checked="checked"'; ?> />股东</label>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="achieve_val">销售业绩：</td>
          <td>
            <input class="text w200" type="text" name="achieve_val" id="achieve_val" value="<?php echo $output['perfor_info']['achieve_val']; ?>" />
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="award_rate">补贴比率：</td>
          <td>
            <input class="text w200" type="text" name="award_rate" id="award_rate" value="<?php echo $output['perfor_info']['award_rate']; ?>" />
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td class="required tr"><label class="validation" for="award_level">补贴分享层数：</td>
          <td>
            <input class="text w200" type="text" name="award_level" id="award_level" value="<?php echo $output['perfor_info']['award_level']; ?>" />
          </td>
          <td class="vatop tips"></td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td></td>
          <td colspan="15" ><a href="JavaScript:void(0);" class="imap-btn-big imap-btn-green" id="submitBtn"><span>保存</span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
<script type="text/javascript">
$(function(){
	//按钮先执行验证再提交表单
    $("#submitBtn").click(function(){
        if($("#extension_config_form").valid()){
            ajaxpost('extension_config_form', '', '', 'onerror') 
        }
    });
	
    $('#extension_config_form').validate({
        rules : {
            award_name : {
                required : true
            },
			achieve_val  : {
				required : true,
                number   : true,
				min      : 0
            },
            award_rate : {
				required : true,
                number   : true,
				max      : 100,
				min      : 0
            },
			award_level  : {
				required : true,
                number   : true,
				min      : 0
            }
        },
        messages : {
            award_name : {
                required : '名称不能为空'

            },
			achieve_val  : {
				required : '销售业绩不能为空',
                number   : '销售业绩必须是数字',
				min      : '销售业绩不能小于0',
            },
            award_rate  : {
				required : '补贴比率不能为空',
                number   : '补贴比率必须是数字',
				max      : '补贴比率不能大于100',
				min      : '补贴比率不能小于0',
            },
			award_level  : {
				required : '分享层数不能为空',
                number   : '分享层数必须是数字',
				min      : '分享层数不能小于0',
            }
        }
    });
});
</script> 
